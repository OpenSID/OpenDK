<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Riwayat Aktivitas</h3>
        <div class="box-tools">
            <button class="btn btn-default btn-sm" wire:click="resetFilters">Reset Filter</button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Dari Tanggal</label>
                    <input type="date" class="form-control" wire:model.live="dateFrom">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" class="form-control" wire:model.live="dateTo">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Pengguna</label>
                    <select class="form-control" wire:model.live="userId">
                        <option value="">Semua Pengguna</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Jenis Aktivitas</label>
                    <select class="form-control" wire:model.live="event">
                        <option value="">Semua Aktivitas</option>
                        @foreach ($events as $event)
                            <option value="{{ $event }}">{{ $event }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Cari Kata Kunci</label>
                    <input type="text" class="form-control" wire:model.live="keyword" placeholder="Cari deskripsi atau URL...">
                </div>
            </div>
        </div>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aktivitas</th>
                    <th>Deskripsi</th>
                    <th>IP Address</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                    <tr>
                        <td>{{ $activity->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $activity->causer->name ?? 'Sistem' }}</td>
                        <td>{{ $activity->event }}</td>
                        <td>{{ $activity->description }}</td>
                        <td>{{ $activity->properties['ip_address'] ?? '-' }}</td>
                        <td>
                            @if ($this->isActivityFailed($activity))
                                <span class="label label-danger">Gagal</span>
                            @else
                                <span class="label label-success">Berhasil</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-xs btn-info" wire:click="showDetail({{ $activity->id }})">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data aktivitas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="box-footer">
        {{ $activities->links() }}
    </div>

    @if($selectedActivity)
    <div class="modal-overlay" wire:click="closeDetail"></div>
    <div class="modal-simple">
        <div class="modal-simple-content">
            <div class="modal-simple-header">
                <h5 class="modal-title">Detail Aktivitas</h5>
                <button type="button" wire:click="closeDetail">&times;</button>
            </div>
            <div class="modal-simple-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Waktu</th>
                        <td>{{ $selectedActivity->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Pengguna</th>
                        <td>{{ $selectedActivity->causer->name ?? 'Sistem' }} ({{ $selectedActivity->causer->email ?? '-' }})</td>
                    </tr>
                    <tr>
                        <th>Aktivitas</th>
                        <td>{{ $selectedActivity->event }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $selectedActivity->description }}</td>
                    </tr>
                    <tr>
                        <th>IP Address</th>
                        <td>{{ $selectedActivity->properties['ip_address'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if ($this->isActivityFailed($selectedActivity))
                                <span class="label label-danger">Gagal</span>
                            @else
                                <span class="label label-success">Berhasil</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>User Agent</th>
                        <td>{{ $selectedActivity->properties['user_agent'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td>{{ $selectedActivity->properties['url_slug'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Properties</th>
                        <td>
                            <pre class="pre-scrollable">{{ $selectedActivity->properties ? json_encode($selectedActivity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '-' }}</pre>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-simple-footer">
                <button type="button" class="btn btn-danger" wire:click="closeDetail">Tutup</button>
            </div>
        </div>
    </div>
    @endif

</div>
