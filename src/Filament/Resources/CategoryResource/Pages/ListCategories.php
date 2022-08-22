<?php

namespace LaraZeus\Bolt\Filament\Resources\CategoryResource\Pages;

use LaraZeus\Bolt\Filament\Resources\CategoryResource;
use LaraZeus\Bolt\Filament\Resources\FormResource\Widgets\BetaNote;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ListCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BetaNote::class,
        ];
    }
}