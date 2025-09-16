@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.environment.classic.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-code fa-fw" aria-hidden="true"></i> {{ trans('installer_messages.environment.classic.title') }}
@endsection

@section('container')
    <form method="post" action="{{ route('installer.environmentSaveClassic') }}">
        {!! csrf_field() !!}
        <textarea class="textarea" name="envConfig">{{ $envConfig }}</textarea>
        <div class="buttons buttons--right">
            <button class="button button--light" type="submit">
                <i class="fa fa-floppy-o fa-fw" aria-hidden="true"></i>
                {!! trans('installer_messages.environment.classic.save') !!}
            </button>
        </div>

    @if (!isset($environment['errors']))
        <div class="buttons-container">
            <a class="button float-left" href="{{ route('installer.environmentWizard') }}">
                <i class="fa fa-sliders fa-fw" aria-hidden="true"></i>
                {!! trans('installer_messages.environment.classic.back') !!}
            </a>
            <button type="submit" class="button float-right" style="font-size: 12px">
                <i class="fa fa-check fa-fw" aria-hidden="true"></i>
                {!! trans('installer_messages.environment.classic.install') !!}
                <i class="fa fa-angle-double-right fa-fw" aria-hidden="true"></i>
            </button>
        </div>
    @endif
    </form>

@endsection