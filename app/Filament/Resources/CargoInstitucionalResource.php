<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\CargoInstitucionalResource\Pages;
use App\Models\CargoInstitucional;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CargoInstitucionalResource extends ResourceBase
{
    protected static ?string $model = CargoInstitucional::class;

    protected static ?string $modelLabel = 'Cargos Institucionales';

    public static function getNavigationGroup(): ?string
    {
        return 'Trayectoria';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-briefcase';
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

            TextInput::make('cargo')
                ->label('Cargo')
                ->required(),

            \Filament\Schemas\Components\Grid::make(3)
                ->schema([
                    TextInput::make('institucion')->label('Institución'),
                    DatePicker::make('fecha_inicio')->label('Fecha de inicio'),
                    DatePicker::make('fecha_fin')->label('Fecha de fin'),
                ]),

            RichEditor::make('descripcion')
                ->label('Descripción')
                ->toolbarButtons(config('admin.richEditor.minimal', config('admin.richEditor.basic'))),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCargosInstitucionales::route('/'),
            'create' => Pages\CreateCargoInstitucional::route('/create'),
            'edit' => Pages\EditCargoInstitucional::route('/{record}/edit'),
        ];
    }
}
