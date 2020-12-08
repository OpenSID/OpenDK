<?php

use Database\Seeds\Demo\DemoAKIAKBSeeder;
use Database\Seeds\Demo\DemoAnggaranRealisasiSeeder;
use Database\Seeds\Demo\DemoAPBDesaSeeder;
use Database\Seeds\Demo\DemoEpidemiPenyakitSeeder;
use Database\Seeds\Demo\DemoFasilitasPaudSeeder;
use Database\Seeds\Demo\DemoImunisasiSeeder;
use Database\Seeds\Demo\DemoPendudukSeeder;
use Database\Seeds\Demo\DemoPutusSekolahSeeder;
use Database\Seeds\Demo\DemoTingkatPendidikanSeeder;
use Database\Seeds\Demo\DemoToiletSanitasiSeeder;
use Illuminate\Database\Seeder;

class DemoDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DemoAKIAKBSeeder::class);
        $this->call(DemoAnggaranRealisasiSeeder::class);
        $this->call(DemoAPBDesaSeeder::class);
        $this->call(DemoEpidemiPenyakitSeeder::class);
        $this->call(DemoFasilitasPaudSeeder::class);
        $this->call(DemoImunisasiSeeder::class);
        $this->call(DemoPendudukSeeder::class);
        $this->call(DemoPutusSekolahSeeder::class);
        $this->call(DemoTingkatPendidikanSeeder::class);
        $this->call(DemoToiletSanitasiSeeder::class);
    }
}
