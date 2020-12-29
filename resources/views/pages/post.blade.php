@extends('layouts.app')
@section('title','Beranda')
@push('css')
<style>
p{
font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}

h4{
    
    text-transform: uppercase;
    
}
.post {
    width: 350px;
    border: 0px solid blue;
    overflow: hidden;
    position: relative;
    float: left;
    display: inline-block;
    cursor: pointer;
    margin-right: 20px;
}

.post img {
  border: 0 solid;
  transition: wi2s;
  width: 100%;
  max-width:350px;
  margin: 0 auto;
  clear: both;

}
#myImg:hover {
  transform: scale(1.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
  box-shadow: 2px 2px 4px #000000;
}
</style>
@endpush
@section('content')
<div class="col-md-8 no-padding fadeIn">
    @foreach ($artikel->channel->item as $data)
    <div class="card-body d-flex flex-column align-items-start">
        <div class="box-body">
            <div class="post">
                {{-- @if(! $data->enclosure['url'] == '') --}}
                <img  class="pull-left img-responsive postImg img-thumbnail margin10" src="{{utf8_decode($data->enclosure['url'])}}" alt="{{ $data->title }}">
            {{-- @else
            <img class="img-thumb responsive" src="{{ asset('/img/no-image-post.png') }}" alt="Logo {{ $sebutan_wilayah }}">
            @endif --}}
        </div>
            <h4 class="text-bold">{{ $data->title }}</h4>
                    <small class="text-muted"> <i class="fa fa-clock-o"></i> {!! $data->pubdate !!} </small>
            {{-- <small class="text-muted"><i class="fa fa-user-o fa-xs"></i> Administrator </small>
            <small class="text-muted"><i class="fa fa-comments-o"></i> 0 </small>
            <small class="text-muted"><i class="fa fa-eye"></i> 10 </small> --}}
            <p class="text-justify card-text mb-auto" style="margin: 0px 10px">{!!  $data->description  !!}<a href="{{ $data->link }}" class="btn btn-default btn-xs btn-round">Baca Selengkapnya</a></p>
        </div>
       
            {{-- <div class="pull-right text-muted">
                 <ul class="sharers social-icon list-inline">
                    <li><a class="btn btn-primary btn-xs" data-share="facebook" data-title="{{ $data->title }}" data-link="{{ $data->link }}" href="http://www.facebook.com/sharer.php?u={{ $data->link }}" target="_blank">
                        <i class="fa fa-share"></i> Share
                    </a></li>
                         <li>  
                            <a class="btn btn-primary btn-xs">
                                <i class="fa fa-thumbs-o-up"></i> Like </a>   
                </ul>
            </div> --}}
    </div>
    @endforeach
</div>
@endsection
@push('scripts')
<script>
    $(function() {
    $('.sharers').socialSharers({
    twitter: {
    handle: 'iamdirgarebon'
    },
    facebook: {
    // appID: '3756463867708007' 
    },
    googleplus: {
    // no options yet
    }
    });
    });
    </script>
@endpush

