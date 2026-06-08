<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ContactFormBlock extends PageBlock
{
    protected const NAME = 'ContactForm';

    protected const CATEGORY = 'Interacción';

    protected const LABEL = 'Formulario de contacto';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título del formulario'),
            Field::textarea('description', 'Texto introductorio')->rows(3),
            TextInput::make('recipient_email')
                ->label('Email destino')
                ->email()
                ->required()
                ->helperText('Dirección donde llegan los mensajes'),
            Field::text('success_message', 'Mensaje de confirmación')->default('Mensaje enviado correctamente'),
            Toggle::make('show_phone')
                ->label('Incluir campo teléfono')
                ->default(false),
            Toggle::make('show_subject')
                ->label('Incluir campo asunto')
                ->default(false),
        ];
    }
}
