@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.final.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.final.title') }}
@endsection

@section('container')
    @if(isset($error))
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
            <strong>Error saat instalasi:</strong> {{ $error }}
        </div>
        <div class="buttons">
            <a href="{{ route('installer.database') }}" class="button">
                <i class="fa fa-angle-left fa-fw" aria-hidden="true"></i>
                Kembali ke Database
            </a>
        </div>
    @elseif(isset($success))
        <div class="alert alert-success">
            <i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>
            <strong>{{ trans('installer_messages.final.finished') }}</strong>
        </div>

        @if(isset($migrationOutput) && $migrationOutput)
            <p><strong><small>{{ trans('installer_messages.final.migration') }}</small></strong></p>
            <pre><code>{{ $migrationOutput }}</code></pre>
        @endif

        <p><strong><small>{{ trans('installer_messages.final.log') }}</small></strong></p>
        <pre><code>{{ sprintf('%s berhasil DIPASANG pada %s', config('app.name', 'OpenDK'), now()) }}</code></pre>

        <div class="buttons">
            <a href="{{ url('/') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
        </div>
    @else
        <p>{{ trans('installer_messages.final.finished') }}</p>
        <div class="buttons">
            <a href="{{ url('/') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
        </div>
    @endif
@endsection
