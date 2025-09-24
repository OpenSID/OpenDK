@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Data Sarana</li>
    </ol>
</section>

<section class="content container-fluid">
    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            <form id="filter-form" class="form-inline">
                <div class="form-group">
                    <select name="desa_id" id="desa_id" class="form-control">
                        <option value="">-- Semua Desa --</option>
                        @foreach($desaSelect as $desa)
                            <option value="{{ $desa->id }}" {{ request('desa_id') == $desa->id ? 'selected' : '' }}>
                                {{ $desa->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <select name="kategori" id="kategori" class="form-control">
                        <option value="">-- Semua Kategori --</option>
                        <optgroup label="Sarana Kesehatan">
                            <option value="puskesmas">Puskesmas</option>
                            <option value="puskesmas_pembantu">Puskesmas Pembantu</option>
                            <option value="posyandu">Posyandu</option>
                            <option value="pondok_bersalin">Pondok Bersalin</option>
                        </optgroup>
                        <optgroup label="Sarana Pendidikan">
                            <option value="paud">PAUD/Sederajat</option>
                            <option value="sd">SD/Sederajat</option>
                            <option value="smp">SMP/Sederajat</option>
                            <option value="sma">SMA/Sederajat</option>
                        </optgroup>
                        <optgroup label="Sarana Umum">
                            <option value="masjid_besar">Masjid Besar</option>
                            <option value="mushola">Mushola</option>
                            <option value="gereja">Gereja</option>
                            <option value="pasar">Pasar</option>
                            <option value="balai_pertemuan">Balai Pertemuan</option>
                        </optgroup>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter"></i> Filter
                </button>

                <a href="{{ route('data.data-sarana.import') }}" class="btn btn-success">
                    <i class="fa fa-upload"></i> Import
                </a>
                <a href="{{ route('data.data-sarana.export') }}" class="btn btn-success">
                    <i class="glyphicon glyphicon-download-alt"></i> Export
                </a>
                <a href="{{ route('data.data-sarana.create') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            </form>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="datasarana-table">
                    <thead>
                        <tr>
                            <th style="max-width: 150px;">Aksi</th>
                            <th>Nama Sarana</th>
                            <th>Jumlah</th>
                            <th>Kategori</th>
                            <th>Desa</th>
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
<script>
$(function () {
    var table = $('#datasarana-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('data.data-sarana.getdata') }}",
            data: function (d) {
                d.desa_id = $('#desa_id').val();
                d.kategori = $('#kategori').val();
            }
        },
        columns: [
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' },
            { data: 'nama', name: 'nama' },
            { data: 'jumlah', name: 'jumlah' },
            { data: 'kategori', name: 'kategori' },
            { data: 'desa', name: 'desa.nama' },
        ],
        order: [[1, 'asc']]
    });

    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
});
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')
@endpush
