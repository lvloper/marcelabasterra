<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

class HeroBlock extends PageBlock
{
    protected const NAME = 'Hero';

    protected const CATEGORY = 'Hero';

    protected const LABEL = 'Hero';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título principal')->required(),
            Field::textarea('subtitle', 'Subtítulo')->rows(3),
            Field::image('image', 'Imagen de fondo', '1920', '1080', 'images/hero'),
            Field::text('cta_label', 'Texto del botón'),
            Field::route('cta_route', 'Ruta del CTA'),
        ];
    }
}
