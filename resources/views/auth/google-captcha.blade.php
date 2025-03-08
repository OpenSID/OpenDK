{!! RecaptchaV3::initJs() !!}
<div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
    <div class="col-md-6">
        {!! RecaptchaV3::field('login') !!}
        @if ($errors->has('g-recaptcha-response'))
            <span class="help-block">
                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
            </span>
        @endif
        <div class="recaptcha-container" style="position: relative;height: 80px;"></div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('.grecaptcha-badge').appendTo('.recaptcha-container');
            $('.grecaptcha-badge').css({
                'position': 'absolute',
                'top': '0',
                'left': '-15px'
            });
        })
    </script>
@endpush
