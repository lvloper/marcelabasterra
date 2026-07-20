<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

class BiographySummaryBlock extends PageBlock
{
    protected const NAME = 'BiographySummary';

    protected const CATEGORY = 'Hero';

    protected const LABEL = 'Resumen biográfico';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título'),
            Field::rich('summary', 'Resumen biográfico', 'basic')->required(),
            Field::image('photo', 'Foto de perfil', '400', '400', 'images/biography'),
            Field::text('cta_label', 'Texto del botón'),
            Field::route('cta_route', 'Ruta del botón'),
        ];
    }
}
