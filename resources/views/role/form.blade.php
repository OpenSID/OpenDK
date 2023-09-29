<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
	<label class="control-label" for="first-name">Nama <span class="required">*</span></label>
		{!! Form::text( 'name', null, [ 'class' => 'form-control', 'placeholder' => 'Nama', 'required' => true] ) !!}
</div>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Daftar Menu</th>
			<th class="non-user text-center">
				@php
				if (isset($role)) {
					$format = json_decode(json_encode($role),true);
					$rolePermission = count($format['permissions']) + 1;
					$roleMenu = count($menu);
				} else {
					$rolePermission = 0;
					$roleMenu = 1;
				}
				@endphp

				<div class="checkbox checkbox-custom checkbox-primary" style="margin-top:0px !important;margin-bottom:0px !important;">
					@if($rolePermission == $roleMenu)
					{!! Form::checkbox( 'create-all', 1, null, ['checked']) !!}
					@else
					{!! Form::checkbox( 'create-all', false, null) !!}
					@endif
					<label>
						Select All
					</label>
				</div>
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach( $permissions as $key => $permission )
		@if( $permission['parent_id'] == 0)
		@php
		$childs = define_child($permission['id']);
		if (isset($role)) {
			$permission_val = permission_val($role->id,$permission['slug']);
			$myrole = $role->id;
		} else {
			$permission_val = 0;
			$myrole = 0;
		}
		@endphp
		@if($permission['slug'] != 'admin')
		<tr>
			<td>
				<ul>
					<li>
						{{ $permission['name'] }}
						<ul>
							@foreach($childs as $child)
							<li>{{ ucfirst($child->name) }}</a></li>
							@endforeach
						</ul>
					</li>
				</ul>
			</td>
			<td class="non-user text-center">
				<ul style="list-style:none">
					<li>
						<div class="checkbox checkbox-custom checkbox-primary" style="margin-top:0px !important;margin-bottom:0px !important;">
							{!! Form::checkbox( 'permissions['.$permission['slug'].']', $permission_val , null, [ 'class' => 'create-box parent','data-id' => $permission['slug']]) !!}
							<label>
								@if($permission_val == 1)
								<span class="label label-outline label-success">Active</span>
								@else
								<span class="label label-outline label-danger">InActive</span>
								@endif
							</label>
						</div>
					</li>
					@foreach($childs as $child)
					<li>
						<div class="checkbox checkbox-custom checkbox-primary" style="margin-top:0px !important;margin-bottom:0px !important;">
							{!! Form::checkbox( 'permissions['.$child->slug.']', permission_val($myrole,$child->slug), null, [ 'class' => 'create-box child-'.$permission['slug'].'' ]) !!}
							<label>
								@if(permission_val($myrole,$child->slug) == 1)
								<span class="label label-outline label-success">Active</span>
								@else
								<span class="label label-outline label-danger">InActive</span>
								@endif
							</label>
						</div>
					</li>
					@endforeach
				</ul>
			</td>
		</tr>
		@endif
		@endif
		@endforeach
	</tbody>
</table>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
		<a href="{{ route('setting.role.index') }}"><button class="btn btn-default" type="button">Batal</button></a>
		<button type="submit" class="btn btn-primary">Simpan</button>
	</div>
</div>
@push( 'scripts' )
<script type="text/javascript">
	$( 'input[name=create-all]' ).on('click',function(){
		$(".create-box").prop('checked', $(this).prop('checked'));
		$(".create-box").each(function(n,v){
			if ($(this).prop('checked')){
				$(this).val(1);
				$(this).parent().find('span').removeClass('label-danger').addClass('label-success');
				$(this).parent().find('span').html('Active');
			} else {
				$(this).val(0);
				$(this).parent().find('span').removeClass('label-success').addClass('label-danger');
				$(this).parent().find('span').html('InActive');
			}
		});
	});

	$(document).on('change',".create-box",function(){
		var count_active = 0;
		var count_inactive = 0;
		var active = 0;
		var inactive = 0;
		var base = $(".create-box").length;
		var _this = $(this);
		if (_this.prop('checked')){
			_this.val(1);
			_this.parent().find('span').removeClass('label-danger').addClass('label-success');
			_this.parent().find('span').text('Active');

			if (_this.hasClass('parent')) {
				var parent = _this.data('id');
				$(".child-"+parent+"").each(function(){
					$(this).parent().find('span').removeClass('label-danger').addClass('label-success');
					$(this).parent().find('span').text('Active');
					$(this).prop('checked',true);
					$(this).val(1);
				});
			}

		} else {
			_this.val(0);
			_this.parent().find('span').removeClass('label-success').addClass('label-danger');
			_this.parent().find('span').text('InActive');

			if (_this.hasClass('parent')) {
				var parent = _this.data('id');
				$(".child-"+parent+"").each(function(){
					$(this).parent().find('span').removeClass('label-success').addClass('label-danger');
					$(this).parent().find('span').text('InActive');
					$(this).prop('checked',false);
					$(this).val(0);
				});
			}

		}

		$(".create-box").each(function(k){
			if ($(this).parent().find('span').text() == 'Active'){
				active = k;
			}

			if($(this).parent().find('span').text() == 'InActive'){
				inactive = k;
			}

		});

		if (inactive == 0) {
			$( 'input[name=create-all]' ).prop('checked',true);
		} else {
			$( 'input[name=create-all]' ).prop('checked',false);
		}

	});
</script>
@endpush
