<div class="form-group">
    <label for="hamil" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kehamilan</label>
    <div class="input-group col-md-6 col-sm-6 col-xs-12">
        &nbsp;
        &nbsp;
        &nbsp;
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-primary btn-sm @if($penduduk->hamil == 1) active @endif" disabled>
                <input type="radio" name="hamil" id="hamil" value="1" disabled autocomplete="off" @if($penduduk->hamil == 1) checked @endif> Tidak Hamil
            </label>
            <label class="btn btn-primary btn-sm @if($penduduk->hamil == 2) active @endif" disabled>
                <input type="radio" name="hamil" id="hamil" value="2" disabled autocomplete="off" @if($penduduk->hamil == 2) checked @endif> Hamil
            </label>
        </div>
    </div>
</div>