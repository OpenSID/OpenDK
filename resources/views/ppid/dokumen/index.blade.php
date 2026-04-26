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

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                @include('forms.btn-social', ['create_url' => route('ppid.dokumen.create')])
            </div>

            <div class="box-body">
                <!-- Filter Dropdown untuk jenis dokumen -->
                <div class="form-group row">
                    <div class="col-md-4">
                        {!! html()->select('jenis_dokumen_id')
                            ->options($jenis_dokumen->pluck('nama', 'id')->toArray())
                            ->class('form-control filter-jenis')
                            ->placeholder('- Semua Jenis Dokumen -') !!}
                    </div>
                </div>

                <table class="table table-striped table-bordered" id="data_ppid_dokumen">
                    <thead>
                        <tr>
                            <th style="max-width: 150px;">Aksi</th>
                            <th>Judul</th>
                            <th>Nomor Dokumen</th>
                            <th>Jenis Dokumen</th>
                            <th>Tahun</th>
                            <th>File</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#data_ppid_dokumen').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('ppid.dokumen.getdata') !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'nomor_dokumen',
                        name: 'nomor_dokumen'
                    },
                    {
                        data: 'jenis_dokumen',
                        name: 'jenis_dokumen'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        class: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        class: 'text-center'
                    }
                ],
                order: [
                    [0, 'desc']
                ]
            });

            // Filter jenis dokumen
            $('.filter-jenis').on('change', function() {
                var jenisDokumenId = $(this).val();
                table.column(3).search(jenisDokumenId ? '^' + jenisDokumenId + '$' : '', true, false).draw();
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
