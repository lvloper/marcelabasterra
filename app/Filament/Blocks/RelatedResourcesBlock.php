<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;

class RelatedResourcesBlock extends PageBlock
{
    protected const NAME = 'RelatedResources';

    protected const CATEGORY = 'Listados';

    protected const LABEL = 'Recursos relacionados';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título de la sección'),
            Select::make('resource_types')
                ->label('Tipos de recurso a incluir')
                ->multiple()
                ->options([
                    'libro' => 'Libro',
                    'articulo' => 'Artículo Académico',
                    'entrevista' => 'Entrevista',
                ])
                ->default(['libro', 'articulo', 'entrevista']),
            TagsInput::make('tags')
                ->label('Filtrar por tags / temática')
                ->placeholder('Agregar tag'),
            TextInput::make('max_items')
                ->label('Máximo de items')
                ->numeric()
                ->default(4)
                ->minValue(1),
        ];
    }
}
