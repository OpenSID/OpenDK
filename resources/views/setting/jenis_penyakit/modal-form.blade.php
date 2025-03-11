<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
        <form action="{{ route('setting.jenis-penyakit.store') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Tambah Kategori Komplain</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="nama">Nama Jenis Penyakit</label>
                        <input type="text" id="nama" class="form-control" name="nama">
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
                        $('#data-penyakit').DataTable().ajax.reload();

                        $('#flash-message').html(
                            `<div id="notifikasi" class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-check"></i> Sukses!</h4>
                            <p>${response.message}</p>
                        </div>
                        `);
                    },
                    error: function(xhr) {
                        errorValidation(xhr);
                    }
                });
            });

            $(document).on('click', '.open_form', function() {
                const id = $(this).data('id');
                const routeEdit = '{{ route('setting.jenis-penyakit.edit', ':id') }}'.replace(':id', id);
                const routeUpdate = '{{ route('setting.jenis-penyakit.update', ':id') }}'.replace(':id', id);

                $.get(routeEdit)
                    .done(data => {
                        $('#id').val(data.id);
                        $('#nama').val(data.nama);
                        $('#modal-form').modal('show');
                        $('#modal-form .modal-title').text('Ubah Jenis Penyakit');
                        $('#modal-form form').attr('action', routeUpdate);
                    })
                    .fail(() => {
                        $('#flash-message').html(
                            ` <div id="notifikasi" class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-check"></i> Gagal!!</h4>
                            <p>Gagal memuat data</p>
                        </div>
                        `);
                    });
            });
        });
    </script>
@endpush
