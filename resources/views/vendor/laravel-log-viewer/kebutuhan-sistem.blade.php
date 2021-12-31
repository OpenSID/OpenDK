<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-body">
                @foreach($requirements['requirements'] as $type => $requirement)
                <div class="form-group">
                    <h5><i class="fa fa-{{ $phpSupportInfo['supported'] ? 'check-circle-o' : 'remove-o' }} fa-lg" style="color:{{ $phpSupportInfo['supported'] ? 'green' : 'red' }}"></i> {{ ucfirst($type) }}
                    @if($type == 'php')
                        <small><strong>(Versi yang dibutuhkan Php {{ $phpSupportInfo['minimum'] }})</strong></small>
                        <span class="float-right">
                            <small>Versi Yang Terpasang Php <strong>{{ $phpSupportInfo['current'] }}</strong></small>
                        </span>
                    @endif
                    </h5>
                </div>
                    
                @foreach($requirements['requirements'][$type] as $extention => $enabled)
                <div class="form-group">
                    <h5><i class="fa fa-{{ $enabled ? 'check-circle-o' : 'remove-o' }} fa-lg" style="color:{{ $enabled ? 'green' : 'red' }}"></i> {{ $extention }}</h5>
                </div>
                </li>
                @endforeach
            @endforeach
            </div>
        </div>
    </div>
</div>