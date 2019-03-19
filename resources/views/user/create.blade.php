@extends( 'layouts.dashboard_template' )

@section('title') Tambah Pengguna @endsection

@section( 'content' )
<section class="content-header">
	<h1>
	Tambah Pengguna
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route('setting.user.index')}}">Pengguna</a></li>
		<li class="active">Tambah</li>
	</ol>
</section>

<section class="content">
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah</h3>
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
		{!! Form::open( [ 'route' => 'setting.user.store', 'method' => 'post', 'files' => true, 'id' => 'form-user', 'class' => 'form-horizontal form-label-left' ] ) !!}
		@include( 'flash::message' )
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