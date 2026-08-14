<div class="btn-group" id="exampleToolbar" role="group">
    @if (!empty($arsip_url))
        <a href="{{ $arsip_url }}" class="disabled" title="Arsip" data-button="edit" id="editModal">
            <button type="button" class="btn btn-info btn-sm open_form" style="width: 40px;" data-id="{{ $arsip_url }}" title="Arsip"><i class="glyphicon glyphicon-th-list" aria-hidden="true"></i></button>
        </a>
    @endif

    @if (!empty($turun))
        <a href="{!! $turun !!}" title="Pindah Posisi ke Bawah" data-button="turun">
            <button type="button" class="btn btn-success btn-sm" style="width: 40px;"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($naik))
        <a href="{!! $naik !!}" title="Pindah Posisi ke Atas" data-button="naik">
            <button type="button" class="btn btn-success btn-sm" style="width: 40px;"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($detail_url))
        <a href="{!! $detail_url !!}" title="Selengkapnya" data-button="detail">
            <button type="button" class="btn btn-info btn-sm" style="width: 40px;"><i class="fa fa-list fa-fw"></i></button>
        </a>
    @endif
    @if (!empty($show_url))
        <a href="{!! $show_url !!}" title="Lihat" data-button="show" target="_blank">
            <button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye fa-fw"></i></button>
        </a>
    @endif
    @if (!empty($peta))
        <a href="{!! $peta !!}" title="Peta" data-button="peta" target="_blank">
            <button type="button" class="btn btn-info btn-sm" style="width: 40px;"><i class="fa fa-map" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($lock_url))
        <a href="javascript:void(0)" title="Tidak Aktif" data-href="{!! $lock_url !!}" data-button="delete" id="lockModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #252725; border-color: #252725;"><i class="fa fa-lock" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($unlock_url))
        <a href="javascript:void(0)" title="Aktif" data-href="{!! $unlock_url !!}" data-button="delete" id="unlockModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #1FF43E; border-color: #1FF43E;"><i class="fa fa-unlock" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($edit_url))
        <a href="{!! $edit_url !!}" title="Ubah" data-button="edit">
            <button type="button" class="btn btn-success btn-sm" style="width: 40px;"><i class="fa fa-edit" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($modal_form))
        <a href="javascript:void(0)" title="Ubah" data-button="edit" id="editModal">
            <button type="button" class="btn btn-warning btn-sm open_form" style="width: 40px;" data-id="{{ $modal_form }}" title="Ubah"><i class="fa fa-edit" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($reply_url))
        <a href="{!! $reply_url !!}" title="Membalas" data-button="Membalas">
            <button type="button" class="btn btn-primary btn-sm" style="width: 40px;"><i class="fa fa-reply" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($delete_url))
        <a href="javascript:void(0)" title="Hapus" data-href="{!! $delete_url !!}" data-button="delete" id="deleteModal">
            <button type="button" class="btn btn-icon btn-danger btn-sm" style="width: 40px"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($active_url))
        <a href="javascript:void(0)" title="Aktif" data-href="{!! $active_url !!}" data-button="delete" id="activeModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #1FF43E; border-color: #1FF43E;"><i class="fa fa-check" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($suspend_url))
        <a href="javascript:void(0)" title="Tangguhkan" data-href="{!! $suspend_url !!}" data-button="delete" id="suspendModal">
            <button type="button" class="btn btn-icon btn-danger btn-sm" style="width: 40px; background-color: #FFA500; border-color: #FFA500;"><i class="fa fa-power-off" aria-hidden="true"></i>
            </button>
        </a>
    @endif
    @if (!empty($suspend_span))
        <a href="javascript:void(0)" title="Tangguhkan" data-href="{!! $suspend_span !!}" data-button="delete" id="suspendModal">
            <span style="color: green;">Active</span>
        </a>
    @endif
    @if (!empty($preview_url))
        <button type="button" class="btn btn-danger btn-sm btn-preview-surat" style="width: 40px; margin-right: 2px;" data-url="{{ $preview_url }}" title="Pratinjau Surat">
            <i class="fa fa-file-pdf-o"></i>
        </button>
    @endif
    @if (!empty($download_url))
        <a href="{!! $download_url !!}" title="Unduh" data-button="download">
            <button type="button" class="btn btn-info btn-sm" style="width: 40px;"><i class="fa fa-download"></i></button>
        </a>
    @endif
    @if (!empty($agree_url))
        <a href="javascript:void(0)" title="Ubah Status" data-href="{!! $agree_url !!}" data-button="delete" id="agreeModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #1FF43E; border-color: #1FF43E;"><i class="fa fa-check" aria-hidden="true"></i>
            </button>
        </a>
    @endif
    @if (!empty($anonim))
        <a href="javascript:void(0)" title="Identitas Pelapor" data-href="{!! $anonim !!}" data-button="delete" id="anonimModal">
            <button type="button" class="btn btn-icon btn-info btn-sm" style="width: 40px; background-color: #252725; border-color: #252725;"><i class="fa fa-user" aria-hidden="true"></i>
            </button>
        </a>
    @endif
    @if (!empty($show_web))
        <a href="{!! $show_web !!}" title="Selengkapnya" data-button="detail" target="_blank">
            <button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($passphrase))
        <a href="{!! $passphrase !!}" title="Passphrase" data-button="passphrase">
            <button type="button" class="btn btn-primary btn-sm" style="width: 40px;"><i class="fa fa-key" aria-hidden="true"></i></button>
        </a>
    @endif
    @if (!empty($download_zip))
        <a href="{!! $download_zip !!}" title="Unduh: {{ $nama_file ?? 'Arsip' }}" data-button="download-zip">
            <button type="button" class="btn btn-primary btn-sm" style="width: 40px;">
                <i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i>
            </button>
        </a>
    @endif
</div>
