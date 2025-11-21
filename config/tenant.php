<?php

/*
 * Tenant Configuration for OpenDK Multi-Tenant System
 *
 * This configuration defines the multi-tenant architecture with offset IDs
 * to enable multiple OpenDK instances to share a single database while
 * maintaining data isolation.
 */

return [
    /*
     * Tenant configuration using ID offset ranges
     * Format: [tenant_id => ['start' => start_range, 'end' => end_range, 'name' => tenant_name]]
     */
    'offset_ranges' => [
        // Default tenant configuration - this should be updated based on actual tenants
        // Example:
        // 1 => ['start' => 1000001, 'end' => 1999999, 'name' => 'Kecamatan A'],
        // 2 => ['start' => 2000001, 'end' => 2999999, 'name' => 'Kecamatan B'],
        // 3 => ['start' => 3000001, 'end' => 3999999, 'name' => 'Kecamatan C'],
    ],

    /*
     * Current tenant ID - typically determined by the logged in user's kecamatan
     */
    'current_tenant_id' => env('TENANT_ID', null),

    /*
     * The column name used to identify tenant ownership
     */
    'tenant_column' => 'tenant_id',
    
    /*
     * The column name for kecamatan_code that will be used as tenant identifier
     */
    'kecamatan_column' => 'kecamatan_code',
    
    /*
     * Whether to enable tenant isolation globally
     */
    'enabled' => true, // Multi-tenant system is always enabled

    /*
     * Tables that should be affected by tenant isolation
     */
    'tenant_tables' => [
        // Core profile tables
        'das_profil',
        'das_data_umum',
        'das_data_desa',
        
        // User and permission tables
        'users',
        'pengurus',
        'role_users',
        
        // Content management
        'artikel',
        'artikel_kategoris',
        'das_artikel_comment',
        'slides',
        'albums',
        'galeris',
        'faq',
        'events',
        'pesan',
        'detail_pesan',
        
        // Configuration
        'setting_aplikasis',
        'menus',
        'nav_menus',
        'themes',
        'widgets',
        'navigations',
        
        // Data tables
        'penduduk',
        'keluarga',
        'suplemen',
        'suplemen_terdata',
        'komplain',
        'jawab_komplain',
        'potensi',
        'tipe_potensis',
        
        // Financial
        'coa',
        'sub_coa',
        'sub_sub_coa',
        'coa_type',
        'anggaran_realisasi',
        'anggaran_desa',
        'apbdes',
        'laporan_apbdes',
        
        // Health
        'imunisasi',
        'aki_akb',
        'epidemi_penyakit',
        'toilet_sanitasi',
        'jenis_penyakit',
        
        // Education
        'pendidikan',
        'pendidikan_kk',
        'putus_sekolah',
        'fasilitas_paud',
        'tingkat_pendidikan',
        
        // Infrastructure
        'pembangunan',
        'pembangunan_dokumentasi',
        
        // Documents and letters
        'surat',
        'form_dokumen',
        'jenis_surat',
        'documents',
        'jenis_dokumen',
        'log_surat',
        
        // Social services
        'program',
        'peserta_program',
        
        // Government
        'prosedur',
        'regulasi',
        'tipe_regulasi',
        'kategori_komplain',
        'kategori_lembagas',
        'lembaga',
        'lembaga_anggota',
        
        // Utilities
        'log_tte',
        'log_imports',
        'visitors',
        'counter_visitor',
        'counter_page',
        'counter_page_visitor',
        'log_penduduk',
        
        // Social
        'media_sosial',
        'media_terkaits',
        'sinergi_program',
        
        // Settings and configurations
        'two_factor_auths',
        'otp_tokens',
        'setting_2fa',
        'setting_capctha',
        'email_smtps',
        'setting_sinkronisasi',
        'surveis',
    ],
];