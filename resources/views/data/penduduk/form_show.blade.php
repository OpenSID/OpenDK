<?php
$radio_sex = [1 => 'Laki-Laki', 2 => 'Perempuan'];
$radio_status = [1 => 'Tetap', 2 => 'Tidak Aktif', 3 => 'Pendatang'];
$radio_ktp_el = [1 => 'Ya', 2 => 'Tidak'];

$wajib_ktp = is_wajib_ktp(convert_born_date_to_age($penduduk->tanggal_lahir), $penduduk->status_kawin) ? 'Ya' : 'Tidak';

$hubungan = \App\Models\HubunganKeluarga::pluck('nama', 'id');
$agama = \App\Models\Agama::pluck('nama', 'id');
$pendidikanKk = \App\Models\PendidikanKK::pluck('nama', 'id');
$pendidikan = \App\Models\Pendidikan::pluck('nama', 'id');
$pekerjaan = \App\Models\Pekerjaan::pluck('nama', 'id');
$wargaNegara = \App\Models\Warganegara::pluck('nama', 'id');
$statusKawin = \App\Models\Kawin::pluck('nama', 'id');
$golonganDarah = \App\Models\GolonganDarah::pluck('nama', 'id');
$cacat = \App\Models\Cacat::pluck('nama', 'id');
$sakitMenahun = \App\Models\SakitMenahun::pluck('nama', 'id');
$caraKb = \App\Models\CaraKB::pluck('nama', 'id');
$statusRekam = status_rekam();
?>

<div class="row">
    <div class="col-md-6">
        <legend>DATA DIRI</legend>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Foto</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <img src="{{ is_user($penduduk->foto, $penduduk->sex) }}" id="showgambar" style="max-width:120px;max-height:150px;float:left;" />
            </div>
        </div>

        <div class="form-group">
            <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Nama</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->nama ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->nik ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="no_kk_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">No KK Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->no_kk_sebelumnya ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="kk_level" class="control-label col-md-4 col-sm-3 col-xs-12">Hubungan dalam Keluarga</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $hubungan[$penduduk->kk_level] ?? '' }}</p>
            </div>
        </div>
        <div class="form-group">
            <label for="jenis_kelamin" class="control-label col-md-4 col-sm-3 col-xs-12">Jenis Kelamin</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $radio_sex[$penduduk->sex] ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="agama_id" class="control-label col-md-4 col-sm-3 col-xs-12">Agama</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $agama[$penduduk->agama_id] ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="status" class="control-label col-md-4 col-sm-3 col-xs-12">Status Penduduk</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $radio_status[$penduduk->status] ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="akta_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->akta_lahir ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tempat Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->tempat_lahir ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ format_date($penduduk->tanggal_lahir) }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="wajib_ktp" class="control-label col-md-4 col-sm-3 col-xs-12">Wajib KTP</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $wajib_ktp }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="status_rekam" class="control-label col-md-4 col-sm-3 col-xs-12">Status Rekam</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $statusRekam[$penduduk->status_rekam] ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="ktp_el" class="control-label col-md-4 col-sm-3 col-xs-12">e-KTP</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $radio_ktp_el[$penduduk->ktp_el] ?? '' }}</p>
            </div>
        </div>

        <legend>PENDIDIKAN DAN PEKERJAAN</legend>

        <div class="form-group">
            <label for="pendidikan_kk_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan dalam KK</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $pendidikanKk[$penduduk->pendidikan_kk_id] ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="pendidikan_sedang_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan sedang ditempuh</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $pendidikan[$penduduk->pendidikan_sedang_id] ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="pekerjaan_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pekerjaan</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $pekerjaan[$penduduk->pekerjaan_id] ?? '' }}</p>
            </div>
        </div>

        <legend>DATA KEWARGANEGARAAN</legend>

        <div class="form-group">
            <label for="warga_negara_id" class="control-label col-md-4 col-sm-3 col-xs-12">Warganegara</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $wargaNegara[$penduduk->warga_negara_id] ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->dokumen_pasport ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_akhir_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Akhir Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ format_date($penduduk->tanggal_akhir_pasport) }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_kitas" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor KITAS/KITAP</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->dokumen_kitas ?? '' }}</p>
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <legend>ORANG TUA</legend>

        <div class="form-group">
            <label for="ayah_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->ayah_nik ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ayah" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->nama_ayah ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="ibu_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->ibu_nik ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ibu" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->nama_ibu ?? '' }}</p>
            </div>
        </div>

        <legend>ALAMAT</legend>

        <div class="form-group">
            <label for="telepon" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Telepon</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->telepon ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="alamat_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->alamat_sebelumnya ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="alamat" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sekarang</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->alamat ?? '' }}</p>
            </div>
        </div>

        <legend>STATUS KAWIN</legend>

        <div class="form-group">
            <label for="status_kawin" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kawin</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $statusKawin[$penduduk->status_kawin] ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">No. Akta Nikah (Buku Nikah)</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->akta_perkawinan ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Nikah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ format_date($penduduk->tanggal_perkawinan) }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $penduduk->akta_perceraian ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ format_date($penduduk->tanggal_perceraian) }}</p>
            </div>
        </div>

        <legend>DATA KESEHATAN</legend>

        <div class="form-group">
            <label for="golongan_darah_id" class="control-label col-md-4 col-sm-3 col-xs-12">Golongan Darah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $golonganDarah[$penduduk->golongan_darah_id] ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="cacat_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cacat</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $cacat[$penduduk->cacat_id] ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="sakit_menahun_id" class="control-label col-md-4 col-sm-3 col-xs-12">Sakit Menahun</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $sakitMenahun[$penduduk->sakit_menahun_id] ?? '' }}</p>
            </div>
        </div>

        <div class="form-group">
            <label for="cara_kb_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cara KB</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="form-control-static">{{ $caraKb[$penduduk->cara_kb_id] ?? '' }}</p>
            </div>
        </div>
        @includeWhen($penduduk->sex == 2 && $penduduk->status_kawin > 1, 'data.penduduk.form_show_status_kehamilan')
    </div>
</div>

<div class="ln_solid"></div>
