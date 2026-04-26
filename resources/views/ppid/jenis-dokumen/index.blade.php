@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li>PPID</li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        <div id="flash-message"></div>

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                @include('forms.btn-social', ['modal_url' => '#modal-form'])
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered" id="data_ppid_jenis_dokumen">
                    <thead>
                        <tr>
                            <th style="max-width: 100px;">Aksi</th>
                            <th>Nama</th>
                            <th>Keterangan</th>
                            <th>Urutan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        @include('ppid.jenis-dokumen.modal-form')
    </section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#data_ppid_jenis_dokumen').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('ppid.jenis-dokumen.getdata') !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'urutan',
                        name: 'urutan'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    }
                ],
                order: [
                    [3, 'asc']
                ]
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
