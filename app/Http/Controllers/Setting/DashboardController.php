<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $page_title       = 'Pegaturan Beranda';
        $browser_title    = $this->browser_title;

        return view('setting.dashboard.index', compact('page_title', 'browser_title'));
    }

    public function edit_browser_title()
    {
        $page_title       = 'Ubah Judul Beranda';
        $browser_title    = $this->browser_title;

        return view('setting.dashboard.edit_browser_title', compact('page_title', 'browser_title'));
    }
}
