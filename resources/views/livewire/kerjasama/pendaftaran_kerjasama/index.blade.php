@push('css')
    <style>
        /* Menandai form-group dengan error */
        .has-error .form-control {
            border-color: #a94442;
            box-shadow: none;
            /* Menghilangkan shadow default */
        }

        /* Warna teks untuk pesan error */
        .has-error .control-label,
        .has-error .help-block {
            color: #a94442;
        }

        /* Tambahkan pesan di bawah input */
        .help-block {
            font-size: 12px;
            margin-top: 5px;
            color: #a94442;
        }
    </style>
@endpush
<div>
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content" id="maincontent">

        <x-check_connection>
            <div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pendaftaran Kerjasama {{ $profil['nama_kecamatan'] }}</h3>
                    </div>
                    <div class="box-body">
                        <p>{{ $profil['nama_kecamatan'] }} (lembaga hukum dikukuhkan Keputusan Menteri Hukum dan Hak
                            Asasi
                            Manusia
                            Nomor AHU-0001417.AH.01.08.Tahun 2021) menyediakan aplikasi dan layanan yang memerlukan
                            kontribusi yang
                            perlu dianggarkan Desa. Untuk memenuhi peraturan pengadaan yang berlaku, Desa perlu memiliki
                            kerjasama
                            pengadaan dengan {{ $profil['nama_kecamatan'] }} sebelum dapat menggunakan aplikasi dan
                            layanan
                            {{ $profil['nama_kecamatan'] }} berbayar tersebut.</p>
                        <p>Gunakan fitur ini untuk mendaftarkan dan mengeksekusi kerjasama resmi dengan
                            {{ $profil['nama_kecamatan'] }}. Setelah Kesepakatan Kerjasama antara Desa dan
                            {{ $profil['nama_kecamatan'] }} berlaku, Desa akan
                            terdaftar sebagai Desa Digital {{ $profil['nama_kecamatan'] }} dan
                            berhak mengakses aplikasi dan layanan {{ $profil['nama_kecamatan'] }} berbayar dan
                            program-program
                            peningkatan desa digital lainnya.</p>
                        <p>Cetak dokumen Kesepakatan Kerjasama menggunakan tombol yang disediakan. Langkah untuk
                            melengkapi
                            pendaftaran adalah sebagai berikut:</p>
                        <p>
                        <ol>
                            <li>Cetak dokumen Kesepakatan Kerjasama (Pada pengaturan cetak, Option : Headers and Footers
                                jangan di
                                centang).</li>
                            <li>Isi tanggal penandatanganan.</li>
                            <li>Tandatangani oleh Kades sebagai PIHAK KESATU di atas meterai Rp10.000</li>
                            <li>Scan dokumen yang telah ditandatangani.</li>
                            <li>Unggah hasil scan menggunakan form pendaftaran.</li>
                            <li>Simpan dokumen asli di arsip kantor desa.</li>
                            <li>Cek email inbox/pesan yang Anda gunakan untuk memverifikasi.</li>
                            <li>Setelah pendaftaran diverifikasi dan kerjasama diaktifkan oleh
                                {{ $profil['nama_kecamatan'] }},
                                email pemberitahuan akan dikirim ke alamat email terdaftar.</li>
                        </ol>
                    </div>
                </div>

                @if ($status_langganan === 'menunggu verifikasi email')
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <i class="icon fa fa-info"></i>
                            <h3 class="box-title">Status Registrasi</h3>
                        </div>
                        <div class="box-body">
                            <div class="callout callout-info">
                                <h5>Kami telah mengirim link verifikasi ke {{ $email }} <br> Silahkan cek email
                                    Anda
                                    untuk memverifikasi, atau kirim ulang pendaftaran kerjasama menggunakan email aktif
                                    untuk menerima
                                    link verifikasi baru.</h5>
                            </div>
                        </div>
                    </div>

                    <div class="box box-info">
                        <div class="box-header with-border">
                            <i class="icon fa fa-info"></i>
                            <h3 class="box-title">Langkah-langkah melakukan pengecekan email untuk verifikasi
                        </div>
                        <div class="box-body">
                            <div class="callout callout-info">
                                <h5>1. Cek folder kotak masuk / inbox, jika ada, maka silahkan klik pesan tersebut lalu
                                    klik
                                    tombol
                                    verifikasi email. </h5>
                                <h5>2. Cek folder spam, jika ada, maka:<br>
                                    - Klik pesan lalu hapus label spam pada pesan tersebut.<br>
                                    - Setelah label spam dihapus, pesan akan masuk ke folder inbox.<br>
                                    - Selanjutnya cek folder inbox, dan silahkan klik pesan dan klik tombol
                                    verifikasi.<br>
                                </h5>
                                <h5>3. Jika Anda tidak menerima pesan pada folder inbox dan folder spam, silahkan kirim
                                    ulang
                                    pendaftaran kerjasama menggunakan email aktif untuk menerima link verifikasi baru,
                                    pastikan
                                    email
                                    sudah benar.</h5>
                            </div>
                        </div>
                    </div>
                @elseif ($status_langganan === 'menunggu verifikasi pendaftaran')
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <i class="icon fa fa-info"></i>
                            <h3 class="box-title">Status Registrasi</h3>
                        </div>
                        <div class="box-body">
                            <div class="callout callout-info">
                                <h5>Dokumen permohonan kerjasama Desa anda sedang diperiksa oleh Pelaksana Layanan
                                    {{ $profil['nama_kecamatan'] }}.</h5>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- sudah terdaftar --}}
                @if ($status_registrasi_id == 6)
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <i class="icon fa fa-info-circle"></i>
                            <h3 class="box-title">{{ $pesan_terdaftar }}</h3>
                        </div>
                        <div class="box-body">
                            <h5 class="text-bold">Rincian Pelanggan</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover tabel-rincian">
                                    <tbody>
                                        <tr>
                                            <td width="20%">ID Pelanggan</td>
                                            <td width="1">:</td>
                                            <td>{{ $response['id'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status Registrasi</td>
                                            <td>:</td>
                                            <td>{{ $response['status_langganan'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>KODE {{ strtoupper($profil['nama_kecamatan']) }}</td>
                                            <td> : </td>
                                            <td>{{ $response['kecamatan_id'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ strtoupper($profil['nama_kecamatan']) }}</td>
                                            <td> : </td>
                                            {{-- prettier-ignore-start --}}
                                            <td>{{ "Kecamatan {$profil['nama_kecamatan']}" }}
                                            </td>
                                            {{-- prettier-ignore-end --}}
                                        </tr>
                                        <tr>
                                            <td>Domain Desa</td>
                                            <td> : </td>
                                            <td>{{ $response['domain'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nama Kontak</td>
                                            <td> : </td>
                                            <td>{{ "{$response['nama_kontak']} | {$response['no_hp_kontak']}" }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <livewire:components.alert />

                    <div class="box box-info">
                        <div class="box-header with-border clearfix">
                            <h3 class="box-title pull-left">Form Pendaftaran Kerjasama</h3>
                            <a target="_blank" href="{{ route('kerjasama.pendaftaran.kerjasama.template') }}" type="button" class="btn btn-success pull-right"><i class="fa fa-download"></i> Unduh
                                Dokumen
                                Kerjasama</a>
                        </div>
                        <form class="form-horizontal" enctype="multipart/form-data">
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label" for="email">Email</label>
                                    <div class="col-sm-8">
                                        <input id="email" class="form-control input-sm required" type="text" placeholder="Gunakan email yang valid" wire:model="email">

                                        @if ($errors->has('email'))
                                            <span class="help-block">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('status_registrasi') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label">Status Registrasi</label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm" type="text" wire:model="status_registrasi" readonly>
                                        @if ($errors->has('status_registrasi'))
                                            <span class="help-block">{{ $errors->first('status_registrasi') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('kecamatan_id') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label" for="kecamatan_id">Kode Kecamatan
                                        {{ ucfirst($profil['nama_kecamatan']) }}</label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm bilangan_titik required" type="text" wire:model="kecamatan_id" />
                                        @if ($errors->has('kecamatan_id'))
                                            <span class="help-block">{{ $errors->first('kecamatan_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label" for="domain">Domain Kecamatan
                                        {{ ucfirst($profil['nama_kecamatan']) }}</label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm" type="text" readonly wire:model="domain">
                                        @if ($errors->has('domain'))
                                            <span class="help-block">{{ $errors->first('domain') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('kontak_nama') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label" for="kontak_nama">Nama Kontak</label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm" type="text" wire:model="kontak_nama" />
                                        @if ($errors->has('kontak_nama'))
                                            <span class="help-block">{{ $errors->first('kontak_nama') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('kontak_no_hp') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label" for="kontak_no_hp">No HP. Kontak</label>
                                    <div class="col-sm-8">
                                        <input id="kontak_no_hp" class="form-control input-sm" type="number" wire:model="kontak_no_hp" />
                                        @if ($errors->has('kontak_no_hp'))
                                            <span class="help-block">{{ $errors->first('kontak_no_hp') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('permohonan') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label" for="permohonan">Unggah Dokumen Yang Telah
                                        Ditandatangani
                                        <code>(format .pdf)</code></label>
                                    <div class="col-sm-8">
                                        <x-upload-file name="permohonan" iteration="{{ $iteration }}" />
                                        @if ($errors->has('permohonan'))
                                            <span class="help-block">{{ $errors->first('permohonan') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                                    Batal</button>
                                <button type="button" class="simpan btn btn-social btn-info btn-sm pull-right" wire:click="register" @if (empty($permohonan)) disabled @endif><i class="fa fa-check"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

        </x-check_connection>

    </section>

</div>
