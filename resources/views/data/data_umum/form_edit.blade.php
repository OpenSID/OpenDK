<div class="row">
  <div class="col-md-6">
    <legend>Info Wilayah</legend>
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">{{ $sebutan_wilayah }} <span
          class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('kecamatan', $data_umum->profil->nama_kecamatan, ['class' => 'form-control', 'disabled'=>true])
        !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Tipologi <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('tipologi', null, ['placeholder' => 'Tipologi', 'class' => 'form-control', 'required',
        'rows'=>2]) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Ketinggian (MDPL) <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('ketinggian', null, ['placeholder' => '0', 'class' => 'form-control', 'required', 'style' =>
        'text-align:right;']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Luas Wilayah (km<sup>2</sup>)<span
          class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
          <div class="col-md-5">
            {{ Form::select('sumber_luas_wilayah', ['1' => 'Manual',2=>'Dari Luas Desa'],isset($data_umum->sumber_luas_wilayah)? $data_umum->sumber_luas_wilayah:null, ['class' => 'form-control sumber_luas_wilayah']) }}
          </div>
          <div class="col-md-7">
            {!! Form::number('luas_wilayah', $luas_wilayah , ['placeholder' => '0', 'class' => 'form-control
            luas_wilayah', 'required', 'style' => 'text-align:right;', 'placeholder' => 'Luas Wilayah Kecamatan','step'
            => '0.01']) !!}
          </div>
        </div>
      </div>
    </div>

    <br>
    <legend>Batas Wilayah</legend>
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Utara <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('bts_wil_utara', null, ['placeholder' => 'Batas Utara', 'class' => 'form-control',
        'required', 'rows' => 2]) !!}
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Timur <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('bts_wil_timur', null, ['placeholder' => 'Batas Timur', 'class' => 'form-control',
        'required', 'rows' => 2]) !!}
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Selatan <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('bts_wil_selatan', null, ['placeholder' => 'Batas Selatan', 'class' => 'form-control',
        'required', 'rows' => 2]) !!}
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Barat <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('bts_wil_barat', null, ['placeholder' => 'Batas Barat', 'class' => 'form-control',
        'required', 'rows' => 2]) !!}
      </div>
    </div>
     
  </div>
  <div class="col-md-6">

    <legend>Jumlah Sarana Kesehatan</legend>
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Puskesmas <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_puskesmas', null, ['placeholder' => '0', 'class' => 'form-control', 'required', 'style' =>
        'text-align:right;']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Puskesmas Pembantu <span
          class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_puskesmas_pembantu', null, ['placeholder' => '0', 'class' => 'form-control', 'required',
        'style' => 'text-align:right;']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Posyandu <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_posyandu', null, ['placeholder' => '0', 'class' => 'form-control', 'required', 'style' =>
        'text-align:right;']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Pondok Bersalin <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_pondok_bersalin', null, ['placeholder' => '0', 'class' => 'form-control', 'required',
        'style' => 'text-align:right;']) !!}
      </div>
    </div>

    <br>
    <legend>Jumlah Sarana Pendidikan</legend>
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">PAUD/Sederajat <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_paud', null, ['placeholder' => '0', 'class' => 'form-control', 'required', 'style' =>
        'text-align:right;']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">SD/Sederajat <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_sd', null, ['placeholder' => '0', 'class' => 'form-control', 'required', 'style' =>
        'text-align:right;']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">SMP/Sederajat <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_smp', null, ['placeholder' => '0', 'class' => 'form-control', 'required', 'style' =>
        'text-align:right;']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">SMA/Sederajat <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_sma', null, ['placeholder' => '0', 'class' => 'form-control', 'required', 'style' =>
        'text-align:right;']) !!}
      </div>
    </div>

    <br>
    <legend>Jumlah Sarana Umum</legend>
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Masjid Besar <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_masjid_besar', null, ['placeholder' => '0', 'class' => 'form-control', 'required', 'style'
        => 'text-align:right;']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Mushola <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_mushola', null, ['placeholder' => '0', 'class' => 'form-control', 'required', 'style' =>
        'text-align:right;']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Gereja <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_gereja', null, ['placeholder' => '0', 'class' => 'form-control', 'required', 'style' =>
        'text-align:right;']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Pasar <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_pasar', null, ['placeholder' => '0', 'class' => 'form-control', 'required', 'style' =>
        'text-align:right;']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-4 col-sm-3 col-xs-12">Balai Pertemuan <span class="required">*</span></label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jml_balai_pertemuan', null, ['placeholder' => '0', 'class' => 'form-control', 'required',
        'style' => 'text-align:right;']) !!}
      </div>
    </div>
  </div>
</div>
<div class="ln_solid"></div>