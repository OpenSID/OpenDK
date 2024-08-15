<div class="col-md-4">
    @foreach ($widgets as $widget)
        @php
            $isi = explode('.', $widget->isi);
        @endphp
        @if ($isi[0] == 'camat')
            <div class="box box-widget">
                @include('widgets.camat')
            </div>
        @elseif($isi[0] == 'sinergi_program')
            @if(count($sinergi) > 0)
            <div class="box box-widget">
                @include('widgets.sinergi_program')
            </div>
            @endif
        @elseif($isi[0] == 'komplain')
            <div class="box box-widget">
                @include('widgets.komplain')
            </div>  
        @elseif ($isi[0] == 'event')
            <div class="box box-widget">
                @include('widgets.event')
            </div>  
        @elseif ($isi[0] == 'pengurus')
            @if(count($pengurus) > 0)
            <div class="box box-widget">
                @include('widgets.pengurus')
            </div>  
            @endif
        @elseif ($isi[0] == 'media_sosial')
            @if(count($medsos) > 0)
            <div class="box box-widget">
                @include('widgets.media_sosial')
            </div>  
            @endif
        @elseif ($isi[0] == 'visitor')
            <div class="box box-widget">
                @include('widgets.visitor')
            </div>
        @else
            <div class="box box-widget">
                <div class="box-header text-center  with-border bg-blue">
                    <h2 class="box-title text-bold">{{$widget->judul}}</h2>
                </div>
                <div class="pad text-bold bg-white">
                    {!! $widget->isi !!}
                </div>
            </div>
        @endif
    @endforeach
</div>
