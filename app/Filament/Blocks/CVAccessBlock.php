<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;

class CVAccessBlock extends PageBlock
{
    protected const NAME = 'CVAccess';

    protected const CATEGORY = 'Interacción';

    protected const LABEL = 'Accesos al CV';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título general'),
            Field::textarea('description', 'Descripción')->rows(3),
            Field::repeater('documents', 'Versiones del CV', [
                Field::select('type', 'Versión', [
                    'full' => 'CV completo',
                    'short' => 'CV reducido',
                ])
                    ->required()
                    ->distinct(),
                Field::text('title', 'Nombre visible')->required(),
                Field::textarea('description', 'Descripción')->rows(3)->required(),
                FileUpload::make('file')
                    ->label('Archivo PDF')
                    ->directory('pdfs/cv')
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules([
                        'mimes:pdf',
                        'mimetypes:application/pdf',
                    ])
                    ->required(),
                DatePicker::make('updated_at')
                    ->label('Última actualización')
                    ->required(),
                Field::text('view_label', 'Texto para visualizar')
                    ->default('Ver CV')
                    ->required(),
                Field::text('download_label', 'Texto para descargar')
                    ->default('Descargar PDF')
                    ->required(),
            ])
                ->helperText('Cargá exactamente un CV completo y un CV reducido, ambos en PDF.')
                ->minItems(2)
                ->maxItems(2)
                ->defaultItems(2)
                ->addActionLabel('Agregar versión'),
        ];
    }
}
