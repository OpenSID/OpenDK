<?php

namespace App\Rules;

use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use App\Services\PendudukService;
use Illuminate\Contracts\Validation\Rule;

class ValidasiNikRule implements Rule
{
    protected $tanggalLahir;
    protected $settings;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($tanggalLahir)
    {
        $this->tanggalLahir = $tanggalLahir;
        $this->settings = SettingAplikasi::pluck('value', 'key');
    }

    protected function isDatabaseGabungan()
    {
        return ($this->settings['sinkronisasi_database_gabungan'] ?? null) === '1';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->isDatabaseGabungan()) {

            return (new PendudukService)->cekPendudukNikTanggalLahir($value, $this->tanggalLahir);
            
        } else {
            return Penduduk::where('nik', $value)->where('tanggal_lahir', $this->tanggalLahir)->exists();
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'NIK tidak ditemukan atau tidak sesuai dengan tanggal lahir.';
    }
}
