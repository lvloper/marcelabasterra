<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

class TimelineBlock extends PageBlock
{
    protected const NAME = 'Timeline';

    protected const CATEGORY = 'Contenido';

    protected const LABEL = 'Línea de tiempo';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título de la sección'),
            Field::repeater('items', 'Hitos', [
                Field::text('year', 'Año')->required(),
                Field::text('title', 'Título del hito')->required(),
                Field::textarea('description', 'Descripción')->rows(3),
            ])
                ->addActionLabel('Agregar hito')
                ->columns(1)
                ->defaultItems(3),
        ];
    }
}
