<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

class HeroBlock extends PageBlock
{
    protected const NAME = 'Hero';

<<<<<<< HEAD
    protected const LABEL = 'Hero';

    protected const CATEGORY = 'Contenido';
=======
    protected const CATEGORY = 'Hero';

    protected const LABEL = 'Hero';
>>>>>>> 85ac118f44c5d2c1cda44c274bef3d05c175fc3c

    protected static function fields(): array
    {
        return [
<<<<<<< HEAD
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
=======
            Field::text('title', 'Título principal')->required(),
            Field::textarea('subtitle', 'Subtítulo')->rows(3),
            Field::image('image', 'Imagen de fondo', '1920', '1080', 'images/hero'),
            Field::text('cta_label', 'Texto del botón'),
            Field::route('cta_route', 'Ruta del CTA'),
>>>>>>> 85ac118f44c5d2c1cda44c274bef3d05c175fc3c
        ];
    }
}
