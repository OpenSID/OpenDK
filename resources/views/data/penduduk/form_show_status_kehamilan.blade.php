<div class="form-group">
    <label for="hamil" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kehamilan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <p class="form-control-static">{{ $penduduk->hamil == 1 ? 'Tidak Hamil' : ($penduduk->hamil == 2 ? 'Hamil' : '') }}</p>
    </div>
</div>
