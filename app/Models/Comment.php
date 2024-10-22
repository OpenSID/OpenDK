<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'das_artikel_comment';

    protected $guarded = ['id'];

    // Relasi dengan model Artikel
    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'das_artikel_id');
    }

    // Relasi untuk komentar yang membalas komentar lain
    public function parentComment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    // Relasi untuk mendapatkan balasan dari komentar
    public function replies()
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }
}
