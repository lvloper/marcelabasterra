<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Filament\Forms\Components\Gallery;
use Filament\Schemas\Components\Grid;

class BookPresentationBlock extends PageBlock
{
    protected const NAME = 'BookPresentation';

    protected const CATEGORY = 'Contenido';

    protected const LABEL = 'Presentación de libro';

    protected static function fields(): array
    {
        return [
            Field::text('intro_title', 'Título de introducción')->required(),
            Field::textarea('intro_description', 'Descripción de introducción')->rows(3),
            Field::repeater('items', 'Cards', [
                Field::text('title', 'Título')->required(),
                Field::textarea('description', 'Descripción')->rows(3),
                Field::image('image', 'Imagen', '400', '400', 'images/libros'),
            ])
                ->addActionLabel('Agregar card')
                ->columns(1)
                ->defaultItems(3)
                ->maxItems(3),
            Gallery::make('gallery', 'Galería del evento', 'images/presentaciones'),
            Grid::make(2)
                ->schema([
                    Field::text('external_url', 'URL externa (editorial)'),
                    Field::text('external_label', 'Texto del botón externo')->default('Ver obra en la editorial'),
                ]),
        ];
    }
}
