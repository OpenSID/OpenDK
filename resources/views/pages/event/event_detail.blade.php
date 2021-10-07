@extends('layouts.app   ')
@section('content')
<!-- Main content -->
<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-body">
            <h4> {{ $event->event_name }}</h4>
            <p> <i class="fa fa-calendar" aria-hidden="true"></i> {{ $event->start->format('d M Y') }}  - {{ $event->end->format('d M Y') }}</p>
            <p>{!! $event->description !!}</p>
            <p>Yang Akan Hadir : {!! $event->attendants !!}</p>
        </div>
    </div>
</div>
<!-- /.content -->
@endsection

