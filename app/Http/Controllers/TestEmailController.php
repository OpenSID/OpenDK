<?php

namespace App\Http\Controllers;

use App\Notifications\TestEmail;
use Illuminate\Http\Request;

class TestEmailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            $request->user()->notify(new TestEmail());
            return response()->json([
                'message' => 'Email berhasil dikirim'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Email gagal dikirim',
                'error' => $e->getMessage()
            ], 500);
        }        
    }
}
