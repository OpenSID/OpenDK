@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.program-bantuan.index') }}">Program Bantuan</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed">
                        <tr>
                            <th class="col-md-2">Nama</th>
                            <td>: {{ $program->nama }}</td>
                        </tr>
                        <tr>
                            <th>Desa</th>
                            <td>: {{ $program->desa->nama }}</td>
                        </tr>
                        <tr>
                            <th>Sasaran</th>
                            <td>: {{ $sasaran[$program->sasaran] }}</td>
                        </tr>
                        <tr>
                            <th>Periode Program</th>
                            <td>: {{ $program->start_date }} - {{ $program->end_date }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>: {{ $program->description }}</td>
                        </tr>
                    </table>
                </div>
                <hr>
                <legend>Daftar Peserta Program</legend>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable no-footer" id="program-table">
                        @if ($program->sasaran == 1)
                            <thead>
                                <tr>
                                    <th style="max-width: 150px;" rowspan="2" valign="center">No</th>
                                    <th rowspan="2">NIK</th>
                                    <th rowspan="2">Nama Peserta</th>
                                    <th colspan="5" class="text-center">Identitas di Kartu Peserta</th>
                                </tr>
                                <tr>
                                    <th>No. Kartu Peserta</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                        @else
                            <thead>
                                <tr>
                                    <th style="max-width: 150px;" rowspan="2" valign="center">Aksi</th>
                                    <th rowspan="2">No. KK</th>
                                    <th rowspan="2">Kepala Keluarga</th>
                                    <th colspan="5" class="text-center">Identitas di Kartu Peserta</th>
                                </tr>
                                <tr>
                                    <th>No. Kartu Peserta</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                        @endif
                        <tbody>
                            @if (count($peserta) > 0)
                                @foreach ($peserta as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->peserta }}</td>
                                        <td>{!! $row->penduduk->nama !!}</td>
                                        <td>{!! $row->no_id_kartu !!}</td>
                                        <td>{!! $row->kartu_nik !!}</td>
                                        <td>{!! $row->kartu_nama !!}</td>
                                        <td>{!! $row->kartu_tempat_lahir !!}</td>
                                        <td>{!! $row->kartu_tanggal_lahir !!}</td>
                                        <td>{!! $row->kartu_alamat !!}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10" class="dataTables_empty">Tidak ada peserta.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
