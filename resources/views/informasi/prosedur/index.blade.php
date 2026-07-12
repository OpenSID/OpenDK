@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{!! $page_title !!}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                @include('forms.btn-social', ['create_url' => auth()->user()->can('access.informasi.prosedur.create') ? route('informasi.prosedur.create') : null])
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="prosedur-table" data-testid="table-informasi">
                        <thead>
                            <tr>
                                <th style="width: 40px;">No</th>
                                <th>Judul Prosedur </th>
                                <th>Jenis File</th>
                                <th>Ukuran File</th>
                                <th class="text-center" style="max-width: 100px;">Aksi</th>
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
            var data = $('#prosedur-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('informasi.prosedur.getdata') !!}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'judul_prosedur',
                        name: 'judul_prosedur'
                    },
                    {
                        data: 'jenis_file',
                        name: 'jenis_file',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'ukuran_file',
                        name: 'ukuran_file',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center text-nowrap',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });
            // Event untuk tombol pratinjau
            $(document).on('click', '.btn-preview-prosedur', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                $('#modalPreviewSuratLabel').text('Pratinjau');
                $('#modalPreviewSurat .modal-body').html('<iframe src="' + url + '" width="100%" height="500px" style="border:none;"></iframe>');
                $('#modalPreviewSurat').modal('show');
            });
        });
    </script>
    @include('components.modal-preview-surat')
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
