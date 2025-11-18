@extends('layouts.app')
@section('title', 'Galeri')
@section('content')
    <div class="col-md-8">
        <div id="albums-container">
            <!-- Albums will be loaded here via JavaScript -->
        </div>
        <div class="text-center">
            <div id="pagination-container">
                <!-- Pagination will be loaded here via JavaScript -->
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(function(){
        // Function to load albums from API
        function loadAlbums(page = 1) {
            // Show loading indicator
            $('#albums-container').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
            
            // Make API call to get albums
            $.ajax({
                url: '{!! $urlApi !!}/galeri?page[number]=' + page + '&include=album',
                method: 'GET',
                success: function(response) {
                    var albumsHtml = '';
                    var paginationHtml = '';
                    
                    // Check if there are albums
                    if (response.data && response.data.length > 0) {
                        // Group galeri by album
                        var albumsMap = {};
                        response.data.forEach(function(item) {
                            if (item.attributes.album) {
                                var album = item.attributes.album;
                                if (!albumsMap[album.id]) {
                                    albumsMap[album.id] = {
                                        id: album.id,
                                        judul: album.judul,
                                        slug: album.slug,
                                        gambar: album.gambar,
                                        created_at: album.created_at,
                                        galeri_count: 1
                                    };
                                } else {
                                    albumsMap[album.id].galeri_count++;
                                }
                            }
                        });
                        
                        // Convert map to array and generate HTML
                        Object.values(albumsMap).forEach(function(album) {
                            var albumImage = album.gambar ? '{{ asset("") }}' + album.gambar : '{{ asset("img/no-image.png") }}';
                            albumsHtml += '<div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">' +
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
                        });
                    } else {
                        albumsHtml = '<div class="callout callout-info">' +
                            '<p class="text-bold">Tidak ada Album yang ditampilkan!</p>' +
                        '</div>';
                    }
                    
                    // Generate pagination if available
                    if (response.links) {
                        paginationHtml = '<nav aria-label="Page navigation">' +
                            '<ul class="pagination justify-content-center">';
                        
                        // Previous link
                        if (response.links.prev) {
                            paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' +
                                (parseInt(response.meta.current_page) - 1) + '">Previous</a></li>';
                        } else {
                            paginationHtml += '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                        }
                        
                        // Page links
                        var startPage = Math.max(1, response.meta.current_page - 2);
                        var endPage = Math.min(response.meta.last_page, response.meta.current_page + 2);
                        
                        for (var i = startPage; i <= endPage; i++) {
                            if (i == response.meta.current_page) {
                                paginationHtml += '<li class="page-item active"><span class="page-link">' + i + '</span></li>';
                            } else {
                                paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
                            }
                        }
                        
                        // Next link
                        if (response.links.next) {
                            paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' +
                                (parseInt(response.meta.current_page) + 1) + '">Next</a></li>';
                        } else {
                            paginationHtml += '<li class="page-item disabled"><span class="page-link">Next</span></li>';
                        }
                        
                        paginationHtml += '</ul></nav>';
                    }
                    
                    $('#albums-container').html(albumsHtml);
                    $('#pagination-container').html(paginationHtml);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading albums:', error);
                    $('#albums-container').html('<div class="callout callout-danger"><p class="text-bold">Gagal memuat album. Silakan coba lagi nanti.</p></div>');
                }
            });
        }
        
        // Load albums on page load
        loadAlbums();
        
        // Handle pagination clicks
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            if (page) {
                loadAlbums(page);
            }
        });
        
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