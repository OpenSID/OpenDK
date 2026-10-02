<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipe <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->select('tipe_regulasi', \App\Models\TipeRegulasi::pluck('nama', 'id'), old('tipe_regulasi', isset($regulasi) ? $regulasi->tipe_regulasi : null))->class('form-control')->id('tipe')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Judul <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('judul', old('judul'))->class('form-control')->placeholder('Judul Regulasi')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->textarea('deskripsi', old('deskripsi'))->class('form-control')->placeholder('Deskripsi')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">File Regulasi <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input accept=".jpg,.jpeg,.png,.gif,.pdf" type="file" id="file_regulasi" name="file_regulasi" class="form-control" required>
        <x-upload-hint formats="JPG, JPEG, PNG, GIF, PDF" />
        <div class="clearfix"></div>
        <br>
        <img class="hide" id="showgambar" style="max-width:400px;max-height:250px;float:left;" />
        <iframe src="" class="showpdf hide" id="showpdf" style="width: 100%; height: 400px; border: none;"></iframe>
    </div>
</div>

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\RegulasiRequest', '#form-regulasi') !!}

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
