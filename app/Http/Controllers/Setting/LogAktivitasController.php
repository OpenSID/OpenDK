<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function __invoke(Request $request)
    {
        $page_title = 'Riwayat Aktivitas';
        $page_description = 'Log aktivitas pengguna dan sistem';

        return view('setting.log-aktivitas.index', compact('page_title', 'page_description'));
    }
}
