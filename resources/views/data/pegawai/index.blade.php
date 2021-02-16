@extends('layouts.dashboard_template')

@section('title') Data Profil @endsection

@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    @include('partials.flash_message')
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="float-right">
                        <div class="btn-group">
                            <a href="{{ route('data.pegawai.create') }}">
                                <button type="button" class="btn btn-primary btn-sm" title="Tambah Data"><i class="fa fa-plus"></i> Tambah Pegawai</button>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="pegawai-table">
                                    <thead>
                                        <tr>
                                            <th width="80px">Aksi</th>
                                            <th>Foto</th>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
@include('partials.asset_select2')
@include('partials.asset_datatables')

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var data = $('#pegawai-table').DataTable({
            autoWidth: false,
            // processing: true,
            serverSide: true,
            ajax: {
                url: "{!! route( 'data.pegawai.getdata' ) !!}",
            },
            columns: [
                {data: 'action', name: 'action', class: 'text-center', searchable: false, orderable: false},
                {data: 'foto', name: 'foto',
                "searchable": false,
                "orderable":false,
                "render": function (data, type, row) {
                    if ( !row.foto == '') {
                      return "<img src=\"{{ asset('storage/pegawai/foto') }}" + "/" + data + "\" class=\"img-rounded\" alt=\"Foto Pegawai\" height=\"50\"/>";
                    }
                    else {
                      return "<img src=\"{{ asset('storage/pegawai/foto/kuser.png') }}" + "\" class=\"img-rounded\" alt=\"Foto Pegawai\" height=\"50\"/>";
                    }
                  }
                },
                {data: 'nip', name: 'nip'},
                {data: 'nama_pegawai', name: 'nama_pegawai'},
                {data: 'nama_jabatan', name: 'das_jabatan.nama_jabatan'},
            ],
            order: [[0, 'desc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush
