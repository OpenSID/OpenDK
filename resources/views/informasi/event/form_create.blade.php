<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Kegiatan <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! Form::text('event_name', null, ['placeholder' => 'Nama kegiatan', 'class' => 'form-control', 'required' => true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Mulai / Selesai<span class="required">*</span></label>
    <div class="col-md-3 col-sm-8 col-xs-12">
        {!! Form::text('waktu', null, ['id' => 'waktu', 'placeholder' => 'Waktu kegiatan', 'class' => 'form-control', 'required' => true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! Form::textarea('description', null, ['class' => 'textarea my-editor', 'placeholder' => 'Deskripsi kegiatan', 'style' => 'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;', 'required' => 'required']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Orang yang hadir <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! Form::text('attendants', null, ['placeholder' => 'contoh: BAPENAS, GUBERNUR, ' . strtoupper($sebutan_kepala_wilayah) . '', 'class' => 'form-control', 'required' => true]) !!}
    </div>
</div>
<div id="attachment_input" class="form-group">

</div>
<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')
@include('partials.asset_daterangepicker')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\EventRequest', '#form-event') !!}
    <script type="application/javascript">
    $('#status').on('change', function() {
        if( this.value == 'CLOSED' ) {
            $('#attachment_input').html('<label class="control-label col-md-3 col-sm-3 col-xs-12">Attachment <span class="required">*</span></label>' +
                    '<div class="col-md-6 col-sm-6 col-xs-12">' +
                    '<input id="attachment" name="attachment" class="form-control" type="file" required>' +
                    '</div>');
        }else{
            $('#attachment_input').html("");
        }
    });

    //Datetimepicker
    $('#waktu').daterangepicker({ timePicker: true, timePicker24Hour: true, locale: { format: 'YYYY/MM/D HH:mm' }})
</script>
@endpush
