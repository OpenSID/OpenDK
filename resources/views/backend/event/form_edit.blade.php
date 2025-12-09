<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Kegiatan <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! html()->text('event_name', old('event_name', $event->event_name))
        ->placeholder('Nama kegiatan')
        ->class('form-control')
        ->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Mulai / Selesai<span class="required">*</span></label>
    <div class="col-md-3 col-sm-8 col-xs-12">
        {!! html()->text('waktu', old('waktu', $event->start . ' - ' . $event->end))
        ->id('waktu')
        ->placeholder('Waktu kegiatan')
        ->class('form-control')
        ->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! html()->textarea('description', old('description', $event->description))
        ->class('textarea my-editor')
        ->placeholder('Deskripsi kegiatan')
        ->style('width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding:
        10px;')
        ->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Orang yang hadir <span class="required">*</span></label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        {!! html()->text('attendants', old('attendants', $event->attendants))
        ->placeholder('contoh: BAPENAS, GUBERNUR, CAMAT')
        ->class('form-control')
        ->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required">*</span></label>
    <div class="col-md-2 col-sm-8 col-xs-12">
        {!! html()->select('status', ['OPEN' => 'Open', 'CLOSED' => 'Closed'], old('status', $event->status))
        ->class('form-control')
        ->required()
        ->id('status') !!}
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
    $(document).ready(function () {
        if ($('#status').val() == 'CLOSED') {
            add_atachment();
        }

        $('#status').on('change', function() {
            if( this.value == 'CLOSED' ) {
                add_atachment();
            }else{
                $('#attachment_input').html("");
            }
        });

        function add_atachment() {
            $('#attachment_input').html(`
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Attachment <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="attachment" name="attachment" class="form-control" type="file" required>
                </div>
            `);
        };

        //Datetimepicker
        $('#waktu').daterangepicker({ timePicker: true, timePicker24Hour: true, locale: { format: 'YYYY/MM/D HH:mm' }})
    });
</script>
@endpush