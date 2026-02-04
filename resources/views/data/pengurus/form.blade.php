@php
    // dd(isset($pengurus) && !empty($pengurus))
    // if(!$pengurus->nama) {
    // $pengurus = '';
    // }
@endphp
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Foto</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="file" name="foto" id="foto" class="form-control" accept="jpg, jpeg, png">
        <br>
        <img src="{{ is_img($pengurus->foto ?? null) }}" id="showfoto"
            style="max-width:400px;max-height:250px;float:left;" />
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Pengurus <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nama')->class('form-control')->required()->placeholder('Nama Pengurus')->value(old('nama', isset($pengurus) && !empty($pengurus) ? $pengurus->nama : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Gelar</label>
    <div class="col-md-2 col-sm-4 col-xs-6">
        {!! html()->text('gelar_depan')->class('form-control')->placeholder('Gelar Depan')->value(old('gelar_depan', isset($pengurus) && !empty($pengurus) ? $pengurus->gelar_depan : '')) !!}
    </div>

    <div class="col-md-2 col-sm-4 col-xs-6">
        {!! html()->text('gelar_belakang')->class('form-control')->placeholder('Gelar Belakang')->value(old('gelar_belakang', isset($pengurus) && !empty($pengurus) ? $pengurus->gelar_belakang : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Induk Kependudukan <span
            class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nik')->class('form-control')->required()->placeholder('Nomor Induk Kependudukan')->value(old('nik', isset($pengurus) && !empty($pengurus) ? $pengurus->nik : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">NIP</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nip')->class('form-control')->placeholder('NIP')->value(old('nip', isset($pengurus) && !empty($pengurus) ? $pengurus->nip : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tempat Lahir <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('tempat_lahir')->class('form-control')->required()->placeholder('Tempat Lahir')->value(old('tempat_lahir', isset($pengurus) && !empty($pengurus) ? $pengurus->tempat_lahir : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Lahir <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('tanggal_lahir')->class('form-control datetime')->required()->placeholder('Tanggal Lahir')->value(old('tanggal_lahir', isset($pengurus) && !empty($pengurus) ? $pengurus->tanggal_lahir : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sex">Jenis Kelamin</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->select('sex', ['1' => 'LAKI-LAKI', '2' => 'PEREMPUAN'])->class('form-control')->value(old('sex', isset($pengurus) && !empty($pengurus) ? $pengurus->sex : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pendidikan">Pendidikan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->select('pendidikan_id', $pendidikan)->class('form-control')->value(old('pendidikan_id', isset($pengurus) && !empty($pengurus) ? $pengurus->pendidikan_id : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agama">Agama</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->select('agama_id', $agama)->class('form-control')->value(old('agama_id', isset($pengurus) && !empty($pengurus) ? $pengurus->agama_id : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Pangkat/Golongan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('pangkat')->class('form-control')->placeholder('Pangkat/Golongan')->value(old('pangkat', isset($pengurus) && !empty($pengurus) ? $pengurus->pangkat : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor SK Pengangkatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('no_sk')->class('form-control')->placeholder('Nomor SK Pengangkatan')->value(old('no_sk', isset($pengurus) && !empty($pengurus) ? $pengurus->no_sk : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal SK Pengangkatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('tanggal_sk')->class('form-control datetime')->placeholder('Tanggal SK Pengangkatan')->value(old('tanggal_sk', isset($pengurus) && !empty($pengurus) ? $pengurus->tanggal_sk : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor SK Pemberhentian</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('no_henti')->class('form-control')->placeholder('Nomor SK Pemberhentian')->value(old('no_henti', isset($pengurus) && !empty($pengurus) ? $pengurus->no_henti : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal SK Pemberhentian</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('tanggal_henti')->class('form-control datetime')->placeholder('Tanggal SK Pemberhentian')->value(old('tanggal_henti', isset($pengurus) && !empty($pengurus) ? $pengurus->tanggal_henti : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Masa Jabatan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('masa_jabatan')->class('form-control')->required()->placeholder('Masa Jabatan')->value(old('masa_jabatan', isset($pengurus) && !empty($pengurus) ? $pengurus->masa_jabatan : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agama">Jabatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->select('jabatan_id', $jabatan)->class('form-control')->value(old('jabatan_id', isset($pengurus) && !empty($pengurus) ? $pengurus->jabatan_id : '')) !!}
    </div>
</div>

@if (!empty($atasan) && count($atasan) > 0)
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="atasan">Atasan</label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! html()->select('atasan', $atasan)->class('form-control')->placeholder('Pilih Atasan')->value(old('atasan', isset($pengurus) && !empty($pengurus) ? $pengurus->atasan : '')) !!}
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bagan_tingkat">Bagan - Tingkat</label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! html()->number('bagan_tingkat')->class('form-control')->id('bagan_tingkat')->placeholder('Angka menunjukkan tingkat di bagan organisasi. Contoh: 2')->value(old('bagan_tingkat', isset($pengurus) && !empty($pengurus) ? $pengurus->bagan_tingkat : '')) !!}
            <small class="text-muted">Gunakan angka 0 untuk tingkatan tertinggi.</small>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bagan_warna">Bagan - Warna</label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="input-group my-colorpicker2">
                {!! html()->text('bagan_warna')->class('form-control')->placeholder('#007ad0')->value(old('bagan_warna', isset($pengurus) && !empty($pengurus) ? $pengurus->bagan_warna : '')) !!}
                <div class="input-group-addon">
                    <i></i>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="ln_solid"></div>
@include('partials.asset_jqueryvalidation')

@include('partials.asset_colorpicker')
@push('scripts')
    <script>
        //color picker with addon
        $('.my-colorpicker2').colorpicker();

        document.addEventListener("DOMContentLoaded", function() {
            const inputTingkat = document.getElementById("bagan_tingkat");

            inputTingkat.addEventListener("input", function() {
                if (this.value < 0) {
                    this.value = 0; // Paksa angka negatif menjadi 0
                }
            });
        });
    </script>
@endpush

@include('partials.asset_datetimepicker')
@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\PengurusRequest', '#form-pengurus') !!}

    <script>
        $(function() {
            $('.datetime').each(function() {
                var $this = $(this);
                $this.datetimepicker({
                    format: 'YYYY-MM-D'
                });
            });
        })
    </script>
@endpush
