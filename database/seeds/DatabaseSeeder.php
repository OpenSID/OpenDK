<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        //$this->call(RefProvinsiTableSeeder::class);
        //$this->call(RefKabupatenTableSeeder::class);
        //$this->call(RefKecamatanTableSeeder::class);
        //$this->call(RefDesaTableSeeder::class);
		$this->call(RefWilayahTableSeeder::class);
        $this->call(RefPekerjaanTableSeeder::class);
        $this->call(RefAgamaTableSeeder::class);
        $this->call(RefKawinTableSeeder::class);
        $this->call(RefHubunganKeluargaTableSeeder::class);
        $this->call(RefPendidikanTableSeeder::class);
        $this->call(RefPendidikanKkTableSeeder::class);
        $this->call(RefGolonganDarahTableSeeder::class);
        $this->call(RefCaraKbTableSeeder::class);
        $this->call(RefWarganegaraTableSeeder::class);
        $this->call(RefCacatTableSeeder::class);
        $this->call(RefSakitMenahunTableSeeder::class);
        $this->call(RefUmurTableSeeder::class);
        $this->call(DasKategoriKomplainTableSeeder::class);
        $this->call(DasTipeRegulasiTableSeeder::class);
        $this->call(RefPenyakitTableSeeder::class);
        $this->call(RefCoaTypeTableSeeder::class);
        $this->call(RefSubCoaTableSeeder::class);
        $this->call(RefSubSubCoaTableSeeder::class);
        $this->call(RefCoaTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(DasMenuTableSeeder::class);
        /*
         * Data Seeder Kecamatan - ubah ini untuk kecamatan yg akan diinstal
         */
        $this->call(DasProfilTableSeeder::class);
        $this->call(DasDataUmumTableSeeder::class);
        $this->call(DasDataDesaTableSeeder::class);

        /*
         * Data Seeder Kecamatan Luak
         */
        // $this->call(DasProfilLuakTableSeeder::class);
        // $this->call(DasDataUmumLuakTableSeeder::class);
        // $this->call(DasDataDesaLuakTableSeeder::class);
        /*
         * Data Seeder Kecamatan Aikmel
         */
        /*
        $this->call(DasProfilAikmelTableSeeder::class);
        $this->call(DasDataUmumAikmelTableSeeder::class);
        $this->call(DasDataDesaAikmelTableSeeder::class);*/

        //$this->call(DasPendudukAikmelTableSeeder::class);
        //$this->call(DasWilClusterdesaAikmelTableSeeder::class);
        //$this->call(DasKeluargaAikmelTableSeeder::class);
    }
}
