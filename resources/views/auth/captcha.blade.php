<div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
    <div class="col-auto">
        <div class="captcha" style="margin-bottom: 10px;">
            <span>{!! captcha_img('mini') !!}</span>
            <button type="button" class="btn btn-success btn-refresh"><i class="fa fa-refresh"></i></button>
        </div>
        <input id="captcha" type="text" class="form-control" required placeholder="Masukkan Kode Verifikasi" name="captcha">
        @if ($errors->has('captcha'))
            <span class="help-block">
                <strong>{{ $errors->first('captcha') }}</strong>
            </span>
        @endif
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(function() {
            $(".btn-refresh").click(function() {
                $.ajax({
                    type: 'GET',
                    url: '/refresh-captcha',
                    success: function(data) {
                        $(".captcha span").html(data.captcha);
                    }
                });
            });

        })
    </script>
@endpush
