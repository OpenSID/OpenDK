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
                        <h3 class="box-title">Pesan</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-controls">
                                @if($pesan->diarsipkan === 0)
                                    {!! Form::open( [ 'route' => 'pesan.arsip.post', 'class' => 'form-group inline', 'method' => 'post','id' => 'form-arisp-pesan'] ) !!}
                                    {!! Form::text('id', $pesan->id, ['hidden' => true]) !!}
                                    <button id="arsip-action" type="submit" class="btn btn-default btn-sm"><i class="fa fa-archive"></i> Arsipkan </button>
                                    {!! Form::close() !!}
                                @endif
                                {{ $pesan->detailPesan->sortBy('created_at')->paginate(20)->links('vendor.pagination.pesan') }}
                        </div>
                        <div class="mailbox-read-info">
                            <h3 class="text-bold">{{ $pesan->judul }}</h3>
                            <h5>@if($pesan->jenis === "Pesan Masuk") Dari @else Ditujukan untuk @endif:
                                Desa {{ $pesan->dataDesa->nama }}
                                <span class="mailbox-read-time pull-right">{{ $pesan->custom_date }}</span></h5>
                        </div>  
                        @foreach($pesan->detailPesan->sortBy('created_at')->paginate(20) as $single_pesan )
                            <div class="mailbox-read-message">
                                <div class="row">
                                    <div class="col-xs-1">
                                        <div class="card img-thumbnail profil">
                                            @if ($single_pesan->pengirim == 'kecamatan')
                                                <img class="img-responsive" src="{{ is_logo($profil->file_logo) }}">
                                            @else
                                                <img class="img-responsive" src="{{ '../img/pengguna/kuser.png' }}">
                                            @endif
                                            
                                        </div>
                                    </div>
                                    <div class="col-xs-11">
                                        <h5>
                                            <span class="username ">    
                                                <span class="text-bold">{{ $single_pesan->nama_pengirim }}</span>                            
                                                <span class="text-muted pull-right">{{ $single_pesan->custom_date }}</span>
                                            </span>
                                        </h5>
                                        {!! $single_pesan->text !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div style="padding-right: 10px; padding-left: 10px" class="box-footer form-group {{ ($pesan->diarsipkan == 1)?'hidden' : '' }}">
                        {!! Form::open( [ 'route' => 'pesan.reply.post', 'class' => 'form-group inline', 'method' => 'post','id' => 'form-reply-pesan'] ) !!}
                        {!! Form::text('id', $pesan->id, ['hidden' => true]) !!}
                        {!! Form::textarea('text', null,['class'=>'textarea', 'id' => 'reply_message', 'placeholder'=>'Balas Pesan', 'style'=>'width: 100%;
             height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
                        <button id="action-reply" type="submit" class="btn btn-default" style="margin-top: 1rem"><i class="fa fa-reply"></i> Balas</button>
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /. box -->
            </div>
        </div>
    </section>
@endsection
@include('partials.asset_tinymce')
@push('scripts')
    <script type="application/javascript">
        $(document).ready(function () {

            $('#prev-links').click(function () {
                let page = $(this).data('currentPage');
                if(page <= 1){
                    return;
                }else{
                    window.location = window.location.origin +
                        window.location.pathname + '?' + $.param({page: page - 1})
                }
            })

            $('#next-links').click(function () {
                let last = $(this).data('lastPage');
                let page = $(this).data('currentPage');
                if(last <= page){
                    return;
                }else{
                    window.location = window.location.origin +
                        window.location.pathname +  '?' + $.param({page: page + 1})
                }
            })

            $('#arsip-action').click(function (e) {
                e.preventDefault();
                let response = window.confirm("Apakah Anda yakin akan mengarsipkan pesan?")
                if (response) {
                    $('#form-arisp-pesan').submit()
                }
            })

            $('#action-reply').click(function (e) {
                e.preventDefault();
                if($('#reply_message').val() === '') {
                    window.alert("silahkan isi pesan");
                    return;
                }
                $('#form-reply-pesan').submit()
            })
            
            $('#reply_message').tinymce({
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
             
        })
    </script>
@endpush