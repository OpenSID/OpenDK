<?php

namespace App\Http\Controllers\Informasi;

use App\Facades\Counter;
use App\Http\Controllers\Controller;

use function view;

class InformasiController extends Controller
{
    /**
     * Menampilkan Kumpulan Prosedur
     **/

    public function showProsedur()
    {
        $data['page_title']       = 'Kumpulan Prosedur ';
        $data['page_description'] = 'Kumpulan Prosedur ';

        return view('Informasi.prosedur')->with($data);
    }

    /**
     * Menampilkan Data Potensi
     **/

    public function showPotensi()
    {
        $data['page_title']       = 'Potensi Kecamatan';
        $data['page_description'] = 'Menampilkan Data Potensi Kecamatan';

        return view('informasi.potensi')->with($data);
    }

    /**
     * Menampilkan Data Event
     **/

    public function showEvent()
    {
        $data['page_title']       = 'Event';
        $data['page_description'] = 'Menampilkan Event Terdekat';

        return view('Informasi.event')->with($data);
    }

    /**
     * Menampilkan Data FAQ
     **/

    public function showFAQ()
    {
        $data['page_title'] = 'FAQ';
        //$data['page_description'] = 'Menampilkan Event Terdekat';

        return view('Informasi.faq')->with($data);
    }

    /**
     * Menampilkan Kontak Kecamatan
     **/

    public function showKontak()
    {
        $data['page_title'] = 'Kontak Kecamatan ';
        //$data['page_description'] = 'Menampilkan Event Terdekat';

        return view('informasi.kontak')->with($data);
    }

    /**
     * Menampilkan Kalender Kecamatan
     **/

    public function showKalender()
    {
        $data['page_title'] = 'Kalender Kecamatan ';
        //$data['page_description'] = 'Menampilkan Event Terdekat';

        return view('informasi.kalender')->with($data);
    }
}
