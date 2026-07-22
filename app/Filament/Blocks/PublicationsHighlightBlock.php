<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Models\ArticuloAcademico;
use App\Models\Libro;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;

class PublicationsHighlightBlock extends PageBlock
{
    protected const NAME = 'PublicationsHighlight';

    protected const CATEGORY = 'Listados';

    protected const LABEL = 'Publicaciones destacadas';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título de la sección'),
            Field::textarea('description', 'Texto introductorio')->rows(3),
            Field::select('layout', 'Presentación', [
                'featured' => 'Publicación protagonista',
                'library_grid' => 'Biblioteca en grilla',
            ])->default('featured')->required(),
            ToggleButtons::make('source_mode')
                ->label('Fuente')
                ->options(['latest' => 'Más recientes', 'manual' => 'Selección manual'])
                ->default('manual')
                ->inline()
                ->required(),
            Select::make('libros')
                ->label('Libros destacados')
                ->multiple()
                ->options(fn () => Libro::pluck('subtitulo', 'id')->map(fn ($v, $k) => $v ?: "Libro #{$k}"))
                ->searchable(),
            Select::make('articulos')
                ->label('Artículos destacados')
                ->multiple()
                ->options(fn () => ArticuloAcademico::pluck('resumen', 'id')->map(fn ($v, $k) => $v ? "Artículo #{$k}" : "Artículo #{$k}"))
                ->searchable(),
            TextInput::make('max_items')
                ->label('Máximo de items')
                ->numeric()
                ->default(6)
                ->minValue(1),
            Toggle::make('show_type_label')
                ->label('Mostrar etiqueta de tipo')
                ->default(true),
            Field::text('cta_label', 'Texto del enlace general'),
            Field::route('cta_route', 'Página de publicaciones'),
        ];
    }
}
