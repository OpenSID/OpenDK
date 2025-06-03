<div x-data="{ isOnline: navigator.onLine }" x-init="window.addEventListener('online', () => {
    isOnline = true;
});

window.addEventListener('offline', () => {
    isOnline = false;
});">
    <template x-if="!isOnline">
        <div class="box box-danger">
            <div class="box-header with-border">
                <i class="icon fa fa-ban"></i>
                <h3 class="box-title">Tidak Terhubung Dengan Jaringan</h3>
            </div>
            <div class="box-body">
                <div class="callout callout-danger">
                    <h5>Data Gagal Dimuat, Harap Periksa Jaringan Anda Telebih Dahulu.</h5>
                </div>
            </div>
        </div>
    </template>

    <div x-if="isOnline">
        {{ $slot }}
    </div>

</div>
