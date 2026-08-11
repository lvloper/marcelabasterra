<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\Image;
use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\LibroResource\Pages;
use App\Models\Libro;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LibroResource extends ResourceBase
{
    protected static ?string $model = Libro::class;

    protected static ?string $modelLabel = 'Libros';

    public static function getNavigationGroup(): ?string
    {
        return 'Publicaciones';
    }

    protected static ?int $navigationSort = 10;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-book-open';
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

            TextInput::make('subtitulo')
                ->label('Subtítulo'),

            TextInput::make('autoria')
                ->label('Autoría'),

            Image::make('portada', 'Portada', '400', '600', 'libros'),

            RichEditor::make('descripcion')
                ->label('Descripción')
                ->toolbarButtons(config('admin.richEditor.minimal', config('admin.richEditor.basic'))),

            \Filament\Schemas\Components\Grid::make(3)
                ->schema([
                    DatePicker::make('fecha_publicacion')->label('Fecha de publicación'),
                    TextInput::make('editorial')->label('Editorial'),
                    TextInput::make('isbn')->label('ISBN'),
                ]),

            TextInput::make('area_tematica')
                ->label('Área temática'),

            \Filament\Schemas\Components\Select::make('categoria')
                ->label('Categoría')
                ->options([
                    'autoria' => 'Libros',
                    'coautoria' => 'Libros en coautoría',
                    'direccion' => 'Dirección de libros',
                ])
                ->default('autoria'),

            Repeater::make('tomos')
                ->label('Tomos')
                ->schema([
                    TextInput::make('nombre')->label('Nombre del tomo')->required(),
                    TextInput::make('paginas')->label('Páginas')->numeric()->minValue(1),
                    TextInput::make('isbn')->label('ISBN'),
                ])
                ->columns(3)
                ->collapsible()
                ->collapsed(),

            Repeater::make('enlaces')
                ->label('Enlaces')
                ->schema([
                    TextInput::make('label')->label('Texto del enlace')->required(),
                    TextInput::make('url')->label('URL')->url()->required(),
                ])
                ->collapsible()
                ->collapsed(),

            Toggle::make('destacado')
                ->label('Destacado')
                ->default(false),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLibros::route('/'),
            'create' => Pages\CreateLibro::route('/create'),
            'edit' => Pages\EditLibro::route('/{record}/edit'),
        ];
    }
}
