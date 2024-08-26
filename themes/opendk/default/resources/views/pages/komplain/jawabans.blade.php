@foreach ($jawabans as $jawab)
    <div class="media well well-sm">
        <div class="media-left"></div>
        <div class="media-body">
            @if ($jawab->penjawab == 'Admin')
                <h4 class="media-heading">Dijawab oleh: {{ $jawab->penjawab }}</h4>
            @elseif($jawab->penjawab == $jawab->komplain->nik)
                <h4 class="media-heading">Dikomentari oleh: Pelapor</h4>
            @else
                <h4 class="media-heading">Dikomentari oleh: {{ $jawab->penjawab_komplain->nama }}</h4>
            @endif

            <p>{{ $jawab->jawaban }}</p>

            <div class="pull-right">
                <div class="control-group">
                    @php $user = auth()->user(); @endphp
                    @if (isset($user) && $user->hasRole(['super-admin', 'admin-kecamatan', 'admin-komplain']) && $jawab->penjawab == 'Admin' && $komplain->status != 'SELESAI')
                        <a id="btn-ubah-reply-admin" data-href="{{ route('admin-komplain.getkomentar', $jawab->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-reply"></i> Ubah Jawaban</a>

                        {!! Form::open(['method' => 'DELETE', 'route' => ['admin-komplain.deletekomentar', $jawab->id], 'style' => 'display:inline']) !!}
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin akan menghapus data tersebut?')"><i class="fa fa-trash margin-r-5"></i> Hapus
                        </button>
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
