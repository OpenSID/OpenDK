@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li>PPID</li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Pengaturan Layanan PPID</h3>
            </div>

            <form method="POST" action="{{ route('ppid.pengaturan.update') }}">
                @csrf

                <div class="box-body">
                    <div class="form-group">
                        <label for="nama_ppid">Nama Instansi PPID</label>
                        <input type="text" id="nama_ppid" name="nama_ppid" class="form-control"
                               value="{{ $pengaturan['nama_ppid'] ?? '' }}"
                               placeholder="Contoh: PPID Kecamatan Sejahtera">
                    </div>

                    <div class="form-group">
                        <label for="alamat_ppid">Alamat PPID</label>
                        <textarea id="alamat_ppid" name="alamat_ppid" class="form-control" rows="3"
                                  placeholder="Masukkan alamat lengkap kantor PPID">{{ $pengaturan['alamat_ppid'] ?? '' }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nomor_telepon">Nomor Telepon</label>
                                <input type="text" id="nomor_telepon" name="nomor_telepon" class="form-control"
                                       value="{{ $pengaturan['nomor_telepon'] ?? '' }}"
                                       placeholder="Contoh: (021) 1234567">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email_ppid">Email PPID</label>
                                <input type="email" id="email_ppid" name="email_ppid" class="form-control"
                                       value="{{ $pengaturan['email_ppid'] ?? '' }}"
                                       placeholder="Contoh: ppid@kecamatan.go.id">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jam_operasional">Jam Operasional Pelayanan</label>
                        <input type="text" id="jam_operasional" name="jam_operasional" class="form-control"
                               value="{{ $pengaturan['jam_operasional'] ?? '' }}"
                               placeholder="Contoh: Senin - Jumat: 08.00 - 16.00 WIB">
                    </div>

                    <hr>
                    <h4>Data Pejabat PPID</h4>

                    <div class="form-group">
                        <label for="nama_pejabat">Nama Pejabat PPID</label>
                        <input type="text" id="nama_pejabat" name="nama_pejabat" class="form-control"
                               value="{{ $pengaturan['nama_pejabat'] ?? '' }}"
                               placeholder="Masukkan nama pejabat PPID">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nip_pejabat">NIP Pejabat</label>
                                <input type="text" id="nip_pejabat" name="nip_pejabat" class="form-control"
                                       value="{{ $pengaturan['nip_pejabat'] ?? '' }}"
                                       placeholder="Masukkan NIP pejabat">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jabatan_pejabat">Jabatan Pejabat</label>
                                <input type="text" id="jabatan_pejabat" name="jabatan_pejabat" class="form-control"
                                       value="{{ $pengaturan['jabatan_pejabat'] ?? '' }}"
                                       placeholder="Contoh: Kepala Dinas Komunikasi dan Informatika">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
