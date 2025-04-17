@extends('layouts.dashboard_template')

@section('title')
    {{ $page_title ?? 'Page Title' }}
@endsection

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>

    <section class="content container-fluid">

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                @include('forms.btn-social', ['add_jenis_document' => route('data.jenis-document.create')])
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="jenis-document-table">
                        <thead>
                            <tr>
                                <th style="min-width: 150px;">No</th>
                                <th style="min-width: 150px;">Nama</th>
                                <th style="min-width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#jenis-document-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('data.jenis-document.index') !!}",
                    data: function(d) {
                        d.status = $('#status').val();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
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
