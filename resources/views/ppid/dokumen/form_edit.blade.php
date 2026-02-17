<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Judul <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('judul', old('judul', $dokumen->judul))
        ->class('form-control')
        ->placeholder('Judul Dokumen')
        ->required() !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Dokumen <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->select('jenis_dokumen_id', $jenis_dokumen)
        ->value(old('jenis_dokumen_id', $dokumen->jenis_dokumen_id))
        ->placeholder('- Pilih Jenis Dokumen -')
        ->class('form-control')
        ->required() !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipe Dokumen <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        @foreach($tipe_dokumen_options as $key => $label)
        <div class="radio">
            <label>
                {!! html()->radio('tipe_dokumen', $key, old('tipe_dokumen', $dokumen->tipe_dokumen))
                ->required() !!} {{ $label }}
            </label>
        </div>
        @endforeach
    </div>
</div>

<div class="form-group" id="file-field-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">File</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->file('file_path')
        ->class('form-control')
        ->attribute('accept', '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png') !!}
        @if (!empty($dokumen->file_path))
        <br>
        <p>File saat ini: <a href="{{ asset($dokumen->file_path) }}" target="_blank">Lihat File</a></p>
        @endif
        <p class="help-block">Format yang diperbolehkan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG. Maksimal 10MB.</p>
    </div>
</div>

<div class="form-group" id="url-field-group" style="display: none;">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">URL</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('url', old('url', $dokumen->url))
        ->class('form-control')
        ->placeholder('https://example.com/dokumen') !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Ringkasan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->textarea('ringkasan', old('ringkasan', $dokumen->ringkasan))
        ->class('form-control')
        ->placeholder('Ringkasan dokumen')
        ->rows(3) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Status Terbit <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="btn-group status-toggle" data-toggle="buttons">
            <label class="btn btn-success {{ old('status', $dokumen->status ?? 'terbit') === 'terbit' ? 'active' : '' }}">
                <input type="radio" name="status" value="terbit"
                       {{ (old('status', $dokumen->status ?? 'terbit') === 'terbit') ? 'checked' : '' }}
                       required> Terbit
            </label>
            <label class="btn btn-danger {{ old('status', $dokumen->status ?? 'terbit') === 'tidak_terbit' ? 'active' : '' }}">
                <input type="radio" name="status" value="tidak_terbit"
                       {{ (old('status', $dokumen->status ?? 'terbit') === 'tidak_terbit') ? 'checked' : '' }}> Tidak
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Publikasi</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->date('tanggal_publikasi', old('tanggal_publikasi', $dokumen->tanggal_publikasi ? $dokumen->tanggal_publikasi->format('Y-m-d') : null))
        ->class('form-control datepicker')
        ->placeholder('DD/MM/YYYY') !!}
    </div>
</div>

<div class="ln_solid"></div>
