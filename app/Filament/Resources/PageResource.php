<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

class PageResource extends ResourceBase
{
    protected static ?string $model = Page::class;

    protected static ?string $modelLabel = 'Página';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-square-2-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Sitio';

    protected static ?int $navigationSort = 10;

    protected static function mainTab(Schema $schema): array
    {
        $record = $schema->getRecord();
        $specializedRoutes = [
            'publicaciones',
            'publicaciones/libros',
            'publicaciones/articulos-academicos',
        ];

        if ($record instanceof Page && in_array($record->route?->full_slug, $specializedRoutes, true)) {
            return [
                Section::make('Contenido administrado desde Publicaciones')
                    ->description('Esta URL usa una vista especializada y no renderiza bloques de página.')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Text::make('Gestioná el contenido desde Libros y Artículos académicos. En esta pantalla sólo corresponde editar la configuración de página y el SEO.'),
                    ]),
            ];
        }

        return parent::mainTab($schema);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
