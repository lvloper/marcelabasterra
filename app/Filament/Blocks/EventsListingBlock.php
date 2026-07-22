<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Models\Conferencia;
use App\Models\Evento;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
            Field::select('display_mode', 'Presentación', [
                'activities' => 'Listado cronológico de actividades',
                'videos' => 'Galería de videos',
            ])->default('activities')->required(),
            Toggle::make('include_conferences')
                ->label('Incluir conferencias en el listado')
                ->default(true),
            Select::make('conferencias')
                ->label('Conferencias seleccionadas')
                ->multiple()
                ->options(fn (): array => Conferencia::query()->with('route')->get()
                    ->mapWithKeys(fn (Conferencia $item): array => [$item->id => $item->title ?: "Conferencia #{$item->id}"])->all())
                ->searchable()
                ->helperText('Si queda vacío, muestra automáticamente las conferencias destacadas.'),
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
                    'seminario' => 'Seminario',
                    'taller' => 'Taller',
                    'otro' => 'Otro',
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
            Toggle::make('show_filters')
                ->label('Mostrar filtros públicos')
                ->helperText('Habilita filtros por estado, año, país y tipo de actividad.')
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
            Field::text('empty_message', 'Mensaje sin resultados')
                ->default('No hay actividades que coincidan con los filtros seleccionados.'),
        ];
    }
}
