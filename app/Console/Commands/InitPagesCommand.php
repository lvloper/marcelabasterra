<?php

namespace App\Console\Commands;

use App\Enums\Status;
use App\Models\Configuration;
use App\Models\Page;
use App\Models\Route;
use Illuminate\Console\Command;

class InitPagesCommand extends Command
{
    protected $signature = 'cms:init-pages';

    protected $description = 'Crea las páginas Home, Sobre mí y Contacto con sus rutas';

    public function handle()
    {
        $this->createHome();
        $this->createPage('Sobre mí', 'sobre-mi', 'default');
        $this->createPage('Contacto', 'contacto', 'default');

        $this->info('Páginas inicializadas correctamente');
    }

    protected function createHome(): void
    {
        $existingRoute = Route::where('slug', 'home')->first();

        if ($existingRoute) {
            $this->warn('Home ya existe');
            return;
        }

        $home = Page::create(['name' => 'Home', 'blocks' => []]);
        $homeRoute = $home->route()->create([
            'title' => 'Home',
            'slug' => 'home',
            'status' => Status::Published,
            'layout' => 'home',
        ]);

        Configuration::updateOrCreate(
            ['key' => 'home_route_id'],
            ['type' => 'url', 'value' => ['route' => ['route_id' => (string) $homeRoute->id]]]
        );

        $this->info('Home creada con ruta /');
    }

    protected function createPage(string $name, string $slug, string $layout): void
    {
        $existingRoute = Route::where('slug', $slug)->first();

        if ($existingRoute) {
            $this->warn("\"{$name}\" ya existe con ruta /{$slug}");
            return;
        }

        $record = Page::create(['name' => $name, 'blocks' => []]);
        $record->route()->create([
            'title' => $name,
            'slug' => $slug,
            'status' => Status::Published,
            'layout' => $layout,
        ]);

        $this->info("\"{$name}\" creada con ruta /{$slug}");
    }
}
