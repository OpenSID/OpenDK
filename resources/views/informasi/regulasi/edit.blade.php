@extends('layouts.dashboard_template')

@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title or "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{route('informasi.regulasi.index')}}">Regulasi</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                {{-- <div class="box-header with-border">
                     <h3 class="box-title">Aksi</h3>
                 </div>--}}
                <!-- /.box-header -->

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>

                    @endif

                            <!-- form start -->
                    {!!  Form::model($regulasi, [ 'route' => ['informasi.regulasi.update', $regulasi->id], 'method' => 'put','id' => 'form-visimisi', 'class' => 'form-horizontal form-label-left', 'files'=>true] ) !!}

                    <div class="box-body">


                        @include( 'flash::message' )
                        @include('informasi.regulasi.form_update')

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="pull-right">
                            <div class="control-group">
                                <a href="{{ route('informasi.regulasi.index') }}">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Batal
                                    </button>
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection

@push('css')
        <!-- WYSIHTML5 -->
<link rel="stylesheet"
      href="{{ asset ("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}">
@endpush

@push('scripts')
        <!-- WYSIHTML5 -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}"></script>
<script>
    $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        //bootstrap WYSIHTML5 - text editor
        $('.textarea').wysihtml5()
    })
</script>
@endpush
@include('partials.asset_select2')
@push('scripts')
<script>
    $(function () {

        $('#kecamatan_id').select2({
            placeholder: "Pilih Kecamatan",
            allowClear: true,
            ajax: {
                url: '{!! route('api.profil') !!}',
                dataType: 'json',
                delay: 200,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                        pagination: {
                            more: (params.page * 10) < data.total
                        }
                    };
                }
            },
            minimumInputLength: 1,
            templateResult: function (repo) {
                if (repo.loading) return repo.nama;
                var markup = repo.nama;
                return markup;
            },
            templateSelection: function (repo) {
                return repo.nama;
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            initSelection: function (element, callback) {

                var id = '{{ config('app.default_profile')}}'; // env()
                //var id = $('#defaultProfil').val();
                if (id !== "") {
                    $.ajax('{!! route('api.profil-byid') !!}', {
                        data: {id: id},
                        dataType: "json"
                    }).done(function (data) {
                        callback(data);
                    });
                }
            }
        });
    });
</script>

@endpush