@extends('layouts.dashboard_template')


@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? '' }}
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
            <div class="control-group">
                <a href="{{ route('data.epidemi-penyakit.import') }}">
                    <button type="button" class="btn btn-warning btn-sm" title="Import Data"><i class="fa fa-upload"></i>&ensp;Impor</button>
                </a>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable" id="aki-table">
                    <thead>
                        <tr>
                            <th style="max-width: 100px;">Aksi</th>
                            <th>Desa</th>
                            <th>Jenis Penyakit</th>
                            <th>Jumlah Penderita</th>
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

@include('partials.asset_datatables')

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var data = $('#aki-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route( 'data.epidemi-penyakit.getdata' ) !!}",
            columns: [
                {data: 'aksi', name: 'aksi', class: 'text-center', searchable: false, orderable: false},
                {data: 'desa.nama', name: 'desa.nama'},
                {data: 'penyakit.nama', name: 'penyakit.nama'},
                {data: 'jumlah_penderita', name: 'jumlah_penderita'},
                {data: 'bulan', name: 'bulan'},
                {data: 'tahun', name: 'tahun'},
            ],
            order: [[1, 'asc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush
