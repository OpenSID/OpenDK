@extends('layouts.app')
@section('title','Beranda')
@push('css')
<style>
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

.page-header strong{
  padding-left: 90px;
  font-family: 'Lato', sans-serif;
}

@media(max-width:980px){
  .page-header strong{
  font-size: 18px;
}

.card-body p{
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
  .title-article{
    font-size: 14px;
    font-family: 'Lato', sans-serif;
    /* font-family: 'Varela', sans-serif; */
    /* font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: 400; line-height: 21px;  */

  }

</style>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
@endpush
@section('content')
<div class="col-md-8">
  <div class="fat-arrow">
    <div class="flo-arrow"><i class="fa fa-globe fa-lg fa-spin"></i></div>
  </div>
  <section class="page-header" style="margin:0px 0px;">
  <strong class="">{{ $page_description }}
  </strong>
</section>
  @forelse ($feeds as $item)
        <div class="card flex-md-row mb-4 box-shadow h-md-250">
          <div class="card-body d-flex flex-column align-items-start">
            <h4 > <a class="text-dark" href="{{ $item['link'] }}" target="_blank">
            <strong class="d-inline-block mb-2  text-primary title-article">{{ strtoupper($item['title']) }}</strong></a>
            </h4>
            <div class="mb-1 text-muted" style="font-size: 10px"> <i class="fa fa-link"></i> {{ ucwords($item['feed_title']) }} | <i class="fa fa-user-circle-o"></i> {{ $item['author']}}  @if(isset($item['date'])) | <i class="fa fa-calendar-o"></i> {!! \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y H:i') !!} @endif</div>
            <hr style="margin-top:0px">
            <div class="divider"></div>
            <img class="card-img-right flex-auto d-none d-lg-block" data-src="holder.js/200x250?theme=thumb" alt="Thumbnail [200x250]" style="width: 100%; height: 100%; display;block;" src="{{ get_tag_image($item['description']) }}" data-holder-rendered="true">
            <p class="card-text mb-auto" style=" text-align: justify;">{{ strip_tags($item['description']) }}</p>
            <a href="{{ $item['link'] }}" target="_blank">Baca Selengkapnya</a>
          </div>
        </div>
        <br>
        @empty
        <div class="text-center">
            <p class="text-bold">Tidak ada berita yang ditampilkan!</p>
        </div>
        @endforelse
        {{ $feeds->links() }}
</div>
@endsection


