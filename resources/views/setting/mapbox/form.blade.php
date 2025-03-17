<div class="form-group">
    <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Token <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('token', null, ['class' => 'form-control', 'required' => true, 'id' => 'token']) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Default Map</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-md-5">
                {{ Form::select('default_map', ['OpenStreetMap' => 'OpenStreetMap', 'OpenStreetMap H.O.T.' => 'OpenStreetMap H.O.T.', 'Mapbox Streets' => 'Mapbox Streets', 'Mapbox Satellite' =>'Mapbox Satellite', 'Mapbox Satellite-Streets' => 'Mapbox Satellite-Streets']) }}
            </div>
            
        </div>
    </div>
</div>  

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')
