<div class="row">
    <div class="col-md-6">
        <legend>DATA DIRI</legend>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Foto</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <img src="{{ is_user($penduduk->foto, $penduduk->sex) }}" id="showgambar"
                    style="max-width:120px;max-height:150px;float:left;" />
            </div>
        </div>

        <div class="form-group">
            <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Nama</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('nama')->value(old('nama', isset($penduduk)->value(old('nama', isset($penduduk) ? $penduduk->nama : '')) ? $penduduk->nama : ''))->class('form-control')->id('nama') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('nik')->value(old('nik', isset($penduduk)->value(old('nik', isset($penduduk) ? $penduduk->nik : '')) ? $penduduk->nik : ''))->class('form-control')->id('nik') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="no_kk_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">No KK Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('no_kk_sebelumnya')->value(old('no_kk_sebelumnya', isset($penduduk)->value(old('no_kk_sebelumnya', isset($penduduk) ? $penduduk->no_kk_sebelumnya : '')) ? $penduduk->no_kk_sebelumnya : ''))->class('form-control')->id('no_kk_sebelumnya') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="kk_level" class="control-label col-md-4 col-sm-3 col-xs-12">Hubungan dalam Keluarga</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('kk_level', \App\Models\HubunganKeluarga::pluck('nama', 'id')->value(old('kk_level', isset($penduduk)->value(old('kk_level', isset($penduduk) ? $penduduk->kk_level : '')) ? $penduduk->kk_level : '')), null, ['class' =>
                'form-control', 'disabled' => true, 'id' => 'kk_level']) !!}
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
                        <input type="radio" name="sex" id="jenis_kelamin" disabled value="1" autocomplete="off" @if
                            ($penduduk->sex == 1) checked @endif
                        > Laki-Laki
                    </label>
                    <label class="btn btn-primary btn-sm @if ($penduduk->sex == 2) active @endif disabled">
                        <input type="radio" name="sex" id="jenis_kelamin" disabled value="2" autocomplete="off" @if
                            ($penduduk->sex == 2) checked @endif
                        > Perempuan
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="agama_id" class="control-label col-md-4 col-sm-3 col-xs-12">Agama</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('agama_id', \App\Models\Agama::pluck('nama', 'id')->value(old('agama_id', isset($penduduk)->value(old('agama_id', isset($penduduk) ? $penduduk->agama_id : '')) ? $penduduk->agama_id : '')), null, ['class' => 'form-control',
                'disabled' => true, 'id' => 'agama_id']) !!}
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
                        <input type="radio" name="status" id="status" value="1" disabled autocomplete="off" @if
                            ($penduduk->status == 1) checked @endif
                        > Tetap
                    </label>
                    <label class="btn btn-primary btn-sm @if ($penduduk->status == 2) active @endif" disabled>
                        <input type="radio" name="status" id="status" value="2" disabled autocomplete="off" @if
                            ($penduduk->status == 2) checked @endif
                        > Tidak Aktif
                    </label>
                    <label class="btn btn-primary btn-sm @if ($penduduk->status == 3) active @endif" disabled>
                        <input type="radio" name="status" id="status" value="3" disabled autocomplete="off" @if
                            ($penduduk->status == 3) checked @endif
                        > Pendatang
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="akta_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('akta_lahir')->value(old('akta_lahir', isset($penduduk)->value(old('akta_lahir', isset($penduduk) ? $penduduk->akta_lahir : '')) ? $penduduk->akta_lahir : ''))->class('form-control')->id('akta_lahir') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tempat Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('tempat_lahir')->value(old('tempat_lahir', isset($penduduk)->value(old('tempat_lahir', isset($penduduk) ? $penduduk->tempat_lahir : '')) ? $penduduk->tempat_lahir : ''))->class('form-control')->id('tempat_lahir') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('tanggal_lahir', format_date($penduduk->tanggal_lahir), ['class' => 'form-control',
                'disabled' => true, 'id' => 'tanggal_lahir']) !!}
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
                {!! Html::text('wajib_ktp')->value(old('wajib_ktp', isset($penduduk)->value(old('wajib_ktp', isset($penduduk) ? $penduduk->wajib_ktp : '')) ? $penduduk->wajib_ktp : ''))->class('form-control')->id('wajib_ktp')
                !!}
            </div>
        </div>

        <div class="form-group">
            <label for="status_rekam" class="control-label col-md-4 col-sm-3 col-xs-12">Status Rekam</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('status_rekam', status_rekam()->value(old('status_rekam', isset($penduduk)->value(old('status_rekam', isset($penduduk) ? $penduduk->status_rekam : '')) ? $penduduk->status_rekam : '')), null, ['class' => 'form-control', 'disabled', 'id' =>
                'status_rekam']) !!}
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
                        <input type="radio" name="ktp_el" id="ktp_el" value="1" disabled autocomplete="off" @if
                            ($penduduk->ktp_el == 1) checked @endif
                        > Ya
                    </label>
                    <label class="btn btn-primary btn-sm @if ($penduduk->ktp_el == 2) active @endif" disabled>
                        <input type="radio" name="ktp_el" id="ktp_el" value="2" disabled autocomplete="off" @if
                            ($penduduk->ktp_el == 2) checked @endif
                        > Tidak
                    </label>
                </div>
            </div>
        </div>

        <legend>PENDIDIKAN DAN PEKERJAAN</legend>

        <div class="form-group">
            <label for="pendidikan_kk_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan dalam KK</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('pendidikan_kk_id', \App\Models\PendidikanKK::pluck('nama', 'id')->value(old('pendidikan_kk_id', isset($penduduk)->value(old('pendidikan_kk_id', isset($penduduk) ? $penduduk->pendidikan_kk_id : '')) ? $penduduk->pendidikan_kk_id : '')), null, ['class' =>
                'form-control', 'disabled', 'id' => 'pendidikan_kk_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="pendidikan_sedang_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan sedang
                ditempuh</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('pendidikan_sedang_id', \App\Models\Pendidikan::pluck('nama', 'id')->value(old('pendidikan_sedang_id', isset($penduduk)->value(old('pendidikan_sedang_id', isset($penduduk) ? $penduduk->pendidikan_sedang_id : '')) ? $penduduk->pendidikan_sedang_id : '')), null, ['class' =>
                'form-control', 'disabled', 'id' => 'pendidikan_sedang_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="pekerjaan_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pekerjaan</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('pekerjaan_id', \App\Models\Pekerjaan::pluck('nama', 'id')->value(old('pekerjaan_id', isset($penduduk)->value(old('pekerjaan_id', isset($penduduk) ? $penduduk->pekerjaan_id : '')) ? $penduduk->pekerjaan_id : '')), null, ['class' =>
                'form-control', 'disabled', 'id' => 'pekerjaan_id']) !!}
            </div>
        </div>

        <legend>DATA KEWARGANEGARAAN</legend>

        <div class="form-group">
            <label for="warga_negara_id" class="control-label col-md-4 col-sm-3 col-xs-12">Warganegara</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('warga_negara_id', \App\Models\Warganegara::pluck('nama', 'id')->value(old('warga_negara_id', isset($penduduk)->value(old('warga_negara_id', isset($penduduk) ? $penduduk->warga_negara_id : '')) ? $penduduk->warga_negara_id : '')), null, ['class' =>
                'form-control', 'disabled', 'id' => 'warga_negara_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('dokumen_pasport')->value(old('dokumen_pasport', isset($penduduk)->value(old('dokumen_pasport', isset($penduduk) ? $penduduk->dokumen_pasport : '')) ? $penduduk->dokumen_pasport : ''))->class('form-control')->id('dokumen_pasport') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_akhir_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Akhir
                Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('tanggal_akhir_pasport', format_date($penduduk->tanggal_akhir_pasport), ['class' =>
                'form-control', 'disabled', 'id' => 'tanggal_akhir_pasport']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_kitas" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor KITAS/KITAP</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('dokumen_kitas')->value(old('dokumen_kitas', isset($penduduk)->value(old('dokumen_kitas', isset($penduduk) ? $penduduk->dokumen_kitas : '')) ? $penduduk->dokumen_kitas : ''))->class('form-control')->id('dokumen_kitas')
                !!}
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <legend>ORANG TUA</legend>

        <div class="form-group">
            <label for="ayah_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('ayah_nik')->value(old('ayah_nik', isset($penduduk)->value(old('ayah_nik', isset($penduduk) ? $penduduk->ayah_nik : '')) ? $penduduk->ayah_nik : ''))->class('form-control')->id('ayah_nik') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ayah" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('nama_ayah')->value(old('nama_ayah', isset($penduduk)->value(old('nama_ayah', isset($penduduk) ? $penduduk->nama_ayah : '')) ? $penduduk->nama_ayah : ''))->class('form-control')->id('nama_ayah') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="ibu_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('ibu_nik')->value(old('ibu_nik', isset($penduduk)->value(old('ibu_nik', isset($penduduk) ? $penduduk->ibu_nik : '')) ? $penduduk->ibu_nik : ''))->class('form-control')->id('ibu_nik') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ibu" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('nama_ibu')->value(old('nama_ibu', isset($penduduk)->value(old('nama_ibu', isset($penduduk) ? $penduduk->nama_ibu : '')) ? $penduduk->nama_ibu : ''))->class('form-control')->id('nama_ibu') !!}
            </div>
        </div>

        <legend>ALAMAT</legend>

        <div class="form-group">
            <label for="telepon" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Telepon</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('telepon')->value(old('telepon', isset($penduduk)->value(old('telepon', isset($penduduk) ? $penduduk->telepon : '')) ? $penduduk->telepon : ''))->class('form-control')->id('telepon') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="alamat_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('alamat_sebelumnya')->value(old('alamat_sebelumnya', isset($penduduk)->value(old('alamat_sebelumnya', isset($penduduk) ? $penduduk->alamat_sebelumnya : '')) ? $penduduk->alamat_sebelumnya : ''))->class('form-control')->id('alamat_sebelumnya') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="alamat" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sekarang</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('alamat')->value(old('alamat', isset($penduduk)->value(old('alamat', isset($penduduk) ? $penduduk->alamat : '')) ? $penduduk->alamat : ''))->class('form-control')->id('alamat_s') !!}
            </div>
        </div>

        <legend>STATUS KAWIN</legend>

        <div class="form-group">
            <label for="status_kawin" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kawin</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('status_kawin', \App\Models\Kawin::pluck('nama', 'id')->value(old('status_kawin', isset($penduduk)->value(old('status_kawin', isset($penduduk) ? $penduduk->status_kawin : '')) ? $penduduk->status_kawin : '')), null, ['class' =>
                'form-control', 'disabled', 'id' => 'status_kawin']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">No. Akta Nikah (Buku
                Nikah)</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('akta_perkawinan')->value(old('akta_perkawinan', isset($penduduk)->value(old('akta_perkawinan', isset($penduduk) ? $penduduk->akta_perkawinan : '')) ? $penduduk->akta_perkawinan : ''))->class('form-control')->id('akta_perkawinan') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Nikah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('tanggal_perkawinan', format_date($penduduk->tanggal_perkawinan), ['class' =>
                'form-control', 'disabled', 'id' => 'tanggal_perkawinan']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('akta_perceraian')->value(old('akta_perceraian', isset($penduduk)->value(old('akta_perceraian', isset($penduduk) ? $penduduk->akta_perceraian : '')) ? $penduduk->akta_perceraian : ''))->class('form-control')->id('akta_perceraian') !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('tanggal_perceraian', format_date($penduduk->tanggal_perceraian), ['class' =>
                'form-control', 'disabled', 'id' => 'tanggal_perceraian']) !!}
            </div>
        </div>

        <legend>DATA KESEHATAN</legend>

        <div class="form-group">
            <label for="golongan_darah_id" class="control-label col-md-4 col-sm-3 col-xs-12">Golongan Darah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('golongan_darah_id', \App\Models\GolonganDarah::pluck('nama', 'id')->value(old('golongan_darah_id', isset($penduduk)->value(old('golongan_darah_id', isset($penduduk) ? $penduduk->golongan_darah_id : '')) ? $penduduk->golongan_darah_id : '')), null, ['class' =>
                'form-control', 'disabled', 'id' => 'golongan_darah_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="cacat_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cacat</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('cacat_id', \App\Models\Cacat::pluck('nama', 'id')->value(old('cacat_id', isset($penduduk)->value(old('cacat_id', isset($penduduk) ? $penduduk->cacat_id : '')) ? $penduduk->cacat_id : '')), null, ['class' => 'form-control',
                'disabled', 'id' => 'cacat_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="sakit_menahun_id" class="control-label col-md-4 col-sm-3 col-xs-12">Sakit Menahun</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('sakit_menahun_id', \App\Models\SakitMenahun::pluck('nama', 'id')->value(old('sakit_menahun_id', isset($penduduk)->value(old('sakit_menahun_id', isset($penduduk) ? $penduduk->sakit_menahun_id : '')) ? $penduduk->sakit_menahun_id : '')), null, ['class' =>
                'form-control', 'disabled', 'id' => 'sakit_menahun_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="cara_kb_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cara KB</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::select('cara_kb_id', \App\Models\CaraKB::pluck('nama', 'id')->value(old('cara_kb_id', isset($penduduk)->value(old('cara_kb_id', isset($penduduk) ? $penduduk->cara_kb_id : '')) ? $penduduk->cara_kb_id : '')), null, ['class' =>
                'form-control', 'disabled', 'id' => 'cara_kb_id']) !!}
            </div>
        </div>
        @includeWhen($penduduk->sex == 2 && $penduduk->status_kawin > 1, 'data.penduduk.form_show_status_kehamilan')
    </div>
</div>

<div class="ln_solid"></div>