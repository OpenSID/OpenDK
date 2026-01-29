@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_description ?? '' }}</li>
    </ol>
</section>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Ada kesalahan pada kolom inputan.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- form start -->
                {!! html()->form('PUT', route('ppid.pengaturan.update', $pengaturan->id))
                ->id('form-ppid-pengaturan')->class('fform-label-left')->acceptsFiles()->open() !!}

                <div class="box-body">

                    @include('flash::message')
                    @include('ppid.pengaturan._form')

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    @include('partials.button_reset_submit')
                </div>
                {!! html()->form()->close() !!}
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(function() {
        var fileTypes = ['jpg', 'jpeg', 'png', 'bmp']; //acceptable file types

        function readURL(input) {
            if (input.files && input.files[0]) {
                var extension = input.files[0].name.split('.').pop()
                    .toLowerCase(),
                    isSuccess = fileTypes.indexOf(extension) > -1;

                if (isSuccess) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#banner-preview').attr('src', e.target.result);
                        $('#banner-preview').css('opacity', '1').css('box-shadow', '0 2px 4px rgba(0,0,0,0.1)');
                    }

                    reader.readAsDataURL(input.files[0]);
                } else {
                    $("#ppid_banner").val('');
                    alert('File tersebut tidak diperbolehkan. Gunakan file jpg, jpeg, png, atau bmp.');
                }
            }
        }

        $("#ppid_banner").change(function() {
            readURL(this);
        });
    });
</script>
@endpush
