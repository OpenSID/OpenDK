@foreach($jawabans as $jawab)
<div class="media well well-sm">
    <div class="media-left">
        <a href="#">
            <img class="media-object" src="http://placehold.it/32x32" alt="...">
        </a>
    </div>
    <div class="media-body">
        @if($jawab->penjawab == 'Admin')
        <h4 class="media-heading">Dijawab oleh: {{ $jawab->penjawab }}</h4>
        @elseif($jawab->penjawab == $jawab->komplain->nik)
            <h4 class="media-heading">Dijawab oleh: Pelapor</h4>
        @else
            <h4 class="media-heading">Dijawab oleh: {{ $jawab->penjawab_komplain->nama }}</h4>
        @endif

        <p>{{ $jawab->jawaban }}</p>
    </div>
</div>
@endforeach