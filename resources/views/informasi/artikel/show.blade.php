@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        Artikel
        <small>detail artikel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{route('informasi.artikel.index')}}">artikel</a></li>
        <li class="active">detail artikel</li>
    </ol>
</section>

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Nama artikel</label>
                        <div class="col-sm-10">
                            {{ $article->title }}
                       </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Gambar</label>
                        <div class="col-sm-10">
                            <img src="{{ url($path) }}" alt="">
                       </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <div style="height:150px;overflow-y: scroll;"> {!! $article->description !!}</div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="pull-right">
                        <div class="control-group">
                            <a href="{{ route('informasi.artikel.index') }}">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Kembali</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection