<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pengurus_id">Nama Penduduk</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <select id="penduduk_id" name="penduduk_id" class="form-control">
            <option value="">Pilih Penduduk</option>
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
{!! Form::hidden('pengurus_id', $pengurus_id, ['id' => 'pengurus_id']) !!}
@push('scripts')
<script>
    $(document).ready(function() {
        $("#penduduk_id").on('change', function() {
            let pendudukId = $(this).val();
            let pengurusId = {{ $pengurus_id }}; 

            if (pendudukId) {
                let urlBtnTambah = `{{ route('data.pengurus.create.arsip', ['penduduk_id' => ':penduduk_id', 'pengurus_id' => ':pengurus_id']) }}`
                    .replace(':penduduk_id', pendudukId)
                    .replace(':pengurus_id', pengurusId);

                $("#btn-tambah")
                    .attr("href", urlBtnTambah)
                    .removeClass('disabled')
                    .css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    });

                let url = "{{ route('data.pengurus.penduduk.arsip', ['id' => ':penduduk_id']) }}".replace(':penduduk_id', pendudukId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let tempat_tanggal_lahir = response.tempat_lahir + " / " + response.tanggal_lahir;
                        let warga_negara_agama = response.warga_negara + " / " + response.agama;
                        $("input[name='tempat_tanggal_lahir']").val(tempat_tanggal_lahir);
                        $("input[name='alamat']").val(response.alamat);
                        $("input[name='pendidikan']").val(response.pendidikan);
                        $("input[name='warga_negara_agama']").val(warga_negara_agama);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert("Terjadi kesalahan saat mengambil data.");
                    }
                });
            } else {
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

