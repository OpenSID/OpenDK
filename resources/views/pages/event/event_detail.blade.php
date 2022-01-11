@extends('layouts.app   ')
@section('content')
<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-body">
            <h4> {{ $event->event_name }}</h4>
            <p> <i class="fa fa-calendar" aria-hidden="true"></i> {{ date('d M Y H:i:s', strtotime($event->start)) }}  - {{ date('d M Y H:i:s', strtotime($event->end)) }}</p>
            <p>{!! $event->description !!}</p>
            <p>Yang Akan Hadir : {!! $event->attendants !!}</p>
        </div>
    </div>
</div>
@endsection

