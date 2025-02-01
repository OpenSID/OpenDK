@include('partials.asset_sweetalert')
<div style="margin-top: 5px;">
    <button id="btn-token" type="button" class="btn btn-success btn-sm btn-social" title="Buat Token Baru">
        <i class="fa fa-key"></i>Buat Token Baru
    </button>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#btn-token').on('click', function() {
                Swal.fire({
                    title: 'Apakah anda yakin ingin membuat token baru?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: 'Tidak!',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch(`{{ route('setting.aplikasi.token') }}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(response.statusText)
                                }
                                return response.json()
                            })
                            .catch(error => {
                                Swal.showValidationMessage(
                                    `Request failed: ${error}`
                                )
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'Sukses!',
                            'Token berhasil dibuat',
                            'success'
                        ).then(() => {
                            $('#value').val(result.value.token);
                        });
                    }
                });
            });
        });
    </script>
@endpush
