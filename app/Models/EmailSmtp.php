<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSmtp extends Model
{
    use HasFactory;

    protected $table = 'ref_smtp';
    protected $primaryKey = 'id';

    protected $fillable = [
        'provider',
        'host',
        'port',
        'username',
        'password',
        'status',
    ];

    public $timestamps = true;

    //mengambil data smtp terakhir berdasaerkan waktu penambahan data dan status
    public static function getLatestEmailSmtp()
    {
        return EmailSmtp::latest('created_at')->where('status', '=', 1)->first();
    }
}
