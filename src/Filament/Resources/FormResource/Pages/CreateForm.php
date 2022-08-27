<?php

namespace LaraZeus\Bolt\Filament\Resources\FormResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use LaraZeus\Bolt\Filament\Resources\FormResource;
use LaraZeus\Bolt\Filament\Resources\FormResource\Widgets\BetaNote;

class CreateForm extends CreateRecord
{
    protected static string $resource = FormResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            BetaNote::class,
        ];
    }
}
