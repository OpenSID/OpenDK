@props([
    'id' => 'modal-image-preview',
    'title' => 'Pratinjau Gambar',
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="{{ $id }}Label">{{ $title }}</h4>
            </div>
            <div class="modal-body text-center" style="background-color: #f8f9fa; min-height: 200px; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 15px;">
                <img id="{{ $id }}-img" src="" alt="Pratinjau Gambar" style="max-height: 70vh; max-width: 100%; object-fit: contain; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);" />
            </div>
            <div class="modal-footer">
                <a href="#" id="{{ $id }}-download" class="btn btn-primary" download target="_blank">
                    <i class="fa fa-download"></i> Unduh
                </a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
    function openImageModal(imageUrl, title, downloadUrl, targetModal) {
        var modalSelector = targetModal || '#{{ $id }}';
        var $modal = $(modalSelector);
        if (!$modal.length) {
            $modal = $('#{{ $id }}');
        }

        var $img = $modal.find('.modal-body img');
        var $download = $modal.find('.modal-footer a[download]');
        var $title = $modal.find('.modal-title');

        if (title) {
            $title.text(title);
        } else {
            $title.text('Pratinjau Gambar');
        }

        $img.attr('src', imageUrl);

        var dl = downloadUrl || imageUrl;
        $download.attr('href', dl);

        var filename = '';
        try {
            var raw = dl.split('?')[0].split('#')[0];
            filename = raw.substring(raw.lastIndexOf('/') + 1);
        } catch (e) {
            filename = '';
        }

        if (!filename || filename.indexOf('.') === -1) {
            filename = (title ? title.replace(/[^a-z0-9]/gi, '_').toLowerCase() : 'gambar') + '.jpg';
        }
        $download.attr('download', filename);

        $modal.modal('show');
    }

    $(document).on('click', '[data-toggle="modal-image"], .preview-image-trigger', function(e) {
        e.preventDefault();
        var $this = $(this);
        var targetModal = $this.attr('data-target');
        var url = $this.attr('data-url') || $this.attr('data-src') || $this.attr('src') || $this.attr('href');
        var title = $this.attr('data-title') || $this.attr('alt') || $this.attr('title') || 'Pratinjau Gambar';
        var downloadUrl = $this.attr('data-download') || url;

        if (url && url !== '#' && url !== 'javascript:void(0)') {
            openImageModal(url, title, downloadUrl, targetModal);
        }
    });
</script>
@endpush
@endonce
