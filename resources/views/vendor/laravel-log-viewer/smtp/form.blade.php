<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="provider">Penyedia Server</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->select('provider', ['smtp' => 'SMTP', 'webmail' => 'Webmail', 'google' => 'Google'])->class('form-control')->value($email_smtp->provider) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Host Server</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('host')->value($email_smtp->host)->class('form-control')->placeholder('Host Server')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Port</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('port')->value($email_smtp->port)->class('form-control')->placeholder('Port')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Username</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('username')->value($email_smtp->username)->class('form-control')->placeholder('Username')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Password</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('password')->value($email_smtp->password)->class('form-control password')->placeholder('Password')->required() !!}
    </div>
    <div class="col-md-1 col-sm-1 col-xs-12">
        <button type="button" class="btn showpass"><i class="fa fa-eye" aria-hidden="true"></i></button>
    </div>
</div>
@if ($email_smtp->host)
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Email Tujuan (Tes Email)</label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! html()->text('testing_mail')->class('form-control')->id('testing_mail')->placeholder('Email Tujuan (Tes Email)') !!}
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
