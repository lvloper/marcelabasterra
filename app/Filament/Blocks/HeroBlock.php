<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Models\CargoInstitucional;
use Filament\Forms\Components\Select;

class HeroBlock extends PageBlock
{
    protected const NAME = 'Hero';

    protected const CATEGORY = 'Hero';

    protected const LABEL = 'Hero';

    protected static function fields(): array
    {
        return [
            Field::toggleButtons('variant', 'Versión visual', [
                'editorial' => '01 · Apertura editorial',
                'institutional' => '02 · Capítulo institucional',
                'portrait' => '03 · Retrato protagonista',
            ])
                ->default('editorial')
                ->inline()
                ->required(),
            Field::image('profile_photo', 'Retrato principal', '1200', '1500', 'images/hero'),
            Field::text('image_alt', 'Texto alternativo del retrato')
                ->helperText('Describe la fotografía sin repetir el título visible.'),
            Field::text('badge', 'Etiqueta editorial'),
            Field::text('name', 'Nombre o título principal')->required(),
            Field::text('subtitle', 'Área de especialización')->required(),
            Field::textarea('description', 'Presentación breve')
                ->rows(4)
                ->maxLength(360),
            Field::repeater('indicators', 'Indicadores', [
                Field::text('label', 'Texto'),
            ])
                ->helperText('Cargos, áreas o hitos breves. Se recomienda usar entre 2 y 4.')
                ->maxItems(4)
                ->collapsible(),
            Select::make('featured_positions')
                ->label('Cargos institucionales destacados')
                ->multiple()
                ->options(fn (): array => CargoInstitucional::query()
                    ->orderByDesc('featured')
                    ->orderBy('cargo')
                    ->get()
                    ->mapWithKeys(fn (CargoInstitucional $position): array => [
                        $position->id => trim($position->cargo.' · '.$position->institucion),
                    ])->all())
                ->searchable()
                ->helperText('Se muestran desde el recurso Cargos institucionales, en el orden seleccionado.'),
            Field::route('cta_primary', 'Ver CV')->buttonLabel(),
            Field::route('cta_secondary', 'Ver publicaciones')->buttonLabel(),
            Field::route('cta_tertiary', 'Actualidad')->buttonLabel(),
        ];
    }
}
