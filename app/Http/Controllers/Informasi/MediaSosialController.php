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
        $page             = 'Facebook';
        $placeholder      = 'https://web.facebook.com/tokoopendesa atau tokoopendesa';

        return view('informasi.media_sosial.index', compact('page_title', 'page_description', 'medsos', 'page', 'placeholder'));
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'link' => 'required',
        ]);

        $page = 'index';
        if ($request->medsos != 'Facebook') {
            $page = strtolower($request->medsos);
        }

        try {
            MediaSosial::findOrFail($id)->update($request->except(['request']));
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', $request->medsos .' gagal diubah!');
        }

        return redirect()->route('informasi.media-sosial.' . $page)->with('success', $request->medsos . ' berhasil diubah!');
    }

    public function twitter()
    {
        $medsos           = MediaSosial::findOrFail(2);
        $page_title       = 'Media Sosial';
        $page_description = 'Pengaturan Twitter';
        $page             = 'Twitter';
        $placeholder      = 'https://twitter.com/opendesa, opendesa atau @opendesa';

        return view('informasi.media_sosial.index', compact('page_title', 'page_description', 'medsos', 'page', 'placeholder'));
    }

    public function youtube()
    {
        $medsos           = MediaSosial::findOrFail(3);
        $page_title       = 'Media Sosial';
        $page_description = 'Pengaturan YouTube';
        $page             = 'YouTube';
        $placeholder      = 'https://www.youtube.com/channel/UCvZuSYtrWYuE8otM4SsdT0Q atau UCvZuSYtrWYuE8otM4SsdT0Q';

        return view('informasi.media_sosial.index', compact('page_title', 'page_description', 'medsos', 'page', 'placeholder'));
    }

    public function instagram()
    {
        $medsos           = MediaSosial::findOrFail(4);
        $page_title       = 'Media Sosial';
        $page_description = 'Pengaturan YouTube';
        $page             = 'YouTube';
        $placeholder      = 'https://www.instagram.com/OpenDesa, OpenDesa atau @OpenDesa';

        return view('informasi.media_sosial.index', compact('page_title', 'page_description', 'medsos', 'page', 'placeholder'));
    }

    public function whatsapp()
    {
        $medsos           = MediaSosial::findOrFail(5);
        $page_title       = 'Media Sosial';
        $page_description = 'Pengaturan YouTube';
        $page             = 'YouTube';
        $placeholder      = '0851234567890 atau CryQ1VyOXghEVJUTFpwFPb';

        return view('informasi.media_sosial.index', compact('page_title', 'page_description', 'medsos', 'page', 'placeholder'));
    }

    public function telegram()
    {
        $medsos           = MediaSosial::findOrFail(6);
        $page_title       = 'Media Sosial';
        $page_description = 'Pengaturan YouTube';
        $page             = 'YouTube';
        $placeholder      = 'https://t.me/OpenDesa';

        return view('informasi.media_sosial.index', compact('page_title', 'page_description', 'medsos', 'page', 'placeholder'));
    }
}
