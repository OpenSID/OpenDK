<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">New Password</label>
  <div class="col-md-6 col-sm-6 col-xs-12">
  {!! Form::password( 'password', [ 'class' => 'form-control password'] ) !!}
  </div>
  <div class="col-md-1 col-sm-1 col-xs-12">
  <button type="button" class="btn showpass"><i class="fa fa-eye" aria-hidden="true"></i></button>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Confirm Password</label>
  <div class="col-md-6 col-sm-6 col-xs-12">
  {!! Form::password( 'conf-password', [ 'class' => 'form-control'] ) !!}
  </div>
</div>
<input type="hidden" name="status" value="1">


<div class="ln_solid"></div>
<div class="form-group">
  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
    <a href="{{ route('admin.user.index') }}"><button type="button" class="btn btn-pdanger">Cancel</button></a>
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</div>