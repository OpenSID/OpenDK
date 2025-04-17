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
                <th>Tanda Tangan</th>
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
                url: "{{ route('data.pengurus.arsip', ['pengurus_id' => $pengurus_id]) }}",
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
                { data: 'jenis_document.nama', name: 'jenis_document.nama' },
                { data: 'penduduk.nama', name: 'penduduk.nama' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'pengurus.nama', name: 'pengurus.nama' },
            ],
            columnDefs: [
                {
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
