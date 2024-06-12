<?php

namespace LaraZeus\Bolt\Filament\Actions;

use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use LaraZeus\Bolt\BoltPlugin;
use LaraZeus\Bolt\Models\Response;

/**
 * @property mixed $record
 */
class SetResponseStatus extends Action
{
    protected ?Closure $mutateRecordDataUsing = null;

    public static function getDefaultName(): ?string
    {
        return 'set-status';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->visible(function (Response $record): bool {
            return $record->form->extensions === null;
        });

        $this->label(__('Set Status'));

        $this->icon('heroicon-o-tag');

        $this->action(function (array $data): void {
            $this->record->status = $data['status'];
            $this->record->notes = $data['notes'];
            $this->record->output = $data['output'];
            $this->record->save();
        });

        $this->form([
            Select::make('status')
                ->label(__('status'))
                ->default(fn (Response $record) => $record->status)
                ->options(BoltPlugin::getModel('FormsStatus')::query()->pluck('label', 'key'))
                ->required(),
            Textarea::make('notes')
                ->default(fn (Response $record) => $record->notes)
                ->label(__('Notes')),
            FileUpload::make('output')
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label(__('Output')),
        ]);
    }

    public function mutateRecordDataUsing(?Closure $callback): static
    {
        $this->mutateRecordDataUsing = $callback;

        return $this;
    }
}
