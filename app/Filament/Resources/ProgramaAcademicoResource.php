<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\ProgramaAcademicoResource\Pages;
use App\Models\ProgramaAcademico;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProgramaAcademicoResource extends ResourceBase
{
    protected static ?string $model = ProgramaAcademico::class;

    protected static ?string $modelLabel = 'Programas Académicos';

    public static function getNavigationGroup(): ?string
    {
        return 'Actividad académica';
    }

    protected static ?int $navigationSort = 30;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-academic-cap';
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

            Grid::make(3)
                ->schema([
                    TextInput::make('institucion')->label('Institución'),
                    DatePicker::make('fecha_inicio')->label('Fecha de inicio'),
                    DatePicker::make('fecha_fin')->label('Fecha de fin'),
                ]),

            TextInput::make('enlace')->label('Enlace')->url(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProgramasAcademicos::route('/'),
            'create' => Pages\CreateProgramaAcademico::route('/create'),
            'edit' => Pages\EditProgramaAcademico::route('/{record}/edit'),
        ];
    }
}
