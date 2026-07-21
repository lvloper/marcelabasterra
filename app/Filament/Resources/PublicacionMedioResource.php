<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\PublicacionMedioResource\Pages;
use App\Models\PublicacionMedio;
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

class PublicacionMedioResource extends ResourceBase
{
    protected static ?string $model = PublicacionMedio::class;

    protected static ?string $modelLabel = 'Publicación en medios';

    protected static ?string $pluralModelLabel = 'Publicaciones en medios';

    public static function getNavigationGroup(): ?string
    {
        return 'Prensa';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-newspaper';
    }

    protected static function mainTab(Schema $schema): array
    {
        return [
            TextInput::make('route.title')
                ->label('Título')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $operation, ?string $old, ?string $state, ?Model $record): void {
                    if ($operation === 'edit' && $record?->isPublished()) {
                        return;
                    }

                    if (($get('route.slug') ?? '') !== Str::slug($old)) {
                        return;
                    }

                    $set('route.slug', Str::slug($state));
                }),

            Grid::make(3)
                ->schema([
                    Select::make('tipo')
                        ->label('Tipo')
                        ->options([
                            'articulo' => 'Artículo en medio',
                            'entrevista' => 'Entrevista',
                            'noticia' => 'Noticia o nota periodística',
                        ])
                        ->required(),
                    TextInput::make('medio')
                        ->label('Medio o institución')
                        ->required(),
                    DatePicker::make('fecha')
                        ->label('Fecha'),
                ]),

            RichEditor::make('resumen')
                ->label('Resumen')
                ->toolbarButtons(config('admin.richEditor.minimal', config('admin.richEditor.basic'))),

            Grid::make(2)
                ->schema([
                    TextInput::make('autoria')
                        ->label('Autoría o coautoría'),
                    TextInput::make('tematica')
                        ->label('Tema'),
                ]),

            TextInput::make('enlace_externo')
                ->label('Enlace externo')
                ->url(),

            Toggle::make('destacado')
                ->label('Destacado')
                ->default(false),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublicacionesMedios::route('/'),
            'create' => Pages\CreatePublicacionMedio::route('/create'),
            'edit' => Pages\EditPublicacionMedio::route('/{record}/edit'),
        ];
    }
}
