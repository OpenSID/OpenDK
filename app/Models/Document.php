<?php

namespace App\Models;

use App\Traits\HandlesResourceDeletion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory, HandlesResourceDeletion;

    protected $fillable = [
        'judul_document',
        'path_document',
        'nama_document',
        'pengurus_id',
        'kode_surat',
        'no_urut',
        'jenis_surat',
        'keterangan',
        'ditandatangani'
    ];

    protected $resources = ['path_document'];

    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class, 'pengurus_id', 'id');
    }

    public function jenis_surat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat', 'id');
    }

    public function tanggal(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->locale('id')->translatedFormat('d F Y H:i:s'),
        );
    }
}
