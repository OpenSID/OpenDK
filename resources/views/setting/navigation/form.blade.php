<div class="form-group">
    <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Nama <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => true, 'id' => 'name']) !!}
        {!! Form::hidden('parent_id', $parent_id, ['class' => 'form-control', 'required' => true, 'id' => 'parent_id']) !!}
    </div>
</div>
<div class="form-group">
    <label for="slug" class="control-label col-md-4 col-sm-3 col-xs-12">Slug <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('slug', null, ['class' => 'form-control', 'required' => true, 'id' => 'slug']) !!}
    </div>
</div>
<div class="form-group">
    <label for="nav_type" class="control-label col-md-4 col-sm-3 col-xs-12">Tipe <span class="required">*</span></label>
    <div class="col-md-3 col-sm-3 col-xs-12">
        {!! Form::select('nav_type', ['system' => 'System', 'external' => 'Eksternal'], 'system', ['class' => 'form-control', 'required' => true, 'id' => 'nav_type']) !!}
    </div>
</div>
<div class="form-group">
    <label for="url" class="control-label col-md-4 col-sm-3 col-xs-12">URL <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('url', null, ['class' => 'form-control', 'required' => true, 'id' => 'url']) !!}
    </div>
</div>
<div class="form-group">
    <label for="order" class="control-label col-md-4 col-sm-3 col-xs-12">Order <span class="required">*</span></label>
    <div class="col-md-2 col-sm-2 col-xs-12">
        {!! Form::number('order', null, ['class' => 'form-control', 'required' => true, 'id' => 'order']) !!}
    </div>
</div>
<div class="form-group">
    <label for="is_active" class="control-label col-md-4 col-sm-3 col-xs-12">Aktif <span class="required">*</span></label>
    <div class="col-md-2 col-sm-2 col-xs-12">
        {!! Form::select('is_active', ['1' => 'Ya', '0' => 'Tidak'], 1, ['class' => 'form-control', 'required' => true, 'id' => 'is_active']) !!}
    </div>
</div>
<div class="ln_solid"></div>
