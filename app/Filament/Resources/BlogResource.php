<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\Image;
use App\Filament\Resources\Bases\ResourceBase;
use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogResource extends ResourceBase
{
    protected static ?string $model = Blog::class;

    protected static ?string $modelLabel = 'Novedades';

    protected static ?string $navigationLabel = 'Noticias';

    protected static string|\UnitEnum|null $navigationGroup = 'Actualidad';

    protected static ?int $navigationSort = 10;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

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

            Grid::make(2)
                ->schema([
                    Image::make(
                        name: 'image',
                        label: 'Imagen',
                        width: '1910',
                        height: '1000',
                    ),
                    Group::make()
                        ->schema([
                            SpatieTagsInput::make('tags')->label('Nube de tags'),
                            Toggle::make('is_featured')->label('Destacada'),
                        ]),
                ]),
            RichEditor::make('description')
                ->label('Descripción')
                ->toolbarButtons(config('admin.richEditor.minimal', config('admin.richEditor.basic'))),

            RichEditor::make('content')
                ->label('Contenido')
                ->toolbarButtons(config('admin.richEditor.advanced', config('admin.richEditor.basic')))
                ->fileAttachmentsDisk(config('admin.contentMedia.disk'))
                ->fileAttachmentsDirectory(config('admin.contentMedia.imageDirectory'))
                ->fileAttachmentsVisibility(config('admin.contentMedia.visibility'))
                ->fileAttachmentsAcceptedFileTypes(config('admin.contentMedia.imageMimeTypes'))
                ->fileAttachmentsMaxSize(config('admin.contentMedia.imageMaxSize'))
                ->resizableImages()
                ->extraInputAttributes(['class' => 'editorial-rich-editor'])
                ->required(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
