<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Pengaturan SMTP</h3>
            </div>
            {!! Form::open([
                'route' => 'setting.info-sistem.store-email-smtp',
                'method' => 'post',
                'id' => 'form-smtp',
                'class' => 'form-horizontal form-label-left',
            ]) !!}
            <div class="box-body">
                @include('vendor.laravel-log-viewer.smtp.form')
            </div>
            <div class="box-footer pull-right">
                @if ($email_smtp->host)
                    <a class="btn btn-danger btn-sm" id="test-smtp"><i class="fa fa-envelope-o"></i> Tes
                        Email</a>
                @endif
                <button type="submit" id="store-smtp" class="btn btn-primary btn-sm" id><i class="fa fa-save"></i>
                    Simpan</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#store-smtp').click(function() {
                return confirm('Yakin Untuk Mengganti SMTP?');
            });
            $('#test-smtp').click(function() {
                //get testing email data
                var email = $("#testing_mail").val();
                if (email) {
                    $.ajax({
                        type: "POST",
                        url: "{{ URL('setting/info-sistem/send-test-email-smtp') }}/" + email,
                        beforeSend: function() {
                            Swal.showLoading()
                        },
                        success: function(data) {
                            console.log(data);
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Berhasil mengirim email testing',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Gagal mengirim email testing',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    });
                }
            });
        });
    </script>
@endpush
