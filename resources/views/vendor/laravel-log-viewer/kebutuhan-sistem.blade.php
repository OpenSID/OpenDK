<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <div class="p-3">
                    <a class="btn btn-social btn-flat btn-info visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" id="run-linkstorage" href="{{ URL('linkstorage') }}">
                        <span class="fa fa-play-circle"></span> Jalankan php artisan storage:link
                    </a>
                </div>
            </div>
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