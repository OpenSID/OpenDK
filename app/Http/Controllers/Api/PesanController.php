<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PesanRequest;
use App\Models\Pesan;
use App\Models\PesanDetail;

class PesanController extends Controller
{
    public function store(PesanRequest $request)
    {
        if ($request->has('pesan_id')) {
            // insert percakapan
            $response = PesanDetail::create($request->all());
        } else {
            $request['das_data_desa_id'] = 1;
            // insert subject pesan
            $pesan = Pesan::create($request->all());
            // insert percakapan
            $response = $pesan->detailPesan()->create($request->all());
        }

        return response()->json(['result'=>$response]);
    }
}
