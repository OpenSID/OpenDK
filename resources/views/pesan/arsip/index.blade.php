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
                        <h3 class="box-title">{{ $page_title }}</h3>
                        <div class="pull-right">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::open( [ 'route' => 'pesan.index', 'method' => 'get','id' => 'form-search-desa'] ) !!}
                                    {!! Form::select('das_data_desa_id', $list_desa->pluck('nama', 'id'), $desa_id,['placeholder' => 'pilih desa', 'class'=>'form-control', 'id'=>'list_desa', 'required']) !!}
                                    {!! Form::close() !!}
                                </div>
                                <div class="col-md-6">
                                    <input id="cari-pesan" value="{{ $search_query }}" type="text" class="form-control" placeholder="Cari Pesan">
                                    <span style="padding-right: 25px" class="glyphicon glyphicon-search form-control-feedback"></span>
                                </div>

                            </div>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-controls">
                            {{ $list_pesan->links('vendor.pagination.pesan') }}
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
                                                    href="{{ route('pesan.read', $pesan->id) }}">{{ $pesan->dataDesa->nama }}</a></td>
                                        <td style="width: 65%" class="mailbox-subject">
                                            <div>
                                                <b>
                                                @if($pesan->diarsipkan === 1)
                                                    [ARSIP]
                                                @endif
                                                {{ $pesan->judul }}</b> -
                                                @if($pesan->detailPesan->count() > 0)
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($pesan->detailPesan->last()->text), 50) }}
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
@include('partials.asset_select2')
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#list_desa').select2({
                placeholder: "Pilih Desa",
                allowClear: true
            });

            $('#prev-links').click(function () {
                let page = $(this).data('currentPage');
                let desa_id = $('#list_desa').val();
                let q = $('#cari-pesan').val();
                if(page <= 1){
                    return;
                }else{
                    window.location = window.location.origin +
                        window.location.pathname + '?' + $.param({page: page - 1, desa_id, q})
                }
            })

            $('#next-links').click(function () {
                let last = $(this).data('lastPage');
                let page = $(this).data('currentPage');
                let q = $('#cari-pesan').val();
                let desa_id = $('#list_desa').val();
                if(last <= page){
                    return;
                }else{
                    window.location = window.location.origin +
                        window.location.pathname +  '?' + $.param({page: page + 1, desa_id, q})
                }
            })

            $('#list_desa').on('select2:select', function (e) {
                let page = $('#next-links').data('currentPage');
                let q = $('#cari-pesan').val();
                window.location = window.location.origin +
                    window.location.pathname +  '?' + $.param({page, desa_id: $(this).val(), q})
            });

            $('#list_desa').on('select2:unselect', function (e) {
                let page = $('#next-links').data('currentPage');
                let q = $('#cari-pesan').val();
                window.location = window.location.origin +
                    window.location.pathname +  '?' + $.param({page, q})
            });

            $('#cari-pesan').keypress(function (e) {
                var key = e.which;
                let desa_id = $('#list_desa').val();
                if(key === 13)  // the enter key code
                {
                    let page = $('#next-links').data('currentPage');
                    window.location = window.location.origin +
                        window.location.pathname +  '?' + $.param({page, desa_id, q: $(this).val()})
                }
            }).focusout(function () {
                let page = $('#next-links').data('currentPage');
                let desa_id = $('#list_desa').val();
                if($(this).val() === ''){
                    window.location = window.location.origin +
                        window.location.pathname +  '?' + $.param({page, desa_id})
                }

            });
        });
    </script>
@endpush