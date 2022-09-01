<?php

namespace LaraZeus\Bolt\Filament\Resources\FormResource;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;
use LaraZeus\Bolt\Facades\Bolt;

trait Schemata
{
    public static function getMainFormSchema() : array{
        return [
            Hidden::make('user_id')->default(auth()->user()->id ?? null),
            Hidden::make('layout')->default(1),

            Tabs::make('Name')->tabs(static::getTabsSchema())->columnSpan(2),
            Card::make()->schema([
                Placeholder::make('Sections-title')->label(__('Sections'))->helperText(__('sections are here to group the fields, and you can display it as pages from the Form options. if you have one section, it wont show in the form')),
            ]),

            Repeater::make('sections')
                ->label('')
                ->schema(static::getSectionsSchema())
                ->relationship()
                ->orderable('ordering')
                ->createItemButtonLabel(__('Add Section'))
                ->cloneable()
                ->collapsible()
                ->itemLabel(fn(array $state) : ?string => $state['name'] ?? null)
                ->columnSpan(2),
        ];
    }
    public static function getTabsSchema() : array
    {
        return [
            Tabs\Tab::make('title-slug')->label(__('Title & Slug'))->schema([
                TextInput::make('name')->required()->maxLength(255)->reactive()
                    ->label(__('Form Name'))
                    ->afterStateUpdated(function (\Closure $set, $state, $context) {
                        if ($context === 'edit') {
                            return;
                        }
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')->required()->maxLength(255)->rules([ 'alpha_dash' ])->unique(ignoreRecord: true)->label(__('Form Slug')),
            ])->columns(2),
            Tabs\Tab::make('text-details')->label(__('Text & Details'))->schema([
                Textarea::make('desc')->label(__('Form Description'))->helperText(__('shown under the title of the form and used in SEO')),
                RichEditor::make('details')->label(__('Form Details'))->helperText(__('a highlighted section above the form, to show some instructions or more details')),
                RichEditor::make('options.confirmation-message')->label(__('Confirmation Message'))->helperText(__('optional, show a massage whenever any one submit a new entery')),
            ]),
            Tabs\Tab::make('display-access')->label(__('Display & Access'))->schema([
                Toggle::make('is_active')->label(__('is_active'))->helperText(__('Activate the form and let users start submissions')),
                Toggle::make('options.show-as-wizard')->label(__('Show As Wizard'))->helperText(__('instead of showing all section in one page, separate them in multiple steps')),
                Toggle::make('options.require-login')->label(__('require Login'))->helperText(__('User must be logged in or create an account before can submit a new entry'))->reactive(),
                Toggle::make('options.one-entry-per-user')->label(__('One Entry Per User'))->helperText(__('to check if the user already submitted an entry in this form'))
                    ->visible(function (\Closure $get) {
                        return $get('options.require-login');
                    }),
            ])->columns(2),
            Tabs\Tab::make('advanced')->label(__('Advanced'))->schema([
                Select::make('category_id')
                    ->label(__('Category'))
                    ->helperText(__('optional, organize your forms into categories'))
                    ->options(\LaraZeus\Bolt\Models\Category::pluck('name', 'id')),
                Grid::make()->schema([
                    Placeholder::make('form-dates')->label('Form Dates')->content(__('optional, specify when the form will be active and receiving new entries'))->columnSpan(2),
                    DateTimePicker::make('start_date')->label(__('Start Date')),
                    DateTimePicker::make('end_date')->label(__('End Date')),
                ])->columns(2),
                Grid::make()->schema([
                    TextInput::make('options.emails-notification')
                        ->label(__('Emails Notifications'))
                        ->helperText(__('optional, enter the emails you want to receive notification when ever you got a new entry')),
                    TextInput::make('options.web-hook')
                        ->label(__('enter webHook URL'))
                        ->helperText(__('Send the form data to a webHook')),
                ])->columns(2),
            ]),
        ];
    }

    public static function getSectionsSchema() : array
    {
        return [
            TextInput::make('name')->required()->lazy()->label(__('Section Name')),
            Placeholder::make('Fields')->label(__('Section Fields')),
            Repeater::make('fields')
                ->schema(static::getFieldsSchema())
                ->relationship()
                ->label('')
                ->orderable('ordering')
                ->cloneable()
                ->collapsible()
                ->grid([
                    'default' => 1,
                    'md'      => 2,
                ])
                ->label('')
                ->itemLabel(fn(array $state) : ?string => $state['name'] ?? null)
                ->createItemButtonLabel(__('Add field')),
        ];
    }

    public static function getFieldsSchema() : array
    {
        return [
            TextInput::make('name')->required()->lazy()->label(__('Field Name')),
            Select::make('type')
                ->required()
                ->options(Bolt::availableFields()->pluck('title', 'class'))
                ->reactive()
                ->default('\LaraZeus\Bolt\Fields\Classes\TextInput')
                ->label(__('Field Type')),
            Fieldset::make('Options')
                ->label(__('Field Options'))
                ->visible(function (\Closure $get) {
                    $class = $get('type');

                    return ( new $class )->hasOptions();
                })
                ->schema(function (\Closure $get) {
                    return $get('type')::getOptions();
                }),
        ];
    }
}