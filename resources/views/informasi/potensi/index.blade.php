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

        @if ($kategoriPotensi->count() > 0)
            <div class="box box-primary">
                <div class="box-header with-border">
                    @include('forms.btn-social', ['create_url' => route('informasi.potensi.create')])
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="potensi-table">
                            <thead>
                                <tr>
                                    <th style="max-width: 150px;">Aksi</th>
                                    <th>Potensi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="callout callout-warning">
                <h4>Informasi!</h4>
                <p>Data tipe potensi belum tersedia. Silahkan tambah data <b><a href="{{ route('setting.tipe-potensi.index') }}">tipe potensi</a></b> terlebih dahulu.</p>
            </div>
        @endif
    </section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#potensi-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{!! route('informasi.potensi.getdata') !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center text-nowrap',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama_potensi',
                        name: 'nama_potensi'
                    },
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
