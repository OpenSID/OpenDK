@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
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
                <div class="control-group">
                    <a href="{{ route('setting.widget.create') }}">
                        <button type="button" class="btn btn-primary btn-sm" title="Tambah Data"><i class="fa fa-plus"></i>
                            Tambah Widget</button>
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select class="form-control" id="list_aktif">
                                <option value="Semua">Semua</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="data-widget">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Judul</th>
                                <th>Jenis Widget</th>
                                <th>Aktif</th>
                                <th>Isi</th>
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
            let data = $('#data-widget').DataTable({
                processing: true,
                // serverSide: true,
                ajax: "{!! route('setting.widget.getdata') !!}",
                columns: [
                    {
                        data: 'urut',
                        name: 'urut',
                        class: 'text-center w-100',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        class: 'text-center',
                        searchable: false,
                        orderable: true
                    }, {
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center w-auto',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'jenis_widget',
                        render: function(data, type, row, meta) {
                            return data === 1 ? 'Sistem' : data === 2 ? 'Statis' : 'Dinamis'
                        }
                    },
                    {
                        data: 'enabled',
                        render: function(data, type, row, meta) {
                            return data === 1 ? 'Ya' : 'Tidak'
                        }
                    },
                    {
                        data: 'isi',
                        name: 'isi',
                        render: function(data, type, row, meta) {
                            const maxLength = 150;
                            return data.length > maxLength ? data.substring(0, maxLength) + '...' : data;
                        }
                    }
                ]
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
