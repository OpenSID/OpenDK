<div class="form-group">
    <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Nama <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => true, 'id' => 'name']) !!}
        {!! Form::hidden('parent_id', $parent_id, ['class' => 'form-control', 'required' => true, 'id' => 'parent_id']) !!}
    </div>
</div>
<div class="form-group">
    <label for="type" class="control-label col-md-4 col-sm-3 col-xs-12">Tipe <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="type" id="type" class="form-control select2" required>
            <option value="">Pilih URL</option>
            <optgroup label="Profil">
                @foreach (\App\Enums\MenuTipe::getProfil() as $key => $value)
                    <option value="1" data-value="{{ 'profil/' . $key }}" @selected('profil/' . $key == optional($navigation)->url))>{{ $value }}</option>
                @endforeach
            </optgroup>
            <optgroup label="Desa">
                @foreach (\App\Enums\MenuTipe::getDesa() as $key => $value)
                    @php $nama_desa  = ucwords($key . ' ' . $value); @endphp
                    <option value="2" data-value="{{ 'desa/' . Str::slug($nama_desa) }}" @selected('desa/' . Str::slug($nama_desa) == optional($navigation)->url)>{{ $nama_desa }}</option>
                @endforeach
            </optgroup>
            <optgroup label="Statistik">
                @foreach (\App\Enums\MenuTipe::getStatistik() as $key => $value)
                    <option value="3" data-value="{{ 'statistik/' . $key }}" @selected('statistik/' . $key == optional($navigation)->url)>{{ $value }}</option>
                @endforeach
            </optgroup>
            <optgroup label="Potensi">
                @foreach (\App\Enums\MenuTipe::getPotensi() as $key => $value)
                    <option value="3" data-value="{{ 'potensi/' . $key }}" @selected('potensi/' . $key == optional($navigation)->url)>{{ $value }}</option>
                @endforeach
            </optgroup>
            <optgroup label="Unduhan">
                @foreach (\App\Enums\MenuTipe::getUnduhan() as $key => $value)
                    <option value="4" data-value="{{ 'unduhan/' . $key }}" @selected('unduhan/' . $key == optional($navigation)->url)>{{ $value }}</option>
                @endforeach
            </optgroup>
            <optgroup label="Publikasi">
                @foreach (\App\Enums\MenuTipe::getPublikasi() as $key => $value)
                    <option value="4" data-value="{{ 'publikasi/' . $key }}" @selected('publikasi/' . $key == optional($navigation)->url)>{{ $value }}</option>
                @endforeach
            </optgroup>
            <option value="0" @selected('0' == optional($navigation)->type)>Eksternal</option>
        </select>
    </div>
</div>
<div class="form-group" id="view-url">
    <label for="url" class="control-label col-md-4 col-sm-3 col-xs-12">Url <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('url', null, ['class' => 'form-control', 'required' => true, 'id' => 'url', 'value' => optional($navigation)->url]) !!}
    </div>
</div>
<div class="form-group">
    <label for="status" class="control-label col-md-4 col-sm-3 col-xs-12">Aktif <span class="required">*</span></label>
    <div class="col-md-2 col-sm-2 col-xs-12">
        {!! Form::select('status', ['1' => 'Ya', '0' => 'Tidak'], 1, ['class' => 'form-control', 'required' => true, 'id' => 'status']) !!}
    </div>
</div>
<div class="ln_solid"></div>
@push('scripts')
    <script>
        $(document).ready(function() {
            var base = '{{ url('/') }}';

            function toggleUrlFields(type) {
                var url = $('#url');
                var viewUrl = $('#view-url');
                if (type == '') {
                    viewUrl.hide();
                } else {
                    viewUrl.show();
                    if (type == '0') {
                        url.prop('readonly', false).attr('required', true);
                    } else {
                        let dataValue = $('#type option:selected').data('value');
                        url.val(base + '/' + dataValue).prop('readonly', true).removeAttr('required');
                    }
                }
            }

            $('#type').on('change', function() {
                toggleUrlFields($(this).val());
            }).trigger('change');
        });
    </script>
@endpush
