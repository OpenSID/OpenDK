@extends('layouts.dashboard_template')

@section('title', 'Ganti Password')

@section('content')
    <!-- Content Header -->
    <section class="content-header block-breadcrumb">
        <h1>
            Ganti Password
            <small>Ubah password akun Anda</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Ganti Password</li>
        </ol>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-lock"></i> Ganti Password</h3>
                    </div>

                    <div class="box-body">
                        @include('partials.flash_message')

                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan!</h4>
                                <ul class="no-margin">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.password.update') }}" class="form-horizontal" autocomplete="off">
                            @csrf

                            <!-- Password Saat Ini -->
                            <div class="form-group {{ $errors->has('current_password') ? 'has-error' : '' }}">
                                <label for="current_password" class="col-sm-3 control-label">
                                    <i class="fa fa-key text-primary"></i> Password Saat Ini
                                </label>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control" id="current_password"
                                           name="current_password" placeholder="Masukkan password saat ini" required autofocus>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-default btn-sm toggle-password" data-target="current_password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>

                            <!-- Password Baru -->
                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label for="password" class="col-sm-3 control-label">
                                    <i class="fa fa-lock text-primary"></i> Password Baru
                                </label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" id="password"
                                           name="password" placeholder="Masukkan password baru (min. 8 karakter)" required>
                                    <!-- Password Strength Indicator -->
                                    <div class="progress password-strength mt-5" style="height: 5px; display: none;">
                                        <div class="progress-bar progress-bar-danger" style="width: 0%"></div>
                                    </div>
                                    <small class="help-block text-muted">
                                        Minimal 8 karakter.
                                        <span class="text-success" id="password-optional" style="display: none;">
                                            <i class="fa fa-check-circle"></i> Lebih aman dengan angka & simbol
                                        </span>
                                    </small>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-default btn-sm toggle-password" data-target="password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                <label for="password_confirmation" class="col-sm-3 control-label">
                                    <i class="fa fa-check-circle text-primary"></i> Konfirmasi Password
                                </label>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control" id="password_confirmation"
                                           name="password_confirmation" placeholder="Ulangi password baru" required>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-default btn-sm toggle-password" data-target="password_confirmation">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>

                            <!-- Buttons -->
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fa fa-save"></i> Simpan Password
                                    </button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-default btn-lg">
                                        <i class="fa fa-arrow-left"></i> Batal
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Box Footer with Info -->
                    <div class="box-footer">
                        <div class="callout callout-info callout-collapsed">
                            <h4><i class="fa fa-info-circle"></i> Tips Password</h4>
                            <ul class="no-margin">
                                <li><strong>Wajib:</strong> Minimal 8 karakter</li>
                                <li><strong>Disarankan:</strong> Gunakan kombinasi angka, huruf besar/kecil, dan simbol untuk keamanan lebih</li>
                                <li>Jangan gunakan password yang sama dengan akun lain</li>
                                <li>Ganti password secara berkala</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
(function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            var targetId = this.getAttribute('data-target');
            var input = document.getElementById(targetId);
            var icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Password strength checker
    var passwordInput = document.getElementById('password');
    var progressBar = document.querySelector('.password-strength');
    var progress = document.querySelector('.password-strength .progress-bar');
    var optionalHint = document.getElementById('password-optional');

    if (passwordInput && progressBar && progress) {
        passwordInput.addEventListener('input', function() {
            var password = this.value;

            // Show progress bar when typing
            if (password.length > 0) {
                progressBar.style.display = 'block';
            } else {
                progressBar.style.display = 'none';
                optionalHint.style.display = 'none';
                return;
            }

            // Calculate strength
            var strength = 0;

            // Length check (minimum 8)
            if (password.length >= 8) {
                strength += 40;
            } else if (password.length > 0) {
                strength += (password.length / 8) * 40;
            }

            // Has numbers (optional bonus)
            var hasNumber = /\d/.test(password);

            // Has special character (optional bonus)
            var hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

            // Has uppercase (optional bonus)
            var hasUpper = /[A-Z]/.test(password);

            // Has lowercase (optional bonus)
            var hasLower = /[a-z]/.test(password);

            // Add optional bonuses
            var optionalCount = 0;
            if (hasNumber) optionalCount++;
            if (hasSpecial) optionalCount++;
            if (hasUpper) optionalCount++;
            if (hasLower) optionalCount++;

            strength += (optionalCount / 4) * 60;

            // Update progress bar
            progress.style.width = Math.min(strength, 100) + '%';

            // Update color based on strength
            progress.classList.remove('progress-bar-danger', 'progress-bar-warning', 'progress-bar-success', 'progress-bar-primary');
            if (strength < 30) {
                progress.classList.add('progress-bar-danger');
            } else if (strength < 60) {
                progress.classList.add('progress-bar-warning');
            } else if (strength < 80) {
                progress.classList.add('progress-bar-primary');
            } else {
                progress.classList.add('progress-bar-success');
            }

            // Show optional hint when password is good
            if (password.length >= 8 && (hasNumber || hasSpecial || hasUpper)) {
                optionalHint.style.display = 'inline';
            } else {
                optionalHint.style.display = 'none';
            }
        });
    }

    // Password confirmation match indicator
    var confirm = document.getElementById('password_confirmation');

    if (confirm && passwordInput) {
        confirm.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
    }
})();
</script>
@endpush

@push('css')
<style>
.hr-line-dashed {
    border-top: 1px dashed #e7eaec;
    margin: 20px 0;
}

.mt-5 {
    margin-top: 5px;
}

.toggle-password {
    padding: 6px 12px;
}

.toggle-password:hover {
    background-color: #e7eaec;
}

.form-group.has-error .control-label {
    color: #dd4b39;
}

.form-group.has-error .form-control {
    border-color: #dd4b39;
}

.callout-collapsed {
    padding: 15px;
    margin-bottom: 0;
    border-left: 4px solid #00a7d0;
}

.callout-collapsed h4 {
    margin-top: 0;
    margin-bottom: 10px;
}

.callout-collapsed ul {
    padding-left: 20px;
}

.no-margin {
    margin: 0;
}

.btn-primary.btn-lg {
    padding: 12px 24px;
}

.btn-default.btn-lg {
    padding: 12px 24px;
}
</style>
@endpush
