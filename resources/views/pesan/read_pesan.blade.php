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
                        @foreach($pesan->detailPesan->slice(0)->sortBy('created_at') as $single_pesan )
                            <div class="box-footer box-comments">
                                <div class="box-comment">
                                    <div>
                                <span class="username">
                                    @if(!is_null($single_pesan->dataDesa))
                                        {{ $single_pesan->dataDesa->nama }}
                                    @else
                                        Anda
                                    @endif
                                    <span class="text-muted pull-right">{{ $single_pesan->custom_date }}</span>
                                </span><!-- /.username -->
                                        {{$single_pesan->text}}
                                    </div>
                                    <!-- /.comment-text -->
                                </div>
                                <!-- /.box-comment -->
                            </div>
                        @endforeach
                    @endif
                    <div class="box-footer">
                        <div class="pull-right">
                            @if($pesan->diarsipkan === 0)
                                <button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                                {!! Form::open( [ 'route' => 'pesan.arsip.post', 'class' => 'form-group inline', 'method' => 'post','id' => 'form-arisp-pesan'] ) !!}
                                {!! Form::text('id', $pesan->id, ['hidden' => true]) !!}
                                <button id="arsip-action" type="submit" class="btn btn-default"><i
                                            class="fa fa-archive"></i> Arsipkan
                                </button>
                                {!! Form::close() !!}
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /. box -->
            </div>
        </div>
    </section>
@endsection
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
        })
    </script>
@endpush