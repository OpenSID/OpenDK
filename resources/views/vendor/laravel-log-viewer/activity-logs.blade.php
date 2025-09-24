<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Log Aktivitas</h3>
        <div class="box-tools pull-right">
                        <button type="button" class="btn btn-danger" id="cleanup-logs">
                <i class="fa fa-trash"></i> Bersihkan Log Lama
            </button>
        </div>
    </div>
    
    <div class="box-body">
        <!-- Filter Section -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-3">
                <select class="form-control" id="filter-action">
                    <option value="">Semua Event</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                    <option value="created">Created</option>
                    <option value="updated">Updated</option>
                    <option value="deleted">Deleted</option>
                    <option value="retrieved">Retrieved</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" id="filter-date-from" placeholder="Tanggal Dari">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" id="filter-date-to" placeholder="Tanggal Sampai">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary" id="apply-filter">
                    <i class="fa fa-search"></i> Filter
                </button>
                <button type="button" class="btn btn-default" id="reset-filter">
                    <i class="fa fa-refresh"></i> Reset
                </button>
            </div>
        </div>

        <!-- DataTable -->
        <div class="table-responsive">
            <table id="activity-logs-table" class="table table-bordered table-striped" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengguna</th>
                        <th>Event</th>
                        <th>Deskripsi</th>
                        <th>Subject</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Log -->
<div class="modal fade" id="modal-detail-log" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Detail Log Aktivitas</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Pengguna:</strong>
                        <p id="detail-user"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Aksi:</strong>
                        <p id="detail-action"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <strong>Deskripsi:</strong>
                        <p id="detail-description"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Subject Type:</strong>
                        <p id="detail-ip"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Subject ID:</strong>
                        <p id="detail-status"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <strong>Tanggal:</strong>
                        <p id="detail-created-at"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>IP Address:</strong>
                        <p id="detail-ip-address"></p>
                    </div>
                    <div class="col-md-8">
                        <strong>User Agent:</strong>
                        <p id="detail-user-agent"></p>
                    </div>
                </div>
                <div class="row" id="detail-properties-section" style="display: none;">
                    <div class="col-md-12">
                        <strong>Detail Perubahan:</strong>
                        <pre id="detail-properties" style="background: #f4f4f4; padding: 10px; border-radius: 4px;"></pre>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cleanup -->
<div class="modal fade" id="modal-cleanup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Bersihkan Log Aktivitas</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Hapus log yang lebih dari:</label>
                    <select class="form-control" id="cleanup-days">
                        <option value="7">7 hari</option>
                        <option value="30" selected>30 hari</option>
                        <option value="60">60 hari</option>
                        <option value="90">90 hari</option>
                    </select>
                </div>
                <div class="alert alert-warning">
                    <i class="fa fa-warning"></i> 
                    Tindakan ini tidak dapat dibatalkan. Log yang dihapus tidak dapat dikembalikan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning" id="confirm-cleanup">Hapus Log</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
        
    // Initialize DataTable
    var table = $('#activity-logs-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("setting.info-sistem.activity-logs.data") }}',
            data: function(d) {
                d.action = $('#filter-action').val();
                d.date_from = $('#filter-date-from').val();
                d.date_to = $('#filter-date-to').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'user_display', name: 'causer_id' },
            { data: 'action_badge', name: 'event' },
            { data: 'description', name: 'description' },
            { data: 'subject_display', name: 'subject_type' },
            { data: 'formatted_date', name: 'created_at' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ],
        order: [[5, 'desc']],
        pageLength: 25,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        }
    });

    
    // Filter functionality
    $('#apply-filter').click(function() {
        table.ajax.reload();
    });

    $('#reset-filter').click(function() {
        $('#filter-action').val('');
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        table.ajax.reload();
    });

    // View detail functionality
    $(document).on('click', '.view-detail', function() {
        var logId = $(this).data('id');
        
        $.get('{{ route("setting.info-sistem.activity-logs.detail", ":id") }}'.replace(':id', logId))
            .done(function(response) {
                if (response.success) {
                    var data = response.data;
                    $('#detail-user').text(data.user);
                    $('#detail-action').text(data.event);
                    $('#detail-description').text(response.description);
                    $('#detail-status').text(response.subject_type ? (response.subject_type + ' (ID: ' + response.subject_id + ')') : 'N/A');
                    $('#detail-created-at').text(new Date(response.created_at).toLocaleString('id-ID'));

                    // Populate new fields from properties
                    // Safely get properties, default to an empty object if it's null/undefined
                    var properties = response.properties || {};
                    $('#detail-ip-address').text(properties.ip_address || 'N/A');
                    $('#detail-user-agent').text(properties.user_agent || 'N/A');

                    if (data.properties && Object.keys(data.properties).length > 0) {
                        $('#detail-properties').text(JSON.stringify(data.properties, null, 2));
                        $('#detail-properties-section').show();
                    } else {
                        $('#detail-properties-section').hide();
                    }
                    
                    $('#modal-detail-log').modal('show');
                }
            })
            .fail(function() {
                alert('Gagal memuat detail log');
            });
    });

    // Cleanup functionality
    $('#cleanup-logs').click(function() {
        $('#modal-cleanup').modal('show');
    });

    $('#confirm-cleanup').click(function() {
        var days = $('#cleanup-days').val();
        
        $.post('{{ route("setting.info-sistem.activity-logs.cleanup") }}', {
            days: days,
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                alert(response.message);
                table.ajax.reload();
                $('#modal-cleanup').modal('hide');
            } else {
                alert(response.message);
            }
        })
        .fail(function() {
            alert('Gagal membersihkan log');
        });
    });
});
</script>
@endpush
