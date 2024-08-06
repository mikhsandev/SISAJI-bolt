<?php

namespace LaraZeus\Bolt\Filament\Actions;

use Closure;
use Filament\Forms\Get;
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

        if (auth()->user()->hasRole(['Admin Super', 'Admin'])) {
            $this->label(__('Set Status'));

        } else {
            if ($this->record->status == 'MENUNGGU_PEMBAYARAN') {
                $this->label(__('Atur Pembayaran'));
            } else {
                $this->visible(false);
            }
        }

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
                ->required()
                ->live()
                ->hidden(!auth()->user()->hasRole(['Admin Super', 'Admin'])),

            Textarea::make('notes')
                ->default(fn (Response $record) => $record->notes)
                ->label(__('Notes'))
                ->hidden(!auth()->user()->hasRole(['Admin Super', 'Admin'])),

            FileUpload::make('dokumen_permohonan_disetujui')
                ->default(fn (Response $record) => $record->dokumen_permohonan_disetujui)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Permohonan Disetujui')
                ->hidden(fn (Get $get): bool => $get('status') != 'PERMOHONAN_DISETUJUI' || !auth()->user()->hasRole(['Admin Super', 'Admin']) ),

            FileUpload::make('dokumen_permohonan_ditolak')
                ->default(fn (Response $record) => $record->dokumen_permohonan_ditolak)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Permohonan Ditolak')
                ->hidden(fn (Get $get): bool => $get('status') != 'PERMOHONAN_DITOLAK' || !auth()->user()->hasRole(['Admin Super', 'Admin']) ),

            FileUpload::make('dokumen_pelaksanaan')
                ->default(fn (Response $record) => $record->dokumen_pelaksanaan)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Pelaksanaan')
                ->hidden(fn (Get $get): bool => $get('status') != 'PELAKSANAAN' || !auth()->user()->hasRole(['Admin Super', 'Admin']) ),

            FileUpload::make('dokumen_tagihan')
                ->default(fn (Response $record) => $record->dokumen_tagihan)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Tagihan')
                ->hidden(fn (Get $get): bool => $get('status') != 'MENUNGGU_PEMBAYARAN' || !auth()->user()->hasRole(['Admin Super', 'Admin']) ),

            FileUpload::make('dokumen_bukti_pembayaran')
                ->default(fn (Response $record) => $record->dokumen_bukti_pembayaran)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Bukti Pembayaran')
                ->hidden(fn (Get $get): bool => $get('status') != 'MENUNGGU_PEMBAYARAN' ),

            FileUpload::make('dokumen_output')
                ->default(fn (Response $record) => $record->dokumen_output)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Output')
                ->hidden(fn (Get $get): bool => $get('status') != 'HASIL_TERBIT' || !auth()->user()->hasRole(['Admin Super', 'Admin']) )
        ]);
    }

    public function mutateRecordDataUsing(?Closure $callback): static
    {
        $this->mutateRecordDataUsing = $callback;

        return $this;
    }
}
