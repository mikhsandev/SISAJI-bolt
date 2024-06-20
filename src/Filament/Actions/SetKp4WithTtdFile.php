<?php

namespace LaraZeus\Bolt\Filament\Actions;

use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\Action;
use LaraZeus\Bolt\Models\Response;

/**
 * @property mixed $record
 */
class SetKp4WithTtdFile extends Action
{
    protected ?Closure $mutateRecordDataUsing = null;

    public static function getDefaultName(): ?string
    {
        return 'set-kp4-with-ttd-file';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->visible(function (Response $record): bool {
            return $record->form->extensions === null;
        });

        $this->label(__('Upload KP-4 dengan TTD'));

        $this->icon('heroicon-o-arrow-up-tray');

        $this->action(function (array $data): void {
            $this->record->kp4 = $data['kp4'];
            $this->record->save();
        });

        $this->form([
            FileUpload::make('kp4')
                ->default(fn (Response $record) => $record->kp4)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label(__('KP-4')),
        ]);
    }

    public function mutateRecordDataUsing(?Closure $callback): static
    {
        $this->mutateRecordDataUsing = $callback;

        return $this;
    }
}
