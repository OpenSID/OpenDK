@foreach($comments as $comment)
    <div class="display-comment" @if($comment->parent_id != null) style="margin-left:40px;" @endif>
    <ul class="timeline">
	<!-- timeline item -->
	<li>
		<div class="timeline-item">
		<span class="time"><i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }} </span>

		<h3 class="timeline-header"><a href="#"><div class="avatar avatar-xs">
            <img style="width:20px;height:20px;" src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="..." class="avatar-img rounded-circle">
        </div> </a>
    </h3>
		<div class="timeline-body">
		{{ $comment->komentar }}
		</div>
        <div class="timeline-footer"> 
            <a href="" id="reply"></a>
        <form method="post" action="{{ route('komentar.store') }}">
            @csrf
                <input type="text" name="komentar" class="form-control" />
                <input type="hidden" name="artikel_id" value="{{ $artikel_id }}" />
                <input type="hidden" name="parent_id" value="{{ $comment->id }}" />
                <br>
                <div class="pull-right">
                    <button type="submit" class="btn btn-xs btn-default"><i class="fa fa-reply"></i> Reply</button>
                </div>
                <div class="clearfix"></div>
        </form>
        @include('pages.berita._komentar', ['comments' => $comment->replies])
        </div>
	</li>
	<!-- END timeline item -->
</ul>
    </div>
@endforeach