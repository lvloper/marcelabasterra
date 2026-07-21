<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\Image;
use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\ConferenciaResource\Pages;
use App\Models\Conferencia;
use Filament\Forms\Components\DatePicker;
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

class ConferenciaResource extends ResourceBase
{
    protected static ?string $model = Conferencia::class;
    protected static ?string $modelLabel = 'Conferencia';
    protected static ?string $pluralModelLabel = 'Conferencias';

    public static function getNavigationGroup(): ?string { return 'Actividad académica'; }
    public static function getNavigationIcon(): string { return 'heroicon-o-video-camera'; }

    protected static function mainTab(Schema $schema): array
    {
        return [
            TextInput::make('route.title')->label('Título')->required()->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $operation, ?string $old, ?string $state, ?Model $record): void {
                    if ($operation === 'edit' && $record?->isPublished()) return;
                    if (($get('route.slug') ?? '') !== Str::slug($old)) return;
                    $set('route.slug', Str::slug($state));
                }),
            Grid::make(3)->schema([
                Select::make('tipo')->label('Tipo')->options([
                    'conferencia' => 'Conferencia', 'exposicion' => 'Exposición',
                    'entrevista' => 'Entrevista', 'panel' => 'Panel',
                ])->default('conferencia')->required(),
                TextInput::make('institucion')->label('Institución o medio'),
                DatePicker::make('fecha')->label('Fecha'),
            ]),
            RichEditor::make('descripcion')->label('Descripción')
                ->toolbarButtons(config('admin.richEditor.minimal', config('admin.richEditor.basic'))),
            Image::make('imagen', 'Imagen', '1200', '675', 'images/conferencias', forceRatio: true),
            Grid::make(2)->schema([
                TextInput::make('external_url')->label('URL de YouTube o fuente')->url()->required()->maxLength(2048),
                TextInput::make('link_label')->label('Texto del enlace')->default('Ver conferencia')->required(),
            ]),
            Toggle::make('destacado')->label('Destacada')->default(false),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConferencias::route('/'),
            'create' => Pages\CreateConferencia::route('/create'),
            'edit' => Pages\EditConferencia::route('/{record}/edit'),
        ];
    }
}
