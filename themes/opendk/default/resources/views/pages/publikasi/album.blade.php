@extends('layouts.app')
@section('title', 'Galeri')
@section('content')
    <div class="col-md-8">
        <div id="albums-container">
            <div class="post clearfix">

            </div>
            @include('components.pagination')
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(function(){
        function renderAlbums(items) {
            if (!items || items.length === 0) {
                return '<div class="callout callout-info"><p class="text-bold">Tidak ada Album yang ditampilkan!</p></div>';
            }

            // Process album data from API response
            return items.map(function(item) {
                var album = item.attributes;
                var albumImage = album.gambar_path || '{{ asset("img/no-image.png") }}';
                return '<div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">' +
                    '<div class="row">' +
                        '<div class="col-sm-4">' +
                            '<img class="img-responsive" src="' + albumImage + '" alt="' + (album.slug || '') + '">' +
                        '</div>' +
                        '<div class="col-sm-8">' +
                            '<h5 style="margin-top: 5px; text-align: justify;"><b><a href="{{ url("/publikasi/galeri") }}/' + (album.slug || '') + '">' + (album.judul || '') + '</a></b></h5>' +
                            '<p style="font-size:11px;"><i class="fa fa-calendar"></i>&ensp;' + formatDate(album.created_at) + '&ensp;|&ensp;<i class="fa fa-user"></i>&ensp;Administrator</p>' +
                            '<a href="{{ url("/publikasi/galeri") }}/' + (album.slug || '') + '" class="btn btn-sm btn-primary" target="_blank">Selengkapnya</a>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            }).join('');
        }

        // Function to load albums from API
        function loadAlbums(page = 1) {
            var $container = $('#albums-container .post.clearfix');
            $container.html(`@include('components.placeholder')`);
            
            // Make API call to get albums
            $.ajax({
                url: '{!! $urlApi !!}/album?filter[status]=1&page[number]=' + page,
                method: 'GET',
                success: function(response) {
                    var items = response.data || response;
                    
                    var html = renderAlbums(items);
                    var $container = $('#albums-container .post.clearfix');

                    $container.html(html);
                    initPagination(response, function() {
                        $('.pagination').on('click', '.btn-page', function() {
                            var params = {};
                            var page = $(this).data('page');
                            loadAlbums(page);
                        });
                        $('.pagination').find('.btn-page').attr('href','#albums-container')
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading albums:', error);
                    var $container = $('#albums-container .post.clearfix');
                    $container.html('<div class="callout callout-danger"><p class="text-bold">Gagal memuat album. Silakan coba lagi nanti.</p></div>');
                }
            });
        }
        
        // Load albums on page load
        loadAlbums();
        
        // Helper function to format date
        function formatDate(dateString) {
            if (!dateString) return '';
            
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            var date = new Date(dateString);
            return date.toLocaleDateString('id-ID', options);
        }
    });
</script>
@endpush