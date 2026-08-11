<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Filament\Forms\Components\Gallery;

class GalleryBlock extends PageBlock
{
    protected const NAME = 'Gallery';

    protected const CATEGORY = 'Multimedia';

    protected const LABEL = 'Galería de imágenes';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título'),
            Gallery::make('images', 'Imágenes', 'images/galerias'),
            Field::select('style', 'Estilo', [
                'full' => 'Carrusel completo',
                'container' => 'Contenedor',
            ])
                ->default('full'),
            Field::toggleButtons('auto_play', 'Reproducción automática', [
                true => 'Sí',
                false => 'No',
            ])
                ->default(false)
                ->inline(),
        ];
    }
}
