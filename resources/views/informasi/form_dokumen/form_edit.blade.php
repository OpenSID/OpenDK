<div class="form-group">
    <label for="jenis_dokumen_id" class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Dokumen<span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('jenis_dokumen_id', \App\Models\JenisDokumen::pluck('nama', 'id'), null, ['placeholder' => '-Pilih', 'class' => 'form-control', 'id' => 'jenis_dokumen_id', 'required' => true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Judul Dokumen <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('nama_dokumen', null, ['class' => 'form-control', 'placeholder' => 'Nama Dokumen']) !!}
    </div>
</div>
<div class="form-group row">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Retensi Dokumen<span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::hidden('retention_days', 0, ['id' => 'retention_days']) !!}
        <div class="row">
            <div class="col-md-6 pl-md-1">
                {!! Form::select('jumlah_waktu', array_combine(range(0, 31), range(0, 31)), $jumlah_waktu, [
                    'class' => 'form-control',
                    'placeholder' => '- Pilih -',
                ]) !!}
            </div>
            <div class="col-md-6 pr-md-1">
                {!! Form::select('tipe_waktu', ['1' => 'Hari', '2' => 'Bulan', '3' => 'Tahun'], $tipe_waktu, [
                    'class' => 'form-control',
                    'placeholder' => '- Pilih -',
                ]) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Unggah Dokumen</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="file" name="file_dokumen" id="file_prosedur" class="form-control">
        <br>
        @if (!$dokumen->file_dokumen == '')
            <a class="btn btn-sm btn-primary" href="{{ asset($dokumen->file_dokumen) }}">Download File</a>
        @endif
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textArea('description', null, ['class' => 'form-control', 'placeholder' => 'Keterangan', 'required']) !!}
    </div>
</div>
<div class="form-group">
    <label for="jenis_dokumen_id" class="control-label col-md-3 col-sm-3 col-xs-12">Status Terbit<span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('status', ['1' => 'Ya', '2' => 'Tidak',], $status, [
            'class' => 'form-control',
            'placeholder' => '- Pilih -',
        ]) !!}
    </div>
</div>
<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\DokumenRequest', '#form-dokumen') !!}
@endpush
