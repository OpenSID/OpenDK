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
                <input type="text" name="nama" value="{{ $penduduk->nama ?? '' }}" class="form-control" disabled id="nama">
            </div>
        </div>

        <div class="form-group">
            <label for="nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="nik" value="{{ $penduduk->nik ?? '' }}" class="form-control" disabled id="nik">
            </div>
        </div>

        <div class="form-group">
            <label for="no_kk_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">No KK Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="no_kk_sebelumnya" value="{{ $penduduk->no_kk_sebelumnya ?? '' }}" class="form-control" disabled id="no_kk_sebelumnya">
            </div>
        </div>

        <div class="form-group">
            <label for="kk_level" class="control-label col-md-4 col-sm-3 col-xs-12">Hubungan dalam Keluarga</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="kk_level" class="form-control" disabled id="kk_level">
                    @foreach(\App\Models\HubunganKeluarga::pluck('nama', 'id') as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->kk_level == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="jenis_kelamin" class="control-label col-md-4 col-sm-3 col-xs-12">Jenis Kelamin</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if ($penduduk->sex == 1) active @endif" disabled>
                        <input
                            type="radio"
                            name="sex"
                            id="jenis_kelamin"
                            disabled
                            value="1"
                            autocomplete="off"
                            @if ($penduduk->sex == 1) checked @endif
                        > Laki-Laki
                    </label>
                    <label class="btn btn-primary btn-sm @if ($penduduk->sex == 2) active @endif disabled">
                        <input
                            type="radio"
                            name="sex"
                            id="jenis_kelamin"
                            disabled
                            value="2"
                            autocomplete="off"
                            @if ($penduduk->sex == 2) checked @endif
                        > Perempuan
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="agama_id" class="control-label col-md-4 col-sm-3 col-xs-12">Agama</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="agama_id" class="form-control" disabled id="agama_id">
                    @foreach(\App\Models\Agama::pluck('nama', 'id') as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->agama_id == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="status" class="control-label col-md-4 col-sm-3 col-xs-12">Status Penduduk</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if ($penduduk->status == 1) active @endif" disabled>
                        <input
                            type="radio"
                            name="status"
                            id="status"
                            value="1"
                            disabled
                            autocomplete="off"
                            @if ($penduduk->status == 1) checked @endif
                        > Tetap
                    </label>
                    <label class="btn btn-primary btn-sm @if ($penduduk->status == 2) active @endif" disabled>
                        <input
                            type="radio"
                            name="status"
                            id="status"
                            value="2"
                            disabled
                            autocomplete="off"
                            @if ($penduduk->status == 2) checked @endif
                        > Tidak Aktif
                    </label>
                    <label class="btn btn-primary btn-sm @if ($penduduk->status == 3) active @endif" disabled>
                        <input
                            type="radio"
                            name="status"
                            id="status"
                            value="3"
                            disabled
                            autocomplete="off"
                            @if ($penduduk->status == 3) checked @endif
                        > Pendatang
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="akta_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="akta_lahir" value="{{ $penduduk->akta_lahir ?? '' }}" class="form-control" disabled id="akta_lahir">
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tempat Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="tempat_lahir" value="{{ $penduduk->tempat_lahir ?? '' }}" class="form-control" disabled id="tempat_lahir">
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="tanggal_lahir" value="{{ format_date($penduduk->tanggal_lahir) }}" class="form-control" disabled id="tanggal_lahir">
            </div>
        </div>

        <?php
        $wajib_ktp = is_wajib_ktp(convert_born_date_to_age($penduduk->tanggal_lahir), $penduduk->status_kawin);
        if ($wajib_ktp) {
            $status_ktp = 'Ya';
        } else {
            $status_ktp = 'Tidak';
        }
        
        ?>
        <div class="form-group">
            <label for="wajib_ktp" class="control-label col-md-4 col-sm-3 col-xs-12">Wajib KTP</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="wajib_ktp" value="{{ $status_ktp }}" class="form-control" disabled id="wajib_ktp">
            </div>
        </div>

        <div class="form-group">
            <label for="status_rekam" class="control-label col-md-4 col-sm-3 col-xs-12">Status Rekam</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="status_rekam" class="form-control" disabled id="status_rekam">
                    @foreach(status_rekam() as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->status_rekam == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="ktp_el" class="control-label col-md-4 col-sm-3 col-xs-12">e-KTP</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if ($penduduk->ktp_el == 1) active @endif" disabled>
                        <input
                            type="radio"
                            name="ktp_el"
                            id="ktp_el"
                            value="1"
                            disabled
                            autocomplete="off"
                            @if ($penduduk->ktp_el == 1) checked @endif
                        > Ya
                    </label>
                    <label class="btn btn-primary btn-sm @if ($penduduk->ktp_el == 2) active @endif" disabled>
                        <input
                            type="radio"
                            name="ktp_el"
                            id="ktp_el"
                            value="2"
                            disabled
                            autocomplete="off"
                            @if ($penduduk->ktp_el == 2) checked @endif
                        > Tidak
                    </label>
                </div>
            </div>
        </div>

        <legend>PENDIDIKAN DAN PEKERJAAN</legend>

        <div class="form-group">
            <label for="pendidikan_kk_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan dalam KK</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="pendidikan_kk_id" class="form-control" disabled id="pendidikan_kk_id">
                    @foreach(\App\Models\PendidikanKK::pluck('nama', 'id') as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->pendidikan_kk_id == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="pendidikan_sedang_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan sedang ditempuh</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="pendidikan_sedang_id" class="form-control" disabled id="pendidikan_sedang_id">
                    @foreach(\App\Models\Pendidikan::pluck('nama', 'id') as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->pendidikan_sedang_id == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="pekerjaan_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pekerjaan</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="pekerjaan_id" class="form-control" disabled id="pekerjaan_id">
                    @foreach(\App\Models\Pekerjaan::pluck('nama', 'id') as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->pekerjaan_id == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <legend>DATA KEWARGANEGARAAN</legend>

        <div class="form-group">
            <label for="warga_negara_id" class="control-label col-md-4 col-sm-3 col-xs-12">Warganegara</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="warga_negara_id" class="form-control" disabled id="warga_negara_id">
                    @foreach(\App\Models\Warganegara::pluck('nama', 'id') as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->warga_negara_id == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="dokumen_pasport" value="{{ $penduduk->dokumen_pasport ?? '' }}" class="form-control" disabled id="dokumen_pasport">
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_akhir_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Akhir Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="tanggal_akhir_pasport" value="{{ format_date($penduduk->tanggal_akhir_pasport) }}" class="form-control" disabled id="tanggal_akhir_pasport">
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_kitas" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor KITAS/KITAP</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="dokumen_kitas" value="{{ $penduduk->dokumen_kitas ?? '' }}" class="form-control" disabled id="dokumen_kitas">
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <legend>ORANG TUA</legend>

        <div class="form-group">
            <label for="ayah_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="ayah_nik" value="{{ $penduduk->ayah_nik ?? '' }}" class="form-control" disabled id="ayah_nik">
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ayah" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="nama_ayah" value="{{ $penduduk->nama_ayah ?? '' }}" class="form-control" disabled id="nama_ayah">
            </div>
        </div>

        <div class="form-group">
            <label for="ibu_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="ibu_nik" value="{{ $penduduk->ibu_nik ?? '' }}" class="form-control" disabled id="ibu_nik">
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ibu" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="nama_ibu" value="{{ $penduduk->nama_ibu ?? '' }}" class="form-control" disabled id="nama_ibu">
            </div>
        </div>

        <legend>ALAMAT</legend>

        <div class="form-group">
            <label for="telepon" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Telepon</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="telepon" value="{{ $penduduk->telepon ?? '' }}" class="form-control" disabled id="telepon">
            </div>
        </div>

        <div class="form-group">
            <label for="alamat_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="alamat_sebelumnya" value="{{ $penduduk->alamat_sebelumnya ?? '' }}" class="form-control" disabled id="alamat_sebelumnya">
            </div>
        </div>

        <div class="form-group">
            <label for="alamat" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sekarang</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="alamat" value="{{ $penduduk->alamat ?? '' }}" class="form-control" disabled id="alamat_s">
            </div>
        </div>

        <legend>STATUS KAWIN</legend>

        <div class="form-group">
            <label for="status_kawin" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kawin</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="status_kawin" class="form-control" disabled id="status_kawin">
                    @foreach(\App\Models\Kawin::pluck('nama', 'id') as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->status_kawin == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">No. Akta Nikah (Buku Nikah)</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="akta_perkawinan" value="{{ $penduduk->akta_perkawinan ?? '' }}" class="form-control" disabled id="akta_perkawinan">
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Nikah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="tanggal_perkawinan" value="{{ format_date($penduduk->tanggal_perkawinan) }}" class="form-control" disabled id="tanggal_perkawinan">
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="akta_perceraian" value="{{ $penduduk->akta_perceraian ?? '' }}" class="form-control" disabled id="akta_perceraian">
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="tanggal_perceraian" value="{{ format_date($penduduk->tanggal_perceraian) }}" class="form-control" disabled id="tanggal_perceraian">
            </div>
        </div>

        <legend>DATA KESEHATAN</legend>

        <div class="form-group">
            <label for="golongan_darah_id" class="control-label col-md-4 col-sm-3 col-xs-12">Golongan Darah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="golongan_darah_id" class="form-control" disabled id="golongan_darah_id">
                    @foreach(\App\Models\GolonganDarah::pluck('nama', 'id') as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->golongan_darah_id == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="cacat_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cacat</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="cacat_id" class="form-control" disabled id="cacat_id">
                    @foreach(\App\Models\Cacat::pluck('nama', 'id') as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->cacat_id == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="sakit_menahun_id" class="control-label col-md-4 col-sm-3 col-xs-12">Sakit Menahun</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="sakit_menahun_id" class="form-control" disabled id="sakit_menahun_id">
                    @foreach(\App\Models\SakitMenahun::pluck('nama', 'id') as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->sakit_menahun_id == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="cara_kb_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cara KB</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="cara_kb_id" class="form-control" disabled id="cara_kb_id">
                    @foreach(\App\Models\CaraKB::pluck('nama', 'id') as $id => $nama)
                        <option value="{{ $id }}" {{ $penduduk->cara_kb_id == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @includeWhen($penduduk->sex == 2 && $penduduk->status_kawin > 1, 'data.penduduk.form_show_status_kehamilan')
    </div>
</div>

<div class="ln_solid"></div>
