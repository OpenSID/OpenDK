<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Kegiatan <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! html()->text('event_name', old('event_name'))->placeholder('Nama kegiatan')->class('form-control')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Mulai / Selesai<span class="required">*</span></label>
    <div class="col-md-3 col-sm-8 col-xs-12">
        {!! html()->text('waktu', old('waktu'))->id('waktu')->placeholder('Waktu kegiatan')->class('form-control')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! html()->textarea('description', old('description'))->class('textarea my-editor')->placeholder('Deskripsi kegiatan')->style('width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Orang yang hadir <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! html()->text('attendants', old('attendants'))->placeholder('contoh: BAPENAS, GUBERNUR, ' . strtoupper($sebutan_kepala_wilayah) . '')->class('form-control')->required() !!}
    </div>
</div>
<div id="attachment_input" class="form-group">

</div>
<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')
@include('partials.asset_daterangepicker')

@php
    $limitEnabled = \App\Services\FileUploadService::isLimitEnabled();
    $uploadHintEvent = '<small class="help-block"><i class="fa fa-info-circle"></i> Format yang diizinkan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, GIF, SVG.'
        . ($limitEnabled ? ' Ukuran maksimum: <strong>2 MB</strong>.' : '')
        . '</small>';
@endphp

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\EventRequest', '#form-event') !!}
    <script type="application/javascript">
    var fileTypes = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'svg'];
    var imageTypes = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'];

    function readAttachmentURL(input) {
        if (input.files && input.files[0]) {
            var extension = input.files[0].name.split('.').pop().toLowerCase();
            var isSuccess = fileTypes.indexOf(extension) > -1;

            if (isSuccess) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (imageTypes.indexOf(extension) > -1) {
                        $('#showgambar-attachment').attr('src', e.target.result);
                        $('#showgambar-attachment').removeClass('hide');
                        $('#showpdf-attachment').addClass('hide');
                    } else if (extension === 'pdf') {
                        $('#showpdf-attachment').attr('src', e.target.result + '#toolbar=1');
                        $('#showpdf-attachment').removeClass('hide');
                        $('#showgambar-attachment').addClass('hide');
                    } else {
                        $('#showgambar-attachment').addClass('hide');
                        $('#showpdf-attachment').addClass('hide');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                $('#attachment').val('');
                alert('File tersebut tidak diperbolehkan.');
            }
        }
    }

    $('#status').on('change', function() {
        if( this.value == 'CLOSED' ) {
            $('#attachment_input').html('<label class="control-label col-md-3 col-sm-3 col-xs-12">Attachment <span class="required">*</span></label>' +
                    '<div class="col-md-6 col-sm-6 col-xs-12">' +
                    '<input id="attachment" name="attachment" class="form-control" type="file" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.svg">' +
                    {!! json_encode($uploadHintEvent) !!} +
                    '<div class="clearfix"></div>' +
                    '<br>' +
                    '<img class="hide" id="showgambar-attachment" style="max-width:400px;max-height:250px;float:left;" />' +
                    '<iframe src="" class="hide" id="showpdf-attachment" style="width: 100%; height: 400px; border: none;"></iframe>' +
                    '</div>');
            // Bind file preview after inserting element
            $(document).on('change', '#attachment', function() {
                readAttachmentURL(this);
            });
        }else{
            $('#attachment_input').html("");
        }
    });

    //Datetimepicker
    $('#waktu').daterangepicker({ timePicker: true, timePicker24Hour: true, locale: { format: 'YYYY/MM/D HH:mm' }})
</script>
@endpush
