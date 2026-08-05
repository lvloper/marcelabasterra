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
            Field::text('tag', 'Etiqueta editorial'),
            Field::text('title', 'Título'),
            Field::select('heading_level', 'Nivel del título', [
                'h1' => 'H1 · Título principal de página',
                'h2' => 'H2 · Título de sección',
            ])->default('h2')->required(),
            Field::rich('summary', 'Resumen biográfico', 'basic')->required(),
            Field::image('photo', 'Foto de perfil', '400', '400', 'images/biography'),
            Field::text('cta_label', 'Texto del botón'),
            Field::route('cta_route', 'Ruta del botón'),
            Field::repeater('highlights', 'Datos destacados', [
                Field::text('number', 'Número')->placeholder('Ej: 25+'),
                Field::text('label', 'Etiqueta')->placeholder('Ej: años de docencia'),
            ])->columns(2)->defaultItems(0),
        ];
    }
}
