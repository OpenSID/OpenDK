<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pengurus_id">Jenis Dokumen</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('jenis_surat', \App\Models\JenisSurat::pluck('nama', 'id'), null, ['placeholder' => 'Pilih Jenis Dokumen', 'class' => 'form-control', 'id' => 'jenis_dokumen_id', 'required' => true]) !!}
    </div>

</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Judul Dokumen <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('judul_document', $penduduk->judul_document ?? null, ['placeholder' => 'Judul Document', 'class' => 'form-control', 'required' => true]) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Unggah Dokumen <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="file" name="path_document" class="form-control" required="false" accept=".pdf,.doc,.docx,.xls,.xlsx">
        <small class="text-danger">
            Batas maksimal pengunggahan file: 80MB. Hanya mendukung format: .pdf, .doc, .docx, .xls, .xlsx
        </small>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('keterangan', $penduduk->keterangan ?? null, ['placeholder' => 'Keterangan', 'class' => 'form-control', 'required' => true]) !!}
    </div>
</div>
{!! Form::hidden('das_penduduk_id', $data_penduduk->id ?? '', ['placeholder' => 'das_penduduk_id', 'class' => 'form-control', 'required' => true, 'readonly' => true]) !!}
{!! Form::hidden('document_id', $penduduk->document_id ?? '', ['placeholder' => 'document_id', 'class' => 'form-control', 'required' => false, 'readonly' => true]) !!}
{!! Form::hidden('pengurus_id', $pengurus_id ?? '', ['placeholder' => 'pengurus_id', 'class' => 'form-control', 'required' => false, 'readonly' => true]) !!}

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#pengurus_id").on('change', function() {
                let pengurusId = $(this).val(); // Ambil nilai yang dipilih
                if (pengurusId) {
                    let url = "{{ route('data.pengurus.penduduk.arsip', ':id') }}".replace(':id', pengurusId);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            let tempat_lahir = response.tempat_lahir
                            let tanggal_lahir = response.tanggal_lahir
                            let alamat = response.alamat
                            let warga_negara = response.warga_negara
                            let agama = response.agama
                            let pendidikan = response.pendidikan
                            let tempat_tanggal_lahir = tempat_lahir + " / " + tanggal_lahir
                            let warga_negara_agama = warga_negara + " / " + agama
                            $("input[name='tempat_tanggal_lahir']").val(tempat_tanggal_lahir);
                            $("input[name='alamat']").val(alamat);
                            $("input[name='pendidikan']").val(pendidikan);
                            $("input[name='warga_negara_agama']").val(warga_negara_agama);
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            alert("Terjadi kesalahan saat mengambil data.");
                        }
                    });
                }
            });
        });
    </script>
@endpush
