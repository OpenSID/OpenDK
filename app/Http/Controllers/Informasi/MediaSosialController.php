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

    public function twitter()
    {
        $page_title       = 'Media Sosial';
        $page_description = 'Pengaturan Twitter';
        $navigasi         = 'twitter';

        return view('informasi.media_sosial.twitter', compact('page_title', 'page_description'));
    }

    public function youtube()
    {
        $page_title       = 'Media Sosial';
        $page_description = 'Pengaturan YouTube';
        $navigasi         = 'youtube';

        return view('informasi.media_sosial.youtube', compact('page_title', 'page_description'));
    }

    public function instagram()
    {
        $page_title       = 'Media Sosial';
        $page_description = 'Pengaturan Instagram';
        $navigasi         = 'instagram';

        return view('informasi.media_sosial.instagram', compact('page_title', 'page_description'));
    }

    public function whatsapp()
    {
        $page_title       = 'Media Sosial';
        $page_description = 'Pengaturan Whatsapp';
        $navigasi         = 'whatsapp';

        return view('informasi.media_sosial.whatsapp', compact('page_title', 'page_description'));
    }

    public function telegram()
    {
        $page_title       = 'Media Sosial';
        $page_description = 'Pengaturan Telegram';
        $navigasi         = 'telegram';

        return view('informasi.media_sosial.telegram', compact('page_title', 'page_description'));
    }
}
