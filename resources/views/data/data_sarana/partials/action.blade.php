
<div class="btn-group">
    <a href="{{ $editUrl ?? '#' }}">
        <button class="btn btn-success btn-sm" style="width: 40px;">
            <i class="fa fa-edit" aria-hidden="true"></i>
        </button>
    </a>
    
    <div style="display: inline-block; margin-left: 4px">
        <form action="{{ $deleteUrl ?? '#' }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-icon btn-danger btn-sm" style="width: 40px" onclick="return confirm('Yakin ingin menghapus data ini?')">
                <i class="fa fa-trash" aria-hidden="true"></i>
            </button>
        </form>
    </div>
</div>