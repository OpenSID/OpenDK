<div class="form-group">
    <label for="jenis_dokumen_id" class="control-label col-md-4 col-sm-3 col-xs-12">Jenis Dokumen<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::select('jenis_dokumen_id', \App\Models\JenisDokumen::pluck('nama', 'id'), null, ['placeholder' => '-Pilih', 'class' => 'form-control', 'id' => 'jenis_dokumen_id', 'required' => true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Judul Dokumen<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::text('nama_dokumen', null, ['class' => 'form-control', 'placeholder' => 'Judul Dokumen', 'required']) !!}
    </div>
</div>
<div class="form-group row">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Retensi Dokumen<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::hidden('retention_days', 0, ['id' => 'retention_days']) !!}
        <div class="row">
            <div class="col-md-6 pl-md-1">
                {!! Form::select('jumlah_waktu', array_combine(range(0, 31), range(0, 31)), 0, [
                    'class' => 'form-control',
                    'placeholder' => '- Pilih -',
                ]) !!}
            </div>
            <div class="col-md-6 pr-md-1">
                {!! Form::select('tipe_waktu', $tipe_waktu_options, \App\Enums\TipeWaktuFormDokumen::Hari, [
                    'class' => 'form-control',
                    'placeholder' => '- Pilih -',
                ]) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Unggah Dokumen <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        <input type="file" name="file_dokumen" id="file_dokumen" class="form-control" accept="jpeg,png,jpg,gif,svg,xlsx,xls,doc,docx,pdf,ppt,pptx" required>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Keterangan <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::textArea('description', null, ['class' => 'form-control', 'placeholder' => 'Keterangan', 'required']) !!}
    </div>
</div>
<div class="form-group">
    <label for="jenis_dokumen_id" class="control-label col-md-4 col-sm-3 col-xs-12">Status Terbit<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::select('status', $status_options, \App\Enums\StatusFormDokumen::Terbit, [
            'class' => 'form-control',
            'placeholder' => '- Pilih -',
        ]) !!}
    </div>
</div>
<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\DokumenRequest', '#form-dokumen') !!}
    <script>
        const STATUS_DRAFT = {{ \App\Enums\StatusFormDokumen::Draft }};
        const STATUS_TERBIT = {{ \App\Enums\StatusFormDokumen::Terbit }};
        const TIPE_HARI = {{ \App\Enums\KonversiHariFormDokumen::Hari }};
        const TIPE_BULAN = {{ \App\Enums\KonversiHariFormDokumen::Bulan }};
        const TIPE_TAHUN = {{ \App\Enums\KonversiHariFormDokumen::Tahun }};
    </script>
    <script>
        $(document).ready(function() {
            const $status = $('select[name="status"]');
            const $jumlahWaktu = $('select[name="jumlah_waktu"]');
            const $tipeWaktu = $('select[name="tipe_waktu"]');
            const $retentionDays = $('#retention_days');

            const tipeToHari = {
                '1': TIPE_HARI,
                '2': TIPE_BULAN,
                '3': TIPE_TAHUN
            };

            function updateRetentionDays() {
                const status = $status.val();
                const jumlah = parseInt($jumlahWaktu.val()) || 0;
                const tipe = $tipeWaktu.val();

                if (parseInt(status) === STATUS_DRAFT) {
                    $jumlahWaktu.val('0').prop('disabled', true);
                    $tipeWaktu.val('1').prop('disabled', true);
                    $retentionDays.val(0);
                } else {
                    $jumlahWaktu.prop('disabled', false);
                    $tipeWaktu.prop('disabled', false);
                    if (tipe && jumlah >= 0) {
                        const total = jumlah * tipeToHari[tipe];
                        $retentionDays.val(total);
                    }
                }
            }

            $status.on('change', updateRetentionDays);
            $jumlahWaktu.on('change', updateRetentionDays);
            $tipeWaktu.on('change', updateRetentionDays);

            updateRetentionDays();
        });
    </script>
@endpush
