<div class="btn-group" id="exampleToolbar" role="group">
    @if (isset($arsip_url))
        <a href="{{ $arsip_url }}" class="disabled" title="Arsip" data-button="edit" id="editModal">
            <button type="button" class="btn btn-info btn-sm open_form" style="width: 40px;" data-id="{{ $arsip_url }}" title="Arsip"><i class="glyphicon glyphicon-th-list" aria-hidden="true"></i></button>
        </a>
    @endif

    @if (isset($turun))
        <a href="{!! empty($turun) ? 'javascript:void(0)' : $turun !!}" class="{!! empty($turun) ? 'disabled' : '' !!}" title="Pindah Posisi ke Bawah" data-button="turun">
            <button type="button" class="btn btn-success btn-sm" style="width: 40px;"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($naik))
        <a href="{!! empty($naik) ? 'javascript:void(0)' : $naik !!}" class="{!! empty($naik) ? 'disabled' : '' !!}" title="Pindah Posisi ke Atas" data-button="naik">
            <button type="button" class="btn btn-success btn-sm" style="width: 40px;"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($detail_url))
        <a href="{!! empty($detail_url) ? 'javascript:void(0)' : $detail_url !!}" class="{!! empty($detail_url) ? 'disabled' : '' !!}" title="Selengkapnya" data-button="detail">
            <button type="button" class="btn btn-info btn-sm" style="width: 40px;"><i class="fa fa-list fa-fw"></i></button>
        </a>
    @endif
    @if (isset($show_url))
        <a href="{!! empty($show_url) ? 'javascript:void(0)' : $show_url !!}" class="{!! empty($show_url) ? 'disabled' : '' !!}" title="Lihat" data-button="show" target="_blank">
            <button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye fa-fw"></i></button>
        </a>
    @endif
    @if (isset($peta))
        <a href="{!! empty($peta) ? 'javascript:void(0)' : $peta !!}" class="{!! empty($peta) ? 'disabled' : '' !!}" title="Peta" data-button="peta" target="_blank">
            <button type="button" class="btn btn-info btn-sm" style="width: 40px;"><i class="fa fa-map" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($lock_url))
        <a href="javascript:void(0)" class="{!! empty($lock_url) ? 'disabled' : '' !!}" title="Tidak Aktif" data-href="{!! empty($lock_url) ? 'javascript:void(0)' : $lock_url !!}" data-button="delete" id="lockModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #252725; border-color: #252725;"><i class="fa fa-lock" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($unlock_url))
        <a href="javascript:void(0)" class="{!! empty($unlock_url) ? 'disabled' : '' !!}" title="Aktif" data-href="{!! empty($unlock_url) ? 'javascript:void(0)' : $unlock_url !!}" data-button="delete" id="unlockModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #1FF43E; border-color: #1FF43E;"><i class="fa fa-unlock" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($edit_url))
        <a href="{!! empty($edit_url) ? 'javascript:void(0)' : $edit_url !!}" class="{!! empty($edit_url) ? 'disabled' : '' !!}" title="Ubah" data-button="edit">
            <button type="button" class="btn btn-success btn-sm" style="width: 40px;"><i class="fa fa-edit" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($modal_form))
        <a href="javascript:void(0)" class="disabled" title="Ubah" data-button="edit" id="editModal">
            <button type="button" class="btn btn-warning btn-sm open_form" style="width: 40px;" data-id="{{ $modal_form }}" title="Ubah"><i class="fa fa-edit" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($reply_url))
        <a href="{!! empty($reply_url) ? 'javascript:void(0)' : $reply_url !!}" class="{!! empty($reply_url) ? 'disabled' : '' !!}" title="Membalas" data-button="Membalas">
            <button type="button" class="btn btn-primary btn-sm" style="width: 40px;"><i class="fa fa-reply" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($delete_url))
        <a href="javascript:void(0)" class="{!! empty($delete_url) ? 'disabled' : '' !!}" title="Hapus" data-href="{!! empty($delete_url) ? 'javascript:void(0)' : $delete_url !!}" data-button="delete" id="deleteModal">
            <button type="button" class="btn btn-icon btn-danger btn-sm" style="width: 40px"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($active_url))
        <a href="javascript:void(0)" class="{!! empty($active_url) ? 'disabled' : '' !!}" title="Aktif" data-href="{!! empty($active_url) ? 'javascript:void(0)' : $active_url !!}" data-button="delete" id="activeModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #1FF43E; border-color: #1FF43E;"><i class="fa fa-check" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($suspend_url))
        <a href="javascript:void(0)" class="{!! empty($suspend_url) ? 'disabled' : '' !!}" title="Tangguhkan" data-href="{!! empty($suspend_url) ? 'javascript:void(0)' : $suspend_url !!}" data-button="delete" id="suspendModal">
            <button type="button" class="btn btn-icon btn-danger btn-sm" style="width: 40px; background-color: #FFA500; border-color: #FFA500;"><i class="fa fa-power-off" aria-hidden="true"></i>
            </button>
        </a>
    @endif
    @if (isset($suspend_span))
        <a href="javascript:void(0)" class="{!! empty($suspend_span) ? 'disabled' : '' !!}" title="Tangguhkan" data-href="{!! empty($suspend_span) ? 'javascript:void(0)' : $suspend_span !!}" data-button="delete" id="suspendModal">
            <span style="color: green;">Active</span>
        </a>
    @endif
    @if (isset($download_url))
        <a href="{!! empty($download_url) ? 'javascript:void(0)' : $download_url !!}" class="{!! empty($download_url) ? 'disabled' : '' !!}" title="Unduh" data-button="download">
            <button type="button" class="btn btn-info btn-sm" style="width: 40px;"><i class="fa fa-download"></i></button>
        </a>
    @endif
    @if (isset($agree_url))
        <a href="javascript:void(0)" class="{!! empty($agree_url) ? 'disabled' : '' !!}" title="Ubah Status" data-href="{!! empty($agree_url) ? 'javascript:void(0)' : $agree_url !!}" data-button="delete" id="agreeModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #1FF43E; border-color: #1FF43E;"><i class="fa fa-check" aria-hidden="true"></i>
            </button>
        </a>
    @endif
    @if (isset($anonim))
        <a href="javascript:void(0)" class="{!! empty($anonim) ? 'disabled' : '' !!}" title="Identitas Pelapor" data-href="{!! empty($anonim) ? 'javascript:void(0)' : $anonim !!}" data-button="delete" id="anonimModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #252725; border-color: #252725;"><i class="fa fa-user" aria-hidden="true"></i>
            </button>
        </a>
    @endif
    @if (isset($show_web))
        <a href="{!! empty($edit_url) ? 'javascript:void(0)' : $show_web !!}" class="{!! empty($show_web) ? 'disabled' : '' !!}" title="Selengkapnya" data-button="detail" target="_blank">
            <button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($passphrase))
        <a href="{!! empty($passphrase) ? 'javascript:void(0)' : $passphrase !!}" class="{!! empty($passphrase) ? 'disabled' : '' !!}" title="Passphrase" data-button="passphrase">
            <button type="button" class="btn btn-primary btn-sm" style="width: 40px;"><i class="fa fa-key" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (isset($download_zip))
        <a href="{!! empty($download_zip) ? 'javascript:void(0)' : $download_zip !!}" class="{!! empty($download_zip) ? 'disabled' : '' !!}" title="Unduh: {{ $nama_file ?? 'Arsip' }}" data-button="download-zip">
            <button type="button" class="btn btn-primary btn-sm" style="width: 40px;">
                <i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i>
            </button>
        </a>
    @endif
</div>
