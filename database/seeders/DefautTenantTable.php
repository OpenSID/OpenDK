<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace Database\Seeders;


class DefautTenantTable
{
    const HAS_TENANT_COLUMN = [
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
        'galeris',        
        'kategoris',
        'log_imports',
        'log_penduduk',
        'media_terkaits',        
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
        'throttle',
        'two_factor_auths',
        'users',
        'visitors',
        'widgets',
    ];
}
