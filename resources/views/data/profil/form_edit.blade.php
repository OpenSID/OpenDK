<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Provinsi <span class="required">*</span></label>
            <div class="col-md-2 col-sm-3 col-xs-12">
                <input id="provinsi_id" class="form-control" placeholder="00" type="text" readonly value="{{ $profil->provinsi_id }}"/>
                <input id="nama_provinsi" type="hidden" name="nama_provinsi" value="{{ $profil->nama_provinsi }}"/>
            </div>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <select class="form-control" id="list_provinsi" name="provinsi_id" style="width: 100%;">
                <option selected value="" disabled>Pilih Provinsi</option>
                @if ($profil->provinsi_id || $profil->nama_provinsi)
                    <option selected value="{{ $profil->provinsi_id }}">{{ $profil->nama_provinsi }}</option>
                @endif
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kabupaten <span class="required">*</span></label>
            <div class="col-md-2 col-sm-3 col-xs-12">
                <input id="kabupaten_id" class="form-control" placeholder="00.00" type="text" readonly value="{{ $profil->kabupaten_id }}"/>
                <input id="nama_kabupaten" type="hidden" name="nama_kabupaten" value="{{ $profil->nama_kabupaten }}"/>
            </div>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <select class="form-control" id="list_kabupaten" name="kabupaten_id" style="width: 100%;">
                <option selected value="" disabled>Pilih Kabupaten</option>
                @if ($profil->kabupaten_id || $profil->nama_kabupaten)
                    <option selected value="{{ $profil->kabupaten_id }}">{{ $profil->nama_kabupaten }}</option>
                @endif
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kecamatan <span class="required">*</span></label>
            <div class="col-md-2 col-sm-3 col-xs-12">
                <input id="kecamatan_id" class="form-control" placeholder="00.00.00" type="text" readonly value="{{ $profil->kecamatan_id }}"/>
                <input id="nama_kecamatan" type="hidden" name="nama_kecamatan" value="{{ $profil->nama_kecamatan }}"/>
            </div>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <select class="form-control" id="list_kecamatan" name="kecamatan_id" data-placeholder="Pilih kecamatan" style="width: 100%;">
                <option selected value="" disabled>Pilih Kecamatan</option>
                @if ($profil->kecamatan_id || $profil->nama_kecamatan)
                    <option selected value="{{ $profil->kecamatan_id }}">{{ $profil->nama_kecamatan }}</option>
                @endif
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Tahun Pembentukan <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('tahun_pembentukan', null, ['placeholder' => 'Tahun Pembentukan', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Dasar Hukum Pembentukan <span class="required">*</span></label><div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('dasar_pembentukan', null, ['placeholder' => 'Dasar Hukum Pembentukan', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Alamat <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::textarea('alamat', null, ['placeholder' => 'Alamat', 'class' => 'form-control', 'rows' => 3, 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kode Pos <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('kode_pos', null, ['placeholder' => '13210', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Telepon <span class="required">*</span></label>

            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('telepon', null, ['placeholder' => '021-4567890', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Email <span class="required">*</span></label>

            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('email', null, ['placeholder' => '021-4567890', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">File Struktur Organisasi</label>

            <div class="col-md-7 col-sm-6 col-xs-12">
                <input type="file" id="file_struktur" name="file_struktur_organisasi" accept="image/*" class="validate form-control"/>
                    <br>
                    <img src="{{ asset($profil->file_struktur_organisasi) }}" id="showgambar" style="max-width:200px;max-height:200px;float:left;"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Sambutan {{ $sebutan_kepala_wilayah }}</label>
                <div class="col-md-8 col-sm-6 col-xs-12">
                    {!! Form::textarea('sambutan', null, ['class' => 'textarea', 'placeholder' => 'Sambutan '.$sebutan_kepala_wilayah. ' ' .$profil->nama_kecamatan .'', 'style' => 'width: 100%;
                    height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Foto {{ $sebutan_kepala_wilayah }}</label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                <input type="file" id="foto_kepala_wilayah" name="foto_kepala_wilayah" accept="image/*" class="validate form-control"/>
                <br>
                <img src="{{ asset($profil->foto_kepala_wilayah) }}" id="showgambar2" style="max-width:200px;max-height:200px;float:left;"/>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Nama {{ $sebutan_kepala_wilayah }} <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('nama_camat', null, ['placeholder' => 'Nama Camat', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Sekretaris <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('sekretaris_camat', null, ['placeholder' => 'Sekretaris Camat', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Seksi Pemerintahan Umum <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('kepsek_pemerintahan_umum', null, ['placeholder' => 'Kepala Seksi Pemerintahan Umum', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Seksi Kesejahteraan Masyarakat <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('kepsek_kesejahteraan_masyarakat', null, ['placeholder' => 'Kepala Seksi Kesejahteraan Masyarakat', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Seksi Pemberdayaan Masyarakat <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('kepsek_pemberdayaan_masyarakat', null, ['placeholder' => 'Kepala Seksi Pemberdayaan Masyarakat', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Seksi Pelayanan Umum <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('kepsek_pelayanan_umum', null, ['placeholder' => 'Kepala Seksi Pelayanan Umum', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Seksi TRANTIB <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::text('kepsek_trantib', null, ['placeholder' => 'Kepala Seksi TRANTIB', 'class' => 'form-control', 'required']) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">File Logo</label>

            <div class="col-md-7 col-sm-6 col-xs-12">
                <input type="file" id="file_logo" name="file_logo" accept="image/*" class="validate form-control"/>
                <br>
                <img src="{{ asset($profil->file_logo) }}" id="showgambar3" style="max-width:200px;max-height:200px;float:left;"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Media Sosial</label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                @if(is_null($profil->socialmedia))
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-facebook"></i></span>
                    <input type="text" name="socialmedia[0][link]"  class="form-control" placeholder="facebook">
                    <input type="hidden" name="socialmedia[0][icon]"  value="fa fa-facebook" class="form-control" placeholder="facebook">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-twitter"></i></span>
                    <input type="text" name="socialmedia[1][link]"  class="form-control" placeholder="twitter">
                    <input type="hidden" name="socialmedia[1][icon]"  value="fa fa-twitter" class="form-control" placeholder="twitter">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-instagram"></i></span>
                    <input type="text" name="socialmedia[2][link]"  class="form-control" placeholder="instagram">
                    <input type="hidden" name="socialmedia[2][icon]"  value="fa fa-instagram" class="form-control" placeholder="instagram">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-youtube"></i></span>
                    <input type="text" name="socialmedia[3][link]"  class="form-control" placeholder="youtube">
                    <input type="hidden" name="socialmedia[3][icon]"  value="fa fa-youtube" class="form-control" placeholder="youtube">
                </div>
                <br>
                @else
                    @foreach (json_decode($profil->socialmedia, true) as $sosmed)
                    <div class="input-group">
                        <span class="input-group-addon"><i class="{{ $sosmed['icon'] }}"></i></span>
                        <input type="text" name="socialmedia[{{ $loop->iteration - 1 }}][link]" value="{{ $sosmed['link'] }}" class="form-control" placeholder="">
                        <input type="hidden" name="socialmedia[{{ $loop->iteration - 1 }}][icon]" value="{{ $sosmed['icon'] }}" class="form-control" placeholder="">
                    </div>
                    <br>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <legend>Visi & Misi</legend>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Visi</label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::textarea('visi', null, ['class' => 'textarea', 'placeholder' => 'Visi Kecamatan', 'style' => 'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Misi</label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! Form::textarea('misi', null, ['class' => 'textarea', 'placeholder' => 'Misi Kecamatan', 'style' => 'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
            </div>
        </div>
    </div>
</div>
<div class="ln_solid"></div>
