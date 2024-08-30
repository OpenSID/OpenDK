<div class="col-md-4">
    <div class="box box-widget">
        @include('widgets.camat')
    </div>
    @if (count($sinergi) > 0)
        <div class="box box-widget">
            @include('widgets.sinergi_program')
        </div>
    @endif
    <div class="box box-widget">
        @include('widgets.komplain')
    </div>
    <div class="box box-widget">
        @include('widgets.event')
    </div>
    @if (count($pengurus) > 0)
        <div class="box box-widget">
            @include('widgets.pengurus')
        </div>
    @endif
    @if (count($medsos) > 0)
        <div class="box box-widget">
            @include('widgets.media_sosial')
        </div>
    @endif
    <div class="box box-widget">
        @include('widgets.visitor')
    </div>
</div>
