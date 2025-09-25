<div class="box box-info">
    <div class="box-body">
        <!-- Filter Section -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-3">
                <select class="form-control" id="filter-category">
                    <option value="">Pilih Kategori</option>
                    @foreach ($activityCategories as $category)
                        <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="filter-event">
                    <option value="">Pilih Peristiwa</option>
                    @foreach ($activityEvents as $event)
                        <option value="{{ $event }}">{{ ucfirst($event) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="filter-user">
                    <option value="">Pilih Pengguna</option>
                    @foreach ($activityUsers as $user)
                        <option value="{{ $user['type'] }}|{{ $user['id'] }}">{{ $user['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 text-right">
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
                        <th>Aksi</th>
                        <th>Kategori</th>
                        <th>Peristiwa</th>
                        <th>Subjek Tipe</th>
                        <th>Penyebab Tipe</th>
                        <th>Pengguna</th>
                        <th>Deskripsi</th>
                        <th>Dibuat Pada</th>
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
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#detail-main" role="tab" data-toggle="tab">Informasi</a></li>
                    <li role="presentation"><a href="#detail-properties-tab" role="tab" data-toggle="tab">Detail Properti</a></li>
                </ul>

                <div class="tab-content" style="margin-top: 15px;">
                    <div role="tabpanel" class="tab-pane active" id="detail-main">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">Pengguna</th>
                                        <td id="detail-user"></td>
                                    </tr>
                                    <tr>
                                        <th>Aksi</th>
                                        <td id="detail-action"></td>
                                    </tr>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <td id="detail-description"></td>
                                    </tr>
                                    <tr>
                                        <th>Subjek</th>
                                        <td id="detail-status"></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <td id="detail-created-at"></td>
                                    </tr>
                                    <tr>
                                        <th>IP Address</th>
                                        <td id="detail-ip-address"></td>
                                    </tr>
                                    <tr>
                                        <th>User Agent</th>
                                        <td id="detail-user-agent"></td>
                                    </tr>
                                    <tr>
                                        <th>Lokasi</th>
                                        <td id="detail-location"></td>
                                    </tr>
                                    <tr>
                                        <th>Referer</th>
                                        <td id="detail-referer"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="detail-properties-tab">
                        <div id="detail-properties-section" style="display: none;">
                            <pre id="detail-properties" style="background: #f4f4f4; padding: 10px; border-radius: 4px;"></pre>
                        </div>
                        <p id="detail-properties-empty" class="text-muted" style="display:none;">Tidak ada properti tambahan.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
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
                d.category = $('#filter-category').val();
                d.event = $('#filter-event').val();
                d.user = $('#filter-user').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
            { data: 'category', name: 'log_name' },
            { data: 'event_badge', name: 'event' },
            { data: 'subject_type', name: 'subject_type' },
            { data: 'causer_type', name: 'causer_type' },
            { data: 'user_display', name: 'causer_id' },
            { data: 'description', name: 'description' },
            { data: 'formatted_date', name: 'created_at' }
        ],
        order: [[8, 'desc']],
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
        $('#filter-category').val('');
        $('#filter-event').val('');
        $('#filter-user').val('');
        table.ajax.reload();
    });

    // View detail functionality
    $(document).on('click', '.view-detail', function() {
        var logId = $(this).data('id');
        
        $.get('{{ route("setting.info-sistem.activity-logs.detail", ":id") }}'.replace(':id', logId))
            .done(function(response) {
                if (response.success) {
                    var data = response.data;

                    $('#detail-user').text(data.user || 'System');
                    $('#detail-action').text(data.event || '-');
                    $('#detail-description').text(data.description || '-');

                    var subjectInfo = data.subject_type
                        ? data.subject_type + (data.subject_id ? ' (ID: ' + data.subject_id + ')' : '')
                        : 'N/A';
                    $('#detail-status').text(subjectInfo);

                    $('#detail-created-at').text(data.created_at || '-');
                    $('#detail-ip-address').text(data.ip_address || 'N/A');
                    $('#detail-user-agent').text(data.user_agent || 'N/A');

                    var locationParts = [];
                    if (data.ip_city) locationParts.push(data.ip_city);
                    if (data.ip_region) locationParts.push(data.ip_region);
                    if (data.ip_country) {
                        var countryDisplay = data.ip_country;
                        if (data.ip_country_code) {
                            countryDisplay += ' (' + data.ip_country_code + ')';
                        }
                        locationParts.push(countryDisplay);
                    }

                    var locationText = locationParts.join(', ');
                    if (! locationText) {
                        if (data.ip_location_available === false && data.ip_location_message) {
                            locationText = data.ip_location_message;
                        } else {
                            locationText = 'Tidak tersedia';
                        }
                    }
                    $('#detail-location').text(locationText);

                    $('#detail-referer').text(data.referer || 'Tidak tersedia');

                    // switch to information tab by default
                    $('#detail-main').addClass('active');
                    $('#detail-properties-tab').removeClass('active');
                    $('a[href="#detail-main"]').parent().addClass('active');
                    $('a[href="#detail-properties-tab"]').parent().removeClass('active');

                    var extra = {
                        url: data.url || null,
                        slug: data.slug || null,
                        method: data.method || null,
                        browser: data.browser || null,
                        platform: data.platform || null,
                        causer_type: data.causer_type || null,
                        causer_id: data.causer_id || null,
                        category: data.category || null,
                        referer: data.referer || null,
                        ip_country: data.ip_country || null,
                        ip_region: data.ip_region || null,
                        ip_city: data.ip_city || null,
                        properties: data.properties || null,
                    };

                    // Remove empty values
                    Object.keys(extra).forEach(function(key) {
                        if (extra[key] === null || (typeof extra[key] === 'object' && extra[key] !== null && !Object.keys(extra[key]).length)) {
                            delete extra[key];
                        }
                    });

                    if (Object.keys(extra).length) {
                        $('#detail-properties-section').show();
                        $('#detail-properties').text(JSON.stringify(extra, null, 2));
                    } else {
                        $('#detail-properties-section').hide();
                        $('#detail-properties').text('');
                    }

                    $('#modal-detail-log').modal('show');
                }
            })
            .fail(function() {
                alert('Gagal memuat detail log');
            });
    });
});
</script>
@endpush
