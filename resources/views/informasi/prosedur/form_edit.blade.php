<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Judul Prosedur <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ html()->text('judul_prosedur')->class('form-control')->placeholder('Judul Prosedur')->value(old('judul_prosedur', $prosedur->judul_prosedur ?? '')) }}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">File Prosedur</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="file" name="file_prosedur" id="file_prosedur" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf" />
        <small class="help-block">
            <i class="fa fa-info-circle"></i>
            Format yang diizinkan: JPG, JPEG, PNG, GIF, PDF.
            Ukuran maksimum: <strong>2 MB</strong>.
        </small>
        <br />

        @if (isset($prosedur->file_prosedur))
            @php
                $isPdf = str_contains($prosedur->mime_type ?? '', 'pdf');
                $fileName = basename($prosedur->file_prosedur);
            @endphp
            <div style="padding:8px; background:#f5f5f5; border-radius:4px; margin-bottom:8px; display:flex; align-items:center; gap:10px;">
                @if ($isPdf)
                    <i class="fa fa-file-pdf-o fa-2x text-danger"></i>
                @else
                    <img src="{{ asset($prosedur->file_prosedur) }}"
                         style="max-height:60px; max-width:90px; object-fit:contain; border-radius:3px;"
                         class="img-thumbnail">
                @endif
                <div style="overflow: hidden;">
                    <span class="text-muted" style="word-break: break-all;">{{ $fileName }}</span><br>
                    <a href="{{ route('informasi.prosedur.download', $prosedur->id) }}" class="btn btn-xs btn-default" style="margin-top: 4px;">
                        <i class="fa fa-download"></i> Unduh
                    </a>
                </div>
            </div>
            <small class="help-block text-muted">
                Upload file baru di atas untuk menggantikan file yang ada.
            </small>
        @endif

        <div class="clearfix"></div>
        <br>
        <img class="hide" id="showgambar" style="max-width:400px;max-height:250px;float:left;" />
        <iframe src="" class="showpdf hide" id="showpdf" style="width: 100%; height: 400px; border: none;"></iframe>

    </div>
</div>
<div class="ln_solid"></div>
