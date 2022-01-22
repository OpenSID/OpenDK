<div class="box-header text-center  with-border bg-blue">
    <h2 class="box-title text-bold"> <span class="glyphicon glyphicon-comment"></span> JAJAK PENDAPAT</h2>
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
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-arrow-right"></span> {{ $question->question }}
            </h3>
        </div>
    </div>
    <div class="panel-body">
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
    </div>
    {{-- <div class="panel-footer"> --}}
        <input type="submit" class="btn btn-primary btn-block" value="Vote" />
    {{-- </div> --}}
</form>

    </div>
<!-- /.col -->