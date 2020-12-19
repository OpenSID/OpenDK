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
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Ganti Foto</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" id="foto" name="foto" class="validate form-control" accept="image/*" />
            </div>
        </div>

        <div class="form-group">
            <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Nama<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nama', null,['placeholder'=>'Nama', 'class'=>'form-control', 'required', 'id'=>'nama']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nik', null,['placeholder'=>'NIK', 'class'=>'form-control', 'required', 'id'=>'nik']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="no_kk_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">No KK Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('no_kk_sebelumnya', null,['placeholder'=>'No KK', 'class'=>'form-control', 'id'=>'no_kk_sebelumnya']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="kk_level" class="control-label col-md-4 col-sm-3 col-xs-12">Hubungan dalam Keluarga<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('kk_level', \App\Models\HubunganKeluarga::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'kk_level', 'required']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="jenis_kelamin" class="control-label col-md-4 col-sm-3 col-xs-12">Jenis Kelamin<span class="required">*</span></label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if($penduduk->sex == 1) active @endif">
                        <input type="radio" name="sex" id="jenis_kelamin" value="1" autocomplete="off" @if($penduduk->sex == 1) checked @endif> Laki-Laki
                    </label>
                    <label class="btn btn-primary btn-sm @if($penduduk->sex == 2) active @endif">
                        <input type="radio" name="sex" id="jenis_kelamin" value="2" autocomplete="off" @if($penduduk->sex == 2) checked @endif> Perempuan
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="agama_id" class="control-label col-md-4 col-sm-3 col-xs-12">Agama<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('agama_id', \App\Models\Agama::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'agama_id', 'required']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="status" class="control-label col-md-4 col-sm-3 col-xs-12">Status Penduduk<span class="required">*</span></label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if($penduduk->status == 1) active @endif">
                        <input type="radio" name="status" id="status" value="1" autocomplete="off" @if($penduduk->status == 1) checked @endif> Tetap
                    </label>
                    <label class="btn btn-primary btn-sm @if($penduduk->status == 2) active @endif">
                        <input type="radio" name="status" id="status" value="2" autocomplete="off" @if($penduduk->status == 2) checked @endif> Tidak Aktif
                    </label>
                    <label class="btn btn-primary btn-sm @if($penduduk->status == 3) active @endif">
                        <input type="radio" name="status" id="status" value="3" autocomplete="off" @if($penduduk->status == 3) checked @endif> Pendatang
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="akta_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Lahir<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('akta_lahir', null,['placeholder'=>'Akta Kelahiran', 'class'=>'form-control', 'required', 'id'=>'akta_lahir']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tempat_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tempat Lahir<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tempat_lahir', null,['placeholder'=>'Tempat Lahir', 'class'=>'form-control', 'required', 'id'=>'tempat_lahir']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Lahir<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tanggal_lahir', null,['placeholder'=>'0000-00-00', 'class'=>'form-control datepicker', 'required', 'id'=>'tanggal_lahir']) !!}
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
                {!! Form::text('wajib_ktp',$status_ktp,['placeholder'=>'Wajib KTP', 'class'=>'form-control', 'disabled', 'id'=>'wajib_ktp']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="status_rekam" class="control-label col-md-4 col-sm-3 col-xs-12">Status Rekam<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('status_rekam', status_rekam(), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'status_rekam', 'required']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="ktp_el" class="control-label col-md-4 col-sm-3 col-xs-12">e-KTP<span class="required">*</span></label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if($penduduk->ktp_el == 1) active @endif">
                        <input type="radio" name="ktp_el" id="ktp_el" value="1" autocomplete="off" @if($penduduk->ktp_el == 1) checked @endif> Ya
                    </label>
                    <label class="btn btn-primary btn-sm @if($penduduk->ktp_el == 2) active @endif">
                        <input type="radio" name="ktp_el" id="ktp_el" value="2" autocomplete="off" @if($penduduk->ktp_el == 2) checked @endif> Tidak
                    </label>
                </div>
            </div>
        </div>

        <legend>PENDIDIKAN DAN PEKERJAAN</legend>

        <div class="form-group">
            <label for="pendidikan_kk_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan dalam KK<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('pendidikan_kk_id', \App\Models\PendidikanKK::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'pendidikan_kk_id', 'required']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="pendidikan_sedang_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan sedang ditempuh<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('pendidikan_sedang_id', \App\Models\Pendidikan::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'pendidikan_sedang_id', 'required']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="pekerjaan_id" class="control-label col-md-4 col-sm-3 col-xs-12">Pekerjaan<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('pekerjaan_id', \App\Models\Pekerjaan::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'pekerjaan_id', 'required']) !!}
            </div>
        </div>

        <legend>DATA KEWARGANEGARAAN</legend>

        <div class="form-group">
            <label for="warga_negara_id" class="control-label col-md-4 col-sm-3 col-xs-12">Warganegara<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('warga_negara_id', \App\Models\Warganegara::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'warga_negara_id', 'required']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('dokumen_pasport', null,['placeholder'=>'Nomor Paspor', 'class'=>'form-control', 'id'=>'dokumen_pasport']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_akhir_pasport" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Akhir Paspor</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tanggal_akhir_pasport', null,['placeholder'=>'0000-00-00', 'class'=>'form-control datepicker','id'=>'tanggal_akhir_pasport']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="dokumen_kitas" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor KITAS/KITAP</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('dokumen_kitas', null,['placeholder'=>'Nomor KITAS/KITAP', 'class'=>'form-control', 'id'=>'dokumen_kitas']) !!}
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <legend>ORANG TUA</legend>

        <div class="form-group">
            <label for="ayah_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('ayah_nik', null,['placeholder'=>'NIK Ayah', 'class'=>'form-control', 'id'=>'ayah_nik']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ayah" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ayah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nama_ayah', null,['placeholder'=>'Nama Ayah', 'class'=>'form-control', 'id'=>'nama_ayah']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="ibu_nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('ibu_nik', null,['placeholder'=>'NIK Ibu', 'class'=>'form-control', 'id'=>'ibu_nik']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="nama_ibu" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Ibu</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nama_ibu', null,['placeholder'=>'Nama Ibu', 'class'=>'form-control', 'id'=>'nama_ibu']) !!}
            </div>
        </div>

        <legend>ALAMAT</legend>

        <div class="form-group">
            <label for="telepon" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Telepon</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('telepon', null,['placeholder'=>'021-5432109', 'class'=>'form-control', 'id'=>'telepon']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="alamat_sebelumnya" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sebelumnya</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('alamat_sebelumnya', null,['placeholder'=>'Alamat Sebelumnya', 'class'=>'form-control', 'id'=>'alamat_sebelumnya']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="alamat" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat Sekarang</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('alamat', null,['placeholder'=>'Alamat Sekarang', 'class'=>'form-control', 'id'=>'alamat_s']) !!}
            </div>
        </div>

        <legend>STATUS KAWIN</legend>

        <div class="form-group">
            <label for="status_kawin" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kawin<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('status_kawin', \App\Models\Kawin::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'status_kawin', 'required']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">No. Akta Nikah (Buku Nikah)</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('akta_perkawinan', null,['placeholder'=>'No. Akta Nikah (Buku Nikah)', 'class'=>'form-control', 'id'=>'akta_perkawinan']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perkawinan" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Nikah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tanggal_perkawinan', null,['placeholder'=>'0000-00-00', 'class'=>'form-control datepicker','id'=>'tanggal_perkawinan']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="akta_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Akta Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('akta_perceraian', null,['placeholder'=>'Alamat Sekarang', 'class'=>'form-control', 'id'=>'akta_perceraian']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_perceraian" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Perceraian</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tanggal_perceraian', null,['placeholder'=>'0000-00-00', 'class'=>'form-control datepicker','id'=>'tanggal_perceraian']) !!}
            </div>
        </div>

        <legend>DATA KESEHATAN</legend>

        <div class="form-group">
            <label for="golongan_darah_id" class="control-label col-md-4 col-sm-3 col-xs-12">Golongan Darah</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('golongan_darah_id', \App\Models\GolonganDarah::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'golongan_darah_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="cacat_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cacat</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('cacat_id', \App\Models\Cacat::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'cacat_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="sakit_menahun_id" class="control-label col-md-4 col-sm-3 col-xs-12">Sakit Menahun</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('sakit_menahun_id', \App\Models\SakitMenahun::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'sakit_menahun_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="cara_kb_id" class="control-label col-md-4 col-sm-3 col-xs-12">Cara KB</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('cara_kb_id', \App\Models\CaraKB::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'cara_kb_id']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="hamil" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kehamilan</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if($penduduk->hamil == 1) active @endif">
                        <input type="radio" name="hamil" id="hamil" value="1" autocomplete="off" @if($penduduk->hamil == 1) checked @endif> Tidak Hamil
                    </label>
                    <label class="btn btn-primary btn-sm @if($penduduk->hamil == 2) active @endif">
                        <input type="radio" name="hamil" id="hamil" value="2" autocomplete="off" @if($penduduk->hamil == 2) checked @endif> Hamil
                    </label>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="ln_solid"></div>