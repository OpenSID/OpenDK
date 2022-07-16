<?php

namespace App\Http\Controllers\Informasi;

use App\Http\Controllers\Controller;
use App\Models\MediaSosial;
use Illuminate\Http\Request;

class MediaSosialController extends Controller
{
    public function index()
    {
        $medsos           = MediaSosial::findOrFail(1);
        $page_title       = 'Media Sosial';
        $page_description = 'Pengaturan Facebook';
        $navigasi         = 'facebook';

        return view('informasi.media_sosial.index', compact('page_title', 'page_description', 'medsos'));
    }
}
