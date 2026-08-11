<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\Image;
use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\EventoResource\Pages;
use App\Models\Evento;
use Filament\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventoResource extends ResourceBase
{
    protected static ?string $model = Evento::class;

    protected static ?string $modelLabel = 'Eventos';

    protected static ?string $navigationLabel = 'Jornadas y congresos';

    public static function getNavigationGroup(): ?string
    {
        return 'Actividad académica';
    }

    protected static ?int $navigationSort = 50;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-calendar-days';
    }

    protected static function mainTab(Schema $schema): array
    {
        return [
            TextInput::make('route.title')
                ->label('Título')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $operation, ?string $old, ?string $state, ?Model $record) {
                    if ($operation === 'edit' && $record?->isPublished()) {
                        return;
                    }
                    if (($get('route.slug') ?? '') !== Str::slug($old)) {
                        return;
                    }
                    $set('route.slug', Str::slug($state));
                }),

            RichEditor::make('descripcion')
                ->label('Descripción')
                ->toolbarButtons(config('admin.richEditor.minimal', config('admin.richEditor.basic'))),

            Grid::make(2)
                ->schema([
                    DateTimePicker::make('fecha_inicio')->label('Fecha de inicio')->required()->seconds(false),
                    DateTimePicker::make('fecha_fin')->label('Fecha de fin')->seconds(false),
                ]),

            Grid::make(3)
                ->schema([
                    TextInput::make('ubicacion')->label('Sede o espacio'),
                    TextInput::make('ciudad')->label('Ciudad'),
                    TextInput::make('pais')->label('País'),
                ]),

            Grid::make(3)
                ->schema([
                    TextInput::make('institucion')->label('Institución'),
                    TextInput::make('rol')->label('Rol o participación'),
                    TextInput::make('tema')->label('Tema o eje'),
                ]),

            Grid::make(3)
                ->schema([
                    Select::make('tipo')
                        ->label('Tipo')
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
                        ]),
                    Select::make('modalidad')
                        ->label('Modalidad')
                        ->options([
                            'presencial' => 'Presencial',
                            'virtual' => 'Virtual',
                            'hibrida' => 'Híbrida',
                        ]),
                    Select::make('estado_confirmacion')
                        ->label('Estado de confirmación')
                        ->options([
                            'pendiente' => 'Pendiente',
                            'confirmado' => 'Confirmado',
                            'cancelado' => 'Cancelado',
                        ])
                        ->default('pendiente'),
                ]),

            Grid::make(2)
                ->schema([
                    TextInput::make('enlace_inscripcion')->label('Enlace de inscripción')->url(),
                    TextInput::make('video')->label('Video')->url()->maxLength(2048),
                ]),

            Image::make('imagen', 'Imagen de la actividad', '1200', '675', 'images/eventos', forceRatio: true),

            Toggle::make('destacado')
                ->label('Destacado')
                ->default(false),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('route.title')
                    ->label('Actividad')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('institucion')
                    ->label('Institución')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge(),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado_confirmacion')
                    ->label('Confirmación')
                    ->badge()
                    ->placeholder('—'),
            ])
            ->defaultSort('fecha_inicio')
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventos::route('/'),
            'create' => Pages\CreateEvento::route('/create'),
            'edit' => Pages\EditEvento::route('/{record}/edit'),
        ];
    }
}
