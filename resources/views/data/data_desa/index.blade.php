@extends('layouts.dashboard_template')

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

            @if ($profil->kecamatan_id)
                <div class="box-header with-border">
                    @include('forms.btn-social', ['create_url' => route('data.data-desa.create')])
                    @include('forms.btn-social', ['desa_url' => route('data.data-desa.getdesa')])
                </div>
            @endif

            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="datadesa-table">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Kode Desa</th>
                                <th>Nama Desa</th>
                                <th>Website</th>
                                <th>Luas Wilayah (km<sup>2</sup>)</th>
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
            var data = $('#datadesa-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{!! route('data.data-desa.getdata') !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'desa_id',
                        name: 'desa_id'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'website',
                        name: 'website'
                    },
                    {
                        data: 'luas_wilayah',
                        name: 'luas_wilayah'
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
