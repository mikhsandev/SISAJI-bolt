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

        $this->label(__('Set Status'));

        $this->icon('heroicon-o-tag');

        $this->action(function (array $data): void {
            $this->record->status = $data['status'] ?? $this->record->status;
            $this->record->notes = $data['notes'] ?? $this->record->notes;

            switch ($this->record->status) {
                case 'PERMOHONAN_DISETUJUI':
                    if (isset($data['dokumen_permohonan_disetujui'])) {
                        $this->record->dokumen_permohonan_disetujui = $data['dokumen_permohonan_disetujui'];
                    }
                    $this->record->time_status_permohonan_disetujui = now();
                    break;
                case 'PERMOHONAN_DITOLAK':
                    if (isset($data['dokumen_permohonan_ditolak'])) {
                        $this->record->dokumen_permohonan_ditolak = $data['dokumen_permohonan_ditolak'];
                    }
                    $this->record->time_status_permohonan_ditolak = now();
                    break;
                case 'PELAKSANAAN':
                    if (isset($data['dokumen_pelaksanaan'])) {
                        $this->record->dokumen_pelaksanaan = $data['dokumen_pelaksanaan'];
                    }
                    $this->record->time_status_pelaksanaan = now();
                    break;
                case 'MENUNGGU_PEMBAYARAN':
                    if (isset($data['dokumen_tagihan'])) {
                        $this->record->dokumen_tagihan = $data['dokumen_tagihan'];
                    }
                    if (isset($data['dokumen_bukti_pembayaran'])) {
                        $this->record->dokumen_bukti_pembayaran = $data['dokumen_bukti_pembayaran'];
                        $this->record->time_dokumen_bukti_pembayaran = now();
                    }
                    $this->record->time_status_menunggu_pembayaran = now();
                    break;
                case 'PEMBAYARAN_DITERIMA':
                    if (isset($data['dokumen_bukti_pembayaran'])) {
                        $this->record->dokumen_bukti_pembayaran = $data['dokumen_bukti_pembayaran'];
                        $this->record->time_dokumen_bukti_pembayaran = now();
                    }
                    $this->record->time_status_pembayaran_diterima = now();
                    break;
                case 'HASIL_TERBIT':
                    if (isset($data['dokumen_output'])) {
                        $this->record->dokumen_output = $data['dokumen_output'];
                    }
                    $this->record->time_status_hasil_terbit = now();
                    break;
            }

            $this->record->save();
        });

        $this->form([
            Select::make('status')
                ->label(__('status'))
                ->default(fn (Response $record) => $record->status)
                ->options(BoltPlugin::getModel('FormsStatus')::query()->pluck('label', 'key'))
                ->required()
                ->live()
                ->disabled(!auth()->user()->hasRole(['Admin Super', 'Admin'])),

            Textarea::make('notes')
                ->default(fn (Response $record) => $record->notes)
                ->label(__('Notes'))
                ->visible(auth()->user()->hasRole(['Admin Super', 'Admin'])),

            FileUpload::make('dokumen_permohonan_disetujui')
                ->default(fn (Response $record) => $record->dokumen_permohonan_disetujui)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Permohonan Disetujui')
                ->visible(fn (Get $get): bool => $get('status') == 'PERMOHONAN_DISETUJUI' && auth()->user()->hasRole(['Admin Super', 'Admin']) ),

            FileUpload::make('dokumen_permohonan_ditolak')
                ->default(fn (Response $record) => $record->dokumen_permohonan_ditolak)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Permohonan Ditolak')
                ->visible(fn (Get $get): bool => $get('status') == 'PERMOHONAN_DITOLAK' && auth()->user()->hasRole(['Admin Super', 'Admin']) ),

            FileUpload::make('dokumen_pelaksanaan')
                ->default(fn (Response $record) => $record->dokumen_pelaksanaan)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Pelaksanaan')
                ->visible(fn (Get $get): bool => $get('status') == 'PELAKSANAAN' && auth()->user()->hasRole(['Admin Super', 'Admin']) ),

            FileUpload::make('dokumen_tagihan')
                ->default(fn (Response $record) => $record->dokumen_tagihan)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Tagihan')
                ->visible(fn (Get $get): bool => $get('status') == 'MENUNGGU_PEMBAYARAN' && auth()->user()->hasRole(['Admin Super', 'Admin']) ),

            FileUpload::make('dokumen_bukti_pembayaran')
                ->default(fn (Response $record) => $record->dokumen_bukti_pembayaran)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Bukti Pembayaran')
                ->visible(fn (Get $get): bool => (auth()->user()->hasRole('Pelanggan') && $this->record->status == 'MENUNGGU_PEMBAYARAN') || $get('status') == 'PEMBAYARAN_DITERIMA' ),

            FileUpload::make('dokumen_output')
                ->default(fn (Response $record) => $record->dokumen_output)
                ->disk(config('zeus-bolt.uploadDisk'))
                ->directory(config('zeus-bolt.uploadDirectory'))
                ->visibility(config('zeus-bolt.uploadVisibility'))
                ->label('Dokumen Output')
                ->visible(fn (Get $get): bool => $get('status') == 'HASIL_TERBIT' && auth()->user()->hasRole(['Admin Super', 'Admin']) )
        ]);
    }

    public function mutateRecordDataUsing(?Closure $callback): static
    {
        $this->mutateRecordDataUsing = $callback;

        return $this;
    }
}
