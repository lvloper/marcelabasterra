<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\BannerResource;
use App\Filament\Resources\DossierPrensaResource;
use App\Filament\Resources\EntrevistaResource;
use App\Filament\Resources\ProgramaAcademicoResource;
use App\Filament\Templates\DefaultTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

final class AdminCleanupTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_builder_only_registers_productive_blocks(): void
    {
        $this->assertSame([
            'Hero',
            'Text',
            'MediaText',
            'Cards',
            'Search',
            'CTA',
            'PublicationsHighlight',
            'EventsHighlight',
            'EventsListing',
            'ContentList',
            'TeachingListing',
            'PressFeed',
            'CVAccess',
            'Intro',
        ], collect(DefaultTemplate::blocks())->map->getName()->all());
    }

    public function test_empty_or_replaced_resources_are_hidden_from_navigation(): void
    {
        $this->assertFalse(BannerResource::shouldRegisterNavigation());
        $this->assertFalse(DossierPrensaResource::shouldRegisterNavigation());
        $this->assertFalse(EntrevistaResource::shouldRegisterNavigation());
        $this->assertFalse(ProgramaAcademicoResource::shouldRegisterNavigation());
    }

    public function test_block_inventory_uses_the_runtime_template_registration(): void
    {
        $this->assertSame(0, Artisan::call('cms:blocks-list', ['--json' => true]));

        $payload = json_decode(Artisan::output(), true, flags: JSON_THROW_ON_ERROR);
        $hero = collect($payload['blocks'])->firstWhere(0, 'Hero');

        $this->assertSame('REGISTRADO', $hero[1]);
        $this->assertSame('Si', $hero[5]);
    }

    public function test_publication_preview_accepts_filaments_associative_upload_state(): void
    {
        $html = view('blocks.PublicationsHighlight', [
            'image' => ['temporary-upload-key' => 'images/example-cover.png'],
            'preview' => true,
        ])->render();

        $this->assertStringContainsString('/storage/images/example-cover.png', $html);
        $this->assertStringNotContainsString('Seleccioná una publicación con portada', $html);
    }
}
