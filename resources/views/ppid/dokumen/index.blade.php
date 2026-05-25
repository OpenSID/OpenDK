@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('ppid.dokumen.index') }}">PPID</a></li>
        <li class="active">{!! $page_title !!}</li>
    </ol>
</section>
<section class="content container-fluid">

    @include('partials.flash_message')

    @if ($jenis_dokumen->count() > 0)
    <div class="box box-primary">
        <div class="box-header with-border">
            @include('forms.btn-social', ['create_url' => route('ppid.dokumen.create')])
        </div>

        <div class="box-body">
            <!-- Filter Section -->
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="row">
                        {{-- Filter Jenis Dokumen --}}
                        <div class="col-md-3">
                            {!! html()->select('jenis_dokumen_id')
                            ->options($jenis_dokumen->pluck('nama', 'id')->toArray())
                            ->class('form-control filter-jenis-dokumen')
                            ->placeholder('- Semua Jenis Dokumen -') !!}
                        </div>

                        {{-- Filter Status --}}
                        <div class="col-md-3">
                            {!! html()->select('status')
                            ->options(\App\Enums\PpidStatusEnum::options())
                            ->class('form-control filter-status')
                            ->placeholder('- Semua Status -') !!}
                        </div>

                        {{-- Filter Tipe Dokumen --}}
                        <div class="col-md-3">
                            {!! html()->select('tipe_dokumen')
                            ->options(\App\Enums\PpidTipeDokumenEnum::options())
                            ->class('form-control filter-tipe-dokumen')
                            ->placeholder('- Semua Tipe -') !!}
                        </div>

                        {{-- Filter Tanggal --}}
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control pull-right date-range-filter" placeholder="Filter Tanggal">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <!-- BUG-008: Global search input -->
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <input type="text" class="form-control" id="global-search" placeholder="Cari judul dokumen...">
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered" id="dokumen-table">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap">Aksi</th>
                            <th>Judul</th>
                            <th>Jenis Dokumen</th>
                            <th>Tipe Dokumen</th>
                            <th>Status</th>
                            <th>Tanggal Publikasi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="callout callout-warning">
        <h4>Informasi!</h4>
        <p>Data jenis dokumen belum tersedia. Silakan tambah data <b><a
                    href="{{ route('ppid.jenis-dokumen.index') }}">jenis dokumen PPID</a></b> terlebih dahulu.</p>
        <div style="margin-top: 10px;">
            <a href="{{ route('ppid.jenis-dokumen.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Jenis Dokumen
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-default btn-sm">
                <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
    @endif
</section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#dokumen-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route('ppid.dokumen.getdata') !!}",
            columns: [
                {
                    data: 'aksi',
                    name: 'aksi',
                    class: 'text-center text-nowrap',
                    searchable: false,
                    orderable: false
                },
                { data: 'judul', name: 'judul' },
                { data: 'jenis_dokumen', name: 'jenisDokumen.nama' },
                { data: 'tipe_dokumen', name: 'tipe_dokumen' },
                { data: 'status', name: 'status' },
                { data: 'tanggal_publikasi', name: 'tanggal_publikasi' }
            ],
            order: [[5, 'desc']]
        });

        // BUG-008: Global search handler
        $('#global-search').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        // Filter handlers
        function applyFilters() {
            var jenisDokumenId = $('select[name="jenis_dokumen_id"]').val();
            var status = $('select[name="status"]').val();
            var tipeDokumen = $('select[name="tipe_dokumen"]').val();
            var tanggalRange = $('.date-range-filter').val();

            var url = "{!! route('ppid.dokumen.getdata') !!}?";
            if (jenisDokumenId) url += '&jenis_dokumen_id=' + jenisDokumenId;
            if (status) url += '&status=' + status;
            if (tipeDokumen) url += '&tipe_dokumen=' + tipeDokumen;
            if (tanggalRange) {
                var dates = tanggalRange.split(' - ');
                if (dates.length === 2) {
                    url += '&tanggal_mulai=' + dates[0] + '&tanggal_selesai=' + dates[1];
                }
            }

            table.ajax.url(url).load();
        }

        $('.filter-jenis-dokumen, .filter-status, .filter-tipe-dokumen').on('change', applyFilters);

        // Date range picker (requires daterangepicker plugin)
        if ($.fn.daterangepicker) {
            $('.date-range-filter').daterangepicker({
                format: 'DD/MM/YYYY',
                separator: ' - ',
            }).on('apply.daterangepicker', function(ev, picker) {
                applyFilters();
            });
        }
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')
@endpush
