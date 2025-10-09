<div class="form-group">
    <label for="jenis_dokumen_id" class="control-label col-md-4 col-sm-3 col-xs-12">Jenis Dokumen<span
            class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! html()->select('jenis_dokumen_id', \App\Models\JenisDokumen::pluck('nama', 'id')->toArray())
        ->value(old('jenis_dokumen_id', isset($dokumen) ? $dokumen->jenis_dokumen_id : null))
        ->placeholder('- Pilih -')
        ->class('form-control')
        ->id('jenis_dokumen_id')
        ->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Judul Dokumen<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! html()->text('nama_dokumen', old('nama_dokumen'))
        ->class('form-control')
        ->placeholder('Judul Dokumen')
        ->required() !!}
    </div>
</div>
<div class="form-group row">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Retensi Dokumen<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! html()->hidden('retention_days', 0)->id('retention_days') !!}
        <div class="row">
            <div class="col-md-6 pl-md-1">
                {!! html()->select('jumlah_waktu', collect(range(0, 31))->mapWithKeys(fn($v) => [$v => $v])->toArray())
                ->value(old('jumlah_waktu', isset($dokumen) ? $dokumen->jumlah_waktu : 0))
                ->class('form-control')
                ->placeholder('- Pilih -') !!}
            </div>
            <div class="col-md-6 pr-md-1">
                {!! html()->select('tipe_waktu', $tipe_waktu_options)
                ->value(old('tipe_waktu', isset($dokumen) ? $dokumen->tipe_waktu :
                \App\Enums\TipeWaktuFormDokumen::Hari))
                ->class('form-control')
                ->placeholder('- Pilih -') !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Unggah Dokumen <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! html()->file('file_dokumen')
        ->class('form-control')
        ->id('file_dokumen')
        ->attribute('accept', '.jpeg,.png,.jpg,.gif,.svg,.xlsx,.xls,.doc,.docx,.pdf,.ppt,.pptx')
        ->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Keterangan <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! html()->textarea('description', old('description'))
        ->class('form-control')
        ->placeholder('Keterangan')
        ->required() !!}
    </div>
</div>
<div class="form-group">
    <label for="status" class="control-label col-md-4 col-sm-3 col-xs-12">Status Terbit<span
            class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! html()->select('status', $status_options)
        ->value(old('status', isset($dokumen) ? $dokumen->status : \App\Enums\StatusFormDokumen::Terbit))
        ->class('form-control')
        ->id('status')
        ->placeholder('- Pilih -')
        ->required() !!}
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