<div class="form-group">
    {!! html()->label('judul', 'Judul Dokumen')->class('col-sm-2 control-label')->required() !!}
    <div class="col-sm-10">
        {!! html()->text('judul')->class('form-control')->placeholder('Masukkan judul dokumen')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('nomor_dokumen', 'Nomor Dokumen')->class('col-sm-2 control-label') !!}
    <div class="col-sm-10">
        {!! html()->text('nomor_dokumen')->class('form-control')->placeholder('Masukkan nomor dokumen') !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('id_jenis_dokumen', 'Jenis Dokumen')->class('col-sm-2 control-label') !!}
    <div class="col-sm-10">
        {!! html()->select('id_jenis_dokumen', $jenis_dokumen->pluck('nama', 'id')->toArray())
            ->class('form-control')
            ->placeholder('- Pilih Jenis Dokumen -') !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('deskripsi', 'Deskripsi')->class('col-sm-2 control-label') !!}
    <div class="col-sm-10">
        {!! html()->textarea('deskripsi')->class('form-control')->rows(4)->placeholder('Masukkan deskripsi dokumen') !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('tahun', 'Tahun')->class('col-sm-2 control-label') !!}
    <div class="col-sm-10">
        {!! html()->number('tahun')->class('form-control')->placeholder('Masukkan tahun dokumen')->min(1900)->max(2100) !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('file', 'File Dokumen')->class('col-sm-2 control-label') !!}
    <div class="col-sm-10">
        {!! html()->file('file')->class('form-control')->accept('pdf,doc,docx,xls,xlsx,zip,rar') !!}
        <p class="help-block">Format yang diperbolehkan: PDF, DOC, DOCX, XLS, XLSX, ZIP, RAR. Maksimal 10MB.</p>
    </div>
</div>

<div class="form-group">
    {!! html()->label('status', 'Status')->class('col-sm-2 control-label') !!}
    <div class="col-sm-10">
        <div class="radio">
            <label>
                <input type="radio" name="status" value="1" checked> Aktif
            </label>
            &nbsp;
            <label>
                <input type="radio" name="status" value="0"> Tidak Aktif
            </label>
        </div>
    </div>
</div>
