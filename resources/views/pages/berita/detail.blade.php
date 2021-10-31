@extends('layouts.app')
@section('title','Beranda')
@push('css')
<style>
	.select2-container--default {
		padding: 5px !important;
		font-size: 12px !important;
	}

	.card-body {
		padding: 10px;
		background-color: white;
	}

	.page-header{
		background: rgb(0, 43, 105);
		background: linear-gradient(180deg, rgba(0, 43, 105, 1) 0%, rgba(0, 25, 142, 1) 50%, rgba(0, 43, 105, 1) 100%);
		color:white;
	}

	p {
		font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: 400; line-height: 21px;
	}

	.page-header strong {
		padding-left: 90px;
		font-family: 'Lato', sans-serif;
	}

	@media(max-width:980px) {
		.page-header strong{
			font-size: 18px;
		}

		.card-body p {
			text-align: justify;
		}
	}

	.fat-arrow {
		display: flex;
		align-items:center;
		width: 60px;
		height: 40px;
		position: absolute;
		background: #ff4900;
		margin-right: 20px;
		color: white;
		text-align: left;
		line-height: 15px;
	}

	.fat-arrow:before {
		content: "";
		position: absolute;
		right: -20px;
		bottom: 0;
		width: 0;
		height: 0;
		border-left: 20px solid #ff4900;
		border-top: 20px solid transparent;
		border-bottom: 20px solid transparent;
	}

	.flo-arrow {
		display: -webkit-box;
		display: flex;
		-webkit-box-align: center;
		align-items: center;
		justify-content: center;
		width: 30px;
		height: 25px;
		position: absolute;
		left: 3px;
		padding-left: 8px;
		background: #fff;
		color: #000;
		font-weight:bold;
		z-index: 2;
		box-shadow: 2px 2px 3px 0px rgba(0,0,0,0.75);
	}

	.flo-arrow:before {
		content: "";
		position: absolute;
		right: -7px;
		bottom: 0;
		width: 0;
		height: 0;
		border-left: 7px solid #FFF;
		border-top: 13px solid transparent;
		border-bottom: 12px solid transparent;
	}

	.title-artikel {
		font-size: 14px;
		font-family: 'Lato', sans-serif;
		/* font-family: 'Varela', sans-serif; */
		/* font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: 400; line-height: 21px;  */
	}

	.card-horizontal {
		display: flex;
		flex: 1 1 auto;
	}

	img {
		width: 100%;
	}
</style>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
@endpush
@section('content')
<div class="col-md-8">
	<div class="card flex-md-row mb-4 box-shadow h-md-250">
		<div class="card-body d-flex flex-column align-items-start">
			<img src="{{ url($artikel->gambar)}}" alt="{{ $artikel->judul }}"/>
			<div style="padding-bottom: 10px">
				<h3 class="card-title">{{ $artikel->judul }}</h3>
				<p><i class="fa fa-calendar"></i>&ensp;{{ $artikel->created_at->format('d M Y') }}</p>
			</div>
		<div>
		<p class="description-artikel">{!! $artikel->isi !!}</p>
	</div>
</div>
    </div>
</div>

@endsection
@include('partials.asset_select2')

@push('scripts')
<script type="text/javascript">
	$(document).ready(function () {
		$( '#list_desa' ).select2();
		$( "#list_desa" ).change(function() {
			$( "#form_filter" ).submit();
		});

		$(function($){
			$(document).on('submit', '#form_filter', function(event){
				$.ajax({
					url: "{{ route('feeds.filter') }}",
					type: 'get',
					dataType:'json',
					data:$("#form_filter").serialize(),
					success: function(data){
						$("#feeds").html(data.html);
					}
				});
		    event.preventDefault();
			})
		})

	});
</script>
@endpush
