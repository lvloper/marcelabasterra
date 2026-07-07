<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

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
        ];
    }
}
