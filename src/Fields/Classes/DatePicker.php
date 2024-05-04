<?php

namespace LaraZeus\Bolt\Fields\Classes;

use LaraZeus\Accordion\Forms\Accordion;
use LaraZeus\Accordion\Forms\Accordions;
use LaraZeus\Bolt\Fields\FieldsContract;

class DatePicker extends FieldsContract
{
    public string $renderClass = \Filament\Forms\Components\DatePicker::class;

    public int $sort = 6;

    public function title(): string
    {
        return __('Date Picker');
    }

    public function icon(): string
    {
        return 'tabler-calendar';
    }

    public function description(): string
    {
        return __('full date picker');
    }

    public static function getOptions(?array $sections = null): array
    {
        return [
            Accordions::make('check-list-options')
                ->accordions([
                    Accordion::make('general-options')
                        ->label(__('General Options'))
                        ->icon('iconpark-checklist-o')
                        ->schema([
                            self::isActive(),
                            self::required(),
                            self::columnSpanFull(),
                            self::htmlID(),
                        ]),
                    self::hintOptions(),
                    self::visibility($sections),
                ]),
        ];
    }

    public static function getOptionsHidden(): array
    {
        return [
            self::hiddenIsActive(),
            self::hiddenHtmlID(),
            self::hiddenHintOptions(),
            self::hiddenRequired(),
            self::hiddenColumnSpanFull(),
            self::hiddenVisibility(),
        ];
    }
}
