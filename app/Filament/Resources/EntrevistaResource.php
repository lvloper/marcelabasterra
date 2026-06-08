<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\EntrevistaResource\Pages;
use App\Models\Entrevista;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EntrevistaResource extends ResourceBase
{
    protected static ?string $model = Entrevista::class;

    protected static ?string $modelLabel = 'Entrevistas';

    public static function getNavigationGroup(): ?string
    {
        return 'Prensa';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-microphone';
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
                    TextInput::make('medio')->label('Medio'),
                    DatePicker::make('fecha')->label('Fecha'),
                ]),

            \Filament\Schemas\Components\Grid::make(2)
                ->schema([
                    TextInput::make('enlace')->label('Enlace')->url(),
                    TextInput::make('video')->label('Video (YouTube/Vimeo)')->url(),
                ]),

            RichEditor::make('descripcion')
                ->label('Descripción')
                ->toolbarButtons(config('admin.richEditor.minimal', config('admin.richEditor.basic'))),

            Toggle::make('destacado')
                ->label('Destacado')
                ->default(false),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntrevistas::route('/'),
            'create' => Pages\CreateEntrevista::route('/create'),
            'edit' => Pages\EditEntrevista::route('/{record}/edit'),
        ];
    }
}
