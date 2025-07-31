<div class="col-md-4">
    @if ($widgetAktif)
        @foreach ($widgetAktif as $widget)
            @php
                $namaWidget = $widget['isi'];
                $bolehTampil = true;

                if ($namaWidget == 'sinergi_program' && (!isset($sinergi) || count($sinergi) == 0)) {
                    $bolehTampil = false;
                }

                if ($namaWidget == 'pengurus' && (!isset($pengurus) || count($pengurus) == 0)) {
                    $bolehTampil = false;
                }

                if ($namaWidget == 'media_sosial' && (!isset($medsos) || count($medsos) == 0)) {
                    $bolehTampil = false;
                }

                if ($namaWidget == 'media_terkait' && (!isset($media_terkait) || count($media_terkait) == 0)) {
                    $bolehTampil = false;
                }
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
