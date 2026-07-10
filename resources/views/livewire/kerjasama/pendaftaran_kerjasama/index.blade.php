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
                        <h3 class="box-title">Pengajuan Kerja Sama OpenDK Portal</h3>
                    </div>
                    <div class="box-body">
                        <div class="callout callout-info">
                            <h5><strong>Kecamatan : {{ $profil['nama_kecamatan'] }}</strong></h5>
                        </div>
                        <p>OpenDesa merupakan penyelenggara OpenDK Portal yang menyediakan aplikasi, layanan
                            pendampingan, serta pengembangan ekosistem digital bagi pemerintah daerah sesuai ketentuan
                            yang berlaku.</p>
                        <p>Sebelum OpenDK Portal dapat digunakan, Kecamatan perlu memiliki dokumen kerja sama resmi
                            dengan OpenDesa sebagai dasar pelaksanaan layanan.</p>
                        <p>Setelah kerja sama disetujui, Kecamatan akan terdaftar sebagai mitra OpenDesa dan dapat
                            menggunakan OpenDK Portal beserta layanan yang disediakan sesuai ruang lingkup kerja sama.</p>
                        <p>Beberapa layanan OpenDesa dapat dikenakan biaya sesuai ketentuan yang berlaku. Pembiayaan
                            menjadi tanggung jawab pihak yang mengajukan kerja sama sesuai kesepakatan dalam dokumen
                            kerja sama.</p>
                        <p>Langkah untuk melengkapi pendaftaran adalah sebagai berikut:</p>
                        <ol>
                            <li>Unduh dokumen kerja sama.</li>
                            <li>Lengkapi tanggal penandatanganan.</li>
                            <li>Dokumen ditandatangani oleh Camat atau pejabat yang berwenang.</li>
                            <li>Scan dokumen yang telah ditandatangani.</li>
                            <li>Unggah dokumen melalui formulir.</li>
                            <li>Simpan dokumen asli.</li>
                            <li>Tunggu proses verifikasi OpenDesa.</li>
                            <li>Email pemberitahuan akan dikirim setelah kerja sama disetujui.</li>
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
                                <h5>Kami telah mengirim link verifikasi ke {{ $email }}. Silakan cek email
                                    Anda untuk memverifikasi, atau kirim ulang pendaftaran kerja sama menggunakan email
                                    aktif untuk menerima link verifikasi baru.</h5>
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
                                <h5>Dokumen permohonan kerja sama Kecamatan {{ $profil['nama_kecamatan'] }} sedang diperiksa oleh OpenDesa.</h5>
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
                                            <td>Kode Kecamatan</td>
                                            <td> : </td>
                                            <td>{{ $response['kecamatan_id'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kecamatan</td>
                                            <td> : </td>
                                            {{-- prettier-ignore-start --}}
                                            <td>{{ "Kecamatan {$profil['nama_kecamatan']}" }}
                                            </td>
                                            {{-- prettier-ignore-end --}}
                                        </tr>
                                        <tr>
                                            <td>Domain</td>
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
                            <h3 class="box-title pull-left">Form Pengajuan Kerja Sama</h3>
                            <a target="_blank" href="{{ route('kerjasama.pendaftaran.kerjasama.template') }}" type="button" class="btn btn-success pull-right"><i class="fa fa-download"></i> Unduh
                                Dokumen
                                Kerja Sama</a>
                        </div>
                        <form class="form-horizontal" enctype="multipart/form-data">
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label" for="email">Email</label>
                                    <div class="col-sm-8">
                                        <input id="email" class="form-control input-sm required" type="text" placeholder="Gunakan email yang valid" wire:model="email">
                                        <span class="help-block" style="color: #737373;">Email digunakan untuk seluruh proses verifikasi dan pemberitahuan aktivasi.</span>
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
                                    <label class="col-sm-3 control-label" for="kecamatan_id">Kode Kecamatan</label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm" type="text" wire:model="kecamatan_id" readonly />
                                        @if ($errors->has('kecamatan_id'))
                                            <span class="help-block">{{ $errors->first('kecamatan_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label" for="domain">Domain</label>
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
                                        <span class="help-block" style="color: #737373;">Diisi otomatis dari data Administrator. Dapat diubah apabila terjadi pergantian PIC.</span>
                                        @if ($errors->has('kontak_nama'))
                                            <span class="help-block">{{ $errors->first('kontak_nama') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('kontak_no_hp') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label" for="kontak_no_hp">Nomor HP</label>
                                    <div class="col-sm-8">
                                        <input id="kontak_no_hp" class="form-control input-sm" type="number" wire:model="kontak_no_hp" />
                                        <span class="help-block" style="color: #737373;">Diisi otomatis dari profil akun. Dapat diperbarui apabila diperlukan.</span>
                                        @if ($errors->has('kontak_no_hp'))
                                            <span class="help-block">{{ $errors->first('kontak_no_hp') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('permohonan') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label" for="permohonan">Unggah Dokumen Yang Telah
                                        Ditandatangani
                                        <code>(format .pdf, maks. 10 MB)</code></label>
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
