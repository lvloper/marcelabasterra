<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\DocenciaResource\Pages;
use App\Models\Docencia;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
        return 'Docencia';
    }

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

            \Filament\Schemas\Components\Grid::make(2)
                ->schema([
                    TextInput::make('institucion')->label('Institución'),
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

            \Filament\Schemas\Components\Grid::make(2)
                ->schema([
                    TextInput::make('materia')->label('Materia'),
                    TextInput::make('catedra')->label('Cátedra'),
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
