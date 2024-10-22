@if (isset($create_url))
    <a href="{{ $create_url }}">
        <button type="button" class="btn btn-success btn-sm btn-social" title="{{ $create_text ?? 'Tambah' }}">
            <i class="fa fa-plus"></i>{{ $create_text ?? 'Tambah' }}
        </button>
    </a>
@endif

@if (isset($import_url))
    <a href="{{ $import_url }}">
        <button type="button" class="btn btn-warning btn-sm btn-social" title="{{ $import_text ?? 'Impor' }}">
            <i class="fa fa-upload"></i>{{ $import_text ?? 'Impor' }}
        </button>
    </a>
@endif

@if (isset($export_url))
    <a href="{{ $export_url }}">
        <button type="button" class="btn btn-primary btn-sm btn-social" title="{{ $export_text ?? 'Ekspor' }}">
            <i class="fa fa-download"></i>{{ $export_text ?? 'Ekspor' }}
        </button>
    </a>
@endif

@if (isset($desa_url))
    <form action="{{ $desa_url }}" method="POST" class="inline">
        {{ csrf_field() }}
        <button type="submit" class="btn bg-purple btn-sm btn-social" title="Ambil Data Desa Dari TrackSID">
            <i class="fa fa-retweet"></i>{{ $desa_text ?? 'Ambil Desa' }}
        </button>
    </form>
@endif

@if (isset($back_url))
    <a href="{{ $back_url }}">
        <button type="button" class="btn btn-info btn-sm btn-social" title="{{ $back_text ?? 'Kembali' }}">
            <i class="fa fa-arrow-left"></i> {{ $back_text ?? 'Kembali' }}
        </button>
    </a>
@endif

@if (isset($modal_url))
    <button type="button" class="btn btn-success btn-sm btn-social" data-toggle="modal" data-target="{{ $modal_url }}">
        <i class="fa fa-plus"></i>{{ $modal_text ?? 'Tambah' }}
    </button>
@endif
