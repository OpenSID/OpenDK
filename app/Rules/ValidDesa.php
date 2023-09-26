<?php

namespace App\Rules;

use App\Models\Profil;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;

class ValidDesa implements Rule
{
    protected $nama_kecamatan;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->nama_kecamatan = Profil::first()->nama_kecamatan;
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
        return Profil::first()->kecamatan_id == Str::substr($value, 0, 8);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Kode desa tidak dikenal di OpenDK Kecamatan ' . $this->nama_kecamatan;
    }
}
