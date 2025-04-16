<?php

namespace App\Traits;

use App\Models\Pengurus;

trait BaganTrait
{
    public function getDataStrukturOrganisasi(): array
    {
        $struktur = Pengurus::select([
                'id',
                'nama',
                'gelar_depan',
                'gelar_belakang',
                'foto',
                'atasan',
                'bagan_warna',
                'bagan_tingkat',
                'jabatan_id'
            ])
            ->with(['jabatan:id,nama']) // Hanya ambil id dan nama jabatan
            ->where('status', 1)
            ->get();

        $data = [];
        $nodes = [];

        foreach ($struktur as $item) {
            if ($item->atasan) {
                $data[] = [
                    (string) $item->atasan, (string) $item->id
                ];
            }

            $nodes[] = [
                'id'    => (string) $item->id,
                'title' => $item->jabatan->nama ?? 'Unknown',
                'name'  => trim(($item->gelar_depan ?? '') . ' ' . $item->nama . ' ' . ($item->gelar_belakang ?? '')),
                'image' => $item->foto ? asset($item->foto) : '',
                'color' => $item->bagan_warna ?? '#007ad0',
                'column' => $item->bagan_tingkat ?? 0
            ];
        }

        return [
            'data' => $data,
            'nodes' => $nodes,
        ];
    }
}
