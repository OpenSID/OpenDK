<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

use function captcha_img;
use function response;
use function view;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $data['page_title']       = 'Dashboard';
<<<<<<< HEAD
        $data['page_description'] = $this->sebutan_wilayah;
=======
        $data['page_description'] = 'Selamat Datang di openDK';
>>>>>>> 2890337063ab134daf3e7f211cd0f029924addf1
        return view('home')->with($data);
    }

    public function refresh_captcha()
    {
        return response()->json(['captcha' => captcha_img('mini')]);
    }
}
