@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('ppid.jenis-dokumen.index') }}">PPID</a></li>
        <li class="active">{!! $page_title !!}</li>
    </ol>
</section>
<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            @include('forms.btn-social', ['create_url' => route('ppid.jenis-dokumen.create')])
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="jenis-dokumen-table">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap">Aksi</th>
                            <th>Urutan</th>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Jumlah Dokumen</th>
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
        var table = $('#jenis-dokumen-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route('ppid.jenis-dokumen.getdata') !!}",
            columns: [
                {
                    data: 'aksi',
                    name: 'aksi',
                    class: 'text-center text-nowrap',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'urutan',
                    name: 'urutan',
                    class: 'text-center'
                },
                { data: 'nama', name: 'nama' },
                { data: 'slug', name: 'slug' },
                { data: 'status', name: 'status' },
                {
                    data: 'jumlah_dokumen',
                    name: 'jumlah_dokumen',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                }
            ],
            order: [[1, 'asc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')
@endpush
