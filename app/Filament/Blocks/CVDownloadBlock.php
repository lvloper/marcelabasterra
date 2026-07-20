<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;

class CVDownloadBlock extends PageBlock
{
    protected const NAME = 'CVDownload';

    protected const CATEGORY = 'Interacción';

    protected const LABEL = 'Descarga de CV';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título'),
            Field::textarea('description', 'Texto de apoyo')->rows(3),
            Field::text('button_text', 'Texto del botón')->default('Descargar'),
        ];
    }
}
