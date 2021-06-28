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
        <li><a href="{{route('setting.aplikasi.index')}}">Setting Aplikasi</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<section class="content container-fluid">
    @include('partials.flash_message')

    <section class="content">
        <div class="row">
            <div class="box box-primary">
                <div class="box-body">
                <form action="{{ route('setting.aplikasi.update', $setting->id) }}" method="POST">
                    @method("PUT")
                    {{ csrf_field() }}
                    @if($setting->isBrowserTitle())
                    <div class="form-group">
                        <label for="input_key">{{ $setting->description }}</label>
                        <input type="text" name="input_key" class="form-control" id="input_key" aria-describedby="input_key" value="{{$setting->value ?? $default_browser_title}}">
                        <small id="default_title" class="form-text text-muted">Jika kosong judul akan dirubah menjadi judul standar "{{$default_browser_title}}".</small>
                    </div>
                    @else
                    <div class="form-group">
                        <label for="value">Value</label>
                        @if($setting->type == 'input')
                        <input type="text" name="value" class="form-control" id="value" aria-describedby="value" value="{{$setting->value ?? old('value')}}">
                        @elseif($setting->type == 'textarea')
                        <textarea name="value" class="form-control" id="value" aria-describedby="value">{{ $setting->value ?? old('value') }}</textarea>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control">
                            <option value="input">input</option>
                            <option value="textarea">textarea</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="option">Options</label>
                        <textarea name="option" class="form-control" id="option" aria-describedby="option">{{ $setting->option ?? old('option') }}</textarea>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
                </div>
            </div>
        </div>
    </section>

</section>
<!-- /.content -->
@endsection

