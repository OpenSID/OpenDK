<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Event Name <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('event_name', null, ['placeholder' => 'Event Name','class' => 'form-control', 'required'=>true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Start <span class="required">*</span></label>

    <div class="col-md-2 col-sm-2 col-xs-3">
        {!! Form::text('start', null, ['placeholder' => 'Event Start','class' => 'form-control datetime', 'required'=>true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">End <span class="required">*</span></label>

    <div class="col-md-2 col-sm-2 col-xs-3">
        {!! Form::text('end', null, ['placeholder' => 'Event End','class' => 'form-control datetime', 'required'=>true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Descriptions <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('description', null,['class'=>'textarea', 'placeholder'=>'Event descriptions', 'style'=>'width: 100%;
         height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;', 'required'=>'required']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Attendants <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('attendants', null, ['placeholder' => 'BAPENAS, GUBERNUR, CAMAT','class' => 'form-control', 'required'=>true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required">*</span></label>

    <div class="col-md-2 col-sm-2 col-xs-3">
        {!! Form::select('status', ['OPEN'=>'Open', 'CLOSED'=>'Closed'],null, ['class' => 'form-control', 'required'=>true, 'id'=> 'status']) !!}
    </div>
</div>
<div id="attachment_input" class="form-group">

</div>
<div class="ln_solid"></div>

@push('scripts')
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
</script>
@endpush