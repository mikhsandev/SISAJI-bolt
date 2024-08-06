<?php

namespace LaraZeus\Bolt\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $key
 * @property string $label
 * @property string $desc
 * @property string $color
 * @property string $chartColor
 * @property string $icon
 * @property string $class
 */
class FormsStatus extends Model
{
    use \Sushi\Sushi;

    public function getRows(): array
    {
        return [
            [
                'key' => 'SURAT_DITERIMA',
                'label' => 'Surat Diterima',
                'description' => '',
                'color' => 'default',
                'chartColor' => '#6B7280',
                'icon' => 'heroicon-o-document',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-default-700 bg-default-500/10',
            ],
            [
                'key' => 'PERMOHONAN_DISETUJUI',
                'label' => 'Permohonan Disetujui',
                'description' => '',
                'color' => 'default',
                'chartColor' => '#FFFF00',
                'icon' => 'heroicon-o-document',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-default-700 bg-default-500/10',
            ],
            [
                'key' => 'PERMOHONAN_DITOLAK',
                'label' => 'Permohonan Ditolak',
                'description' => '',
                'color' => 'default',
                'chartColor' => '#EF4444',
                'icon' => 'heroicon-o-document',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-default-700 bg-default-500/10',
            ],
            [
                'key' => 'PELAKSANAAN',
                'label' => 'Pelaksanaan',
                'description' => '',
                'color' => 'default',
                'chartColor' => '#3B82F6',
                'icon' => 'heroicon-o-document',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-default-700 bg-default-500/10',
            ],
            [
                'key' => 'MENUNGGU_PEMBAYARAN',
                'label' => 'Menunggu Pembayaran',
                'description' => '',
                'color' => 'default',
                'chartColor' => '#F59E0B',
                'icon' => 'heroicon-o-document',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-default-700 bg-default-500/10',
            ],
            [
                'key' => 'PEMBAYARAN_DITERIMA',
                'label' => 'Pembayaran Diterima',
                'description' => '',
                'color' => 'default',
                'chartColor' => '#A855F7',
                'icon' => 'heroicon-o-document',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-default-700 bg-default-500/10',
            ],
            [
                'key' => 'HASIL_TERBIT',
                'label' => 'Hasil Terbit',
                'description' => '',
                'color' => 'default',
                'chartColor' => '#21C55D',
                'icon' => 'heroicon-o-document',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-default-700 bg-default-500/10',
            ],
        ];
    }

    protected function sushiShouldCache(): bool
    {
        return ! app()->isLocal();
    }
}
