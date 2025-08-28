{{ Html::hidden('id', $aplikasi->id) }}

<div class="form-group">
    <label for="key" class="control-label col-md-4 col-sm-3 col-xs-12">Judul</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Html::text('key', null, ['class' => 'form-control', 'id' => 'key', 'readonly' => true]) !!}
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
        {!! Html::textarea('value', null, $attribute) !!}
        @includeWhen($aplikasi->key == 'api_key_opendk', 'setting.aplikasi.generate_key')
        @elseif ($aplikasi->type == 'number')
        {!! Html::number('value', null, ['class' => 'form-control', 'required' => true, 'id' => 'value']) !!}
        @elseif ($aplikasi->type == 'boolean')
        {!! Html::select('value', ['1' => 'Aktif', '0' => 'Tidak Aktif'], null, ['class' => 'form-control']) !!}
        @elseif ($aplikasi->type == 'boolean_peta')
        {!! Html::select('value', ['OpenStreetMap' => 'OpenStreetMap', 'OpenStreetMap H.O.T.' => 'OpenStreetMap H.O.T.',
        'Mapbox Streets' => 'Mapbox Streets', 'Mapbox Satellite' => 'Mapbox Satellite', 'Mapbox Satellite-Streets' =>
        'Mapbox Satellite-Streets'], null, ['class' => 'form-control']) !!}
        @else
        {!! Html::text('value', null, ['class' => 'form-control', 'required' => true, 'id' => 'value']) !!}
        @endif
        <small></small>
    </div>
</div>

<div class="form-group">
    <label for="description" class="control-label col-md-4 col-sm-3 col-xs-12">Deskripsi</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Html::textarea('description', null, ['class' => 'form-control', 'id' => 'description', 'rows' => 5,
        'readonly' => true]) !!}
    </div>
</div>

<div class="form-group">
    <label for="kategori" class="control-label col-md-4 col-sm-3 col-xs-12">Kategori</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Html::text('kategori', null, ['class' => 'form-control', 'id' => 'kategori', 'readonly' => true]) !!}
    </div>
</div>

<div class="ln_solid"></div>
@includeWhen($aplikasi->key == 'login_2fa', 'setting.aplikasi.test_email')