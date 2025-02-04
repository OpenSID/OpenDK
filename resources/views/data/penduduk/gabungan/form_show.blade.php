<div class="row">
    <div class="col-md-6">
        <legend>DATA DIRI</legend>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Foto</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <img src="{{ is_user(null, $penduduk->jenis_kelamin) }}" id="showgambar" style="max-width:120px;max-height:150px;float:left;" />
            </div>
        </div>

        <div class="form-group">
            <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Nama</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->nama }}">
            </div>
        </div>

        <div class="form-group">
            <label for="nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->nik }}">
            </div>
        </div>

        <div class="form-group">
            <label for="no_kk_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">No KK Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->no_kk_sebelumnya }}">
            </div>
        </div>

        <div class="form-group">
            <label for="kk_level" class="control-label col-md-4 col-sm-3 col-xs-12">Hubungan dalam Keluarga</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->hubungan_dalam_keluarga }}">
            </div>
        </div>
        <div class="form-group">
            <label for="jenis_kelamin" class="control-label col-md-4 col-sm-3 col-xs-12">Jenis Kelamin</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->jenis_kelamin }}">
            </div>
        </div>

        <div class="form-group">
            <label for="agama_id" class="control-label col-md-4 col-sm-3 col-xs-12">Agama</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->agama }}">
            </div>
        </div>

        <div class="form-group">
            <label for="status" class="control-label col-md-4 col-sm-3 col-xs-12">Status Penduduk</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->status_penduduk }}">
            </div>
        </div>

        <div class="form-group">
            <label for="akta_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->akta_lahir }}">
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tempat Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->tempat_lahir }}">
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ format_date($penduduk->tanggal_lahir) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="wajib_ktp" class="control-label col-md-4 col-sm-3 col-xs-12">Wajib KTP</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->wajib_ktp }}">
            </div>
        </div>

        <div class="form-group">
            <label for="status_rekam" class="control-label col-md-4 col-sm-3 col-xs-12">Status Rekam</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->status_rekam }}">
            </div>
        </div>

        <div class="form-group">
            <label for="ktp_el" class="control-label col-md-4 col-sm-3 col-xs-12">e-KTP</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->elktp }}">
            </div>
        </div>

        <legend>PENDIDIKAN DAN PEKERJAAN</legend>

        <div class="form-group">
            <label for="pendidikan_kk_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan dalam KK</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->pendidikan_dalam_kk }}">
            </div>
        </div>

        <div class="form-group">
            <label for="pendidikan_sedang_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan sedang ditempuh</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->pendidikan_sedang_ditempuh }}">
            </div>
        </div>

        <div class="form-group">
            <label for="pekerjaan_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pekerjaan</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->pekerjaan }}">
            </div>
        </div>

        <legend>DATA KEWARGANEGARAAN</legend>

        <div class="form-group">
            <label for="warga_negara_id" class="control-label col-md-4 col-sm-3 col-xs-12">Warganegara</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->warga_negara }}">
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->nomor_passport }}">
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_akhir_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Akhir Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->tanggal_akhir_passport }}">
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_kitas" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor KITAS/KITAP</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->nomor_kitas }}">
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <legend>ORANG TUA</legend>

        <div class="form-group">
            <label for="ayah_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->nik_ayah }}">
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ayah" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->nama_ayah }}">
            </div>
        </div>

        <div class="form-group">
            <label for="ibu_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->nik_ibu }}">
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ibu" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->nama_ibu }}">
            </div>
        </div>

        <legend>ALAMAT</legend>

        <div class="form-group">
            <label for="telepon" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Telepon</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->nomor_telepon }}">
            </div>
        </div>

        <div class="form-group">
            <label for="alamat_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->alamat_sebelumnya }}">
            </div>
        </div>

        <div class="form-group">
            <label for="alamat" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sekarang</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->alamat_sekarang }}">
            </div>
        </div>

        <legend>STATUS KAWIN</legend>

        <div class="form-group">
            <label for="status_kawin" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kawin</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->status_kawin }}">
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">No. Akta Nikah (Buku Nikah)</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->no_akta_nikah }}">
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Nikah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ format_date($penduduk->tanggal_nikah) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->akta_perceraian }}">
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ format_date($penduduk->tanggal_perceraian) }}">
            </div>
        </div>

        <legend>DATA KESEHATAN</legend>

        <div class="form-group">
            <label for="golongan_darah_id" class="control-label col-md-4 col-sm-3 col-xs-12">Golongan Darah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->golongan_darah }}">
            </div>
        </div>

        <div class="form-group">
            <label for="cacat_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cacat</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->cacat }}">
            </div>
        </div>

        <div class="form-group">
            <label for="sakit_menahun_id" class="control-label col-md-4 col-sm-3 col-xs-12">Sakit Menahun</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->sakit_menahun }}">
            </div>
        </div>

        <div class="form-group">
            <label for="cara_kb_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cara KB</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->cara_kb }}">
            </div>
        </div>

        <div class="form-group">
            <label for="status_kehamilan" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kehamilan</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" value="{{ $penduduk->status_kehamilan }}">
            </div>
        </div>
    </div>
</div>

<div class="ln_solid"></div>
