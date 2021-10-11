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
                <a href="mailbox.html" class="btn btn-primary btn-block margin-bottom">Buat Pesan</a>
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
                            <h5>Dari: Desa {{ $pesan->dataDesa->nama }}
                                <span class="mailbox-read-time pull-right">{{ $pesan->custom_date }}</span></h5>
                        </div>
                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                            @if($pesan->detailPesan->count() > 0)
                                {{ $pesan->detailPesan->first()->text }}
                            @endif

                        </div>
                        <!-- /.mailbox-read-message -->
                    </div>
                    <!-- /.box-footer -->
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /. box -->
            </div>
        </div>
    </section>
@endsection