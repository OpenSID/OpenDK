@extends( 'layouts.dashboard_template' )

@section('title') UBah Pengguna @endsection

@section( 'content' )
<section class="content-header">
	<h1>
	Ubah Pengguna
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{ route('setting.user.index') }}">Pengguna</a></li>
		<li class="active">Ubah</li>
	</ol>
</section>

<section class="content">
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Users</h3>
	</div>
	<div class="box-body">
	 	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<strong>Whoops!</strong> There were some problems with your input.<br><br>
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>

		</div>

		@endif
		{!! Form::model($user, ['route'=>['setting.user.update', $user->id], 'method' => 'put', 'autocomplete'=>'off', 'id'=>'form-user',  'class' => 'form-horizontal form-label-left', 'files' => true,]) !!}
			@include('user.form')
		{!! Form::close() !!}
	</div>
</div>
</section>
@endsection

@push( 'scripts' )
{!! JsValidator::formRequest('App\Http\Requests\UserRequest', '#form-user') !!}
<script type="text/javascript">
$('.showpass').hover(function () {
   $('.password').attr('type', 'text'); 
}, function () {
   $('.password').attr('type', 'password'); 
});
</script>
@endpush