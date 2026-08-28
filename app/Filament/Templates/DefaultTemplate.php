<?php

namespace App\Filament\Templates;

use App\Filament\Blocks\CardsBlock;
use App\Filament\Blocks\ContentListBlock;
use App\Filament\Blocks\CTABlock;
use App\Filament\Blocks\CVAccessBlock;
use App\Filament\Blocks\EventsHighlightBlock;
use App\Filament\Blocks\EventsListingBlock;
use App\Filament\Blocks\HeroBlock;
use App\Filament\Blocks\IntroBlock;
use App\Filament\Blocks\MediaTextBlock;
use App\Filament\Blocks\PressFeedBlock;
use App\Filament\Blocks\PublicationsHighlightBlock;
use App\Filament\Blocks\SearchBlock;
use App\Filament\Blocks\TeachingListingBlock;
use App\Filament\Blocks\TextBlock;
use Filament\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Hidden;

class DefaultTemplate
{
    public static function blocks(): array
    {
        return [
            HeroBlock::make(),
            TextBlock::make(),
            MediaTextBlock::make(),
            CardsBlock::make(),
            SearchBlock::make(),
            CTABlock::make(),
            PublicationsHighlightBlock::make(),
            EventsHighlightBlock::make(),
            EventsListingBlock::make(),
            ContentListBlock::make(),
            TeachingListingBlock::make(),
            PressFeedBlock::make(),
            CVAccessBlock::make(),
            IntroBlock::make(),
        ];
    }

    public static function schema($form): array
    {
        $blocks = static::blocks();

        // foreach ($blocks as $block) {
        //     $block
        //         ->label(function (?array $state): ?string {
        //             if ($state === null) {
        //                 return nul
        //             //     return 'Bloque';
        //             return $state['blockTitle'] ?? null;
        //         });
        // }

        $defaultTemplate = [
            // [
            //     'type' => 'heading',
            //     'data' => [
            //         'text' => fake()->sentence(),
            //     ],
            // ],
        ];

        return [
            Builder::make('blocks')
                ->label('Bloques')
                ->blockPreviews(areInteractive: false)
                ->default($defaultTemplate)
                ->blocks($blocks)
                ->columnSpan('full')
                ->reorderableWithButtons()
                ->cloneable()
                ->editAction(
                    fn (Action $action) => $action
                        ->closeModalByClickingAway(false)
                        ->modalSubmitActionLabel('Actualizar cambios')
                )
                ->view('filament-forms::components.editor'),

            // Hidden input for paste functionality
            Hidden::make('blocks_pastable')
                ->default('')
                ->dehydrated(false),
        ];
    }
}
