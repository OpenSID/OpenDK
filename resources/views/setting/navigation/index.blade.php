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
                @include('forms.btn-social', [
                    'create_url' => route('setting.navigation.create', $parent_id),
                ])
                @if (!empty($parent_id))
                    @include('forms.btn-social', [
                        'back_url' => route('setting.navigation.index', $prev_parent),
                    ])
                @endif
            </div>
            <div class="box-body">
                @include('flash::message')
                <table class="table table-striped table-bordered" id="data_navigation">
                    <thead>
                        <tr>
                            <th style="max-width: 250px;">Aksi</th>
                            <th>Navigasi</th>
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
                        data: 'full_url',
                        name: 'full_url',
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        render: function(data) {
                            return (data) ? `<span class="badge badge-success">Aktif</span>` :
                                `<span class="badge badge-danger">Nonaktif</span>`;
                        }
                    }
                ]
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
