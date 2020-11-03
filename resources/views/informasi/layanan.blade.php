<?php
use Carbon\Carbon;

?>
@extends('layouts.dashboard_template')

@section('content')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title or "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    @if ($message = Session::get('success'))

        <div class="alert alert-success">

            <p>{{ $message }}</p>

        </div>

    @endif

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_ektp" data-toggle="tab">Pembuatan e-KTP</a></li>
                        <li><a href="#tab_kk" data-toggle="tab">Pembuatan Kartu Keluarga</a></li>
                        <li><a href="#tab_akta" data-toggle="tab">Pembuatan Akta Kelahiran</a></li>
                        <li><a href="#tab_domisili" data-toggle="tab">Pembuatan Surat Pindah Alamat</a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_ektp">
                            <div class="callout callout-info">
                                <h4>Informasi!</h4>

                                <p>Untuk pengajuan e-KTP silahkan hubungi kantor Kelurahan/Desa Anda masing-masing. <br>Untuk
                                    melihat status pengajuan e-KTP Anda bisa melihat tabel di bawah ini.</p>
                            </div>
                            <table class="table table-bordered table-striped" id="ektp-table" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>Nama Penduduk</th>
                                    <th>Alamat</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_kk">
                            <div class="callout callout-info">
                                <h4>Informasi!</h4>

                                <p>Untuk pengajuan Kartu Keluarga(KK) baru, silahkan hubungi kantor Kelurahan/Desa Anda
                                    masing-masing. <br>Untuk melihat status pengajuan Kartu Keluarga tersebut Anda bisa
                                    melihat tabel di bawah ini.</p>
                            </div>
                            <table class="table table-bordered table-striped" id="kk-table" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>Nama Penduduk</th>
                                    <th>Alamat</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_akta">
                            <div class="callout callout-info">
                                <h4>Informasi!</h4>

                                <p>Untuk pengajuan Akta Kelahiran baru, silahkan hubungi kantor Kelurahan/Desa Anda
                                    masing-masing. <br>Untuk melihat status pengajuan Akta Kelahiran tersebut Anda bisa
                                    melihat tabel di bawah ini.</p>
                            </div>
                            <table class="table table-bordered table-striped" id="akta-table" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>Nama Penduduk</th>
                                    <th>Alamat</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_domisili">
                            <div class="callout callout-info">
                                <h4>Informasi!</h4>

                                <p>Untuk pengajuan Surat Pindah Alamat, silahkan hubungi kantor Kelurahan/Desa Anda
                                    masing-masing. <br>Untuk melihat status pengajuan Surat Pindah Alamat tersebut Anda bisa
                                    melihat tabel di bawah ini.</p>
                            </div>
                            <table class="table table-bordered table-striped" id="domisili-table" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>Nama Penduduk</th>
                                    <th>Alamat</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
            </div>
        </div>

    </section>

</section>
<!-- /.content -->
@endsection
@include('partials.asset_datatables')

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var data = $('#ektp-table').DataTable({
            // processing: true,
            //serverSide: true,
            ajax: "{!! route( 'layanan.proses-ektp.data_ktp' ) !!}",
            columns: [
                {data: 'nama_penduduk', name: 'nama_penduduk'},
                {data: 'alamat', name: 'alamat'},
                {data: 'tanggal_pengajuan', name: 'tanggal_pengajuan'},
                {data: 'tanggal_selesai', name: 'tanggal_selesai'},
                {data: 'status', name: 'status', class: 'text-center'},
            ],
            order: [[0, 'desc']],
            info: true,
            autoWidth: true
        });

        var data = $('#kk-table').DataTable({
            //processing: true,
            //serverSide: false,
            ajax: "{!! route( 'layanan.proses-kk.data_kk' ) !!}",
            columns: [
                {data: 'nama_penduduk', name: 'nama_penduduk'},
                {data: 'alamat', name: 'alamat'},
                {data: 'tanggal_pengajuan', name: 'tanggal_pengajuan'},
                {data: 'tanggal_selesai', name: 'tanggal_selesai'},
                {data: 'status', name: 'status'},
                {data: 'catatan', name: 'catatan'},
            ],
            order: [[0, 'desc']],
            info: true,
            autoWidth: true
        });

        var data = $('#akta-table').DataTable({
            //processing: true,
            //serverSide: false,
            ajax: "{!! route( 'layanan.proses-aktalahir.data_akta' ) !!}",
            columns: [
                {data: 'nama_penduduk', name: 'nama_penduduk'},
                {data: 'alamat', name: 'alamat'},
                {data: 'tanggal_pengajuan', name: 'tanggal_pengajuan'},
                {data: 'tanggal_selesai', name: 'tanggal_selesai'},
                {data: 'status', name: 'status'},
                {data: 'catatan', name: 'catatan'},
            ],
            order: [[0, 'desc']],
            info: true,
            autoWidth: true
        });

        var data = $('#domisili-table').DataTable({
            //processing: true,
            //serverSide: false,
            ajax: "{!! route( 'layanan.proses-domisili.data_domisili' ) !!}",
            columns: [
                {data: 'nama_penduduk', name: 'nama_penduduk'},
                {data: 'alamat', name: 'alamat'},
                {data: 'tanggal_pengajuan', name: 'tanggal_pengajuan'},
                {data: 'tanggal_selesai', name: 'tanggal_selesai'},
                {data: 'status', name: 'status'},
                {data: 'catatan', name: 'catatan'},
            ],
            order: [[0, 'desc']],
            info: true,
            autoWidth: true
        });

    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush
