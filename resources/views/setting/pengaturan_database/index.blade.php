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

        <div class="nav-tabs-custom">

            <ul class="nav nav-tabs">
                <li class="{{ Request::is('setting/backup-database') ? 'active' : '' }}">
                    <a href="{{ route('setting.pengaturan-database.backup') }}">Backup Database</a>
                </li>
                <li class="{{ Request::is('setting/restore-database') ? 'active' : '' }}">
                    <a href="{{ route('setting.pengaturan-database.restore') }}">Restore Database</a>
                </li>
            </ul>
            <div class="tab-content">
                @yield('content_pengaturan_database')
            </div>
        </div>

    </section>
@endsection

@include('partials.asset_datatables')

{{-- @push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#data-backup-database').DataTable({
                processing: true,
                //serverSide: true,
                ajax: "{!! route('setting.komplain-kategori.getdata') !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    }
                ],
                order: [
                    [1, 'asc']
                ]
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush --}}
