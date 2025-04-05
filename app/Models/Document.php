<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'das_penduduk_id',
        'judul_document',
        'path_document',
        'user_id',
        'kode_surat',
        'no_urut',
        'jenis_surat',
        'keterangan',
        'ditandatangani',
        'tanggal'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tanggal(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->locale('id')->translatedFormat('d F Y H:i:s'),
        );
    }
}
