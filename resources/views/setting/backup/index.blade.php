@extends('layouts.dashboard_template')

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
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="">
                <a href="{{ route('setting.tipe-regulasi.create') }}">
                    <button type="button" class="btn btn-primary btn-sm" title="Tambah Data"><i class="fa fa-plus"></i> Tambah Tipe</button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="box-body">
                    @include( 'flash::message' )
                    <table class="table table-striped table-bordered" id="setting-backup-database">
                        <thead>
                        <tr>
                            <th style="max-width: 80px;">Aksi</th>
                            <th>Nama File</th>
                            <th>Ukuran</th>
                            <th>Tanggal Backup</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($backups as $key => $backup)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $backup->getFilename() }}</td>
                                <td>{{ format_size_units($backup->getSize()) }}</td>
                                <td>{{ date('Y-m-d H:i:s', $backup->getMTime()) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('setting.backup.index', ['action' => 'restore', 'file_name' => $backup->getFilename()]) }}"
                                        id="restore_{{ str_replace('.gz', '', $backup->getFilename()) }}"
                                        class="btn btn-warning btn-xs"
                                        title="{{ __('backup.restore') }}"><i class="fa fa-rotate-left"></i></a>
                                    <a href="{{ route('setting.backup.download', [$backup->getFilename()]) }}"
                                        id="download_{{ str_replace('.gz', '', $backup->getFilename()) }}"
                                        class="btn btn-info btn-xs"
                                        title="{{ __('backup.download') }}"><i class="fa fa-download"></i></a>
                                    <a href="{{ route('setting.backup.index', ['action' => 'delete', 'file_name' => $backup->getFilename()]) }}"
                                        id="del_{{ str_replace('.gz', '', $backup->getFilename()) }}"
                                        class="btn btn-danger btn-xs"
                                        title="{{ __('backup.delete') }}"><i class="fa fa-remove"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5">{{ __('Anda Belum Backup Database') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                @include('setting.backup.form')
            </div>
        </div>
    </div>

</section>
<!-- /.content -->
@endsection

@include('partials.asset_datatables')
@push('scripts')
@include('forms.datatable-vertical')
@include('forms.delete-modal')
@endpush
