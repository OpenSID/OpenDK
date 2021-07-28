<?php

namespace App\Rules;

use App\Models\Profil;
use App\Models\DataDesa;
use Illuminate\Contracts\Validation\Rule;

class CekDesa implements Rule
{
    
    /**
     * @var int
     */
    protected $nama_kec;
    protected $value;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $this->nama_kec = Profil::where('kecamatan_id', config('app.default_profile'))->first()->kecamatan->nama;
        $this->value = $value;

        return DataDesa::where($attribute, $value)->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Kode desa ' . $this->value . ' tidak dikenal di OpenDK Kecamatan ' . $this->nama_kec;
    }
}
