<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\DocenciaResource\Pages;
use App\Models\Docencia;
use App\Models\InstitucionAcademica;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocenciaResource extends ResourceBase
{
    protected static ?string $model = Docencia::class;

    protected static ?string $modelLabel = 'Docencia';

    public static function getNavigationGroup(): ?string
    {
        return 'Actividad académica';
    }

    protected static ?int $navigationSort = 10;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-presentation-chart-bar';
    }

    protected static function mainTab(Schema $schema): array
    {
        return [
            TextInput::make('route.title')
                ->label('Título')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $operation, ?string $old, ?string $state, ?Model $record) {
                    if ($operation === 'edit' && $record?->isPublished()) return;
                    if (($get('route.slug') ?? '') !== Str::slug($old)) return;
                    $set('route.slug', Str::slug($state));
                }),

            Grid::make(2)
                ->schema([
                    Select::make('institucion_academica_id')
                        ->label('Institución académica')
                        ->options(fn (): array => InstitucionAcademica::query()
                            ->with('route')
                            ->orderBy('orden')
                            ->get()
                            ->mapWithKeys(fn (InstitucionAcademica $institution): array => [
                                $institution->id => $institution->title ?: ($institution->sigla ?: "Institución #{$institution->id}"),
                            ])->all())
                        ->searchable()
                        ->required(),
                    Select::make('nivel')
                        ->label('Nivel')
                        ->options([
                            'grado' => 'Grado',
                            'posgrado' => 'Posgrado',
                            'maestria' => 'Maestría',
                            'doctorado' => 'Doctorado',
                            'otro' => 'Otro',
                        ]),
                ]),

            Grid::make(2)
                ->schema([
                    TextInput::make('facultad')->label('Facultad / unidad académica'),
                    TextInput::make('programa')->label('Carrera / programa')->required(),
                ]),

            Grid::make(2)
                ->schema([
                    TextInput::make('materia')->label('Materia / tema'),
                    TextInput::make('catedra')->label('Cátedra'),
                ]),

            Grid::make(3)
                ->schema([
                    TextInput::make('rol')->label('Rol docente'),
                    Select::make('modalidad')
                        ->label('Modalidad')
                        ->options([
                            'presencial' => 'Presencial',
                            'distancia' => 'A distancia',
                            'hibrida' => 'Híbrida',
                        ]),
                    TextInput::make('periodo')->label('Período')->placeholder('2025/2026'),
                ]),

            Grid::make(3)
                ->schema([
                    TextInput::make('enlace')->label('Enlace institucional')->url(),
                    TextInput::make('orden')->label('Orden')->numeric()->default(0),
                    Toggle::make('vigente')->label('Actividad vigente')->default(true),
                ]),

            RichEditor::make('descripcion')
                ->label('Descripción')
                ->toolbarButtons(config('admin.richEditor.minimal', config('admin.richEditor.basic'))),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocencias::route('/'),
            'create' => Pages\CreateDocencia::route('/create'),
            'edit' => Pages\EditDocencia::route('/{record}/edit'),
        ];
    }
}
