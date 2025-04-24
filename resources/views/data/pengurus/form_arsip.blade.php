<p id="text-loading" class="text-center text-danger hidden">Menunggu data terisi</p>
<div class="form-flex-group">
    <label for="penduduk_id">Nama Penduduk</label>
    <select id="penduduk_id" name="penduduk_id" class="form-control select2">
        <option value="">Pilih Penduduk</option>
    </select>
</div>
<div class="form-flex-group">
    <label>Tempat Tanggal Lahir (Umur) <span class="required">*</span></label>
    {!! Form::text('tempat_tanggal_lahir', null, [
        'placeholder' => 'Tempat Tanggal Lahir (Umur)',
        'class' => 'form-control',
        'required' => true,
        'readonly' => true
    ]) !!}
</div>

<div class="form-flex-group">
    <label>Alamat <span class="required">*</span></label>
    {!! Form::text('alamat', null, [
        'placeholder' => 'Alamat',
        'class' => 'form-control',
        'required' => true,
        'readonly' => true
    ]) !!}
</div>

<div class="form-flex-group">
    <label>Pendidikan <span class="required">*</span></label>
    {!! Form::text('pendidikan', null, [
        'placeholder' => 'Pendidikan',
        'class' => 'form-control',
        'required' => true,
        'readonly' => true
    ]) !!}
</div>

<div class="form-flex-group">
    <label>Warga Negara <span class="required">*</span></label>
    {!! Form::text('warga_negara_agama', null, [
        'placeholder' => 'Warga Negara',
        'class' => 'form-control',
        'required' => true,
        'readonly' => true
    ]) !!}
</div>

{!! Form::hidden('pengurus_id', $pengurus_id, ['id' => 'pengurus_id']) !!}

@include('partials.asset_select2')
@push('scripts')
<script>
    $(document).ready(function() {
        $("#penduduk_id").on('change', function() {

            var btn = document.getElementById('btn-tambah');

            btn.classList.add('disabled');
            btn.style.pointerEvents = 'none';
            btn.style.opacity = '0.6';
            
            let pendudukId = $(this).val();
            let pengurusId = {{ $pengurus_id }}; 
            if (pendudukId) {
                $('#text-loading').removeClass('hidden')
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
                        $('#text-loading').addClass('hidden')
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
        
        $('#penduduk_id').select2({
            placeholder: "Pilih Penduduk",
            allowClear: true,
            width: 'resolve',
            ajax: {
                url: '{{ route("data.pengurus.penduduk.select2") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama + ' - ' + item.nik
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 0
        });

    });
</script>

<style>
    .form-flex-group {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 15px;
    }

    .form-flex-group label {
        margin-left: 20px;
        margin-right: 20px;
        min-width: 200px;
        text-align: left;
    }

    .form-flex-group .form-control {
        flex: 1;
        min-width: 80%;
    }

    @media (max-width: 768px) {
        .form-flex-group {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-flex-group label {
            margin: 0 0 5px 0;
            min-width: auto;
            margin-left: 0;
        }

        .form-flex-group .form-control {
            width: 100%;
        }
    }
</style>
@endpush

