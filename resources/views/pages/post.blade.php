@extends('layouts.app')
@section('title') Profil @endsection
@section('content')
<div class="col-md-8">
    @php
        $maxdata= 10;
    @endphp
    @foreach ($artikel->item as $data)
    @if($data->count() == 5)  
    @continue    
        
    @endif 
    
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $data->title }}</h3>
        </div>
        <div class="box-body">
            <!-- The Modal -->
            <img id="myImg"  style="float:left; width:100%; max-width:200px; " src="{{utf8_decode((string)$data->enclosure['url'])}}">
            <p class="text-justify clearfix" style="margin: 0px 10px">{!!  $data->description  !!}<a href="{{ $data->link }}" class="btn btn-default btn-xs btn-round">Baca Selengkapnya</a></p>
        </div>
        <div class="box-footer">
            <small class="text-muted"> <i class="fa fa-clock-o"></i> {!! $data->pubdate !!} </small>
            <small class="text-muted"><i class="fa fa-user-o fa-xs"></i> Administrator </small>
            <small class="text-muted"><i class="fa fa-comments-o"></i> 0 </small>
            <small class="text-muted"><i class="fa fa-eye"></i> 10 </small>
            
            <div class="pull-right text-muted">
                 <ul class="sharers social-icon list-inline">
                    <li><a class="btn btn-primary btn-xs" data-share="facebook" data-title="{{ $data->title }}" data-link="{{ $data->link }}" href="http://www.facebook.com/sharer.php?u={{ $data->link }}" target="_blank">
                        <i class="fa fa-share"></i> Share
                    </a></li>
                         <li>  
                            <a class="btn btn-primary btn-xs">
                                <i class="fa fa-thumbs-o-up"></i> Like </a>   
                </ul>
            </div>
        </div>
    </div>
    @if ($data->count() == $maxdata) 
    @break;
@endif
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

