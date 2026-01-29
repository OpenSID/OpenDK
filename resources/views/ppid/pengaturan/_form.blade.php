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

        <!-- Box Informasi Tambahan -->
        <div class="box box-solid box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info-circle"></i> Form Permohonan</h3>
            </div>
            <div class="box-body">
                
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>
