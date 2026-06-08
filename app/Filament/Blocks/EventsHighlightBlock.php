<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Models\Evento;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class EventsHighlightBlock extends PageBlock
{
    protected const NAME = 'EventsHighlight';

    protected const CATEGORY = 'Listados';

    protected const LABEL = 'Eventos destacados';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título de la sección'),
            Field::textarea('description', 'Texto introductorio')->rows(3),
            Select::make('eventos')
                ->label('Eventos seleccionados')
                ->multiple()
                ->options(fn () => Evento::pluck('descripcion', 'id')->map(fn ($v, $k) => $v ?: "Evento #{$k}"))
                ->searchable(),
            TextInput::make('max_items')
                ->label('Máximo de items')
                ->numeric()
                ->default(6)
                ->minValue(1),
            Toggle::make('show_past')
                ->label('Mostrar eventos pasados')
                ->default(false)
                ->helperText('Si se desactiva, solo se muestran eventos con fecha_inicio >= ahora'),
        ];
    }
}
