@include('partials.asset_sweetalert')
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('select[name=value]').on('change', function() {
                const val = $(this).val();
                const elm = $(this)
                if (val == 0) return;
                Swal.fire({
                    title: 'Lakukan test kirim email untuk mengaktifkan login dengan 2FA ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: 'Tidak!',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch(`{{ route('testEmail') }}`)
                            .then(response => {
                                if (!response.ok) {
                                    elm.val(0);
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
                    if (!result.isConfirmed) {
                        elm.val(0);
                    }
                });
            });
        });
    </script>
@endpush
