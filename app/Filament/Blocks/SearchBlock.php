<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

class SearchBlock extends PageBlock
{
    protected const NAME = 'Search';

    protected const LABEL = 'Búsqueda';

    protected const CATEGORY = 'Contenido';

    protected static function fields(): array
    {
        return [
            Field::select('mode', 'Tipo de búsqueda', [
                'global' => 'Búsqueda global',
                'academic_catalog' => 'Actualidad y producción académica',
            ])->default('global')->required(),
            Field::text('title', 'Título')->default('Buscar'),
            Field::textarea('description', 'Descripción')
                ->default('Buscá en el sitio lo que necesitás.')
                ->rows(2),
            Field::number('items_per_page', 'Resultados por página')
                ->default(12)
                ->minValue(6)
                ->maxValue(24),
        ];
    }
}
