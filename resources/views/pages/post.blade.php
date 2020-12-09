@extends('layouts.app')
@section('title') Profil @endsection
@section('content')
<div class="col-md-8">
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Lorem Ipsum</h3>
        </div>
        <div class="box-body">
            <!-- The Modal -->
            <img id="myImg"  style="float:left; width:100%; max-width:200px; " src="{{ asset('/post/photo1.png') }}">
            
            <!-- The Modal -->
            <div id="myModal" class="modal">
                <!-- The Close Button -->
                <span class="close">&times;</span>
                <!-- Modal Content (The Image) -->
                <img class="modal-content" id="img01">
                <!-- Modal Caption (Image Text) -->
                <div id="caption"></div>
            </div>
            <p class="text-justify clearfix" style="margin: 0px 10px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac viverra ligula, eget vestibulum enim. Aenean eleifend turpis urna, non mattis quam dapibus sed. Praesent quis eleifend magna. Proin et nunc neque. Vestibulum ullamcorper dui consectetur, aliquam metus at, aliquam risus. Duis ornare nibh in tincidunt mollis. Aliquam erat volutpat. Vestibulum vel massa sit amet lorem sollicitudin condimentum eu eget arcu. Vestibulum sed eros fermentum, sagittis erat vitae, luctus risus. Vestibulum id nisi in odio feugiat pulvinar quis eu velit. Fusce sit amet eros sit amet magna vehicula laoreet. Suspendisse id sodales arcu.
                Vestibulum placerat dictum felis a accumsan. Duis feugiat rutrum tellus quis eleifend. <a href="" class="btn btn-default btn-xs btn-round">Baca Selengkapnya</a></p>
        </div>
        <div class="box-footer">
            <small class="text-muted"> <i class="fa fa-clock-o"></i> {{ now('d m y')->translatedFormat('d F Y') }} </small>
            <small class="text-muted"><i class="fa fa-user-o fa-xs"></i> Administrator </small>
            <small class="text-muted"><i class="fa fa-comments-o"></i> 0 </small>
            <small class="text-muted"><i class="fa fa-eye"></i> 10 </small>
            
            <div class="pull-right text-muted">
                <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-share"></i> Share</button>
                <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button>
            </div>
        </div>
    </div>
</div>
@endsection



