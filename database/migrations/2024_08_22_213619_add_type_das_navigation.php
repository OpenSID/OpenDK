<?php

use App\Enums\MenuTipe;
use App\Models\Navigation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('das_navigation', 'type')) {
            Schema::table('das_navigation', function (Blueprint $table) {
                $table->tinyInteger('type')->default(0)->after('slug');
            });   
        }        

        Navigation::where('url', 'like', '%profil/%')->update(['type' => MenuTipe::PROFIL]);
        Navigation::where('url', 'like', '%desa/%')->update(['type' => MenuTipe::DESA]);
        Navigation::where('url', 'like', '%statistik/%')->update(['type' => MenuTipe::STATISTIK]);
        Navigation::where('url', 'like', '%potensi/%')->update(['type' => MenuTipe::POTENSI]);
        Navigation::where('url', 'like', '%unduhan/%')->update(['type' => MenuTipe::UNDUHAN]);
        Navigation::where('url', '/')->update(['url' => url('/')]);
        Navigation::where('url', '/faq')->update(['url' => url('/faq')]);
        Navigation::where('url', '/berita-desa')->update(['url' => url('/berita-desa')]);
        Navigation::where('url', '/#')->update(['url' => '#']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_navigation', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
