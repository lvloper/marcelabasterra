<?php

namespace App\Console\Commands;

use App\Filament\Blocks\PageBlock;
use App\Filament\Templates\DefaultTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class CmsBlocksList extends Command
{
    protected $signature = 'cms:blocks-list
        {--orphans : Solo bloques sin clase PHP}
        {--unregistered : Solo bloques no registrados en templates}
        {--json : Salida en JSON para consumo por agente}';

    protected $description = 'Lista todos los bloques CMS (PHP + Blade)';

    public function handle(): int
    {
        $blocksDir = app_path('Filament/Blocks');
        $viewsDir = resource_path('views/blocks');
        $phpClasses = $this->getPhpClasses($blocksDir);
        $bladeViews = $this->getBladeViews($viewsDir);
        $registeredBlocks = $this->getRegisteredBlocks();

        $rows = [];
        foreach ($bladeViews as $view) {
            $name = str_replace('.blade.php', '', $view);
            $hasPhp = isset($phpClasses[$name]);
            $reg = in_array($name, $registeredBlocks);

            if ($this->option('orphans') && $hasPhp) {
                continue;
            }
            if ($this->option('unregistered') && $reg) {
                continue;
            }

            $status = $hasPhp ? ($reg ? 'REGISTRADO' : 'SIN_REGISTRO') : 'SOLO_VISTA';
            $label = '';
            $category = '';
            if ($hasPhp) {
                $label = $phpClasses[$name]['label'] ?? $name;
                $category = $phpClasses[$name]['category'] ?? '—';
            }

            $rows[] = [
                $name,
                $status,
                $category,
                $label,
                $hasPhp ? 'Si' : 'No',
                $reg ? 'Si' : 'No',
            ];
        }

        $headers = ['Bloque', 'Estado', 'Categoria', 'Label', 'PHP', 'Template'];

        if ($this->option('json')) {
            $this->line(json_encode(['blocks' => $rows, 'total' => count($rows)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return 0;
        }

        $this->table($headers, $rows);
        $this->newLine();
        $this->info('Total: '.count($rows).' bloques');

        $orphans = count(array_filter($rows, fn ($r) => $r[1] === 'SOLO_VISTA'));
        $unreg = count(array_filter($rows, fn ($r) => $r[1] === 'SIN_REGISTRO'));
        if ($orphans) {
            $this->warn("  $orphans solo tienen vista (sin clase PHP)");
        }
        if ($unreg) {
            $this->warn("  $unreg tienen clase PHP pero no estan registrados en templates");
        }

        return 0;
    }

    private function getPhpClasses(string $dir): array
    {
        if (! is_dir($dir)) {
            return [];
        }
        $classes = [];
        foreach (File::files($dir) as $file) {
            $name = $file->getFilenameWithoutExtension();
            if ($name === 'PageBlock') {
                continue;
            }
            $fqcn = "App\\Filament\\Blocks\\$name";
            if (! class_exists($fqcn)) {
                continue;
            }
            $ref = new ReflectionClass($fqcn);
            if (! $ref->isSubclassOf(PageBlock::class)) {
                continue;
            }
            try {
                $label = $ref->getConstant('LABEL') ?? $name;
                $category = $ref->getConstant('CATEGORY') ?? '—';
                $blockName = $ref->getConstant('NAME') ?? str_replace('Block', '', $name);
                $classes[$blockName] = [
                    'label' => is_string($label) ? $label : $blockName,
                    'category' => is_string($category) ? $category : '—',
                    'class' => $name,
                ];
            } catch (\Throwable) {
                $classes[$name] = ['label' => $name, 'category' => '—', 'class' => $name];
            }
        }

        return $classes;
    }

    private function getBladeViews(string $dir): array
    {
        if (! is_dir($dir)) {
            return [];
        }

        return collect(File::files($dir))
            ->filter(fn ($f) => $f->getExtension() === 'php' && ! str_contains($f->getFilename(), '.bak'))
            ->map(fn ($f) => $f->getFilename())
            ->values()
            ->toArray();
    }

    private function getRegisteredBlocks(): array
    {
        return collect(DefaultTemplate::blocks())
            ->map(fn ($block) => $block->getName())
            ->unique()
            ->values()
            ->all();
    }
}
