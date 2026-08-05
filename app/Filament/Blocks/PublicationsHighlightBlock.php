<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Models\Libro;
use Filament\Forms\Components\Select;

class PublicationsHighlightBlock extends PageBlock
{
    protected const NAME = 'PublicationsHighlight';

    protected const CATEGORY = 'Listados';

    protected const LABEL = 'Publicación destacada';

    protected static function fields(): array
    {
        return [
            Select::make('libro_id')
                ->label('Seleccionar libro')
                ->placeholder('Seleccioná un libro...')
                ->options(fn () => Libro::with('route')->orderByDesc('fecha_publicacion')->get()->mapWithKeys(fn ($libro) => [
                    $libro->id => ($libro->title ?? $libro->subtitulo ?: "Libro #{$libro->id}")
                        . ($libro->autoria ? " — {$libro->autoria}" : '')
                        . ($libro->fecha_publicacion?->year ? " ({$libro->fecha_publicacion->year})" : ''),
                ]))
                ->searchable(),
            Field::image('image', 'Portada principal (opcional — si no se sube, usa la del libro)'),
            Field::image('image_2', 'Portada secundaria (opcional)'),
            Field::text('title', 'Título de sección (opcional)'),
            Field::text('date', 'Año (opcional — si no se completa, usa el del libro)'),
            Field::text('subtitle', 'Subtítulo (opcional — si no se completa, usa el del libro)'),
            Field::text('publisher', 'Editorial (opcional — si no se completa, usa la del libro)'),
            Field::text('cta_label', 'Texto del botón "Ver más"'),
            Field::route('cta_route', 'Enlace del botón "Ver más"'),
        ];
    }
}
