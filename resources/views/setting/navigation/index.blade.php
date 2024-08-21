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
                    <a href="{{ route('setting.navigation.create', $parent_id) }}">
                        <button type="button" class="btn btn-primary btn-sm" title="Tambah Data"><i class="fa fa-plus"></i> Tambah Navigasi</button>
                    </a>
                    @if (!empty($parent_id))
                        <a href="{{ route('setting.navigation.index', $prev_parent) }}">
                            <button type="button" class="btn btn-info btn-sm" title="Kembali"><i class="fa fa-arrow-left"></i> Kembali</button>
                        </a>
                    @endif
                </div>
            </div>
            <div class="box-body">
                @include('flash::message')
                <table class="table table-striped table-bordered" id="data_navigation">
                    <thead>
                        <tr>
                            <th style="max-width: 250px;">Aksi</th>
                            <th>Navigasi</th>
                            <th>Slug</th>
                            <th>Tipe</th>
                            <th>Url</th>
                            <th>Active</th>
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
            var data = $('#data_navigation').DataTable({
                processing: true,
                //serverSide: true,
                ajax: "{!! route('setting.navigation.getdata', $parent_id) !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: false
                    },
                    {
                        data: 'slug',
                        name: 'slug',
                        orderable: false
                    },
                    {
                        data: 'nav_type',
                        name: 'nav_type',
                        orderable: false
                    },
                    {
                        data: 'url',
                        name: 'url',
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        render: function(data) {
                            return (data) ? `<span class="badge badge-success">Aktif</span>` : `<span class="badge badge-danger">Nonaktif</span>`;
                        }
                    }
                ]
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
