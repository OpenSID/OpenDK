@extends('layouts.dashboard_template')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('informasi.event.index') }}">Daftar Event</a></li>
        <li class="active">{{ $page_description }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Ups!</strong> Ada beberapa masalah dengan masukan Anda.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>

                    @endif

                    <!-- form start -->
                    {!!  Form::model($event, [ 'route' => ['informasi.event.update', $event->id], 'method' => 'post','id' => 'form-event', 'class' => 'form-horizontal form-label-left', 'files'=>true] ) !!}

                    <div class="box-body">
                        @include( 'flash::message' )
                        @include('informasi.event.form_edit')
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="pull-right">
                            <div class="control-group">
                                <a href="{{ route('informasi.event.index') }}">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i>&nbsp; Batal</button>
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i>&nbsp; Simpan</button>
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

@include(('partials.asset_wysihtml5'))
@include(('partials.asset_datetimepicker'))
@push('scripts')
<script>
    $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        //bootstrap WYSIHTML5 - text editor
        $('.textarea').wysihtml5()

        //Datetimepicker
        $('.datetime').each(function () {
            var $this = $(this);
            $this.datetimepicker({
                format: 'YYYY-MM-D HH:mm'
            });
        });
    })
</script>
@endpush