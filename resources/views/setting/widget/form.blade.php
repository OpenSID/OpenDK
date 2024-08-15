<div class="form-group">
    <label for="judul" class="control-label col-md-4 col-sm-3 col-xs-12">Judul Widget <span
            class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('judul', null, ['class' => 'form-control', 'required' => true, 'id' => 'judul']) !!}
    </div>
</div>
<div class="form-group">
    <label for="gambar" class="control-label col-md-4 col-sm-3 col-xs-12">Gambar Widget</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                @if (isset($widget->gambar))
                    <img src="{{ is_img($widget->gambar) }}" id="showgambar" class="img-thumbnail" />
                @else
                    <img src="{{ asset('img/no-image.png') }}" id="showgambar" class="img-thumbnail" />
                @endif
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-default btn-file">
                            Pilih Gambar… <input type="file" id="gambar-widget" name="gambar-widget">
                        </span>
                    </span>
                    <input type="text" class="form-control" readonly>
                    <span class="input-group-btn" data-toggle="tooltip" data-placement="top"
                        title="Centang untuk menghapus gambar">
                        <span class="btn btn-danger btn-file">
                            <input type="checkbox" id="hapus-gambar-widget" name="hapus-gambar-widget">
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="jenis_widget" class="control-label col-md-4 col-sm-3 col-xs-12">Jenis Widget <span
            class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('jenis_widget', ['0' => '-- Pilih Jenis Widget --', '2' => 'Statis', '3' => 'Dinamis'], 0, [
            'class' => 'form-control',
            'required' => true,
            'id' => 'jenis_widget',
        ]) !!}
    </div>
</div>
@php
    $statis = (old('jenis_widget', $widget->jenis_widget ?? 0) == 2);
@endphp
<div id="statis" class="form-group" <?php !$statis && (print 'style="display:none;"'); ?>>
    <label for="isi-statis" class="control-label col-md-4 col-sm-3 col-xs-12">Nama File Widget (.php) <span
            class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('isi-statis', ['0' => '--- Pilih Widget ---'] + $widget_list, $widget->jenis_widget ?? 0, [
            'class' => 'form-control',
            'required' => $statis,
            'id' => 'isi',
        ]) !!}
    </div>
</div>
@php
    $dinamis = (old('jenis_widget', $widget->jenis_widget ?? 0) == 3);
@endphp
<div id="dinamis" class="form-group" <?php !$dinamis && (print 'style="display:none;"'); ?>>
    <label for="isi-dinamis" class="control-label col-md-4 col-sm-3 col-xs-12">Kode Widget <span
            class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('isi-dinamis', null, ['class' => 'form-control', 'required' => $dinamis, 'id' => 'isi']) !!}
    </div>
</div>
<div class="ln_solid"></div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var jenisWidgetSelect = document.getElementById('jenis_widget');
        var statisDiv = document.getElementById('statis');
        var dinamisDiv = document.getElementById('dinamis');

        function toggleFields() {
            var value = jenisWidgetSelect.value;
            if (value == '2') {
                statisDiv.style.display = '';
                dinamisDiv.style.display = 'none';
            } else if (value == '3') {
                statisDiv.style.display = 'none';
                dinamisDiv.style.display = '';
            } else {
                statisDiv.style.display = 'none';
                dinamisDiv.style.display = 'none';
            }
        }

        // Initial call to set visibility on page load
        toggleFields();

        // Add event listener to update visibility on change
        jenisWidgetSelect.addEventListener('change', toggleFields);
    });
</script>
