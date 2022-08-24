<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <div class="p-3">
                    <a class="btn btn-social btn-sm btn-info visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" id="run-linkstorage" href="{{ URL('setting/info-sistem/linkstorage') }}">
                        <span class="fa fa-play-circle"></span> Jalankan php artisan storage:link
                    </a>
                    <a class="btn btn-social btn-sm btn-info visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" id="run-queue">
                        <span class="fa fa-play-circle"></span> Jalankan php artisan queue:listen
                    </a>
                </div>
            </div>
            <div class="box-body">
                @foreach($requirements['requirements'] as $type => $requirement)
                <div class="form-group">
                    <h5><i class="fa fa-{{ $phpSupportInfo['supported'] ? 'check-circle-o' : 'remove-o' }} fa-lg" style="color:{{ $phpSupportInfo['supported'] ? 'green' : 'red' }}"></i> {{ ucfirst($type) }}
                    @if($type == 'php')
                        <small><strong>(Versi yang dibutuhkan Php {{ $phpSupportInfo['minimum'] }})</strong></small>
                        <span class="float-right">
                            <small>Versi Yang Terpasang Php <strong>{{ $phpSupportInfo['current'] }}</strong></small>
                        </span>
                    @endif
                    </h5>
                </div>
                    
                @foreach($requirements['requirements'][$type] as $extention => $enabled)
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

<div class="modal fade" id='loading' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-warning">
                <h4 class="modal-title">Proses Queue Work</h4>
            </div>
            <div class="modal-body">
                Harap tunggu sampai proses selesai. Proses ini bisa memakan waktu beberapa menit tergantung data yang dikirmkan.
                <div class='text-center'>
                    <img src="{{ '../img/loading.gif' }}">
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.asset_sweetalert')

@push('scripts')
<script>
    $(document).on('click', '#run-queue', function(e) {
        $.ajax({
            type: "GET",
            url: "{{ URL('setting/info-sistem/queuelisten') }}",
            dataType: "Json"
        });
    });

    $(document)
        .ajaxStart(function () {
            Swal.showLoading()
        })
        .ajaxStop(function () {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Perintah berhasil dijalankan',
                showConfirmButton: false,
                timer: 1500
            })
    });
</script>
@endpush