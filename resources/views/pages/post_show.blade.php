@extends('layouts.app')
@section('title') Profil @endsection
@section('content')
<!-- Main content -->
<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-header with-border section-title">
            <h3 class="box-title">Lorem Ipsum</h3>
        </div>
        <div class="box-body">
            <!-- The Modal -->
            <img id="myImg"  style="display:block; width:95%; margin-right: auto;margin-left: auto;" src="{{ asset('/post/photo1.png') }}">
            
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
                Vestibulum placerat dictum felis a accumsan. Duis feugiat rutrum tellus quis eleifend</p>
        </div>
        <div class="box-footer box-comments">
            <div class="box-comment">
                <!-- User image -->
                <img class="img-circle img-sm" src="{{ asset('/uploads/user/user3-128x128.jpg') }}" alt="User Image">

                <div class="comment-text">
                      <span class="username">
                        Maria Gonzales
                        <span class="text-muted pull-right">8:03 PM Today</span>
                      </span><!-- /.username -->
                  It is a long established fact that a reader will be distracted
                  by the readable content of a page when looking at its layout.
                </div>
                <!-- /.comment-text -->
              </div>
        </div>
        <div class="box-footer">
            <form action="#" method="post">
              <img class="img-responsive img-circle img-sm" src="{{ asset('/uploads/user/user1-128x128.jpg') }}" alt="Alt Text">
              <!-- .img-push is used to add margin to elements next to floating images -->
              <div class="img-push">
                <input type="text" class="form-control input-sm" placeholder="Press enter to post comment">
              </div>
            </form>
          </div>
          <!-- /.box-footer -->
    </div>
</div>
@endsection
