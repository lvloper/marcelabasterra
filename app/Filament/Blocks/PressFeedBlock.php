<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Models\PublicacionMedio;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class PressFeedBlock extends PageBlock
{
    protected const NAME = 'PressFeed';

    protected const CATEGORY = 'Listados';

    protected const LABEL = 'Prensa y actualidad';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título de la sección'),
            Field::textarea('description', 'Texto introductorio')->rows(3),
            Field::select('layout', 'Diseño', [
                'featured' => 'Destacado con piezas secundarias',
                'archive' => 'Archivo editorial unificado',
            ])->default('featured')->required(),
            Field::select('heading_level', 'Nivel del título', [
                'h1' => 'H1 · Título principal de página',
                'h2' => 'H2 · Título de sección',
            ])->default('h2')->required(),
            Field::select('source_mode', 'Origen del contenido', [
                'media_publications' => 'Publicaciones en medios',
                'unified_news' => 'Noticias, prensa y entrevistas',
            ])->default('media_publications')->required(),
            CheckboxList::make('content_types')
                ->label('Tipos de contenido')
                ->options([
                    'articulo' => 'Artículo en medio',
                    'entrevista' => 'Entrevista',
                    'noticia' => 'Noticia o nota periodística',
                ])
                ->default(['articulo', 'entrevista', 'noticia'])
                ->required(),
            Field::text('media', 'Medios a incluir')
                ->helperText('Separar medios por coma. Vacío incluye todos.'),
            Select::make('selected_items')
                ->label('Publicaciones seleccionadas')
                ->multiple()
                ->options(fn (): array => PublicacionMedio::with('route')
                    ->get()
                    ->mapWithKeys(fn (PublicacionMedio $item): array => [
                        $item->id => $item->title ?: "Publicación #{$item->id}",
                    ])
                    ->all())
                ->searchable(),
            TextInput::make('max_items')
                ->label('Items visibles o por página')
                ->numeric()
                ->default(6)
                ->minValue(1)
                ->maxValue(24)
                ->required(),
            Toggle::make('show_filters')
                ->label('Mostrar filtros')
                ->default(false),
            Toggle::make('show_search')
                ->label('Mostrar buscador')
                ->default(false),
            Toggle::make('show_image')
                ->label('Mostrar imagen cuando exista')
                ->default(true),
            Field::text('empty_message', 'Mensaje sin resultados'),
        ];
    }
}
