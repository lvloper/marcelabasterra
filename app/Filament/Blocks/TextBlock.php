<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

class TextBlock extends PageBlock
{
    protected const NAME = 'Text';

    protected const LABEL = 'Texto enriquecido';

    protected const CATEGORY = 'Contenido';

    protected static function fields(): array
    {
        return [
            Field::text('eyebrow', 'Volanta'),
            Field::text('title', 'Título'),
            Field::rich('content', 'Contenido', 'avanced')->required(),
            Field::image('image', 'Imagen', '800', '600', 'images/text'),
            Field::route('cta_primary', 'Botón principal')->buttonLabel(),
            Field::route('cta_secondary', 'Botón secundario')->buttonLabel(),
            Field::toggleButtons('width', 'Ancho', [
                'narrow' => 'Angosto',
                'container' => 'Contenedor',
                'wide' => 'Amplio',
            ])
                ->default('container')
                ->inline()
                ->required(),
        ];
    }
}
