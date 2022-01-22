<div class="btn-group" id="exampleToolbar" role="group">
    @if( isset( $detail_url ) )
        <a href="{!! empty( $detail_url ) ? 'javascript:void(0)' : $detail_url !!}" class="{!! empty( $detail_url ) ? 'disabled' : '' !!}" title="Detail" data-button="detail">
            <button type="button" class="btn btn-info btn-sm" style="width: 40px;"><i class="fa fa-list fa-fw"></i></button>
        </a>
    @endif
    @if( isset( $show_url ) )
            <a href="{!! empty( $show_url ) ? 'javascript:void(0)' : $show_url !!}" class="{!! empty( $show_url ) ? 'disabled' : '' !!}" title="Lihat" data-button="show">
                <button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye fa-fw"></i></button>
            </a>
    @endif
    @if( isset( $edit_url ) )
        <a href="{!! empty( $edit_url ) ? 'javascript:void(0)' : $edit_url !!}" class="{!! empty( $edit_url ) ? 'disabled' : '' !!}" title="Ubah" data-button="edit">
            <button type="button" class="btn btn-success btn-sm" style="width: 40px;"><i class="fa fa-edit" aria-hidden="true"></i></button>
        </a>
    @endif
    @if( isset( $reply_url ) )
        <a href="{!! empty( $reply_url ) ? 'javascript:void(0)' : $reply_url !!}" class="{!! empty( $reply_url ) ? 'disabled' : '' !!}" title="Reply" data-button="reply">
            <button type="button" class="btn btn-primary btn-sm" style="width: 40px;"><i class="fa fa-reply" aria-hidden="true"></i></button>
        </a>
    @endif
    @if( isset( $delete_url ) )
        <a href="javascript:void(0)" class="{!! empty( $delete_url ) ? 'disabled' : '' !!}" title="Hapus" data-href="{!! empty( $delete_url ) ? 'javascript:void(0)' : $delete_url !!}" data-button="delete" id="deleteModal">
            <button type="button" class="btn btn-icon btn-danger btn-sm" style="width: 40px"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </a>
    @endif
    @if( isset( $active_url ) )
        <a href="javascript:void(0)" class="{!! empty( $active_url ) ? 'disabled' : '' !!}" title="Aktif" data-href="{!! empty( $active_url ) ? 'javascript:void(0)' : $active_url !!}" data-button="delete" id="activeModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #1FF43E; border-color: #1FF43E;"><i class="fa fa-check" aria-hidden="true"></i></button>
        </a>
    @endif
    @if( isset( $suspend_url ) )
        <a href="javascript:void(0)" class="{!! empty( $suspend_url ) ? 'disabled' : '' !!}" title="Suspend" data-href="{!! empty( $suspend_url ) ? 'javascript:void(0)' : $suspend_url !!}" data-button="delete" id="suspendModal">
            <button type="button" class="btn btn-icon btn-danger btn-sm" style="width: 40px; background-color: #FFA500; border-color: #FFA500;"><i class="fa fa-power-off" aria-hidden="true"></i>
            </button>
        </a>
    @endif
    @if( isset( $suspend_span ) )
        <a href="javascript:void(0)" class="{!! empty( $suspend_span ) ? 'disabled' : '' !!}" title="Suspend" data-href="{!! empty( $suspend_span ) ? 'javascript:void(0)' : $suspend_span !!}" data-button="delete" id="suspendModal">
            <span style="color: green;">Active</span>
        </a>
    @endif
    @if( isset( $download_url ) )
        <a href="{!! empty( $download_url ) ? 'javascript:void(0)' : $download_url !!}" class="{!! empty( $download_url ) ? 'disabled' : '' !!}" title="Unduh" data-button="download">
            <button type="button" class="btn btn-info btn-sm" style="width: 40px;"><i class="fa fa-download"></i></button>
        </a>
    @endif
    @if( isset( $agree_url ) )
        <a href="javascript:void(0)" class="{!! empty( $agree_url ) ? 'disabled' : '' !!}" title="Setuju" data-href="{!! empty( $agree_url ) ? 'javascript:void(0)' : $agree_url !!}" data-button="delete" id="agreeModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #1FF43E; border-color: #1FF43E;"><i class="fa fa-check" aria-hidden="true"></i>
            </button>
        </a>
    @endif
    @if( isset( $show_web ) )
        <a href="{!! empty( $edit_url ) ? 'javascript:void(0)' : $show_web !!}" class="{!! empty( $show_web ) ? 'disabled' : '' !!}" title="Detail" data-button="detail" target="_blank">
            <button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </a>
    @endif
</div>
