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

        <div id="flash-message"></div>
        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                @include('forms.btn-social', ['modal_url' => '#modal-form'])
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="data-komplain-kategori">
                        <thead>
                            <tr>
                                <th style="max-width: 100px;">Aksi</th>
                                <th>Nama Kategori</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        @include('setting.komplain_kategori.modal-form')
    </section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#data-komplain-kategori').DataTable({
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
@endpush
