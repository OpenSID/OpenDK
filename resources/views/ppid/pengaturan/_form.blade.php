<div class="row">
    <!-- Kolom Kiri: Banner Upload (4 kolom) -->
    <div class="col-md-4">
        <div class="box box-solid box-primary" style="min-height: 400px;">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-image"></i> Banner PPID</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label">Upload Banner</label>
                    <input type="file" name="ppid_banner" id="ppid_banner" class="form-control"
                        accept=".jpg,.jpeg,.png,.bmp" />
                    <p class="help-block">
                        <small>Format: JPG, JPEG, PNG, BMP<br>Maksimal: 2MB<br>Rekomendasi: 1200x400px</small>
                    </p>
                </div>

                <div class="form-group">
                    <label class="control-label">Preview</label>
                    <div class="text-center" style="background: #f5f5f5; padding: 15px; border-radius: 4px;">
                        @if (isset($pengaturan->ppid_banner) && !empty($pengaturan->ppid_banner))
                        <img id="banner-preview"
                            src="{{ asset($pengaturan->ppid_banner) }}"
                            class="img-responsive"
                            style="max-width: 100%; max-height: 300px; margin: 0 auto; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" />
                        @else
                        <img id="banner-preview"
                            src="{{ asset('img/no-image.png') }}"
                            class="img-responsive"
                            style="max-width: 100%; max-height: 300px; margin: 0 auto; border-radius: 4px; opacity: 0.6;" />
                        @endif
                    </div>
                    <p class="help-block text-center">
                        <small>Preview banner yang akan ditampilkan</small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Form Fields (8 kolom) -->
    <div class="col-md-8">
        <div class="box box-solid box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-cogs"></i> Pengaturan PPID</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Judul PPID <span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ html()->text('ppid_judul')
                        ->class('form-control')
                        ->placeholder('Judul Halaman PPID')
                        ->value(old('ppid_judul', $pengaturan->ppid_judul ?? 'Layanan Informasi Publik Desa')) }}
                        <p class="help-block">Judul yang ditampilkan pada halaman PPID</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Informasi</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ html()->textarea('ppid_informasi')
                        ->class('form-control')
                        ->placeholder('Deskripsi informasi PPID')
                        ->rows(4)
                        ->value(old('ppid_informasi', $pengaturan->ppid_informasi ?? '')) }}
                        <p class="help-block">Deskripsi singkat mengenai layanan PPID</p>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="clearfix"></div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Batas Pengajuan <span class="required">*</span></label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group">
                            {{ html()->number('ppid_batas_pengajuan')
                            ->class('form-control')
                            ->placeholder('10')
                            ->attribute('min', 1)
                            ->value(old('ppid_batas_pengajuan', $pengaturan->ppid_batas_pengajuan ?? '')) }}
                            <span class="input-group-addon">Hari</span>
                        </div>
                        <p class="help-block">Batas waktu pemrosesan permohonan</p>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="clearfix"></div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Layanan Permohonan <span class="required">*</span></label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        {{ html()->select('ppid_permohonan', ['1' => 'Aktif', '0' => 'Non-Aktif'])
                        ->class('form-control')
                        ->value(old('ppid_permohonan', $pengaturan->ppid_permohonan ?? '1')) }}
                        <p class="help-block">Aktifkan form permohonan informasi</p>
                    </div>
                </div>
                
                <div class="ln_solid"></div>
                <div class="clearfix"></div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Layanan Keberatan <span class="required">*</span></label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        {{ html()->select('ppid_keberatan', ['1' => 'Aktif', '0' => 'Non-Aktif'])
                        ->class('form-control')
                        ->value(old('ppid_keberatan', $pengaturan->ppid_keberatan ?? '1')) }}
                        <p class="help-block">Aktifkan form keberatan atas informasi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box Form Permohonan - Pertanyaan -->
        <div class="box box-solid box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Form Permohonan</h3>
            </div>
            <div class="box-body">
                <!-- Tab Navigasi -->
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab-informasi" data-toggle="tab" aria-expanded="true">
                            <i class="fa fa-info-circle"></i> Informasi
                        </a>
                    </li>
                    <li class="">
                        <a href="#tab-mendapatkan" data-toggle="tab" aria-expanded="false">
                            <i class="fa fa-download"></i> Mendapatkan
                        </a>
                    </li>
                    <li class="">
                        <a href="#tab-keberatan" data-toggle="tab" aria-expanded="false">
                            <i class="fa fa-exclamation-triangle"></i> Keberatan
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Tab Informasi -->
                    <div class="tab-pane active" id="tab-informasi">
                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-success" onclick="showTambahPertanyaanModal(1)">
                                <i class="fa fa-plus"></i> Tambah Pertanyaan
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="table-informasi">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Pertanyaan</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($pertanyaanInformasi) && $pertanyaanInformasi->count() > 0)
                                        @foreach($pertanyaanInformasi as $index => $item)
                                        <tr data-id="{{ $item->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->ppid_judul }}</td>
                                            <td>
                                                <span class="label {{ $item->ppid_status == '1' ? 'label-success' : 'label-default' }}">
                                                    {{ $item->ppid_status == '1' ? 'Aktif' : 'Non-Aktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-xs btn-warning"
                                                    onclick="toggleStatus({{ $item->id }}, '{{ $item->ppid_status }}')"
                                                    title="Toggle Status">
                                                    <i class="fa fa-power-off"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-danger"
                                                    onclick="deletePertanyaan({{ $item->id }})"
                                                    title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada pertanyaan</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Mendapatkan -->
                    <div class="tab-pane" id="tab-mendapatkan">
                 
                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-success" onclick="showTambahPertanyaanModal(2)">
                                <i class="fa fa-plus"></i> Tambah Pertanyaan
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="table-mendapatkan">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Pertanyaan</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($pertanyaanMendapatkan) && $pertanyaanMendapatkan->count() > 0)
                                        @foreach($pertanyaanMendapatkan as $index => $item)
                                        <tr data-id="{{ $item->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->ppid_judul }}</td>
                                            <td>
                                                <span class="label {{ $item->ppid_status == '1' ? 'label-success' : 'label-default' }}">
                                                    {{ $item->ppid_status == '1' ? 'Aktif' : 'Non-Aktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-xs btn-warning"
                                                    onclick="toggleStatus({{ $item->id }}, '{{ $item->ppid_status }}')"
                                                    title="Toggle Status">
                                                    <i class="fa fa-power-off"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-danger"
                                                    onclick="deletePertanyaan({{ $item->id }})"
                                                    title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada pertanyaan</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Keberatan -->
                    <div class="tab-pane" id="tab-keberatan">
                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-success" onclick="showTambahPertanyaanModal(0)">
                                <i class="fa fa-plus"></i> Tambah Pertanyaan
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="table-keberatan">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Pertanyaan</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($pertanyaanKeberatan) && $pertanyaanKeberatan->count() > 0)
                                        @foreach($pertanyaanKeberatan as $index => $item)
                                        <tr data-id="{{ $item->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->ppid_judul }}</td>
                                            <td>
                                                <span class="label {{ $item->ppid_status == '1' ? 'label-success' : 'label-default' }}">
                                                    {{ $item->ppid_status == '1' ? 'Aktif' : 'Non-Aktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-xs btn-warning"
                                                    onclick="toggleStatus({{ $item->id }}, '{{ $item->ppid_status }}')"
                                                    title="Toggle Status">
                                                    <i class="fa fa-power-off"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-danger"
                                                    onclick="deletePertanyaan({{ $item->id }})"
                                                    title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada pertanyaan</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>
