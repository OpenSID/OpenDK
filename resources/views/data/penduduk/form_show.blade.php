<div class="row">
    <div class="col-md-6">
        <legend>DATA DIRI</legend>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Foto</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <img src="@if(! $penduduk->foto == '') {{ asset('storage/penduduk/foto/'.$penduduk->foto) }} @else {{ "http://placehold.it/120x150" }} @endif" id="showgambar"
                     style="max-width:120px;max-height:150px;float:left;"/>
            </div>
        </div>

        <div class="form-group">
            <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Nama</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nama', null,['class'=>'form-control', 'disabled'=>true, 'id'=>'nama']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nik', null, ['class'=>'form-control', 'disabled'=>true, 'id'=>'nik']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="no_kk_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">No KK Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('no_kk_sebelumnya', null,['class'=>'form-control', 'disabled'=>true, 'id'=>'no_kk_sebelumnya']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="kk_level" class="control-label col-md-4 col-sm-3 col-xs-12">Hubungan dalam Keluarga</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('kk_level', \App\Models\HubunganKeluarga::pluck('nama', 'id'), null,['class'=>'form-control', 'disabled'=>true, 'id'=>'kk_level']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="jenis_kelamin" class="control-label col-md-4 col-sm-3 col-xs-12">Jenis Kelamin</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if($penduduk->sex == 1) active @endif" disabled>
                        <input type="radio" name="sex" id="jenis_kelamin" disabled value="1" autocomplete="off" @if($penduduk->sex == 1) checked @endif> Laki-Laki
                    </label>
                    <label class="btn btn-primary btn-sm @if($penduduk->sex == 2) active @endif disabled">
                        <input type="radio" name="sex" id="jenis_kelamin" disabled value="2" autocomplete="off" @if($penduduk->sex == 2) checked @endif> Perempuan
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="agama_id" class="control-label col-md-4 col-sm-3 col-xs-12">Agama</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('agama_id', \App\Models\Agama::pluck('nama', 'id'), null, ['class'=>'form-control', 'disabled'=>true, 'id'=>'agama_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="status" class="control-label col-md-4 col-sm-3 col-xs-12">Status Penduduk</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if($penduduk->status == 1) active @endif" disabled>
                        <input type="radio" name="status" id="status" value="1" disabled autocomplete="off" @if($penduduk->status == 1) checked @endif> Tetap
                    </label>
                    <label class="btn btn-primary btn-sm @if($penduduk->status == 2) active @endif" disabled>
                        <input type="radio" name="status" id="status" value="2" disabled autocomplete="off" @if($penduduk->status == 2) checked @endif> Tidak Aktif
                    </label>
                    <label class="btn btn-primary btn-sm @if($penduduk->status == 3) active @endif" disabled>
                        <input type="radio" name="status" id="status" value="3" disabled autocomplete="off" @if($penduduk->status == 3) checked @endif> Pendatang
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="akta_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('akta_lahir', null,['class'=>'form-control', 'disabled'=>true, 'id'=>'akta_lahir']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tempat Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tempat_lahir', null, ['class'=>'form-control', 'disabled'=>true, 'id'=>'tempat_lahir']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Lahir</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tanggal_lahir', null, ['class'=>'form-control', 'disabled'=>true, 'id'=>'tanggal_lahir']) !!}
            </div>
        </div>

        <?php
            $wajib_ktp = is_wajib_ktp(convert_born_date_to_age($penduduk->tanggal_lahir), $penduduk->status_kawin);
            if($wajib_ktp){
                $status_ktp = 'Ya';
            }else{
                $status_ktp = 'Tidak';
            }

            ?>
        <div class="form-group">
            <label for="wajib_ktp" class="control-label col-md-4 col-sm-3 col-xs-12">Wajib KTP</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('wajib_ktp',$status_ktp,['class'=>'form-control', 'disabled', 'id'=>'wajib_ktp']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="status_rekam" class="control-label col-md-4 col-sm-3 col-xs-12">Status Rekam</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('status_rekam', status_rekam(), null,['class'=>'form-control', 'disabled', 'id'=>'status_rekam']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="ktp_el" class="control-label col-md-4 col-sm-3 col-xs-12">e-KTP</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if($penduduk->ktp_el == 1) active @endif" disabled>
                        <input type="radio" name="ktp_el" id="ktp_el" value="1" disabled autocomplete="off" @if($penduduk->ktp_el == 1) checked @endif> Ya
                    </label>
                    <label class="btn btn-primary btn-sm @if($penduduk->ktp_el == 2) active @endif" disabled>
                        <input type="radio" name="ktp_el" id="ktp_el" value="2" disabled autocomplete="off" @if($penduduk->ktp_el == 2) checked @endif> Tidak
                    </label>
                </div>
            </div>
        </div>

        <legend>PENDIDIKAN DAN PEKERJAAN</legend>

        <div class="form-group">
            <label for="pendidikan_kk_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan dalam KK</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('pendidikan_kk_id', \App\Models\PendidikanKK::pluck('nama', 'id'), null, ['class'=>'form-control', 'disabled', 'id'=>'pendidikan_kk_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="pendidikan_sedang_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan sedang ditempuh</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('pendidikan_sedang_id', \App\Models\Pendidikan::pluck('nama', 'id'), null, ['class'=>'form-control', 'disabled', 'id'=>'pendidikan_sedang_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="pekerjaan_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pekerjaan</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('pekerjaan_id', \App\Models\Pekerjaan::pluck('nama', 'id'), null, ['class'=>'form-control', 'disabled', 'id'=>'pekerjaan_id']) !!}
            </div>
        </div>

        <legend>DATA KEWARGANEGARAAN</legend>

        <div class="form-group">
            <label for="warga_negara_id" class="control-label col-md-4 col-sm-3 col-xs-12">Warganegara</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('warga_negara_id', \App\Models\Warganegara::pluck('nama', 'id'), null, ['class'=>'form-control', 'disabled', 'id'=>'warga_negara_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('dokumen_pasport', null, ['class'=>'form-control', 'disabled', 'id'=>'dokumen_pasport']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_akhir_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Akhir Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tanggal_akhir_pasport', null, ['class'=>'form-control', 'disabled', 'id'=>'tanggal_akhir_pasport']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_kitas" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor KITAS/KITAP</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('dokumen_kitas', null, ['class'=>'form-control', 'disabled', 'id'=>'dokumen_kitas']) !!}
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <legend>ORANG TUA</legend>

        <div class="form-group">
            <label for="ayah_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('ayah_nik', null, ['class'=>'form-control', 'disabled', 'id'=>'ayah_nik']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ayah" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nama_ayah', null, ['class'=>'form-control', 'disabled', 'id'=>'nama_ayah']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="ibu_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('ibu_nik', null, ['class'=>'form-control', 'disabled', 'id'=>'ibu_nik']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ibu" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nama_ibu', null, ['class'=>'form-control', 'disabled', 'id'=>'nama_ibu']) !!}
            </div>
        </div>

        <legend>ALAMAT</legend>

        <div class="form-group">
            <label for="telepon" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Telepon</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('telepon', null, ['class'=>'form-control', 'disabled', 'id'=>'telepon']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="alamat_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('alamat_sebelumnya', null, ['class'=>'form-control', 'disabled', 'id'=>'alamat_sebelumnya']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="alamat" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sekarang</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('alamat', null, ['class'=>'form-control', 'disabled', 'id'=>'alamat_s']) !!}
            </div>
        </div>

        <legend>STATUS KAWIN</legend>

        <div class="form-group">
            <label for="status_kawin" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kawin</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('status_kawin', \App\Models\Kawin::pluck('nama', 'id'), null,['class'=>'form-control', 'disabled', 'id'=>'status_kawin']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">No. Akta Nikah (Buku Nikah)</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('akta_perkawinan', null, ['class'=>'form-control', 'id'=>'akta_perkawinan']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Nikah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tanggal_perkawinan', null, ['class'=>'form-control', 'disabled', 'id'=>'tanggal_perkawinan']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('akta_perceraian', null, ['class'=>'form-control', 'disabled', 'id'=>'akta_perceraian']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tanggal_perceraian', null, ['class'=>'form-control', 'disabled', 'id'=>'tanggal_perceraian']) !!}
            </div>
        </div>

        <legend>DATA KESEHATAN</legend>

        <div class="form-group">
            <label for="golongan_darah_id" class="control-label col-md-4 col-sm-3 col-xs-12">Golongan Darah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('golongan_darah_id', \App\Models\GolonganDarah::pluck('nama', 'id'), null, ['class'=>'form-control', 'disabled', 'id'=>'golongan_darah_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="cacat_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cacat</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('cacat_id', \App\Models\Cacat::pluck('nama', 'id'), null, ['class'=>'form-control', 'disabled', 'id'=>'cacat_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="sakit_menahun_id" class="control-label col-md-4 col-sm-3 col-xs-12">Sakit Menahun</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('sakit_menahun_id', \App\Models\SakitMenahun::pluck('nama', 'id'), null, ['class'=>'form-control', 'disabled', 'id'=>'sakit_menahun_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="cara_kb_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cara KB</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('cara_kb_id', \App\Models\CaraKB::pluck('nama', 'id'), null,['class'=>'form-control', 'disabled', 'id'=>'cara_kb_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="hamil" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kehamilan</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if($penduduk->hamil == 1) active @endif" disabled>
                        <input type="radio" name="hamil" id="hamil" value="1" disabled autocomplete="off" @if($penduduk->hamil == 1) checked @endif> Tidak Hamil
                    </label>
                    <label class="btn btn-primary btn-sm @if($penduduk->hamil == 2) active @endif" disabled>
                        <input type="radio" name="hamil" id="hamil" value="2" disabled autocomplete="off" @if($penduduk->hamil == 2) checked @endif> Hamil
                    </label>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="ln_solid"></div>