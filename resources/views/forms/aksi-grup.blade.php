<div class="btn-group">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Aksi <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        @if (!empty($preview_url))
        <li>
            <a href="javascript:void(0)" class="btn-preview" data-url="{!! $preview_url !!}" data-testid="btn-lihat">
                <i class="fa fa-eye text-info"></i> Lihat
            </a>
        </li>
        @endif
        @if (!empty($show_url))
        <li>
            <a href="{!! $show_url !!}" data-testid="btn-lihat">
                <i class="fa fa-eye text-info"></i> Lihat
            </a>
        </li>
        @endif
        @if (!empty($show_web))
        <li>
            <a href="{!! $show_web !!}" target="_blank" data-testid="btn-lihat-web">
                <i class="fa fa-external-link text-info"></i> Lihat
            </a>
        </li>
        @endif
        @if (!empty($edit_url))
        <li>
            <a href="{!! $edit_url !!}" data-testid="btn-edit">
                <i class="fa fa-edit text-success"></i> Edit
            </a>
        </li>
        @endif
        @if (!empty($download_url))
        <li>
            <a href="{!! $download_url !!}" data-testid="btn-download">
                <i class="fa fa-download text-info"></i> Unduh
            </a>
        </li>
        @endif
        @if (!empty($delete_url))
        <li role="separator" class="divider"></li>
        <li>
            <a href="javascript:void(0)" data-href="{!! $delete_url !!}" data-button="delete" id="deleteModal" class="text-danger" data-testid="btn-hapus">
                <i class="fa fa-trash text-danger"></i> Hapus
            </a>
        </li>
        @endif
    </ul>
</div>
