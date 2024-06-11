<div class="box box-{{ $active ? 'success' : 'info' }}">
    <div class="box-header with-border text-center">
        <strong>{{ ucwords($name) }}</strong>

        @if ($system)
            <div class="box-tools pull-right" data-toggle="tooltip" title="" data-original-title="Tema Bawaan">
                <i class="fa fa-square text-green"></i>
            </div>
        @endif
    </div>

    <div class="box-body">
        <div class="text-center">
            <center>
                <img style="width:100%; max-height: 160px;" src="{{ url($screenshot) }}" class="img-responsive" alt="{{ $name }}">
            </center>
        </div>
        <br>
        <div class="text-center">
            @if ($active)
                <a href="#" class="btn btn-social btn-success btn-sm" readonly><i class="fa fa-star"></i>Aktif</a>
            @else
                <a href="{{ route('setting.themes.activate', $id) }}" class="btn btn-info btn-sm" title="Aktifkan Tema"><i class="fa fa-star-o"></i></a>
                @include('forms.aksi', ['delete_url' => route('setting.themes.destroy', $id)])
            @endif
            {{-- <a href="{{ route('setting.themes.opt') }}" class="btn bg-navy btn-sm" title="Pengaturan Tema"><i class="fa fa-cog"></i></a> --}}
        </div>
    </div>

</div>
