<?php

namespace App\Filament\Templates;

use Filament\Actions\Action;
use Filament\Forms\Components\Builder;

class ModalTemplate
{
    public static function schema(): array
    {
        // Copy here the blocks you want to use in the template
        $blocks = [
            \App\Filament\Blocks\HeroBlock::make(),
            \App\Filament\Blocks\TextBlock::make(),
            \App\Filament\Blocks\MediaBlock::make(),
            \App\Filament\Blocks\MediaTextBlock::make(),
            \App\Filament\Blocks\CardsBlock::make(),
            \App\Filament\Blocks\SearchBlock::make(),
        ];    

        $defaultTemplate = [
        ];

        return [
            Builder::make('blocks')
                ->label('Bloques')
                ->blockPreviews(areInteractive: true)
                ->default( $defaultTemplate )
                ->blocks($blocks)
                ->columnSpan('full')
                ->cloneable()
                ->reorderableWithButtons()
                ->editAction(
                    fn (Action $action) => $action->closeModalByClickingAway(false)
                )
<<<<<<< HEAD
                
=======
>>>>>>> 85ac118f44c5d2c1cda44c274bef3d05c175fc3c
                ->collapsible(),
        ];
    }
}
