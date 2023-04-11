@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
	<h1>
		{{ $page_title ?? "Page Title" }}
		<small>{{ $page_description ?? '' }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{ route('setting.role.index') }}">{{ $page_title }}</a></li>
		<li class="active">{{ $page_description }}</li>
	</ol>
</section>

<section class="content">
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Roles</h3>
	</div>
	<div class="box-body">
		{!! Form::model($role, ['route'=>['setting.role.update', $role->id], 'method' => 'put', 'autocomplete' => 'off', 'id' => 'form-role']) !!}
			@include('role.form')
		{!! Form::close() !!}
	</div>
</div>
</section>
@endsection
