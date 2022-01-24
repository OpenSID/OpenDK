<div class="box-header with-border text-center">
    <h4 class="box-title">AGENDA KEGIATAN</h4>
</div>
<br>
<ul class="timeline">
        @forelse($events as $key => $event)
            @foreach($event as $value)
            <li>
                <i class="fa fa-calendar @if($value->status=='OPEN') bg-maroon @else bg-gray @endif"></i>
                <div class="timeline-item">
                    <h6 class="timeline-header">{{ link_to('event/' . $value->slug, strtoupper($value->event_name)) }}</h6>
                    <small class="text-yellow"><i class="fa fa-clock-o"></i> {{ Carbon\Carbon::parse($value->start)->translatedFormat('d F Y') }}</small>
                </div>
            </li>
            <br/>
            @endforeach
    @empty
        <!-- <li class="time-label"> -->
            <h6 class="text-center text-muted">Belum ada agenda kegiatan yang ditampilkan</h6>
        <!-- </li> -->
    @endforelse
</ul>
