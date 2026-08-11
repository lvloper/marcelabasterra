<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\DossierPrensaResource\Pages;
use App\Models\DossierPrensa;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DossierPrensaResource extends ResourceBase
{
    protected static ?string $model = DossierPrensa::class;

    protected static ?string $modelLabel = 'Dossiers de Prensa';

    public static function getNavigationGroup(): ?string
    {
        return 'Actualidad';
    }

    protected static ?int $navigationSort = 40;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-folder-open';
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

            FileUpload::make('archivo')
                ->label('Archivo')
                ->acceptedFileTypes(['application/pdf'])
                ->directory('pdfs/dossiers'),

            RichEditor::make('descripcion')
                ->label('Descripción')
                ->toolbarButtons(config('admin.richEditor.minimal', config('admin.richEditor.basic'))),

            DatePicker::make('fecha')->label('Fecha'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDossiersPrensa::route('/'),
            'create' => Pages\CreateDossierPrensa::route('/create'),
            'edit' => Pages\EditDossierPrensa::route('/{record}/edit'),
        ];
    }
}
