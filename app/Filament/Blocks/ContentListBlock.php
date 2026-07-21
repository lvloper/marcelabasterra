<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

class ContentListBlock extends PageBlock
{
    protected const NAME = 'ContentList';

    protected const CATEGORY = 'Listados';

    protected const LABEL = 'Listado de contenido';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título de la sección'),
            Field::textarea('description', 'Introducción breve')->rows(3),
            Field::repeater('items', 'Ítems', [
                Field::text('meta', 'Etiqueta o metadato'),
                Field::text('title', 'Título'),
                Field::textarea('text', 'Texto')->rows(3),
                Field::text('url', 'Enlace (URL)')->url(),
                Field::text('link_label', 'Texto del enlace'),
            ])
                ->addActionLabel('Agregar ítem')
                ->reorderable()
                ->columns(1)
                ->defaultItems(2),
        ];
    }
}
