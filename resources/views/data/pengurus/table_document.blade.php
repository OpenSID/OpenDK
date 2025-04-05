<div class="table-responsive">
    <table class="table table-striped table-bordered" id="document-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Aksi</th>
                <th>Kode Surat</th>
                <th>Judul Surat</th>
                <th>Path</th>
                <th>Nomor Urut</th>
                <th>Jenis Surat</th>
                <th>Nama Penduduk</th>
                <th>Keterangan</th>
                <th>Di Tanda Tangan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
    </table>
</div>

@include('partials.asset_datatables')

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        var data = $('#document-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{!! route('data.pengurus.document') !!}",
                data: function(d) {
                    console.log(d);
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'aksi', name: 'aksi', class: 'text-center', searchable: false, orderable: false },
                { data: 'kode_surat', name: 'kode_surat' },
                { data: 'judul_document', name: 'judul_document' },
                { data: 'path_document', name: 'path_document', orderable: false, searchable: false },
                { data: 'no_urut', name: 'no_urut' },
                { data: 'jenis_surat', name: 'jenis_surat' },
                { data: 'user.name', name: 'user.name' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'ditandatangani', name: 'ditandatangani' },
                { data: 'tanggal', name: 'tanggal' },
            ],
            columnDefs: [
                {
                    // Ganti index 4 sesuai urutan kolom "Path"
                    targets: 4,
                    render: function(data, type, row, meta) {
                        if (data && data.includes('documents/')) {
                            return `<a href="/storage/${data}" target="_blank" class="btn btn-sm btn-primary">Download</a>`;
                        }
                        return `<span class="text-muted">Tidak ada file</span>`;
                    }
                }
            ],
            aaSorting: [],
        });

        $('#status').on('change', function(e) {
            data.ajax.reload();
        });
    });
</script>

@include('forms.datatable-vertical')
@include('forms.delete-modal')
@include('forms.suspend-modal')
@include('forms.active-modal', ['title' => $page_title])
@endpush
