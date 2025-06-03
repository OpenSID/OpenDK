<div class="table-responsive">
    <table class="table table-striped table-bordered" id="document-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Aksi</th>
                <th>Nama Dokumen</th>
                <th>Jenis Dokumen</th>
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
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'judul_document',
                        name: 'judul_document'
                    },
                    {
                        data: 'jenis_surat.nama',
                        name: 'jenis_surat.nama'
                    },
                ],
                aaSorting: [],
            });

            $('#status').on('change', function(e) {
                data.ajax.reload();
            });
        });
    </script>
@endpush
