<div class="box-header with-border text-center  bg-blue">
    <h2 class="box-title text-bold">AGENDA KEGIATAN</h2>
</div>
<br>
    <ul class="timeline">
        @if(count($events) > 0)
            @foreach($events as $key => $event)
                @foreach($event as $value)
                <li>
                    <i class="fa fa-calendar @if($value->status=='OPEN') bg-maroon @else bg-gray @endif"></i>
                    <div class="timeline-item">
                        <h3 class="timeline-header">{{ link_to('event/'.$value->slug,strtoupper($value->event_name)) }}</h3>
                        <small class="text-yellow"><i class="fa fa-clock-o"></i> {{ Carbon\Carbon::parse($value->start)->translatedFormat('d F Y') }}</small>
                    </div>
                </li>
                @endforeach
            @endforeach
        @else
            <li class="time-label">
                <span class="bg-gray">
                    Event tidak tersedia.
                </span>
            </li>
        @endif
        </ul>
