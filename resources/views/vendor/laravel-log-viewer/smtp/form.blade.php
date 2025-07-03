<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="provider">Penyedia Server</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('provider', ['smtp' => 'SMTP', 'webmail' => 'Webmail', 'google' => 'Google'], $email_smtp->provider, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Host Server</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('host', $email_smtp->host, [
            'placeholder' => 'Host Server',
            'class' => 'form-control',
            'required' => true,
        ]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Port</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('port', $email_smtp->port, [
            'placeholder' => 'Port',
            'class' => 'form-control',
            'required' => true,
        ]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Username</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('username', $email_smtp->username, [
            'placeholder' => 'Username',
            'class' => 'form-control',
            'required' => true,
        ]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Password</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('password', $email_smtp->password, [
            'placeholder' => 'Password',
            'class' => 'form-control password',
            'required' => true,
        ]) !!}
    </div>
    <div class="col-md-1 col-sm-1 col-xs-12">
        <button type="button" class="btn showpass"><i class="fa fa-eye" aria-hidden="true"></i></button>
    </div>
</div>
@if ($email_smtp->host)
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Email Tujuan (Tes Email)</label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('testing_mail', null, [
                'placeholder' => 'Email Tujuan (Tes Email)',
                'class' => 'form-control',
                'id' => 'testing_mail',
            ]) !!}
        </div>
    </div>
@endif
<div class="ln_solid"></div>

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\EmailSmtpRequest', '#form-smtp') !!}
    <script type="text/javascript">
        $('.password').attr('type', 'password');
        $('.showpass').hover(function() {
            $('.password').attr('type', 'text');
        }, function() {
            $('.password').attr('type', 'password');
        });
    </script>
@endpush
