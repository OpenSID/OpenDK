@extends('layouts.app')

@section('content')
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-bold">{{ $event->event_name }}</h3>
            </div>
            <div class="box-body">
                <p> <i class="fa fa-calendar" aria-hidden="true"></i> {{ date('d M Y H:i:s', strtotime($event->start)) }} - {{ date('d M Y H:i:s', strtotime($event->end)) }}</p>
                
                <hr>
                <div class="event-description">
                    {!! $event->description !!}
                </div>
                
                @if($event->attendants)
                <hr>
                <p><strong><i class="fa fa-users" aria-hidden="true"></i> Yang Akan Hadir:</strong> {{ $event->attendants }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
