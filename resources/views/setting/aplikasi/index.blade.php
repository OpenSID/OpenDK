@extends('layouts.dashboard_template')


@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_title ?? '' }}</li>
    </ol>
</section>

<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="user-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Isi</th>
                            <th>Deskripsi</th>
                            <th style="max-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settings as $setting) 
                        <tr>
                            <td>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</td>
                            <td>{{ $setting->value }}</td>
                            <td>{{ $setting->description }}</td>
                            <td>
                                <a href="{{ route('setting.aplikasi.edit', $setting->id)}}" title="Ubah" data-button="edit">
                                    <button type="button" class="btn btn-primary btn-xs" style="width: 40px;"><i class="fa fa-edit" aria-hidden="true"></i></button>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
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

