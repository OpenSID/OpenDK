<div class="row">
    <div class="col-md-12 table-container">
        <div class="box box-info">
            <div class="box-header with-border">
                @foreach($requirements['requirements'] as $type => $requirement)
                <ol class="list" style="list-style: none">
                    <li class="list__item list__title {{ $phpSupportInfo['supported'] ? 'success' : 'error' }}">
                        <div class="form-group">
                            <label>
                              <input type="checkbox" class="flat-red" {{  $phpSupportInfo['supported'] ? 'checked' : '' }} >
                            </label>
                            <strong>{{ ucfirst($type) }}</strong>
                            @if($type == 'php')
                            <strong>
                                <small>
                                    (Versi yang dibutuhkan Php {{ $phpSupportInfo['minimum'] }})
                                </small>
                            </strong>
                            <span class="float-right">
                                <small>
                                    Versi Yang Terpasang Php </small> 
                                    <strong>
                                        {{ $phpSupportInfo['current'] }}
                                    </strong>
                                </span>
                                @endif
                            </div>
                    </li>
                    @foreach($requirements['requirements'][$type] as $extention => $enabled)
                    <li class="list__item {{ $enabled ? 'success' : 'error' }}">
                    <div class="form-group">
                        <label>
                          <input type="checkbox" class="flat-red" {{ $enabled ? 'checked' : '' }} >
                          {{ $extention }}
                        </label>
                      </div>
                    </li>
                    @endforeach
                </ol>
            @endforeach
            </div>
        </div>
    </div>
</div>