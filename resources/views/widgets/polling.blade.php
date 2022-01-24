<div class="box-header text-center  with-border">
    <h2 class="box-title">JAJAK PENDAPAT</h2>
</div>
   <div class="pad text-bold bg-white" >
       <?php
       use Inani\Larapoll\Poll;
       $question = Poll::with('options')->latest()->first();
       ?>
<form method="POST" action="{{ route('poll.vote', $question->id) }}" >
    @csrf
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{ $question->question }}</h3>
        </div>
        <ul class="list-group">
            @foreach($question->options as $id => $option)
                <li class="list-group-item">
                    <div class="radio">
                        <label>
                            <input value="{{ $option->id }}" type="radio" name="options">
                            {{ $option->name }}
                        </label>
                    </div>
                </li>
            @endforeach
        </ul>
        <input type="submit" class="btn btn-primary btn-flat btn-block" value="Vote" />
</div>
</form>
 </div>
<!-- /.col -->