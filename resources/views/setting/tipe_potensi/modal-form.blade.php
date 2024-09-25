<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
        <form action="{{ route('setting.tipe-potensi.store') }}" method="post">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tambah Kategori Potensi</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" id="nama_kategori" class="form-control" name="nama_kategori">
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger pull-left" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
        </form>
    </div>
</div>
@include('partials.asset_sweetalert')
@push('scripts')
<script>
    $(function() {
        $('#modal-form').on('click', 'button[type="submit"]', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            var id = form.find('input[name="id"]').val();
            var url = form.attr('action');
            var method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: form.serialize(),
                success: function(response) {
                    $('#modal-form').modal('hide');
                    form[0].reset();
                    $('#data_tipe_potensi').DataTable().ajax.reload();

                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500
                    });
                },
                error: function(xhr) {
                    errorValidation(xhr);
                }
            });
        });

        $(document).on('click', '.open_form', function() {
            const id = $(this).data('id');
            const routeEdit = '{{ route('setting.tipe-potensi.edit', ':id') }}'.replace(':id', id);
            const routeUpdate = '{{ route('setting.tipe-potensi.update', ':id') }}'.replace(':id', id);

            $.get(routeEdit)
                .done(data => {
                    $('#id').val(data.id);
                    $('#nama_kategori').val(data.nama_kategori);
                    $('#modal-form').modal('show');
                    $('#modal-form .modal-title').text('Ubah Kategori Potensi');
                    $('#modal-form form').attr('action', routeUpdate);
                })
                .fail(() => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal memuat data.',
                        icon: 'error',
                        timer: 1500,
                        showConfirmButton: false
                    });
                });
        });
    });
</script>
@endpush