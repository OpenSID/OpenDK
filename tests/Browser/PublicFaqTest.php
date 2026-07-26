<?php

use App\Models\Faq;
use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

test('public faq page should display only active faqs via datatables', function () {
    // 1. Setup Data
    Faq::query()->delete();
    
    Faq::create([
        'question' => 'Apakah ini FAQ Aktif?',
        'answer' => 'Ya, ini aktif dan harus tampil.',
        'status' => 1
    ]);
    
    Faq::create([
        'question' => 'Apakah ini FAQ Draft?',
        'answer' => 'Tidak, ini draft dan disembunyikan.',
        'status' => 0
    ]);

    // 2 & 3. Act & Assert
    visit('/faq')
        // Pastikan halaman terbuka dengan benar
        ->assertSee('Pertanyaan Yang Sering Diajukan')
        
        // Pastikan input pencarian datatables muncul (indikasi datatables inisialisasi sukses)
        ->assertPresent('input[type="search"]')
        
        // Tunggu proses AJAX selesai (menunggu ketersediaan data)
        ->assertScript(
            "new Promise((resolve) => {
                const check = () => {
                    const table = document.querySelector('#faq-table');
                    const tbody = table ? table.querySelector('tbody') : null;
                    const processing = document.querySelector('.dataTables_processing');
                    
                    if (tbody && tbody.children.length > 0 && (!processing || processing.style.display === 'none')) {
                        resolve(true);
                    } else {
                        setTimeout(check, 300);
                    }
                };
                check();
            })",
            true
        )
        
        // Pastikan tabel tidak kosong
        ->assertMissing('.dataTables_empty')
        
        // Pastikan FAQ aktif tampil
        ->assertSee('Apakah ini FAQ Aktif?')
        ->assertSee('Ya, ini aktif dan harus tampil.')
        
        // Pastikan FAQ draft TIDAK tampil
        ->assertDontSee('Apakah ini FAQ Draft?')
        ->assertDontSee('Tidak, ini draft dan disembunyikan.');
        
})->group('browser', 'faq', 'public');
