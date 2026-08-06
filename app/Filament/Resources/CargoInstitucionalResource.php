<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\CargoInstitucionalResource\Pages;
use App\Models\CargoInstitucional;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class CargoInstitucionalResource extends ResourceBase
{
    protected static ?string $model = CargoInstitucional::class;

    protected static ?string $modelLabel = 'Cargo institucional';

    protected static ?string $pluralModelLabel = 'Cargos institucionales';

    public static function getNavigationGroup(): ?string
    {
        return 'Trayectoria';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-building-library';
    }

    protected static function mainTab(Schema $schema): array
    {
        return [
            TextInput::make('cargo')->label('Cargo')->required(),
            TextInput::make('institucion')->label('Institución')->required(),
            FileUpload::make('logo')
                ->label('Logo de la institución')
                ->image()
                ->imageEditor()
                ->directory('logos')
                ->visibility('public')
                ->helperText('Se muestra junto al cargo cuando el bloque lo soporta.'),
            TextInput::make('institutional_url')->label('Fuente institucional')->url(),
            Toggle::make('featured')->label('Destacar en el sitio'),
            Grid::make(2)->schema([
                DatePicker::make('fecha_inicio')->label('Fecha de inicio'),
                DatePicker::make('fecha_fin')->label('Fecha de finalización'),
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
