<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <label>Desa</label>
            <select class="form-control select2" id="list_desa">
                <option value="Semua">Semua Desa</option>
                @foreach ((new App\Services\DesaService())->listDesa()->pluck('nama', 'desa_id') as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
