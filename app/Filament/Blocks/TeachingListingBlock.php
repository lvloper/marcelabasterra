<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Models\Docencia;
use App\Models\InstitucionAcademica;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class TeachingListingBlock extends PageBlock
{
    protected const NAME = 'TeachingListing';

    protected const CATEGORY = 'Listados';

    protected const LABEL = 'Actividad docente';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título de la sección'),
            Field::textarea('description', 'Introducción')->rows(3),
            Select::make('levels')
                ->label('Niveles')
                ->multiple()
                ->options([
                    'posgrado' => 'Posgrado',
                    'maestria' => 'Maestría',
                    'doctorado' => 'Doctorado',
                ]),
            Select::make('scopes')
                ->label('Alcance')
                ->multiple()
                ->options([
                    'nacional' => 'Universidades nacionales',
                    'internacional' => 'Universidades internacionales',
                ])
                ->default(['nacional', 'internacional']),
            Select::make('institutions')
                ->label('Instituciones')
                ->multiple()
                ->options(fn (): array => InstitucionAcademica::query()
                    ->with('route')
                    ->orderBy('orden')
                    ->get()
                    ->mapWithKeys(fn (InstitucionAcademica $institution): array => [
                        $institution->id => $institution->title ?: ($institution->sigla ?: "Institución #{$institution->id}"),
                    ])->all())
                ->searchable(),
            Select::make('selected_items')
                ->label('Actividades seleccionadas')
                ->multiple()
                ->options(fn (): array => Docencia::query()
                    ->with(['route', 'institucionAcademica.route'])
                    ->orderBy('orden')
                    ->get()
                    ->mapWithKeys(fn (Docencia $teaching): array => [
                        $teaching->id => trim(($teaching->institucionAcademica?->title ?: $teaching->institucion).' · '.($teaching->programa ?: $teaching->title)),
                    ])->all())
                ->searchable()
                ->helperText('Si se seleccionan actividades, la selección prevalece sobre los filtros.'),
            Field::number('max_items', 'Máximo de actividades')
                ->default(30)
                ->minValue(1)
                ->maxValue(60)
                ->required(),
            Toggle::make('current_only')->label('Sólo actividades vigentes')->default(true),
            Toggle::make('show_description')->label('Mostrar descripciones')->default(true),
            Toggle::make('show_institutions')->label('Mostrar grilla institucional')->default(true),
            Field::repeater('student_resources', 'Material para alumnos', [
                Field::text('label', 'Nombre')->required(),
                Field::text('url', 'URL')->url()->required(),
            ])
                ->addActionLabel('Agregar recurso')
                ->reorderable()
                ->columns(1),
        ];
    }
}
