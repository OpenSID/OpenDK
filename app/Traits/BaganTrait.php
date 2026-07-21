<?php

namespace App\Traits;

use App\Models\Pengurus;

trait BaganTrait
{
    public function getDataStrukturOrganisasi(): array
    {
        $items = Pengurus::select([
            'id',
            'nama',
            'gelar_depan',
            'gelar_belakang',
            'foto',
            'atasan',
            'bagan_warna',
            'jabatan_id',
        ])
            ->with(['jabatan:id,nama'])
            ->where('status', 1)
            ->get()
            ->keyBy('id');

        $map = [];

        foreach ($items as $item) {
            $name = trim(($item->gelar_depan ?? '') . ' ' . $item->nama . ' ' . ($item->gelar_belakang ?? ''));

            $map[(string) $item->id] = [
                'id'       => (string) $item->id,
                'name'     => $name,
                'title'    => $item->jabatan->nama ?? 'Unknown',
                'image'    => $item->foto ? asset($item->foto) : '',
                'color'    => $item->bagan_warna ?? '#007ad0',
                'children' => [],
                'atasan'   => $item->atasan,
            ];
        }

        // Build nested tree by assigning children to their parent
        $tree = [];

        foreach ($map as $id => &$node) {
            if ($node['atasan'] && isset($map[(string) $node['atasan']])) {
                $map[(string) $node['atasan']]['children'][] = &$node;
            } else {
                $tree[] = &$node;
            }
        }
        unset($node);

        // Remove temporary `atasan` key from output
        $cleanTree = $this->cleanNode($tree);

        // Wrap in object — OrgChart v3 requires a plain object (not array)
        // because jQuery.type() returns "array" for arrays, causing it to
        // treat the data as a URL instead of node data.
        return ['children' => $cleanTree];
    }

    private function cleanNode(array $nodes): array
    {
        foreach ($nodes as &$node) {
            unset($node['atasan']);
            if (!empty($node['children'])) {
                $node['children'] = $this->cleanNode($node['children']);
            }
        }

        return $nodes;
    }
}
