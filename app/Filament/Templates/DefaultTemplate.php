<?php

namespace App\Filament\Templates;

use Filament\Actions\Action;
use Filament\Forms\Components\Builder;

class DefaultTemplate
{
    public static function schema($form): array
    {
        // Copy here the blocks you want to use in the template
        $blocks = [
            \App\Filament\Blocks\HeroBlock::make(),
            \App\Filament\Blocks\TextBlock::make(),
            \App\Filament\Blocks\MediaBlock::make(),
            \App\Filament\Blocks\MediaTextBlock::make(),
            \App\Filament\Blocks\CardsBlock::make(),
            \App\Filament\Blocks\SearchBlock::make(),
            \App\Filament\Blocks\BookPresentationBlock::make(),
            \App\Filament\Blocks\CTABlock::make(),
            \App\Filament\Blocks\TimelineBlock::make(),
            \App\Filament\Blocks\CVDownloadBlock::make(),
            \App\Filament\Blocks\ContactFormBlock::make(),
            \App\Filament\Blocks\FeaturedResourcesBlock::make(),
            \App\Filament\Blocks\PublicationsHighlightBlock::make(),
            \App\Filament\Blocks\InterviewsHighlightBlock::make(),
            \App\Filament\Blocks\EventsHighlightBlock::make(),
            \App\Filament\Blocks\EventsListingBlock::make(),
            \App\Filament\Blocks\ContentListBlock::make(),
            \App\Filament\Blocks\TeachingListingBlock::make(),
            \App\Filament\Blocks\PressFeedBlock::make(),
            \App\Filament\Blocks\CVAccessBlock::make(),
            \App\Filament\Blocks\IntroBlock::make(),
            \App\Filament\Blocks\RelatedResourcesBlock::make(),
        ];

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
                    fn(Action $action) => $action
                        ->closeModalByClickingAway(false)
                        ->modalSubmitActionLabel('Actualizar cambios')
                )
                ->view('filament-forms::components.editor'),
            
            // Hidden input for paste functionality
            \Filament\Forms\Components\Hidden::make('blocks_pastable')
                ->default('')
                ->dehydrated(false),
        ];
    }
}
