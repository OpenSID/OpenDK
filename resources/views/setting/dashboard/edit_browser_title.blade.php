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

<section class="content container-fluid">
    @include('partials.flash_message')

    <section class="content">
        <div class="row">
            <div class="box box-primary">
                <div class="box-body">
                <form action="{{ route('setting.dashboard.update_browser_title') }}" method="POST">
                    @method("PUT")
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="default_title">Judul Standar</label>
                        <input type="text" class="form-control" disabled id="default_title" aria-describedby="defaultTitle" value="{{$default_browser_title}}">
                        <small id="default_title" class="form-text text-muted">Judul default beranda, tidak bisa dirubah.</small>
                    </div>
                    <div class="form-group">
                        <label for="custom_title">Judul Non Standar</label>
                        <input type="text" value="{{ $custom_browser_title }}" class="form-control" name="custom_title" id="custom_title" placeholder="Ubah judul beranda disini...">
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="use_custom_title" class="form-check-input" id="checkCustomBrowserTitle" {{ $custom_browser_title ? 'checked' : 'unchecked'}}>
                        <label class="form-check-label" for="checkCustomBrowserTitle">Gunakan judul non standar</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
                </div>
            </div>
        </div>
    </section>

</section>
<!-- /.content -->
@endsection

