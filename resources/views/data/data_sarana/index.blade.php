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
                    @include('layouts.fragments.select-desa')

                    <div class="form-group">
                        <select name="kategori" id="kategori" class="form-control">
                            <option value="">-- Semua Kategori --</option>
                            @foreach (\App\Enums\KategoriSarana::getGroupedOptions() as $group => $options)
                                <optgroup label="{{ $group }}">
                                    @foreach ($options as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <a href="{{ route('data.data-sarana.import') }}" class="btn btn-success">
                        <i class="fa fa-upload"></i> Import
                    </a>
                    <button id="export-btn" class="btn btn-success" data-href="{{ route('data.data-sarana.getdata') }}">
                        <i class="glyphicon glyphicon-download-alt"></i> Export
                    </button>
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
@include('partials.asset_select2')
@include('partials.asset_datatables')

@push('scripts')
    <script>
        $(function() {
            var table = $('#datasarana-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('data.data-sarana.getdata') }}",
                    data: function(d) {
                        d.desa_id = $('#list_desa').val(),
                            d.kategori = $('#kategori').val()
                    }
                },
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'desa',
                        name: 'desa_id'
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });

            $('#kategori, #list_desa').change(function(e) {
                e.preventDefault();
                table.draw();
            });

            $('#list_desa>option[value=Semua]').val('')

            // Export button functionality
            $('#export-btn').click(function(e) {
                e.preventDefault();
                const _href = $(this).data('href');

                // Get datatable parameters
                var dtParams = $('#datasarana-table').DataTable().ajax.params();

                // Add current filter values to the parameters
                var kategori = $('#kategori').val();
                var desaId = $('#list_desa').val();

                if (kategori) {
                    dtParams.columns[3].search.value = kategori;
                }

                if (desaId) {
                    dtParams.columns[4].search.value = desaId;
                }

                window.location.href = _href + '?action=excel&params=' + JSON.stringify(dtParams);
                return;
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
