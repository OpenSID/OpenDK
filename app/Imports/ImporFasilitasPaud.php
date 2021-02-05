<?php

namespace App\Imports;

use App\Models\FasilitasPAUD;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use function config;

class ImporFasilitasPaud implements ToModel, WithHeadingRow
{
    use Importable;

    /** @var Request $request */
    protected $request;

<<<<<<< HEAD
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function chunkSize(): int
=======
    public function __construct(Request $request)
>>>>>>> 2890337063ab134daf3e7f211cd0f029924addf1
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function model(array $row)
    {
        return new FasilitasPAUD([
            'kecamatan_id'      => config('app.default_profile'),
            'desa_id'           => $this->request->input('desa_id'),
            'jumlah_paud'       => $row['jumlah_paud_ra'],
            'jumlah_guru_paud'  => $row['jumlah_guru_paud_ra'],
            'jumlah_siswa_paud' => $row['jumlah_siswa_paud_ra'],
            'semester'          => $this->request->input('semester'),
            'tahun'             => $this->request->input('tahun'),
        ]);
    }
}
