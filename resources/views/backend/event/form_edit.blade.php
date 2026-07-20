<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Kegiatan <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! html()->text('event_name', old('event_name', $event->event_name))->placeholder('Nama kegiatan')->class('form-control')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Mulai / Selesai<span class="required">*</span></label>
    <div class="col-md-3 col-sm-8 col-xs-12">
        {!! html()->text('waktu', old('waktu', $event->start . ' - ' . $event->end))->id('waktu')->placeholder('Waktu kegiatan')->class('form-control')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! html()->textarea('description', old('description', $event->description))->class('textarea my-editor')->placeholder('Deskripsi kegiatan')->style(
                'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding:
                                                                                                10px;',
            )->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Orang yang hadir <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! html()->text('attendants', old('attendants', $event->attendants))->placeholder('contoh: BAPENAS, GUBERNUR, CAMAT')->class('form-control')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required">*</span></label>
    <div class="col-md-2 col-sm-8 col-xs-12">
        {!! html()->select('status', ['OPEN' => 'Open', 'CLOSED' => 'Closed'], old('status', $event->status))->class('form-control')->required()->id('status') !!}
    </div>
</div>
@if ($event->attachment)
    @php
        $ext = strtolower(pathinfo($event->attachment, PATHINFO_EXTENSION));
        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg']);
        $fileName = basename($event->attachment);
        $iconClass = match ($ext) {
            'pdf' => 'fa-file-pdf-o text-danger',
            'doc', 'docx' => 'fa-file-word-o text-primary',
            'xls', 'xlsx' => 'fa-file-excel-o text-success',
            'ppt', 'pptx' => 'fa-file-powerpoint-o text-warning',
            default => 'fa-file-o text-muted',
        };
    @endphp
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">File Saat Ini</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div style="padding:8px; background:#f5f5f5; border-radius:4px; display:flex; align-items:center; gap:10px;">
                @if ($isImage)
                    <img src="{{ asset($event->attachment) }}" style="max-height:60px; max-width:90px; object-fit:contain; border-radius:3px;" class="img-thumbnail">
                @else
                    <i class="fa {{ $iconClass }} fa-2x"></i>
                @endif
                <div style="overflow: hidden;">
                    <span class="text-muted" style="word-break: break-all;">{{ $fileName }}</span><br>
                    <a href="{{ route('informasi.event.download', $event->id) }}" class="btn btn-xs btn-default" style="margin-top: 4px;">
                        <i class="fa fa-download"></i> Unduh
                    </a>
                </div>
            </div>
            <small class="help-block text-muted">
                Upload file baru di bawah untuk menggantikan file yang ada.
            </small>
        </div>
    </div>
@endif
<div id="attachment_input" class="form-group">

</div>
<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')
@include('partials.asset_daterangepicker')

@php
    $limitEnabled = \App\Services\FileUploadService::isLimitEnabled();
    $uploadHintEvent = '<small class="help-block"><i class="fa fa-info-circle"></i> Format yang diizinkan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, GIF, SVG.' . ($limitEnabled ? ' Ukuran maksimum: <strong>2 MB</strong>.' : '') . '</small>';
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
                    $("#attachment").val('');
                    alert('File tersebut tidak diperbolehkan.');
                }
            }
        }

        $(document).ready(function () {
            if ($('#status').val() == 'CLOSED') {
                add_atachment();
            }

            $('#status').on('change', function () {
                if (this.value == 'CLOSED') {
                    add_atachment();
                } else {
                    $('#attachment_input').html("");
                }
            });

            // Event delegation: input attachment dirender secara dinamis
            $(document).on('change', '#attachment', function() {
                readAttachmentURL(this);
            });

        function add_atachment() {
                var uploadHint = {!! json_encode($uploadHintEvent) !!};
                $('#attachment_input').html(`
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Attachment</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="attachment" name="attachment" class="form-control" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.svg">
                        ${uploadHint}
                        <div class="clearfix"></div>
                        <br>
                        <img class="hide" id="showgambar-attachment" style="max-width:400px;max-height:250px;float:left;" />
                        <iframe src="" class="hide" id="showpdf-attachment" style="width: 100%; height: 400px; border: none;"></iframe>
                    </div>
                `);
            };

            //Datetimepicker
            $('#waktu').daterangepicker({ timePicker: true, timePicker24Hour: true, locale: { format: 'YYYY/MM/D HH:mm' } })
        });
    </script>
@endpush
