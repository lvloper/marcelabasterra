<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Forms\Components\Field;
use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\InstitucionAcademicaResource\Pages;
use App\Models\InstitucionAcademica;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class InstitucionAcademicaResource extends ResourceBase
{
    protected static ?string $model = InstitucionAcademica::class;

    protected static ?string $modelLabel = 'Institución académica';

    protected static ?string $pluralModelLabel = 'Instituciones académicas';

    public static function getNavigationGroup(): ?string
    {
        return 'Actividad académica';
    }

    protected static ?int $navigationSort = 20;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-building-library';
    }

    protected static function mainTab(Schema $schema): array
    {
        return [
            TextInput::make('route.title')
                ->label('Nombre')
                ->required(),
            Grid::make(3)->schema([
                TextInput::make('sigla')->label('Sigla'),
                TextInput::make('pais')->label('País'),
                Field::select('alcance', 'Alcance', [
                    'nacional' => 'Nacional',
                    'internacional' => 'Internacional',
                ])->required()->default('nacional'),
            ]),
            Grid::make(2)->schema([
                TextInput::make('sitio_web')->label('Sitio web')->url(),
                TextInput::make('orden')->label('Orden')->numeric()->default(0),
            ]),
            Field::image('logo', 'Logotipo', '960', '480', 'images/instituciones'),
            Toggle::make('destacada')->label('Mostrar en la grilla institucional')->default(true),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstitucionesAcademicas::route('/'),
            'create' => Pages\CreateInstitucionAcademica::route('/create'),
            'edit' => Pages\EditInstitucionAcademica::route('/{record}/edit'),
        ];
    }
}
