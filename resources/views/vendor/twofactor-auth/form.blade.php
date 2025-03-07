@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">@lang('twofactor-auth::twofactor-auth.title')</div>

                <div class="card-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('auth.token') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="token" class="col-md-4 col-form-label text-md-right">@lang('twofactor-auth::twofactor-auth.label')</label>

                            <div class="col-md-6">
                                <input id="token" type="text" autocomplete="one-time-code" class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" name="token" value="{{ old('token') }}" required autofocus>

                                @if ($errors->has('token'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('token') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('twofactor-auth::twofactor-auth.send')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
