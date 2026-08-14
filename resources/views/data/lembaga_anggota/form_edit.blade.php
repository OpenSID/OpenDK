<style>
    /* custom error css order */
    #penduduk_id-error,
    #jabatan_id-error {
        order: 2
    }
</style>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Anggota</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div style="display: flex; flex-direction: column;">
            {!! html()->select('penduduk_id', $pendudukList)->class('form-control select2')->placeholder('Pilih Nama Anggota')->value(old('penduduk_id', $anggota->penduduk_id ?? ($lembaga->penduduk_id ?? ''))) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Anggota <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('no_anggota')->value(old('no_anggota', $anggota->no_anggota ?? ($lembaga->no_anggota ?? '')))->class('form-control')->required()->placeholder('Nomor Anggota') !!}
        <small class="text-danger" style="font-style: italic; font-weight: 700">*Pastikan nomor anggota belum pernah
            digunakan !</small>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Jabatan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div style="display: flex; flex-direction: column;">
            {{-- TODO: Pakai enum untuk jabatan --}}
            {!! html()->select('jabatan_id', [
                    1 => 'Ketua',
                    2 => 'Wakil Ketua',
                    3 => 'Sekretaris',
                    4 => 'Bendahara',
                    5 => 'Anggota',
                ])->value(old('jabatan_id', isset($anggota) ? $anggota->jabatan : (isset($lembaga) ? $lembaga->jabatan_id : '')))->placeholder('Pilih Jabatan')->class('form-control select2')->required()->style('width:100%;') !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor SK Jabatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('no_sk_jabatan')->value(old('no_sk_jabatan', $anggota->no_sk_jabatan ?? ($lembaga->no_sk_jabatan ?? '')))->class('form-control')->placeholder('Nomor SK Jabatan') !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor SK Pengangkatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('no_sk_pengangkatan')->value(old('no_sk_pengangkatan', $anggota->no_sk_pengangkatan ?? ($lembaga->no_sk_pengangkatan ?? '')))->class('form-control')->placeholder('Nomor SK Pengangkatan') !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal SK Pengangkatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->date('tgl_sk_pengangkatan')->value(old('tgl_sk_pengangkatan', $anggota->tgl_sk_pengangkatan ?? ($lembaga->tgl_sk_pengangkatan ?? '')))->class('form-control')->placeholder('Tanggal SK Pengangkatan') !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor SK Pemberhentian</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('no_sk_pemberhentian')->value(old('no_sk_pemberhentian', $anggota->no_sk_pemberhentian ?? ($lembaga->no_sk_pemberhentian ?? '')))->class('form-control')->placeholder('Nomor SK Pemberhentian') !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal SK Pemberhentian</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->date('tgl_sk_pemberhentian')->value(old('tgl_sk_pemberhentian', $anggota->tgl_sk_pemberhentian ?? ($lembaga->tgl_sk_pemberhentian ?? '')))->class('form-control')->placeholder('Tanggal SK Pemberhentian') !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Masa Jabatan (Usia/Periode)</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('periode')->value(old('periode', $anggota->periode ?? ($lembaga->periode ?? '')))->class('form-control')->placeholder('Contoh: 6 Tahun Periode Pertama (2015 s/d 2021)') !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->textarea('keterangan')->value(old('keterangan', $anggota->keterangan ?? ($lembaga->keterangan ?? '')))->class('form-control')->placeholder('Keterangan')->rows(2) !!}
    </div>
</div>

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')
@include('partials.asset_select2')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\UpdateLembagaAnggotaRequest', '#form-lembaga-anggota') !!}
@endpush
