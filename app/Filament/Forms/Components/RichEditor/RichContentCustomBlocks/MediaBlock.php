<?php

namespace App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'media';
    }

    public static function getLabel(): string
    {
        return 'Imagen o video';
    }

    public static function configureEditorAction(Action $action): Action
    {
        $disk = config('admin.contentMedia.disk');
        $visibility = config('admin.contentMedia.visibility');

        return $action
            ->modalDescription('Subí una imagen o un video para insertarlo en el cuerpo de la publicación.')
            ->schema([
                ToggleButtons::make('type')
                    ->label('Tipo de contenido')
                    ->options([
                        'image' => 'Imagen',
                        'video' => 'Video',
                    ])
                    ->icons([
                        'image' => 'heroicon-o-photo',
                        'video' => 'heroicon-o-video-camera',
                    ])
                    ->default('image')
                    ->inline()
                    ->live()
                    ->required(),

                Grid::make(2)
                    ->schema([
                        FileUpload::make('image')
                            ->label('Imagen')
                            ->image()
                            ->imageEditor()
                            ->orientImagesFromExif()
                            ->acceptedFileTypes(config('admin.contentMedia.imageMimeTypes'))
                            ->maxSize(config('admin.contentMedia.imageMaxSize'))
                            ->disk($disk)
                            ->directory(config('admin.contentMedia.imageDirectory'))
                            ->visibility($visibility)
                            ->openable()
                            ->downloadable()
                            ->required(fn (Get $get): bool => $get('type') === 'image')
                            ->hidden(fn (Get $get): bool => $get('type') !== 'image')
                            ->columnSpanFull(),

                        FileUpload::make('video')
                            ->label('Video')
                            ->acceptedFileTypes(config('admin.contentMedia.videoMimeTypes'))
                            ->maxSize(config('admin.contentMedia.videoMaxSize'))
                            ->disk($disk)
                            ->directory(config('admin.contentMedia.videoDirectory'))
                            ->visibility($visibility)
                            ->openable()
                            ->downloadable()
                            ->required(fn (Get $get): bool => $get('type') === 'video')
                            ->hidden(fn (Get $get): bool => $get('type') !== 'video')
                            ->helperText('Formatos permitidos: MP4 y WebM. Tamaño máximo: '.intdiv((int) config('admin.contentMedia.videoMaxSize'), 1024).' MB.')
                            ->columnSpanFull(),

                        FileUpload::make('poster')
                            ->label('Portada del video (opcional)')
                            ->image()
                            ->imageEditor()
                            ->orientImagesFromExif()
                            ->acceptedFileTypes(config('admin.contentMedia.imageMimeTypes'))
                            ->maxSize(config('admin.contentMedia.imageMaxSize'))
                            ->disk($disk)
                            ->directory(config('admin.contentMedia.posterDirectory'))
                            ->visibility($visibility)
                            ->openable()
                            ->downloadable()
                            ->hidden(fn (Get $get): bool => $get('type') !== 'video')
                            ->columnSpanFull(),

                        TextInput::make('alt')
                            ->label('Texto alternativo')
                            ->helperText('Describí brevemente la imagen para lectores de pantalla.')
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('type') === 'image')
                            ->hidden(fn (Get $get): bool => $get('type') !== 'image')
                            ->columnSpanFull(),

                        Textarea::make('caption')
                            ->label('Epígrafe (opcional)')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getPreviewLabel(array $config): string
    {
        $type = ($config['type'] ?? 'image') === 'video' ? 'Video' : 'Imagen';
        $caption = Str::limit((string) ($config['caption'] ?? ''), 60);

        return filled($caption) ? "{$type}: {$caption}" : $type;
    }

    public static function toPreviewHtml(array $config): ?string
    {
        return static::render($config, isPreview: true);
    }

    public static function toHtml(array $config, array $data): ?string
    {
        return static::render($config, isPreview: false);
    }

    protected static function render(array $config, bool $isPreview): ?string
    {
        $type = ($config['type'] ?? 'image') === 'video' ? 'video' : 'image';
        $path = $config[$type] ?? null;

        if (blank($path)) {
            return null;
        }

        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.media', [
            'type' => $type,
            'mediaUrl' => static::storageUrl($path),
            'posterUrl' => static::storageUrl($config['poster'] ?? null),
            'videoMimeType' => static::videoMimeType($path),
            'alt' => (string) ($config['alt'] ?? ''),
            'caption' => (string) ($config['caption'] ?? ''),
            'isPreview' => $isPreview,
        ])->render();
    }

    protected static function storageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Storage::disk(config('admin.contentMedia.disk'))->url($path);
    }

    protected static function videoMimeType(string $path): string
    {
        return Str::lower(pathinfo($path, PATHINFO_EXTENSION)) === 'webm'
            ? 'video/webm'
            : 'video/mp4';
    }
}
