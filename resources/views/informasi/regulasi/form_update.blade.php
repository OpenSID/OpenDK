<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipe <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->select('tipe_regulasi', \App\Models\TipeRegulasi::pluck('nama', 'id'), old('tipe_regulasi', isset($regulasi) ? $regulasi->tipe_regulasi : null))->class('form-control')->id('tipe')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Judul <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ html()->text('judul')->class('form-control')->placeholder('Judul Regulasi')->required()->value(old('judul', isset($regulasi) ? $regulasi->judul : null)) }}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ html()->textarea('deskripsi')->class('form-control')->placeholder('Deskripsi')->required()->value(old('deskripsi', isset($regulasi) ? $regulasi->deskripsi : null)) }}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">File Regulasi <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input accept=".jpg,.jpeg,.png,.gif,.pdf" type="file" id="file_regulasi" name="file_regulasi" class="form-control">
        <x-upload-hint formats="JPG, JPEG, PNG, GIF, PDF" />
        <br />

        @if (isset($regulasi->file_regulasi))
            <div style="padding:8px; background:#f5f5f5; border-radius:4px; margin-bottom:8px; display:flex; align-items:center; gap:10px;">
                @if ($regulasi->is_pdf)
                    <i class="fa fa-file-pdf-o fa-2x text-danger"></i>
                @else
                    <img src="{{ asset($regulasi->file_regulasi) }}" style="max-height:60px; max-width:90px; object-fit:contain; border-radius:3px;" class="img-thumbnail">
                @endif
                <div style="overflow: hidden;">
                    <span class="text-muted" style="word-break: break-all;">{{ basename($regulasi->file_regulasi) }}</span><br>
                    <a href="{{ route('informasi.regulasi.download', $regulasi->id) }}" class="btn btn-xs btn-default" style="margin-top: 4px;">
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

@include('partials.asset_jqueryvalidation')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\RegulasiUpdateRequest', '#form-regulasi') !!}

    <script>
        $(function() {
            var fileTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
            var currentBlobUrl = null;

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var file = input.files[0];
                    var extension = file.name.split('.').pop().toLowerCase();
                    var isSuccess = fileTypes.indexOf(extension) > -1;

                    if (isSuccess) {
                        if (currentBlobUrl) {
                            URL.revokeObjectURL(currentBlobUrl);
                            currentBlobUrl = null;
                        }

                        currentBlobUrl = URL.createObjectURL(file);

                        if (extension !== 'pdf') {
                            $('#showgambar').attr('src', currentBlobUrl).removeClass('hide');
                            $('#showpdf').addClass('hide').attr('src', '');
                        } else {
                            $('#showpdf').attr('src', currentBlobUrl + '#toolbar=1').removeClass('hide');
                            $('#showgambar').addClass('hide').attr('src', '');
                        }
                    } else {
                        $("#file_regulasi").val('');
                        if (currentBlobUrl) {
                            URL.revokeObjectURL(currentBlobUrl);
                            currentBlobUrl = null;
                        }
                        $('#showgambar').addClass('hide').attr('src', '');
                        $('#showpdf').addClass('hide').attr('src', '');
                        openAlert('File tersebut tidak diperbolehkan.', 'Peringatan', 'warning');
                    }
                }
            }

            $("#file_regulasi").change(function() {
                readURL(this);
            });
        });
    </script>
@endpush
