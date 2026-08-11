<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

class CTABlock extends PageBlock
{
    protected const NAME = 'CTA';

    protected const CATEGORY = 'Interacción';

    protected const LABEL = 'Llamado a la acción';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título'),
            Field::textarea('text', 'Texto descriptivo')->rows(3),
            Field::text('button_label', 'Texto del botón')->required(),
            Field::route('button_route', 'Ruta del botón')->required(),
        ];
    }
}
