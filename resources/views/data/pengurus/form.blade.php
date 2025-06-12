<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Foto</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="file" name="foto" id="foto" class="form-control" accept="jpg, jpeg, png">
        <br>
        <img src="{{ is_img($pengurus->foto ?? null) }}" id="showfoto" style="max-width:400px;max-height:250px;float:left;" />
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Pengurus <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('nama', null, ['placeholder' => 'Nama Pengurus', 'class' => 'form-control', 'required' => true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Gelar</label>
    <div class="col-md-2 col-sm-4 col-xs-6">
        {!! Form::text('gelar_depan', null, ['placeholder' => 'Gelar Depan', 'class' => 'form-control']) !!}
    </div>

    <div class="col-md-2 col-sm-4 col-xs-6">
        {!! Form::text('gelar_belakang', null, ['placeholder' => 'Gelar Belakang', 'class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Induk Kependudukan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('nik', null, [
            'placeholder' => 'Nomor Induk Kependudukan',
            'class' => 'form-control',
            'required' => true,
        ]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">NIP</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('nip', null, ['placeholder' => 'NIP', 'class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tempat Lahir <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('tempat_lahir', null, [
            'placeholder' => 'Tempat Lahir',
            'class' => 'form-control',
            'required' => true,
        ]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Lahir <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('tanggal_lahir', null, [
            'placeholder' => 'Tanggal Lahir',
            'class' => 'form-control datetime',
            'required' => true,
        ]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sex">Jenis Kelamin</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('sex', ['1' => 'LAKI-LAKI', '2' => 'PEREMPUAN'], null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pendidikan">Pendidikan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('pendidikan_id', $pendidikan, null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agama">Agama</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('agama_id', $agama, null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Pangkat/Golongan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('pangkat', null, ['placeholder' => 'Pangkat/Golongan', 'class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor SK Pengangkatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('no_sk', null, ['placeholder' => 'Nomor SK Pengangkatan', 'class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal SK Pengangkatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('tanggal_sk', null, [
            'placeholder' => 'Tanggal SK Pengangkatan',
            'class' => 'form-control datetime',
        ]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor SK Pemberhentian</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('no_henti', null, ['placeholder' => 'Nomor SK Pemberhentian', 'class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal SK Pemberhentian</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('tanggal_henti', null, [
            'placeholder' => 'Tanggal SK Pemberhentian',
            'class' => 'form-control datetime',
        ]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Masa Jabatan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('masa_jabatan', null, [
            'placeholder' => 'Masa Jabatan',
            'class' => 'form-control',
            'required' => true,
        ]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agama">Jabatan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('jabatan_id', $jabatan, null, ['class' => 'form-control']) !!}
    </div>
</div>

@if (!empty($atasan) && count($atasan) > 0)
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="atasan">Atasan</label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::select('atasan', $atasan, null, ['class' => 'form-control', 'placeholder' => 'Pilih Atasan']) !!}
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bagan_tingkat">Bagan - Tingkat</label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::number('bagan_tingkat', null, [
                'placeholder' => 'Angka menunjukkan tingkat di bagan organisasi. Contoh: 2',
                'class' => 'form-control',
                'required' => false,
                'autocomplete' => 'off',
                'min' => 0,
                'id' => 'bagan_tingkat',
            ]) !!}
            <small class="text-muted">Gunakan angka 0 untuk tingkatan tertinggi.</small>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bagan_warna">Bagan - Warna</label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="input-group my-colorpicker2">
                {!! Form::text('bagan_warna', null, ['class' => 'form-control', 'placeholder' => '#007ad0']) !!}
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
