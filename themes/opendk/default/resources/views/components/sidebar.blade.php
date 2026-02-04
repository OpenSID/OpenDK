<div class="col-md-4">
    @if ($widgetAktif)
        @foreach ($widgetAktif as $widget)
            @php
                $namaWidget = $widget['isi'];
                $bolehTampil = true;
                // With API-based loading, we show all widgets and let the API populate them
                // The loading placeholders will be replaced with actual data via JavaScript
            @endphp

            @if ($bolehTampil)
                <div class="box box-widget">
                    @if ($widget['jenis_widget'] == 3)
                        <div class="box-header">
                            <h3 class="box-title">{{ strip_tags($widget['judul']) }}</h3>
                        </div>
                        <div class="box-body">
                            {!! html_entity_decode($widget['isi']) !!}
                        </div>
                    @else
                        @includeIf("widgets.{$namaWidget}", ['judul' => $widget['judul']])
                    @endif
                </div>
            @endif
        @endforeach
    @endif
</div>
