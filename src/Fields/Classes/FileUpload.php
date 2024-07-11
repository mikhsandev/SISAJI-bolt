<?php

namespace LaraZeus\Bolt\Fields\Classes;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use LaraZeus\Accordion\Forms\Accordion;
use LaraZeus\Accordion\Forms\Accordions;
use LaraZeus\Bolt\Facades\Bolt;
use LaraZeus\Bolt\Fields\FieldsContract;
use LaraZeus\Bolt\Models\Field;
use LaraZeus\Bolt\Models\FieldResponse;

class FileUpload extends FieldsContract
{
    public string $renderClass = \Filament\Forms\Components\FileUpload::class;

    public int $sort = 11;

    public function title(): string
    {
        return __('File Upload');
    }

    public function icon(): string
    {
        return 'tabler-cloud-upload';
    }

    public function description(): string
    {
        return __('single or multiple file uploader');
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
                            \Filament\Forms\Components\Toggle::make('options.allow_multiple')->label(__('Allow Multiple')),
                            self::required(),
                            self::columnSpanFull(),
                            self::htmlID(),
                            TextInput::make('options.min_size')
                                ->numeric()
                                ->default(0)
                                ->label(__('Min Size (KB)'))
                                ->hint(__('0 for unlimited. Eg: 1024 for 1MB, 2048 for 2MB')),
                            TextInput::make('options.max_size')
                                ->numeric()
                                ->default(0)
                                ->label(__('Max Size (KB)'))
                                ->hint(__('0 for unlimited. Eg: 1024 for 1MB, 2048 for 2MB')),
                            TextInput::make('options.accepted_file_types')
                                ->label(__('Accepted MIME File Types'))
                                ->hint(__('Space separated. Eg: "image/png image/jpeg application/pdf"')),
                        ]),
                    self::hintOptions(),
                    self::visibility($sections),
                    Bolt::getCustomSchema('field', resolve(static::class)) ?? [],
                ]),
        ];
    }

    public static function getOptionsHidden(): array
    {
        return [
            ...Bolt::getHiddenCustomSchema('field', resolve(static::class)) ?? [],
            self::hiddenHtmlID(),
            self::hiddenHintOptions(),
            self::hiddenRequired(),
            self::hiddenColumnSpanFull(),
            self::hiddenVisibility(),
            Hidden::make('options.allow_multiple')->default(false),
        ];
    }

    public function getResponse(Field $field, FieldResponse $resp): string
    {
        $responseValue = filled($resp->response) ? Bolt::isJson($resp->response) ? json_decode($resp->response) : [$resp->response] : [];

        return view('zeus::filament.fields.file-upload')
            ->with('resp', $resp)
            ->with('responseValue', $responseValue)
            ->with('field', $field)
            ->render();
    }

    public function TableColumn(Field $field): ?\Filament\Tables\Columns\Column
    {
        return null;
    }

    // @phpstan-ignore-next-line
    public function appendFilamentComponentsOptions($component, $zeusField, bool $hasVisibility = false)
    {
        parent::appendFilamentComponentsOptions($component, $zeusField, $hasVisibility);

        $component->disk(config('zeus-bolt.uploadDisk'))
            ->directory(config('zeus-bolt.uploadDirectory'))
            ->visibility(config('zeus-bolt.uploadVisibility'));

        if (isset($zeusField->options['allow_multiple']) && $zeusField->options['allow_multiple']) {
            $component = $component->multiple();
        }

        if (isset($zeusField->options['min_size']) && is_numeric($zeusField->options['min_size']) && $zeusField->options['min_size'] > 0) {
            $component = $component->minSize($zeusField->options['min_size']);
        }

        if (isset($zeusField->options['max_size']) && is_numeric($zeusField->options['max_size']) && $zeusField->options['max_size'] > 0) {
            $component = $component->maxSize($zeusField->options['max_size']);
        }

        if (isset($zeusField->options['accepted_file_types']) && filled($zeusField->options['accepted_file_types'])) {
            $component = $component->acceptedFileTypes(explode(' ', $zeusField->options['accepted_file_types']));
        }

        return $component;
    }
}
