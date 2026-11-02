<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <div class="p-3">
                    <form method="POST" action="{{ route('setting.info-sistem.linkstorage') }}" class="d-inline" id="form-linkstorage">
                        @csrf
                        <button type="button" class="btn btn-social btn-sm btn-info visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" id="run-linkstorage">
                            <span class="fa fa-play-circle"></span> Jalankan php artisan storage:link
                        </button>
                    </form>
                    <a class="btn btn-social btn-sm btn-info visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" id="run-queue">
                        <span class="fa fa-play-circle"></span> Jalankan php artisan queue:listen
                    </a>
                    <form method="POST" action="{{ route('setting.info-sistem.migrasi') }}" class="d-inline" id="form-migrasi">
                        @csrf
                        <button type="button" class="btn btn-social btn-sm btn-info visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" id="run-migrasi">
                            <span class="fa fa-play-circle"></span> Jalankan migrasi
                        </button>
                    </form>
                </div>
            </div>
            <p id="#ajaxProgress" style="display: none;">asdasd</p>
            <div class="box-body">
                @foreach ($requirements['requirements'] as $type => $requirement)
                    <div class="form-group">
                        <h5><i class="fa fa-{{ $phpSupportInfo['supported'] ? 'check-circle-o' : 'remove-o' }} fa-lg" style="color:{{ $phpSupportInfo['supported'] ? 'green' : 'red' }}"></i> {{ ucfirst($type) }}
                            @if ($type == 'php')
                                <small><strong>(Versi yang dibutuhkan Php {{ $phpSupportInfo['minimum'] }})</strong></small>
                                <span class="float-right">
                                    <small>Versi Yang Terpasang Php <strong>{{ $phpSupportInfo['current'] }}</strong></small>
                                </span>
                            @endif
                        </h5>
                    </div>

                    @foreach ($requirements['requirements'][$type] as $extention => $enabled)
                        <div class="form-group">
                            <h5><i class="fa fa-{{ $enabled ? 'check-circle-o' : 'remove-o' }} fa-lg" style="color:{{ $enabled ? 'green' : 'red' }}"></i> {{ $extention }}</h5>
                        </div>
                        </li>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>

@include('partials.asset_sweetalert')

@push('scripts')
    <script>
        $(document).on('click', '#run-linkstorage', function(e) {
            e.preventDefault();
            openConfirm('Yakin Untuk Menjalankan php artisan storage:link?', 'Konfirmasi', function() {
                $('#form-linkstorage')[0].submit();
            });
        });

        $(document).on('click', '#run-migrasi', function(e) {
            e.preventDefault();
            $('#form-migrasi')[0].submit();
        });

        $(document).on('click', '#run-queue', function(e) {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                url: "{{ route('setting.info-sistem.queuelisten') }}",
                beforeSend: function() {
                    Swal.showLoading()
                },
                success: function(data) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Perintah berhasil dijalankan',
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Perintah gagal dijalankan',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            });
        });
    </script>
@endpush
