<x-action-dropdown>
    @if (!empty($show_web))
        <li><a href="{!! $show_web !!}" target="_blank" title="Selengkapnya"><i class="fa fa-eye text-warning"></i> Selengkapnya</a></li>
    @endif
    @if (!empty($detail_url))
        <li><a href="{!! $detail_url !!}" title="Selengkapnya"><i class="fa fa-list text-info"></i> Selengkapnya</a></li>
    @endif
    @if (!empty($show_url))
        <li><a href="{!! $show_url !!}" title="Lihat" target="_blank"><i class="fa fa-eye text-warning"></i> Lihat</a></li>
    @endif
    @if (!empty($peta))
        <li><a href="{!! $peta !!}" title="Peta" target="_blank"><i class="fa fa-map text-info"></i> Peta</a></li>
    @endif
    @if (!empty($preview_url))
        <li><a href="javascript:void(0)" class="btn-preview-surat" data-url="{{ $preview_url }}" title="Pratinjau Surat"><i class="fa fa-file-pdf-o text-danger"></i> Pratinjau Surat</a></li>
    @endif

    @if (!empty($arsip_url))
        <li class="disabled"><a href="{{ $arsip_url }}" title="Arsip"><i class="glyphicon glyphicon-th-list text-info"></i> Arsip</a></li>
    @endif

    @if (!empty($edit_url))
        <li><a href="{!! $edit_url !!}" title="Ubah"><i class="fa fa-edit text-success"></i> Ubah</a></li>
    @endif
    @if (!empty($modal_form))
        <li><a href="javascript:void(0)" class="open_form" data-id="{{ $modal_form }}" title="Ubah"><i class="fa fa-edit text-warning"></i> Ubah</a></li>
    @endif
    @if (!empty($reply_url))
        <li><a href="{!! $reply_url !!}" title="Membalas"><i class="fa fa-reply text-primary"></i> Balas</a></li>
    @endif

    @if (!empty($download_url))
        <li><a href="{!! $download_url !!}" title="Unduh"><i class="fa fa-download text-info"></i> Unduh</a></li>
    @endif
    @if (!empty($download_zip))
        <li><a href="{!! $download_zip !!}" title="Unduh: {{ $nama_file ?? 'Arsip' }}"><i class="glyphicon glyphicon-download-alt text-primary"></i> Unduh ZIP</a></li>
    @endif

    @if (!empty($turun))
        <li><a href="{!! $turun !!}" title="Pindah Posisi ke Bawah"><i class="fa fa-arrow-down text-success"></i> Turun</a></li>
    @endif
    @if (!empty($naik))
        <li><a href="{!! $naik !!}" title="Pindah Posisi ke Atas"><i class="fa fa-arrow-up text-success"></i> Naik</a></li>
    @endif

    @if (!empty($lock_url))
        <li><a href="javascript:void(0)" data-href="{!! $lock_url !!}" id="lockModal" title="Tidak Aktif"><i class="fa fa-lock text-muted"></i> Nonaktifkan</a></li>
    @endif
    @if (!empty($unlock_url))
        <li><a href="javascript:void(0)" data-href="{!! $unlock_url !!}" id="unlockModal" title="Aktif"><i class="fa fa-unlock text-success"></i> Aktifkan</a></li>
    @endif
    @if (!empty($active_url))
        <li><a href="javascript:void(0)" data-href="{!! $active_url !!}" id="activeModal" title="Aktif"><i class="fa fa-check text-success"></i> Aktifkan</a></li>
    @endif
    @if (!empty($agree_url))
        <li><a href="javascript:void(0)" data-href="{!! $agree_url !!}" id="agreeModal" title="Ubah Status"><i class="fa fa-check text-success"></i> Setujui</a></li>
    @endif
    @if (!empty($suspend_url))
        <li><a href="javascript:void(0)" data-href="{!! $suspend_url !!}" id="suspendModal" title="Tangguhkan"><i class="fa fa-power-off text-warning"></i> Tangguhkan</a></li>
    @endif
    @if (!empty($suspend_span))
        <li class="disabled"><a href="javascript:void(0)" title="Tangguhkan"><span style="color: green;">Active</span></a></li>
    @endif
    @if (!empty($passphrase))
        <li><a href="{!! $passphrase !!}" title="Passphrase"><i class="fa fa-key text-primary"></i> Passphrase</a></li>
    @endif
    @if (!empty($anonim))
        <li><a href="javascript:void(0)" data-href="{!! $anonim !!}" id="anonimModal" title="Identitas Pelapor"><i class="fa fa-user text-muted"></i> Identitas Pelapor</a></li>
    @endif

    @if (!empty($delete_url))
        <li class="divider"></li>
        <li><a href="javascript:void(0)" data-href="{!! $delete_url !!}" id="deleteModal" title="Hapus" style="color: #dd4b39;"><i class="fa fa-trash text-danger"></i> Hapus</a></li>
    @endif
</x-action-dropdown>
