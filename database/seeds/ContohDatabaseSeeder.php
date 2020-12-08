<?php

use Illuminate\Database\Seeder;

class ContohDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ContohDasProfilTableSeeder::class);
        $this->call(ContohDasDataUmumTableSeeder::class);
        $this->call(ContohDasDataDesaTableSeeder::class);
        $this->call(ContohDasPendudukTableSeeder::class);
        $this->call(ContohDasKeluargaTableSeeder::class);
    }
}
