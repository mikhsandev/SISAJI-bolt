<?php

namespace LaraZeus\Bolt\Filament\Resources\CollectionResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use LaraZeus\Bolt\Filament\Resources\CollectionResource;

class CreateCollection extends CreateRecord
{
    protected static string $resource = CollectionResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasRole(['Admin Super']);
    }
}
