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
                        <h3 class="box-title">Pesan Masuk</h3>

                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <input type="text" class="form-control input-sm" placeholder="Search Mail">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i
                                        class="fa fa-square-o"></i>
                            </button>
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                            <div class="pull-right">
                                {{ $first_data }} - {{ $last_data }}/{{ $list_pesan->total() }}
                                <div class="btn-group">
                                    @if($list_pesan->onFirstPage())
                                        <a href="#" type="button" class="btn btn-default btn-sm"><i
                                                    class="fa fa-chevron-left"></i></a>
                                    @else
                                        <a href="{{ $list_pesan->previousPageUrl() }}" type="button"
                                           class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                                    @endif

                                    @if($list_pesan->hasMorePages())

                                        <a href="{{ $list_pesan->nextPageUrl() }}" type="button"
                                           class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                                    @else
                                        <a href="#" type="button"
                                           class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                                    @endif

                                </div>
                                <!-- /.btn-group -->
                            </div>
                            <!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped">
                                <tbody>
                                @foreach($list_pesan as $pesan)
                                    <tr>
                                        <td style="width: 5%">
                                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false"
                                                 style="position: relative;"><input type="checkbox"
                                                                                    style="position: absolute; opacity: 0;">
                                                <ins class="iCheck-helper"
                                                     style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                            </div>
                                        </td>
                                        <td style="width: 10%" class="mailbox-name"><a
                                                    href="#">{{ $pesan->dataDesa->nama }}</a></td>
                                        <td style="width: 65%" class="mailbox-subject">
                                            <div>
                                                <b>{{ $pesan->judul }}</b> -
                                                @if($pesan->detailPesan->count() > 0)
                                                    {{ \Illuminate\Support\Str::limit($pesan->detailPesan->last()->text, 50) }}
                                                @endif
                                            </div>
                                        </td>
                                        <td style="width: 20%"
                                            class="mailbox-date text-right">{{ $pesan->custom_date }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /. box -->
            </div>
        </div>
    </section>
@endsection