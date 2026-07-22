<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Models\Conferencia;
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
            Field::select('source_mode', 'Origen', [
                'automatic' => 'Próximo evento o último realizado',
                'selected' => 'Selección editorial',
            ])->default('automatic')->required(),
            Select::make('eventos')
                ->label('Eventos seleccionados')
                ->multiple()
                ->options(fn (): array => Evento::query()->with('route')->get()
                    ->mapWithKeys(fn (Evento $event): array => [$event->id => $event->title ?: "Evento #{$event->id}"])
                    ->all())
                ->searchable(),
            Select::make('conferencias')
                ->label('Conferencias seleccionadas')
                ->multiple()
                ->options(fn (): array => Conferencia::query()->with('route')->get()
                    ->mapWithKeys(fn (Conferencia $conference): array => [$conference->id => $conference->title ?: "Conferencia #{$conference->id}"])
                    ->all())
                ->searchable(),
            Toggle::make('include_conferences')
                ->label('Incluir conferencias')
                ->default(true),
            TextInput::make('max_items')
                ->label('Máximo de items')
                ->numeric()
                ->default(1)
                ->minValue(1)
                ->maxValue(3),
            Toggle::make('show_past')
                ->label('Mostrar eventos pasados')
                ->default(false)
                ->helperText('Si se desactiva, solo se muestran eventos con fecha_inicio >= ahora'),
        ];
    }
}
