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
            <div class="box-header with-border">
                @include('forms.btn-social', ['import_url' => route('data.aki-akb.import')])
            </div>
            <div class="box-body">
                @include('layouts.fragments.list-desa')
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable" id="aki-table">
                        <thead>
                            <tr>
                                <th style="max-width: 100px;">Aksi</th>
                                <th>Desa</th>
                                <th>Jumlah AKI</th>
                                <th>Jumlah AKB</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('partials.asset_select2')
@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#aki-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('data.aki-akb.getdata') !!}",
                    data: function(d) {
                        d.desa_id = $('#list_desa').val();
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
                        data: 'nama_desa',
                        name: 'desa_id',
                    },
                    {
                        data: 'aki',
                        name: 'aki'
                    },
                    {
                        data: 'akb',
                        name: 'akb'
                    },
                    {
                        data: 'bulan',
                        name: 'bulan',
                        searchable: false,
                    },
                    {
                        data: 'tahun',
                        name: 'tahun',
                        searchable: false,
                    },
                ],
                order: [
                    [1, 'desc']
                ]
            });

            $('#list_desa').on('select2:select', function(e) {
                data.columns(1).search(this.value).draw();
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
