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
                <form action="{{ route('setting.aplikasi.update_browser_title') }}" method="POST">
                    @method("PUT")
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="inputTitle">Judul Aplikasi</label>
                        <input type="text" name="title" class="form-control" id="inputTitle" aria-describedby="inputTitle" value="{{$browser_title}}">
                        <small id="default_title" class="form-text text-muted">Jika kosong judul akan dirubah menjadi judul standar "{{$default_browser_title}}".</small>
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

