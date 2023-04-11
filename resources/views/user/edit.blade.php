@extends('layouts.dashboard_template')

@section('content')
<!-- Content Header (Page header) -->
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
	<div class="box-body">
		{!! Form::model($user, ['route'=>['setting.user.update', $user->id], 'method' => 'put', 'autocomplete' => 'off', 'id' => 'form-user',  'class' => 'form-horizontal form-label-left', 'files' => true,]) !!}
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