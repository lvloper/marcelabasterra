<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

class IntroBlock extends PageBlock
{
    protected const NAME = 'Intro';

    protected const CATEGORY = 'Contenido';

    protected const LABEL = 'Intro de sección';

    protected static function fields(): array
    {
        return [
            Field::text('tag', 'Etiqueta editorial'),
            Field::text('title', 'Título'),
            Field::select('heading_level', 'Nivel del título', [
                'h1' => 'H1 · Título principal de página',
                'h2' => 'H2 · Título de sección',
            ])->default('h2')->required(),
            Field::rich('summary', 'Resumen', 'basic')->required(),
            Field::image('photo', 'Foto', '400', '400', 'images/biography'),
            Field::text('cta_label', 'Texto del enlace'),
            Field::route('cta_route', 'Ruta del enlace'),
            Field::repeater('highlights', 'Datos destacados', [
                Field::text('number', 'Número')->placeholder('Ej: 25+'),
                Field::text('label', 'Etiqueta')->placeholder('Ej: años de docencia'),
            ])->columns(2)->defaultItems(0),
        ];
    }
}
