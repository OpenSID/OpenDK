<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
        <form action="{{ route('ppid.jenis-dokumen.store') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Tambah Jenis Dokumen PPID</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="nama">Nama Jenis Dokumen</label>
                        <input type="text" id="nama" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea id="keterangan" class="form-control" name="keterangan" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="urutan">Urutan</label>
                        <input type="number" id="urutan" class="form-control" name="urutan" value="0" min="0">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" class="form-control" name="status">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
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
                        $('#data_ppid_jenis_dokumen').DataTable().ajax.reload();

                        $('#flash-message').html(
                            `
                        <div id="notifikasi" class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-check"></i> Sukses!</h4>
                            <p>${response.message}</p>
                        </div>
                        `
                        );
                    },
                    error: function(xhr) {
                        errorValidation(xhr);
                    }
                });
            });

            $(document).on('click', '.open_form', function() {
                const id = $(this).data('id');
                const routeEdit = '{{ route('ppid.jenis-dokumen.edit', ':id') }}'.replace(':id', id);
                const routeUpdate = '{{ route('ppid.jenis-dokumen.update', ':id') }}'.replace(':id', id);

                $.get(routeEdit)
                    .done(data => {
                        $('#id').val(data.id);
                        $('#nama').val(data.nama);
                        $('#keterangan').val(data.keterangan);
                        $('#urutan').val(data.urutan);
                        $('#status').val(data.status ? '1' : '0');
                        $('#modal-form').modal('show');
                        $('#modal-form .modal-title').text('Ubah Jenis Dokumen PPID');
                        $('#modal-form form').attr('action', routeUpdate);
                    })
                    .fail(() => {

                        $('#flash-message').html(
                            `
                        <div id="notifikasi" class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-check"></i> Gagal!!</h4>
                            <p>Gagal memuat data</p>
                        </div>
                        `
                        );

                    });
            });
        });
    </script>
@endpush
