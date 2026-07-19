<div class="form-group">
    <label for="kategori_id" class="control-label col-md-4 col-sm-3 col-xs-12">Kategori<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! html()->select('kategori_id', \App\Models\TipePotensi::pluck('nama_kategori', 'id'), old('kategori_id', isset($potensi) ? $potensi->kategori_id : null))->placeholder('-Pilih')->class('form-control')->id('kategori_id')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Nama Potensi <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {{ html()->text('nama_potensi')->class('form-control')->placeholder('Nama Potensi')->required()->value(old('nama_potensi', isset($potensi) ? $potensi->nama_potensi : null)) }}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Deskripsi <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {{ html()->textarea('deskripsi')->class('form-control')->placeholder('Deskripsi')->required()->value(old('deskripsi', isset($potensi) ? $potensi->deskripsi : null)) }}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Lokasi <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {{ html()->text('lokasi')->class('form-control')->placeholder('Lokasi')->required()->value(old('lokasi', isset($potensi) ? $potensi->lokasi : null)) }}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Gambar</label>
    <div class="col-md-5 col-sm-6 col-xs-12">
        <input type="file" name="file_gambar" id="file_gambar" class="form-control" accept=".jpg,.jpeg,.png,.bmp,.gif">
        <x-upload-hint formats="JPG, JPEG, PNG, BMP, GIF" :limit-kb="1024" />
        <br />

        @if (isset($potensi->file_gambar) && $potensi->file_gambar)
            @php
                $fileName = basename($potensi->file_gambar);
            @endphp
            <div style="padding:8px; background:#f5f5f5; border-radius:4px; margin-bottom:8px; display:flex; align-items:center; gap:10px;">
                <img src="{{ is_img(str_replace('//', '/', $potensi->file_gambar)) }}" style="max-height:60px; max-width:90px; object-fit:contain; border-radius:3px;" class="img-thumbnail">
                <div style="overflow: hidden;">
                    <span class="text-muted" style="word-break: break-all;">{{ $fileName }}</span><br>
                    <a href="{{ route('informasi.potensi.download', $potensi->id) }}" class="btn btn-xs btn-default" style="margin-top: 4px;">
                        <i class="fa fa-download"></i> Unduh
                    </a>
                </div>
            </div>
            <small class="help-block text-muted">
                Upload gambar baru di atas untuk menggantikan gambar yang ada.
            </small>
        @endif

        <div class="clearfix"></div>
        <br>
        <img class="hide" id="showgambar" style="max-width:400px;max-height:250px;float:left;" />
    </div>
</div>

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\PotensiRequest', '#form-potensi') !!}
@endpush
