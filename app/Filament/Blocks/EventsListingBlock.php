<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Models\Evento;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Get;

class EventsListingBlock extends PageBlock
{
    protected const NAME = 'EventsListing';

    protected const CATEGORY = 'Listados';

    protected const LABEL = 'Listado de actividades';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título de la sección'),
            Field::textarea('description', 'Introducción')->rows(3),
            Field::repeater('manual_items', 'Conferencias y exposiciones', [
                Field::text('title', 'Título')->required(),
                Field::select('type', 'Tipo', [
                    'conferencia' => 'Conferencia',
                    'exposicion' => 'Exposición',
                    'entrevista' => 'Entrevista',
                    'panel' => 'Panel',
                ])->required(),
                Field::text('institution', 'Institución o medio'),
                DatePicker::make('date')->label('Fecha'),
                Field::textarea('description', 'Descripción')->rows(3),
                Field::image('image', 'Imagen', '1200', '675', 'images/conferencias', true),
                Field::text('url', 'URL de YouTube o UBA')->url()->required(),
                Field::text('link_label', 'Texto del enlace')->default('Ver conferencia'),
            ])->collapsible()->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
            Select::make('status')
                ->label('Estado a mostrar')
                ->options([
                    'upcoming' => 'Próximas',
                    'past' => 'Realizadas',
                    'all' => 'Todas',
                ])
                ->default('upcoming')
                ->required(),
            CheckboxList::make('event_types')
                ->label('Tipos de actividad')
                ->options([
                    'jornada' => 'Jornada',
                    'congreso' => 'Congreso',
                    'clase' => 'Clase',
                    'conferencia' => 'Conferencia',
                    'exposicion' => 'Exposición',
                    'panel' => 'Panel',
                    'presentacion' => 'Presentación',
                ])
                ->columns(2),
            Select::make('selected_events')
                ->label('Actividades seleccionadas')
                ->options(fn (): array => Evento::query()
                    ->with('route')
                    ->get()
                    ->mapWithKeys(fn (Evento $evento): array => [
                        $evento->id => $evento->title ?: "Actividad #{$evento->id}",
                    ])
                    ->all())
                ->multiple()
                ->searchable()
                ->helperText('Si se completa, esta selección reemplaza la consulta automática.'),
            TextInput::make('max_items')
                ->label('Máximo de actividades')
                ->numeric()
                ->minValue(1)
                ->maxValue(50)
                ->default(12)
                ->required(),
            Toggle::make('show_image')
                ->label('Mostrar imagen')
                ->default(true)
                ->required(),
            Toggle::make('show_description')
                ->label('Mostrar resumen')
                ->default(true)
                ->required(),
            Toggle::make('show_empty_fallback')
                ->label('Mostrar enlace alternativo si no hay resultados')
                ->default(false)
                ->live(),
            Field::route('fallback_route', 'Enlace alternativo')
                ->visible(fn (Get $get): bool => (bool) $get('show_empty_fallback')),
            Field::text('fallback_label', 'Texto del enlace alternativo')
                ->default('Ver actividades realizadas')
                ->visible(fn (Get $get): bool => (bool) $get('show_empty_fallback')),
        ];
    }
}
