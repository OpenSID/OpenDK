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
                        <div class="pull-right">
                            @if($pesan->diarsipkan === 0)
                                {!! Form::open( [ 'route' => 'pesan.arsip.post', 'class' => 'form-group inline', 'method' => 'post','id' => 'form-arisp-pesan'] ) !!}
                                {!! Form::text('id', $pesan->id, ['hidden' => true]) !!}
                                <button id="arsip-action" type="submit" class="btn btn-default"><i
                                            class="fa fa-archive"></i> Arsipkan
                                </button>
                                {!! Form::close() !!}
                            @endif
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-read-info">
                            <h3>{{ $pesan->judul }}</h3>
                            <h5>@if($pesan->jenis === "Pesan Masuk") Dari @else Ditujukan untuk @endif:
                                Desa {{ $pesan->dataDesa->nama }}
                                <span class="mailbox-read-time pull-right">{{ $pesan->custom_date }}</span></h5>
                        </div>
                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                            @if($pesan->detailPesan->count() > 0)
                                {!! $pesan->detailPesan->first()->text !!}
                            @endif

                        </div>
                        <!-- /.mailbox-read-message -->
                    </div>
                    @if($pesan->detailPesan->count() > 1)
                        @foreach($pesan->detailPesan->sortBy('created_at') as $single_pesan )
                            <div class="box-footer box-comments">
                                <div class="box-comment">
                                    <div>
                                <span class="username">                               
                                       {{ $single_pesan->nama_pengirim }}
                                    <span class="text-muted pull-right">{{ $single_pesan->custom_date }}</span>
                                </span><!-- /.username -->
                                        {!! $single_pesan->text !!}
                                    </div>
                                    <!-- /.comment-text -->
                                </div>
                                <!-- /.box-comment -->
                            </div>
                        @endforeach
                    @endif
                    <div style="padding-right: 10px; padding-left: 10px" class="box-footer form-group">
                        {!! Form::open( [ 'route' => 'pesan.reply.post', 'class' => 'form-group inline', 'method' => 'post','id' => 'form-reply-pesan'] ) !!}
                        {!! Form::text('id', $pesan->id, ['hidden' => true]) !!}
                        {!! Form::textarea('text', null,['class'=>'textarea', 'id' => 'reply_message', 'placeholder'=>'Balas Pesan', 'style'=>'width: 100%;
             height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
                        <button id="action-reply" type="submit" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /. box -->
            </div>
        </div>
    </section>
@endsection
@include('partials.asset_wysihtml5')
@push('scripts')
    <script type="application/javascript">
        $(document).ready(function () {
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

            $('.textarea').wysihtml5();
        })
    </script>
@endpush