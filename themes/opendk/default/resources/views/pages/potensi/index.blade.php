@extends('layouts.app')

@section('content')
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title text-bold"><i id="title-container" class="fa fa-arrow-circle-right fa-lg text-blue"></i> </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="potensi-container">
                    <div class="post clearfix">
                        <!-- Loading indicator will be shown here -->
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix" style="padding:0px; margin: 0px !important;">
                @include('components.pagination')
            </div>
            <!-- /.box-footer -->
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function(){
        function renderPotensi(items) {
            if (!items || items.length === 0) {
                return '<h4 class="text-center"><span class="fa fa-times"></span> Data tidak ditemukan.</h4>';
            }

            return items.map(function(item) {
                var potensi = item.attributes;
                var potensiImage = potensi.file_gambar_path || '{{ asset("/img/no-image.png") }}';
                
                return '<div class="attachment-block clearfix">' +
                    '<img id="myImg" class="attachment-img responsive" src="' + potensiImage + '" alt="' + (potensi.nama_potensi || '') + '">' +
                    '<!-- The Modal -->' +
                    '<div id="myModal" class="modal">' +
                        '<span class="close">&times;</span>' +
                        '<img class="modal-content" id="img01">' +
                        '<div id="caption">' + (potensi.nama_potensi || '') + '</div>' +
                    '</div>' +
                    '<div class="attachment-pushed">' +
                        '<h4 class="attachment-heading"><a href="{{ url("/potensi") }}/' + (potensi.tipe ? potensi.tipe.slug : '') + '/' + (potensi.slug || '') + '"><i class="fa fa-industry" aria-hidden="true"></i> ' + (potensi.nama_potensi || '') + '</a></h4>' +
                        '<div class="attachment-text">' +
                            (potensi.deskripsi ? (potensi.deskripsi.length > 300 ? potensi.deskripsi.substring(0, 300) + ' ...' : potensi.deskripsi) : '') +
                            '<div class="pull-right button-group" style="position:relative; bottom:0px; margin-bottom: 0px;">' +
                                '<a href="{{ url("/potensi") }}/' + (potensi.tipe ? potensi.tipe.slug : '') + '/' + (potensi.slug || '') + '" class="btn btn-xs btn-primary"><i class="fa fa-angle-double-right"></i> Baca Selengkapnya</a>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            }).join('');
        }

        // Function to load potensi from API
        function loadPotensi(page = 1) {
            var $container = $('#potensi-container .post.clearfix');
            $container.html(`@include('components.placeholder')`);
            
            // Make API call to get potensi
            $.ajax({
                url: '{!! $urlApi !!}/potensi?filter[tipe.slug]={!! $slug !!}&include=tipe&page[number]=' + page,
                method: 'GET',
                success: function(response) {
                    var items = response.data || response;
                    
                    var html = renderPotensi(items);                    
                    $('#title-container').text(items[0].attributes.tipe.nama_kategori)                    
                    var $container = $('#potensi-container .post.clearfix');

                    $container.html(html);
                    initPagination(response, function() {
                        $('.pagination').on('click', '.btn-page', function() {
                            var params = {};
                            var page = $(this).data('page');
                            loadPotensi(page);
                        });
                        $('.pagination').find('.btn-page').attr('href','#potensi-container')
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading potensi:', error);
                    var $container = $('#potensi-container .post.clearfix');
                    $container.html('<div class="callout callout-danger"><p class="text-bold">Gagal memuat potensi. Silakan coba lagi nanti.</p></div>');
                }
            });
        }
        
        // Load potensi on page load
        loadPotensi();
    });
</script>
@endpush
