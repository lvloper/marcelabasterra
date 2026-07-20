<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\EventoResource\Pages;
use App\Models\Evento;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventoResource extends ResourceBase
{
    protected static ?string $model = Evento::class;

    protected static ?string $modelLabel = 'Eventos';

    public static function getNavigationGroup(): ?string
    {
        return 'Agenda';
    }

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
                    if ($operation === 'edit' && $record?->isPublished()) return;
                    if (($get('route.slug') ?? '') !== Str::slug($old)) return;
                    $set('route.slug', Str::slug($state));
                }),

            RichEditor::make('descripcion')
                ->label('Descripción')
                ->toolbarButtons(config('admin.richEditor.minimal', config('admin.richEditor.basic'))),

            \Filament\Schemas\Components\Grid::make(2)
                ->schema([
                    DateTimePicker::make('fecha_inicio')->label('Fecha de inicio')->required()->seconds(false),
                    DateTimePicker::make('fecha_fin')->label('Fecha de fin')->seconds(false),
                ]),

            \Filament\Schemas\Components\Grid::make(3)
                ->schema([
                    TextInput::make('ubicacion')->label('Ubicación'),
                    Select::make('tipo')
                        ->label('Tipo')
                        ->options([
                            'conferencia' => 'Conferencia',
                            'seminario' => 'Seminario',
                            'taller' => 'Taller',
                            'congreso' => 'Congreso',
                            'otro' => 'Otro',
                        ]),
                    TextInput::make('enlace_inscripcion')->label('Enlace de inscripción')->url(),
                ]),

            Toggle::make('destacado')
                ->label('Destacado')
                ->default(false),
        ];
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
