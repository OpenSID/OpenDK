<div class="row">
    <div class="col-md-6">
        <legend>Info Wilayah</legend>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">{{ $sebutan_wilayah }} <span
                    class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::text('kecamatan')->class('form-control')->value(old('kecamatan', isset($data_umum) ?
                $data_umum->kecamatan : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Tipologi <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::textarea('tipologi')->class('form-control
                my-editor')->placeholder('Tipologi')->rows(2)->value(old('tipologi', isset($data_umum) ?
                $data_umum->tipologi : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Sejarah <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::textarea('sejarah')->class('form-control
                my-editor')->placeholder('Sejarah')->rows(2)->value(old('sejarah', isset($data_umum) ?
                $data_umum->sejarah : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Ketinggian (MDPL) <span
                    class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::number('ketinggian')->class('form-control')->placeholder('0')->value(old('ketinggian',
                isset($data_umum) ? $data_umum->ketinggian : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Luas Wilayah (km<sup>2</sup>)<span
                    class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-md-5">
                        {!! Html::select(
                        'sumber_luas_wilayah',
                        ['1' => 'Manual', 2 => 'Dari Luas Desa'],
                        old('sumber_luas_wilayah', $data_umum->sumber_luas_wilayah ?? null)
                        )->class('form-control sumber_luas_wilayah') !!}
                    </div>
                    <div class="col-md-7">
                        {!! Html::number('luas_wilayah')->class('form-control
                        luas_wilayah')->placeholder('0')->value(old('luas_wilayah', isset($data_umum) ?
                        $data_umum->luas_wilayah : '')) !!}
                    </div>
                </div>
            </div>
        </div>

        <br>
        <legend>Batas Wilayah</legend>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Utara <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::textarea('bts_wil_utara')->class('form-control')->placeholder('Batas
                Utara')->rows(2)->value(old('bts_wil_utara', isset($data_umum) ? $data_umum->bts_wil_utara : '')) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Timur <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::textarea('bts_wil_timur')->class('form-control')->placeholder('Batas
                Timur')->rows(2)->value(old('bts_wil_timur', isset($data_umum) ? $data_umum->bts_wil_timur : '')) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Selatan <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::textarea('bts_wil_selatan')->class('form-control')->placeholder('Batas
                Selatan')->rows(2)->value(old('bts_wil_selatan', isset($data_umum) ? $data_umum->bts_wil_selatan : ''))
                !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Barat <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::textarea('bts_wil_barat')->class('form-control')->placeholder('Batas
                Barat')->rows(2)->value(old('bts_wil_barat', isset($data_umum) ? $data_umum->bts_wil_barat : '')) !!}
            </div>
        </div>

    </div>
    <div class="col-md-6">

        <legend>Jumlah Sarana Kesehatan</legend>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Puskesmas <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::number('jml_puskesmas')->class('form-control')->placeholder('0')->value(old('jml_puskesmas',
                isset($data_umum) ? $data_umum->jml_puskesmas : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Puskesmas Pembantu <span
                    class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!!
                Html::number('jml_puskesmas_pembantu')->class('form-control')->placeholder('0')->value(old('jml_puskesmas_pembantu',
                isset($data_umum) ? $data_umum->jml_puskesmas_pembantu : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Posyandu <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::number('jml_posyandu')->class('form-control')->placeholder('0')->value(old('jml_posyandu',
                isset($data_umum) ? $data_umum->jml_posyandu : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Pondok Bersalin <span
                    class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!!
                Html::number('jml_pondok_bersalin')->class('form-control')->placeholder('0')->value(old('jml_pondok_bersalin',
                isset($data_umum) ? $data_umum->jml_pondok_bersalin : '')) !!}
            </div>
        </div>

        <br>
        <legend>Jumlah Sarana Pendidikan</legend>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">PAUD/Sederajat <span
                    class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::number('jml_paud')->class('form-control')->placeholder('0')->value(old('jml_paud',
                isset($data_umum) ? $data_umum->jml_paud : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">SD/Sederajat <span
                    class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::number('jml_sd')->class('form-control')->placeholder('0')->value(old('jml_sd',
                isset($data_umum) ? $data_umum->jml_sd : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">SMP/Sederajat <span
                    class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::number('jml_smp')->class('form-control')->placeholder('0')->value(old('jml_smp',
                isset($data_umum) ? $data_umum->jml_smp : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">SMA/Sederajat <span
                    class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::number('jml_sma')->class('form-control')->placeholder('0')->value(old('jml_sma',
                isset($data_umum) ? $data_umum->jml_sma : '')) !!}
            </div>
        </div>

        <br>
        <legend>Jumlah Sarana Umum</legend>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Masjid Besar <span
                    class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!!
                Html::number('jml_masjid_besar')->class('form-control')->placeholder('0')->value(old('jml_masjid_besar',
                isset($data_umum) ? $data_umum->jml_masjid_besar : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Mushola <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::number('jml_mushola')->class('form-control')->placeholder('0')->value(old('jml_mushola',
                isset($data_umum) ? $data_umum->jml_mushola : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Gereja <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::number('jml_gereja')->class('form-control')->placeholder('0')->value(old('jml_gereja',
                isset($data_umum) ? $data_umum->jml_gereja : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Pasar <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Html::number('jml_pasar')->class('form-control')->placeholder('0')->value(old('jml_pasar',
                isset($data_umum) ? $data_umum->jml_pasar : '')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Balai Pertemuan <span
                    class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!!
                Html::number('jml_balai_pertemuan')->class('form-control')->placeholder('0')->value(old('jml_balai_pertemuan',
                isset($data_umum) ? $data_umum->jml_balai_pertemuan : '')) !!}
            </div>
        </div>
    </div>
</div>
<div class="ln_solid"></div>