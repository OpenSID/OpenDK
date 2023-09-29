<div class="form-group">
    <label for="type_id" class="control-label col-md-3 col-sm-6 col-xs-12">Tipe Akun <span class="required">*</span></label>

    <div class="col-md-3 col-sm-4 col-xs-12">
        <select name="type_id" class="form-control" id="type_id">
            @foreach(\App\Models\CoaType::all() as $type)
                <option value="{{ $type->id }}">{{ $type->id.' - ' .$type->type_name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label for="sub_id" class="control-label col-md-3 col-sm-6 col-xs-12">Sub Akun <span class="required">*</span></label>

    <div class="col-md-3 col-sm-4 col-xs-12">
        <select name="sub_id" class="form-control" id="sub_id">
            @foreach(\App\Models\SubCoa::where('type_id', 4)->get() as $sub)
                <option value="{{ $sub->id }}">{{ $sub->id.' - ' .$sub->sub_name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label for="sub_sub_id" class="control-label col-md-3 col-sm-6 col-xs-12">Sub Sub Akun <span class="required">*</span></label>

    <div class="col-md-3 col-sm-4 col-xs-12">
        <select name="sub_sub_id" class="form-control" id="sub_sub_id">
            @foreach(\App\Models\SubSubCoa::where('type_id', 4)->where('sub_id',1)->get() as $sub_sub)
                <option value="{{ $sub_sub->id }}">{{ $sub_sub->id.' - ' .$sub_sub->sub_sub_name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label for="id" class="control-label col-md-3 col-sm-6 col-xs-12">ID Akun <span class="required">*</span></label>

    <div class="col-md-3 col-sm-6 col-xs-12">
        {!! Form::text('id', null, ['class' => 'form-control', 'required'=>true, 'id' => 'id', 'readonly'=>true]) !!}
    </div>
</div>
<div class="form-group">
    <label for="coa_name" class="control-label col-md-3 col-sm-6 col-xs-12">Nama Akun <span class="required">*</span></label>

    <div class="col-md-8 col-sm-6 col-xs-12">
        {!! Form::text('coa_name', null, ['class' => 'form-control', 'required'=>true, 'id' => 'coa_name']) !!}
    </div>
</div>
<div class="ln_solid"></div>
@include('partials.asset_datatables')

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        $('#type_id').on('change', function (e) {
            var type_id = this.value;
            $('#sub_id option').remove();
            $.ajax('{!! url('setting/coa/sub_coa/') !!}' +'/' + type_id, {
            }).done(function (data_sub) {
                $.each(data_sub, function(index, row){

                    $('#sub_id').append('<option value="'+row.id +'" >'+ row.id + ' - ' + row.sub_name + '</option>');
                });

                $('#sub_sub_id option').remove();
                $.ajax('{!! url('setting/coa/sub_sub_coa/') !!}' +'/' +type_id + '/' + data_sub[0].id, {
                }).done(function (data_sub_sub) {
                    $.each(data_sub_sub, function(index, row){
                        $('#sub_sub_id').append('<option value="'+row.id +'" >'+ row.id + ' - ' + row.sub_sub_name + '</option>');
                    });

                    generate_id(type_id, data_sub[0].id, data_sub_sub[0].id);
                });
            });


        });

        $('#sub_id').on('change', function (e) {
            var type_id = $('#type_id').val();
            var sub_id = this.value;
            $('#sub_sub_id option').remove();
            $.ajax('{!! url('setting/coa/sub_sub_coa/') !!}' +'/' +type_id + '/' + sub_id, {
            }).done(function (data) {
                $.each(data, function(index, row){
                    $('#sub_sub_id').append('<option value="'+row.id +'" >'+ row.id + ' - ' + row.sub_sub_name + '</option>');
                });
                generate_id(type_id, sub_id, data[0].id);
            });
        });

        $('#sub_sub_id').on('change', function (e) {
            var type_id = $('#type_id').val();
            var sub_id =$('#sub_id').val();
            var sub_sub_id = this.value;
           generate_id(type_id, sub_id,sub_sub_id);
        });

        function generate_id(type_id,sub_id,sub_sub_id)
        {
            console.log(type_id);
            console.log(sub_id);
            console.log(sub_sub_id);
            $.ajax('{!! url('setting/coa/generate_id/') !!}' +'/' +type_id + '/' + sub_id + '/' + sub_sub_id, {
            }).done(function (data) {
                $('#id').val(data);
            });
        }
        var type_id = $('#type_id').val();
        var sub_id =$('#sub_id').val();
        var sub_sub_id =$('#sub_sub_id').val();
        generate_id(type_id, sub_id,sub_sub_id);
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush
