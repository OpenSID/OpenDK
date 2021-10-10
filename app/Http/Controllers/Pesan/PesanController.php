<?php

namespace App\Http\Controllers\Pesan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PesanController extends Controller
{
    public function index()
    {
        $page_title = 'Pesan';
        $page_description =   'Managemen Pesan';
        return view('pesan.masuk.index', compact('page_title', 'page_description'));
    }

    public function loadPesanKeluar()
    {
        $page_title = 'Pesan Keluar';
        $page_description =   'Managemen Pesan';
        return view('pesan.keluar.index', compact('page_title', 'page_description'));

    }

    public function loadPesanArsip()
    {
        $page_title = 'Pesan Arsip';
        $page_description =   'Managemen Pesan';
        return view('pesan.arsip.index', compact('page_title', 'page_description'));

    }
}
