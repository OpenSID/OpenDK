@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? "Page Title" }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Pesan</li>
        </ol>
    </section>
    <section class="content">
        @include('partials.flash_message')
        <div class="row">
            <div class="col-md-3">
                @include('pesan.partial_pesan_menu')
            </div>

            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buat Pesan Baru</h3>
                    </div>

                {!! Form::open( [ 'route' => 'pesan.compose.post', 'method' => 'post','id' => 'form-compose-pesan'] ) !!}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label>Kirim ke Desa</label>
                            {!! Form::select('das_data_desa_id', $list_desa->pluck('nama', 'id'), null,['placeholder' => 'pilih desa', 'class'=>'form-control', 'id'=>'list_desa', 'required']) !!}
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            {!! Form::text('judul', null,['placeholder'=>'Subject:', 'class'=>'form-control', 'required']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::textarea('text', null,['class'=>'textarea', 'placeholder'=>'Isi Pesan', 'style'=>'width: 100%;
                 height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Kirim</button>
                        </div>
                        <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Batal</button>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /. box -->
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection
@include('partials.asset_tinymce')
@include('partials.asset_select2')
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#list_desa').select2({
                placeholder: "Pilih Desa",
                allowClear: true
            });
        });

        $('.textarea').tinymce({
                
                height: 500,
                theme: 'silver',
                plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                        "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                        "table contextmenu directionality emoticons paste textcolor code"
                ],
                toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                toolbar2: "| link unlink anchor | image media | forecolor backcolor | print preview code | fontselect fontsizeselect",
                image_advtab: true ,
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css'
                ],
                relative_urls : false,
                remove_script_host : false
            });
     </script>
@endpush