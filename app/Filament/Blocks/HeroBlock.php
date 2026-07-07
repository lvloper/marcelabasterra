<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

class HeroBlock extends PageBlock
{
    protected const NAME = 'Hero';

    protected const LABEL = 'Hero';

    protected const CATEGORY = 'Contenido';

    protected static function fields(): array
    {
        return [
            Field::image('profile_photo', 'Foto de perfil', '800', '1200', 'images/hero'),
            Field::text('badge', 'Etiqueta superior'),
            Field::text('name', 'Nombre completo'),
            Field::text('subtitle', 'Subtitulo'),
            Field::textarea('description', 'Descripcion'),
            Field::repeater('indicators', 'Indicadores', [
                Field::text('label', 'Texto'),
            ])->collapsible(),
            Field::route('cta_primary', 'Ver CV')->buttonLabel(),
            Field::route('cta_secondary', 'Ver publicaciones')->buttonLabel(),
            Field::route('cta_tertiary', 'Actualidad')->buttonLabel(),
        ];
    }
}
