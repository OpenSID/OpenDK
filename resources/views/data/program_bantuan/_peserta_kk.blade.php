<div class="form-group">
    <label for="peserta" class="control-label col-md-3 col-sm-3 col-xs-12">NO. KK / Nama Kepala Keluarga <span
                class="required">*</span></label>

    <div class="col-md-4 col-sm-6 col-xs-12">
        {!! Form::hidden('program_id', $program->id) !!}
        {!! Form::hidden('sasaran', $program->sasaran) !!}
        {!! Form::select('peserta', [], null, ['class' => 'form-control', 'required'=>true, 'id'=>'peserta']) !!}
    </div>
</div>
<div id="identitas"></div>
<div class="form-group">
    <label for="kartu_peserta" class="control-label col-md-3 col-sm-3 col-xs-12">No Kartu</label>

    <div class="col-md-2 col-sm-3 col-xs-12">
        {!! Form::text('kartu_peserta', null,['placeholder'=>'No Kartu',  'class' => 'form-control', 'id'=>'kartu_peserta']) !!}
    </div>
</div>

<legend>Identitas Pada Kartu Peserta</legend>
<div class="form-group">
    <label for="kartu_nik" class="control-label col-md-3 col-sm-3 col-xs-12">NIK</label>

    <div class="col-md-4 col-sm-3 col-xs-12">
        {!! Form::text('kartu_nik', null,['placeholder'=>'NIK', 'class' => 'form-control', 'id'=>'kartu_nik']) !!}
    </div>
</div>
<div class="form-group">
    <label for="kartu_nama" class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>

    <div class="col-md-4 col-sm-3 col-xs-12">
        {!! Form::text('kartu_nama', null,['placeholder'=>'Nama',  'class' => 'form-control', 'id'=>'kartu_nama']) !!}
    </div>
</div>
<div class="form-group">
    <label for="kartu_tempat_lahir" class="control-label col-md-3 col-sm-3 col-xs-12">Tempat Lahir</label>

    <div class="col-md-4 col-sm-3 col-xs-12">
        {!! Form::text('kartu_tempat_lahir', null,['placeholder'=>'Tempat Lahir', 'class' => 'form-control', 'id'=>'kartu_tempat_lahir']) !!}
    </div>
</div>
<div class="form-group">
    <label for="kartu_tanggal_lahir" class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Lahir</label>

    <div class="row">
        <div class="col-md-2 col-sm-2 col-xs-3">
            {!! Form::text('kartu_tanggal_lahir', null, ['placeholder' => 'Tanggal Lahir','class' => 'form-control datepicker',  'id'=>'kartu_tanggal_lahir']) !!}
        </div>
    </div>
</div>
<div class="ln_solid"></div>

@include('partials.asset_datetimepicker')
@include('partials.asset_select2')
@push('scripts')
<script>
    $(function () {

        function formatPeserta (data) {
            if (!data.id) {
                return data.nik;
            }
            var markup = repo.nik+" - "+ repo.nama;
            return markup;
        };

        $('#peserta').select2({
            ajax : {
                url : '{{ url('/api/list-peserta-kk')}}',
                dataType : 'json',
                delay : 200,
                data : function(params){
                    return {
                        q : params.term,
                        page : params.page
                    };
                },
                processResults : function(data, params){
                    params.page = params.page || 1;
                    return {
                        results : data.data,
                        pagination: {
                            more : (params.page  * 10) < data.total
                        }
                    };
                }
            },
            minimumInputLength : 1,
            templateResult : function (repo){
                if(repo.loading) return repo.nik;
                var markup = repo.nik+" - "+ repo.text;
                return markup;
            },
            templateSelection : function(repo)
            {
                return repo.nik+" - "+repo.text;
            },
            escapeMarkup : function(markup){ return markup; }
        });

        $('#peserta').on('select2:select', function (e) {
            var data = e.params.data;
            console.log(data);
            $('#kartu_nik').val(data.nik);
            $('#kartu_nama').val(data.nama);
            $('#kartu_tempat_lahir').val(data.tempat_lahir);
            $('#kartu_tanggal_lahir').val(data.tanggal_lahir);
        });

        //Datetimepicker
        $('.datepicker').each(function () {
            var $this = $(this);
            $this.datetimepicker({
                format: 'YYYY-MM-DD'
            });
        });

    })


</script>
@endpush