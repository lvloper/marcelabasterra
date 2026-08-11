<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticuloAcademicoResource\Pages;
use App\Filament\Resources\Bases\ResourceBase;
use App\Models\ArticuloAcademico;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArticuloAcademicoResource extends ResourceBase
{
    protected static ?string $model = ArticuloAcademico::class;

    protected static ?string $modelLabel = 'Artículos Académicos';

    public static function getNavigationGroup(): ?string
    {
        return 'Publicaciones';
    }

    protected static ?int $navigationSort = 20;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-document-text';
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

            RichEditor::make('resumen')
                ->label('Resumen')
                ->toolbarButtons(config('admin.richEditor.minimal', config('admin.richEditor.basic'))),

            RichEditor::make('contenido')
                ->label('Contenido')
                ->toolbarButtons(config('admin.richEditor.avanced', config('admin.richEditor.basic'))),

            \Filament\Schemas\Components\Grid::make(3)
                ->schema([
                    DatePicker::make('fecha_publicacion')->label('Fecha de publicación'),
                    TextInput::make('tematica')
                        ->label('Temática'),
                    FileUpload::make('archivo_pdf')
                        ->label('Subir PDF')
                        ->acceptedFileTypes(['application/pdf'])
                        ->directory('pdfs/articulos'),
                ]),

            TextInput::make('archivo_pdf_url')
                ->label('URL externa del PDF')
                ->helperText('Alternativa al archivo subido. Si ambos existen, se utiliza esta URL.')
                ->url(),

            Toggle::make('destacado')
                ->label('Destacado')
                ->default(false),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticulosAcademicos::route('/'),
            'create' => Pages\CreateArticuloAcademico::route('/create'),
            'edit' => Pages\EditArticuloAcademico::route('/{record}/edit'),
        ];
    }
}
