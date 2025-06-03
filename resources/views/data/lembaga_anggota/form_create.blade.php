<style>
    /* custom error css order */
    #penduduk_id-error,
    #jabatan_id-error {
        order: 2
    }
</style>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Anggota <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div style="display: flex; flex-direction: column;">
            {!! Form::select('penduduk_id', $pendudukList, null, ['class' => 'form-control select2', 'placeholder' => 'Pilih Nama Anggota', 'required' => true, 'style' => 'width:100%;']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Anggota <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('no_anggota', null, ['placeholder' => 'Nomor Anggota', 'class' => 'form-control', 'required' => true, 'min' => 1]) !!}
        <small class="text-danger" style="font-style: italic; font-weight: 700">*Pastikan nomor anggota belum pernah digunakan !</small>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Jabatan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div style="display: flex; flex-direction: column;">
            {!! Form::select(
                'jabatan_id',
                [
                    1 => 'Ketua',
                    2 => 'Wakil Ketua',
                    3 => 'Sekretaris',
                    4 => 'Bendahara',
                    5 => 'Anggota',
                ],
                null,
                ['placeholder' => 'Pilih Jabatan', 'class' => 'form-control select2', 'required' => true, 'style' => 'width:100%;'],
            ) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor SK Jabatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('no_sk_jabatan', null, ['placeholder' => 'Nomor SK Jabatan', 'class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor SK Pengangkatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('no_sk_pengangkatan', null, ['placeholder' => 'Nomor SK Pengangkatan', 'class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal SK Pengangkatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::date('tgl_sk_pengangkatan', null, ['placeholder' => 'Tanggal SK Pengangkatan', 'class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor SK Pemberhentian</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('no_sk_pemberhentian', null, ['placeholder' => 'Nomor SK Pemberhentian', 'class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal SK Pemberhentian</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::date('tgl_sk_pemberhentian', null, ['placeholder' => 'Tanggal SK Pemberhentian', 'class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Masa Jabatan (Usia/Periode)</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('periode', null, ['placeholder' => 'Contoh: 6 Tahun Periode Pertama (2015 s/d 2021)', 'class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('keterangan', null, ['placeholder' => 'Keterangan', 'class' => 'form-control', 'rows' => 2]) !!}
    </div>
</div>

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')
@include('partials.asset_select2')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\StoreLembagaAnggotaRequest', '#form-lembaga-anggota') !!}
@endpush
