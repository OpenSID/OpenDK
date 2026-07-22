@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title ?? '' }}</li>
        </ol>
    </section>
    <section class="content container-fluid" data-testid="settings-index-page">

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="user-table" data-testid="settings-table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Nilai</th>
                                <th>Deskripsi</th>
                                <th style="max-width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($settings as $setting)
                                <tr data-testid="setting-row" data-setting-key="{{ $setting->key }}">
                                    <td>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</td>
                                    <td style="width:40%; word-break:break-all;" data-testid="setting-value">
                                        {{ $setting->type == 'boolean' ? ($setting->value == 1 ? 'Aktif' : 'Tidak Aktif') : $setting->value }}
                                    </td>
                                    <td>{{ $setting->description }}</td>
                                    <td>
                                        <a href="{{ route('setting.aplikasi.edit', $setting->id) }}" title="Ubah" data-button="edit" data-testid="setting-edit-btn">
                                            <button type="button" class="btn btn-primary btn-xs" style="width: 40px;"><i class="fa fa-edit" aria-hidden="true"></i></button>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr data-testid="settings-empty">
                                    <td colspan="5">
                                        Data tidak tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
