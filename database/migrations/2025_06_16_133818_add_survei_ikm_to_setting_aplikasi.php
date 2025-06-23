<?php

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tambahkan setting untuk pertanyaan IKM
        SettingAplikasi::insert([
            'key' => 'survei_ikm',
            'value' => 'Menurut Anda bagaimana informasi yang tercantum dalam website ini?',
            'type' => 'input',
            'description' => 'Pertanyaan untuk survei Indeks Kepuasan Masyarakat (IKM)',
            'kategori' => 'web',
            'option' => '{}',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Hapus setting untuk pertanyaan IKM
        SettingAplikasi::where('key', 'survei_ikm')->delete();
    }
};
