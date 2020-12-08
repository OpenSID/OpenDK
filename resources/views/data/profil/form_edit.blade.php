<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kecamatan <span
                        class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nama_kecamatan', $profil->kecamatan->nama,['placeholder'=>'Nama Kecamatan', 'class'=>'form-control', 'readonly'=>true]) !!}
                {!! Form::hidden('kecamatan_id', $profil->kecamatan_id,['placeholder'=>'Nama Kecamatan', 'class'=>'form-control', 'readonly'=>true]) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Tahun Pembentukan <span
                        class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tahun_pembentukan', null,['placeholder'=>'Tahun Pembentukan', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Dasar Hukum Pembentukan <span
                        class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('dasar_pembentukan', null,['placeholder'=>'Dasar Hukum Pembentukan', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Alamat <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::textarea('alamat', null,['placeholder'=>'Alamat', 'class'=>'form-control', 'rows' => 3, 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kode Pos <span
                        class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('kode_pos', null,['placeholder'=>'13210', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Telepon <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('telepon', null,['placeholder'=>'021-4567890', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Email <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('email', null,['placeholder'=>'021-4567890', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">File Struktur Organisasi</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" id="file_struktur" name="file_struktur_organisasi" accept="image/*"
                       class="validate form-control"/>
                <br>
                <img src="{{ asset($profil->file_struktur_organisasi) }}" id="showgambar"
                     style="max-width:200px;max-height:200px;float:left;"/>
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Nama Camat <span
                        class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nama_camat', null,['placeholder'=>'Nama Camat', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Sekretaris Camat <span
                        class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('sekretaris_camat', null,['placeholder'=>'Sekretaris Camat', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Seksi Pemerintahan Umum <span
                        class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('kepsek_pemerintahan_umum', null,['placeholder'=>'Kepala Seksi Pemerintahan Umum', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Seksi Kesejahteraan Masyarakat <span
                        class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('kepsek_kesejahteraan_masyarakat', null,['placeholder'=>'Kepala Seksi Kesejahteraan Masyarakat', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Seksi Pemberdayaan Masyarakat <span
                        class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('kepsek_pemberdayaan_masyarakat', null,['placeholder'=>'Kepala Seksi Pemberdayaan Masyarakat', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Seksi Pelayanan Umum <span
                        class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('kepsek_pelayanan_umum', null,['placeholder'=>'Kepala Seksi Pelayanan Umum', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Seksi TRANTIB <span
                        class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('kepsek_trantib', null,['placeholder'=>'Kepala Seksi TRANTIB', 'class'=>'form-control', 'required']) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">File Logo</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" id="file_logo" name="file_logo" accept="image/*"
                       class="validate form-control"/>
                <br>
                <img src="{{ asset($profil->file_logo) }}" id="showgambar2"
                     style="max-width:200px;max-height:200px;float:left;"/>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <legend>Visi & Misi</legend>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Visi</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::textarea('visi', null,['class'=>'textarea', 'placeholder'=>'Visi Kecamatan', 'style'=>'width: 100%;
                 height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Misi</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::textarea('misi', null,['class'=>'textarea', 'placeholder'=>'Misi Kecamatan', 'style'=>'width: 100%;
                 height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
            </div>
        </div>
    </div>
</div>

<div class="ln_solid"></div>
