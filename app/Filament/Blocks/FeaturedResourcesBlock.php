<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\Field;
use App\Models\ArticuloAcademico;
use App\Models\Entrevista;
use App\Models\Libro;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

class FeaturedResourcesBlock extends PageBlock
{
    protected const NAME = 'FeaturedResources';

    protected const CATEGORY = 'Listados';

    protected const LABEL = 'Recursos destacados';

    protected static function fields(): array
    {
        return [
            Field::text('title', 'Título de la sección'),
            Field::textarea('description', 'Texto introductorio')->rows(3),
            Field::select('source_mode', 'Origen del contenido', [
                'manual' => 'Selección manual',
                'latest' => 'Contenido más reciente',
            ])->default('manual')->required(),
            Field::number('max_items', 'Máximo de recursos')
                ->default(4)
                ->minValue(1)
                ->maxValue(4),
            Repeater::make('items')
                ->label('Recursos')
                ->schema([
                    Select::make('resource_type')
                        ->label('Tipo de recurso')
                        ->options([
                            'libro' => 'Libro',
                            'articulo' => 'Artículo Académico',
                            'entrevista' => 'Entrevista',
                        ])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('resource_id', null)),
                    Select::make('resource_id')
                        ->label('Recurso')
                        ->options(fn (callable $get) => static::getResourceOptions($get('resource_type')))
                        ->required()
                        ->searchable(),
                ])
                ->addActionLabel('Agregar recurso')
                ->columns(1)
                ->defaultItems(3),
        ];
    }

    protected static function getResourceOptions(?string $type): array
    {
        return match ($type) {
            'libro' => Libro::pluck('subtitulo', 'id')->map(fn ($v, $k) => $v ?: "Libro #{$k}")->toArray(),
            'articulo' => ArticuloAcademico::pluck('resumen', 'id')->map(fn ($v, $k) => $v ? "Artículo #{$k}" : "Artículo #{$k}")->toArray(),
            'entrevista' => Entrevista::pluck('medio', 'id')->map(fn ($v, $k) => $v ?: "Entrevista #{$k}")->toArray(),
            default => [],
        };
    }
}
