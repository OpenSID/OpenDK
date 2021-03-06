<?php

use Illuminate\Database\Seeder;

class DasMenuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('das_menu')->delete();

        \DB::table('das_menu')->insert(array (
            0 =>
            array (
                'id' => 1,
                'parent_id' => '0',
                'name' => 'Data',
                'slug' => 'data',
                'icon' => 'fa-book',
                'url' => 'data',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'parent_id' => '1',
                'name' => 'Kecamatan',
                'slug' => 'data-kecamatan',
                'icon' => 'fa-book',
                'url' => 'data/kecamatan',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'parent_id' => '1',
                'name' => 'Penduduk',
                'slug' => 'data-penduduk',
                'icon' => 'fa-book',
                'url' => 'data/penduduk',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'parent_id' => '1',
                'name' => 'Kesehatan',
                'slug' => 'data-kesehatan',
                'icon' => 'fa-book',
                'url' => 'data/kesehatan',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'parent_id' => '1',
                'name' => 'Pendidikan',
                'slug' => 'data-pendidikan',
                'icon' => 'fa-book',
                'url' => 'data/pendidikan',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'parent_id' => '1',
                'name' => 'Program Bantuan',
                'slug' => 'data-programbantuan',
                'icon' => 'fa-book',
                'url' => 'data/program-bantuan',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'parent_id' => '1',
                'name' => 'Anggaran & Realisasi',
                'slug' => 'data-anggaranrealisasi',
                'icon' => 'fa-book',
                'url' => 'data/anggaran-realisasi',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'parent_id' => '1',
                'name' => 'Anggaran Desa',
                'slug' => 'data-anggarandesa',
                'icon' => 'fa-book',
                'url' => 'data/anggaran-desa',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 =>
            array (
                'id' => 9,
                'parent_id' => '1',
                'name' => 'Layanan Kecamatan',
                'slug' => 'data-layanan',
                'icon' => 'fa-book',
                'url' => 'data/layanan',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 =>
            array (
                'id' => 10,
                'parent_id' => '0',
                'name' => 'Admin Keluhan',
                'slug' => 'adminsikoma',
                'icon' => 'fa-book',
                'url' => 'admin-komplain',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 =>
            array (
                'id' => 11,
                'parent_id' => '0',
                'name' => 'Pengaturan',
                'slug' => 'setting',
                'icon' => 'fa-book',
                'url' => 'settings',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 =>
            array (
                'id' => 12,
                'parent_id' => '11',
                'name' => 'Kategori Komplain',
                'slug' => 'setting-kategorikomplain',
                'icon' => 'fa-book',
                'url' => 'setting/kategori-komplain',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 =>
            array (
                'id' => 13,
                'parent_id' => '11',
                'name' => 'Tipe Regulasi',
                'slug' => 'setting-tiperegulasi',
                'icon' => 'fa-book',
                'url' => 'setting/tipe-regulasi',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 =>
            array (
                'id' => 14,
                'parent_id' => '11',
                'name' => 'Jenis Penyakit',
                'slug' => 'setting-jenispenyakit',
                'icon' => 'fa-book',
                'url' => 'setting/jenis-penyakit',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 =>
            array (
                'id' => 15,
                'parent_id' => '11',
                'name' => 'COA',
                'slug' => 'setting-coa',
                'icon' => 'fa-book',
                'url' => 'setting/coa',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 =>
            array (
                'id' => 16,
                'parent_id' => '11',
                'name' => 'Grup Pengguna',
                'slug' => 'setting-gruppengguna',
                'icon' => 'fa-book',
                'url' => 'setting/role',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 =>
            array (
                'id' => 17,
                'parent_id' => '11',
                'name' => 'Pengguna',
                'slug' => 'setting-pengguna',
                'icon' => 'fa-book',
                'url' => 'setting/user',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 =>
            array (
                'id' => 18,
                'parent_id' => '11',
                'name' => 'Halaman Beranda',
                'slug' => 'setting-dashboard',
                'icon' => 'fa-book',
                'url' => 'setting/user',
                'is_active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}