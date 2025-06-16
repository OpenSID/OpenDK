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
                @include('forms.btn-social', ['create_url' => route('informasi.faq.create')])
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="faq-table">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Pertanyaan</th>
                                <th style="max-width: 100px;">Status</th>
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
            var data = $('#faq-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{!! route('informasi.faq.getdata') !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'question',
                        name: 'question'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [2, 'desc']
                ]
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
