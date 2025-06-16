@extends('layouts.dashboard_template')

@section('title')
    Data Jabatan
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
    <section class="content container-fluid">

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                {{-- @include('forms.btn-social', ['import_url' => route('data.jabatan.import')]) --}}
                @include('forms.btn-social', ['import_url' => route('data.jabatan.create')])
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="jabatan-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">Aksi</th>
                                <th>Nama</th>
                                <th>Tupoksi</th>
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
            var data = $('#jabatan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('data.jabatan.index') !!}",
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
                        data: 'tupoksi',
                        name: 'tupoksi'
                    },
                ],
                aaSorting: [],
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
