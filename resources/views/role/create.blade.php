@extends( 'layouts.dashboard_template' )

@section('title') Create Role @endsection

@section( 'content' )
<section class="content-header">
	<h1>
	Role Management
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Administrator</a></li>
		<li><a href="#">Roles</a></li>
		<li class="active">Create</li>
	</ol>
</section>

<section class="content">
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Roles</h3>
	</div>
	<div class="box-body">
		{!! Form::open( [ 'route' => 'setting.role.store', 'method' => 'post', 'files' => true, 'id' => 'form-role', 'role' => 'form'] ) !!}
			@include( 'flash::message' )
			@include('role.form')
		{!! Form::close() !!}
	</div>
</div>
</section>

@endsection
