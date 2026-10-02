@extends('layouts.app')
@section('title', 'Galeri')
@section('content')
    <div class="col-md-8">
        <div id="galeri-container">
            <div class="post clearfix">

            </div>
            @include('components.pagination')
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(function(){
        function renderGaleri(items) {
            if (!items || items.length === 0) {
                return '<div class="callout callout-info"><p class="text-bold">Tidak ada galeri yang ditampilkan!</p></div>';
            }

            return items.map(function(item) {
                var galeri = item.attributes;
                var media = renderGaleriMedia(galeri, {
                    imageStyle: 'width:100%;height:auto;max-height:220px;object-fit:contain;background:#f5f5f5;'
                });
                var detailUrl = '{{ url("/publikasi/galeri/detail") }}/' + (galeri.slug || '');
                var targetUrl = (galeri.jenis === 'file') ? detailUrl : (galeri.link || detailUrl);
                var badge = isGaleriPlayable(galeri)
                    ? '<span class="label label-primary" style="position:absolute;top:8px;left:8px;z-index:2;"><i class="fa fa-play-circle"></i> ' + (galeri.media_type === 'youtube' ? 'YouTube' : 'Video') + '</span>'
                    : '';

                return '<div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">' +
                    '<div class="row">' +
                        '<div class="col-sm-4" style="position:relative;">' + badge + media + '</div>' +
                        '<div class="col-sm-8">' +
                            '<h5 style="margin-top: 5px; text-align: justify;"><b><a href="' + targetUrl + '">' + (galeri.judul || '') + '</a></b></h5>' +
                            '<p style="font-size:11px;"><i class="fa fa-calendar"></i>&ensp;' + formatDate(galeri.created_at) + '&ensp;|&ensp;<i class="fa fa-user"></i>&ensp;Administrator</p>' +
                            '<a href="' + detailUrl + '" class="btn btn-sm btn-primary" target="_blank">Selengkapnya</a>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            }).join('');
        }

        // Function to load galeri from API
        function loadGaleri(page = 1) {
            var $container = $('#galeri-container .post.clearfix');
            $container.html(`@include('components.placeholder')`);
            
            // Make API call to get galeri
            $.ajax({
                url: '{!! $urlApi !!}/galeri?filter[status]=1&filter[album.slug]={!! $slug !!}&page[number]=' + page,
                method: 'GET',
                success: function(response) {
                    var items = response.data || response;
                    
                    var html = renderGaleri(items);
                    var $container = $('#galeri-container .post.clearfix');

                    $container.html(html);
                    initPagination(response, function() {
                        $('.pagination').on('click', '.btn-page', function() {
                            var params = {};
                            var page = $(this).data('page');
                            loadGaleri(page);
                        });
                        $('.pagination').find('.btn-page').attr('href','#galeri-container')
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading galeri:', error);
                    var $container = $('#galeri-container .post.clearfix');
                    $container.html('<div class="callout callout-danger"><p class="text-bold">Gagal memuat galeri. Silakan coba lagi nanti.</p></div>');
                }
            });
        }
        
        // Load galeri on page load
        loadGaleri();            
    });
</script>
@endpush
