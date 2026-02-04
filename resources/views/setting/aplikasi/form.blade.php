{!! html()->hidden('id')->value($aplikasi->id) !!}

<div class="form-group">
    <label for="key" class="control-label col-md-4 col-sm-3 col-xs-12">Judul</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('key')->value(old('key',
        $aplikasi->key))->class('form-control')->id('key')->attribute('readonly') !!}
    </div>
</div>

<div class="form-group">
    <label for="value" class="control-label col-md-4 col-sm-3 col-xs-12">Isi</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        @if ($aplikasi->type == 'textarea')
        @php
        $attribute = ['class' => 'form-control', 'required' => true, 'id' => 'value'];
        $option = json_decode($aplikasi->option, 1);
        if ($option) {
        $attribute = array_merge($attribute, $option);
        }
        @endphp
        {!! html()->textarea('value')->value(old('value', $aplikasi->value))->attributes($attribute) !!}
        @includeWhen($aplikasi->key == 'api_key_opendk', 'setting.aplikasi.generate_key')
        @elseif ($aplikasi->type == 'number')
        {!! html()->number('value')->value(old('value',
        $aplikasi->value))->class('form-control')->required()->id('value') !!}
        @elseif ($aplikasi->type == 'boolean')
        {!! html()->select('value', ['1' => 'Aktif', '0' => 'Tidak Aktif'])->value(old('value',
        $aplikasi->value))->class('form-control') !!}
        @elseif ($aplikasi->type == 'boolean_peta')
        {!! html()->select('value', ['OpenStreetMap' => 'OpenStreetMap', 'OpenStreetMap H.O.T.' => 'OpenStreetMap
        H.O.T.',
        'Mapbox Streets' => 'Mapbox Streets', 'Mapbox Satellite' => 'Mapbox Satellite', 'Mapbox Satellite-Streets' =>
        'Mapbox Satellite-Streets'])->value(old('value', $aplikasi->value))->class('form-control') !!}
        @else
        {!! html()->text('value')->value(old('value', $aplikasi->value))->class('form-control')->required()->id('value')
        !!}
        @endif
        <small></small>
    </div>
</div>

<div class="form-group">
    <label for="description" class="control-label col-md-4 col-sm-3 col-xs-12">Deskripsi</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->textarea('description')->value(old('description',
        $aplikasi->description))->class('form-control')->id('description')->rows(5)->attribute('readonly') !!}
    </div>
</div>

<div class="form-group">
    <label for="kategori" class="control-label col-md-4 col-sm-3 col-xs-12">Kategori</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('kategori')->value(old('kategori',
        $aplikasi->kategori))->class('form-control')->id('kategori')->attribute('readonly') !!}
    </div>
</div>

<div class="ln_solid"></div>
{{-- @includeWhen($aplikasi->key == 'login_2fa', 'setting.aplikasi.test_email') --}}