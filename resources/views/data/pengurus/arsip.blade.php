@extends('layouts.dashboard_template')

@section('title')
    {{ $page_title ?? 'Page Title' }}
@endsection

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <div class="box-header with-border clearfix">
        <div class="pull-left">
            @include('forms.btn-social', ['back_url' => route('data.pengurus.index')])
            @include('forms.btn-social', [
                'create_url' => route('data.pengurus.create.arsip', $pengurus_id),
            ])
        </div>

        @if ($count_arsip > 0)
            <div class="pull-right">
                @include('forms.btn-social', [
                    'download_zip' => route('data.pengurus.edit.download.arsip.zip', [
                        'pengurus_id' => $pengurus_id,
                    ]),
                ])
            </div>
        @endif
    </div>

    @include('partials.flash_message')

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    {!! Form::open([
                        'route' => 'data.pengurus.store',
                        'method' => 'post',
                        'files' => true,
                        'id' => 'form-pengurus',
                        'class' => 'form-horizontal form-label-left',
                    ]) !!}
                    @include('layouts.fragments.error_message')

                    <div class="box-body">

                        @include('flash::message')

                        <div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="200px">Nama Pengurus</th>
                                        <th width="10px">:</th>
                                        <td>{{ $pengurus ? $pengurus->nama : 'Tidak Ada' }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <th>:</th>
                                        <td>{{ $pengurus ? $pengurus->nik : 'Tidak Ada' }}</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="box-footer">
                            @include('data.pengurus.table_document', ['pengurus_id' => $pengurus_id])
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#pengurus-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('data.pengurus.index') !!}",
                    data: function(d) {
                        d.status = $('#status').val();
                    }
                },
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'identitas',
                        name: 'identitas',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'ttl',
                        name: 'ttl',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'sex',
                        name: 'sex'
                    },
                    {
                        data: 'agama.nama',
                        name: 'agama.nama'
                    },
                    {
                        data: 'pangkat',
                        name: 'pangkat'
                    },
                    {
                        data: 'jabatan.nama',
                        name: 'jabatan.nama'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'pendidikan.nama',
                        name: 'pendidikan.nama'
                    },
                    {
                        data: 'no_sk',
                        name: 'no_sk'
                    },
                    {
                        data: 'tanggal_sk',
                        name: 'tanggal_sk'
                    },
                    {
                        data: 'no_henti',
                        name: 'no_henti'
                    },
                    {
                        data: 'tanggal_henti',
                        name: 'tanggal_henti'
                    },
                    {
                        data: 'masa_jabatan',
                        name: 'masa_jabatan'
                    },
                ],
                aaSorting: [],
            });

            $('#status').on('change', function(e) {
                data.ajax.reload();
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
    @include('forms.suspend-modal')
    @include('forms.active-modal', ['title' => $page_title])
@endpush
