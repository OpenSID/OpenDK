<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pengurus_id">Nama Penduduk</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <select id="pengurus_id" name="pengurus_id" class="form-control">
            <option value="">Pilih Pengurus</option>
            @foreach($penduduk as $p)
                <option value="{{ $p->id }}">{{ $p->nama }} - {{ $p->nik }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tempat Tanggal Lahir (Umur) <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('tempat_tanggal_lahir', null, ['placeholder' => 'Tempat Tanggal Lahir (Umur)', 'class' => 'form-control', 'required' => true, 'readonly' => true]) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('alamat', null, ['placeholder' => 'Alamat', 'class' => 'form-control', 'required' => true, 'readonly' => true]) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Pendidikan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('pendidikan', null, ['placeholder' => 'Pendidikan', 'class' => 'form-control', 'required' => true, 'readonly' => true]) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Warga Negara <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('warga_negara_agama', null, ['placeholder' => 'Warga Negara', 'class' => 'form-control', 'required' => true, 'readonly' => true]) !!}
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $("#pengurus_id").on('change', function() {
            let pengurusId = $(this).val(); // Ambil nilai yang dipilih
            if (pengurusId) {
                let urlTemplate = "{{ route('data.pengurus.create.arsip', ':id') }}";

                // Ganti :id dengan pengurusId
                let urlBtnTambah = urlTemplate.replace(':id', pengurusId);
                $("#btn-tambah").attr("href", urlBtnTambah);
                $("#btn-tambah")
                .attr("href", urlBtnTambah)
                .removeClass('disabled')
                .css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });

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
            }else{
                $("#btn-tambah")
                .attr("href", "javascript:void(0)")
                .addClass('disabled')
                .css({
                    'pointer-events': 'none',
                    'opacity': '0.6'
                }); 
            }
        });
    });
</script>
@endpush

