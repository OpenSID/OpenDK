<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mapbox;

class MapboxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     
        Mapbox::insert([
            'token' => 'pk.eyJ1IjoiaHVzbnVsc2VwdGlhIiwiYSI6ImNtODE5NnpwYjBoNWkyanBpNzRoZzNyMnQifQ.kdePit4Vo48iHuUDKkaCeQ',
            'default_map' => 'Mapbox Streets',

        ]);
     
    }
}
