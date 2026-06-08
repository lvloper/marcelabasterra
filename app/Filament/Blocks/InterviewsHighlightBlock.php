<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Models\Entrevista;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class InterviewsHighlightBlock extends PageBlock
{
    protected const NAME = 'InterviewsHighlight';

    protected const CATEGORY = 'Listados';

    protected const LABEL = 'Entrevistas destacadas';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título de la sección'),
            Field::textarea('description', 'Texto introductorio')->rows(3),
            Select::make('entrevistas')
                ->label('Entrevistas seleccionadas')
                ->multiple()
                ->options(fn () => Entrevista::pluck('medio', 'id')->map(fn ($v, $k) => $v ?: "Entrevista #{$k}"))
                ->searchable(),
            TextInput::make('max_items')
                ->label('Máximo de items')
                ->numeric()
                ->default(6)
                ->minValue(1),
        ];
    }
}
