<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaTerkait extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'url',
        'nama',
        'status',
        'urut',
    ];

    public function scopeSearch($query, $search)
    {
        return empty($search) 
            ? $query 
            : $query->where('nama', 'LIKE', "%{$search}%");
    }

    public function scopeStatus($query, $status)
    {
        return $query->when(is_numeric($status), function ($query) use ($status) {
            $query->where('status', $status);
        });
    }


}
