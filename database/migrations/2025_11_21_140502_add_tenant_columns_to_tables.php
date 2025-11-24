<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tables = [
        'albums',
        'das_akib',
        'das_anggaran_desa',
        'das_anggaran_realisasi',
        'das_apbdes',
        'das_artikel',
        'das_artikel_comment',
        'das_artikel_kategori',
        'das_counter_page',
        'das_counter_page_visitor',
        'das_counter_visitor',
        'das_data_desa',
        'das_data_umum',
        'das_epidemi_penyakit',
        'das_events',
        'das_faq',
        'das_fasilitas_paud',
        'das_form_dokumen',
        'das_imunisasi',
        'das_jawab_komplain',
        'das_jenis_dokumen',
        'das_jenis_surat',
        'das_kategori_komplain',
        'das_keluarga',
        'das_komplain',
        'das_laporan_penduduk',
        'das_lembaga',
        'das_lembaga_anggota',
        'das_lembaga_kategori',
        'das_log_surat',
        'das_log_tte',
        'das_media_sosial',
        'das_menu',
        'das_navigation',
        'das_pembangunan',
        'das_pembangunan_dokumentasi',
        'das_penduduk',
        'das_penduduk_sex',
        'das_pengurus',
        'das_pesan',
        'das_pesan_detail',
        'das_peserta_program',
        'das_potensi',
        'das_profil',
        'das_program',
        'das_prosedur',
        'das_putus_sekolah',
        'das_regulasi',
        'das_setting',
        'das_sinergi_program',
        'das_suplemen',
        'das_suplemen_terdata',
        'das_survei',
        'das_themes',
        'das_tingkat_pendidikan',
        'das_tipe_potensi',
        'das_tipe_regulasi',
        'das_toilet_sanitasi',
        'das_wil_clusterdesa',
        'documents',
        'failed_jobs',
        'galeris',
        'jobs',
        'kategoris',
        'log_imports',
        'log_penduduk',
        'media_terkaits',
        'migrations',
        'model_has_permissions',
        'model_has_roles',
        'nav_menus',
        'otp_tokens',
        'permissions',
        'ref_agama',
        'ref_cacat',
        'ref_cara_kb',
        'ref_coa',
        'ref_coa_type',
        'ref_golongan_darah',
        'ref_hubungan_keluarga',
        'ref_jabatan',
        'ref_kawin',
        'ref_pekerjaan',
        'ref_pendidikan',
        'ref_pendidikan_kk',
        'ref_penyakit',
        'ref_sakit_menahun',
        'ref_smtp',
        'ref_sub_coa',
        'ref_sub_sub_coa',
        'ref_umur',
        'ref_warganegara',
        'role_has_permissions',
        'roles',
        'slides',
        'tenants',
        'throttle',
        'two_factor_auths',
        'users',
        'visitors',
        'widgets',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add tenant_id to core tables that need multi-tenant isolation if they exist
        // This approach checks for table existence before adding columns

        $tables = $this->tables;
        $defaultTenantId = \Illuminate\Support\Facades\DB::table('tenants')->min('id');
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    $blueprint->unsignedBigInteger('tenant_id')->nullable();
                    $blueprint->foreignId('tenant_id')->on('tenants')->references('id')->onDelete('cascade')->onUpdate('cascade');
                });

                // Update existing records to set tenant_id to default tenant
                if ($defaultTenantId) {
                    \Illuminate\Support\Facades\DB::table($table)->update(['tenant_id' => $defaultTenantId]);
                }
            }
            
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove tenant_id columns from tables if they exist
        $tables = $this->tables;

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    $blueprint->dropForeign('tenant_id');
                    $blueprint->dropColumn('tenant_id');
                });
            }
        }

        // Drop the tenants table
        Schema::dropIfExists('tenants');
    }
};
