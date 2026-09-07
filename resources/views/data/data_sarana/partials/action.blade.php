<div class="btn-group" style="display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
    <a href="{{ $editUrl ?? '#' }}" class="btn btn-success btn-sm" style="width: 40px;" title="Ubah">
        <i class="fa fa-edit" aria-hidden="true"></i>
    </a>

    <form action="{{ $deleteUrl ?? '#' }}" method="POST" style="display: inline-block; margin: 0;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-icon btn-danger btn-sm" style="width: 40px;" data-confirm="Yakin ingin menghapus data ini?" title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
        </button>
    </form>
</div>
