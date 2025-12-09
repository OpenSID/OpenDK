<div id="kecamatan">
    <div class="post clearfix">

    </div>
    @include('components.pagination')
</div>
@push('scripts')
<script>
    $(function() {
        let apiBase ="{!! $apiBase !!}";
        
            function renderArticles(items, includes) {
                if (!items || items.length === 0) {
                    return '<div class="callout callout-info"><p class="text-bold">Tidak ada berita kecamatan yang ditampilkan!</p></div>';
                }

                return items.map(function(single) {
                    const item = single.attributes;
                    var kategoriHtml = '';
                    if (item.kategori && item.kategori.nama_kategori) {
                        kategoriHtml = '|&ensp;<i class="fa fa-tag"></i>&ensp;<a href="' + (includes['kategori'][item.id_kategori]?.link) + '">' + item.kategori.nama_kategori + '</a>';
                    }
                    var isi = (item.isi || '').replace(/(<([^>]+)>)/gi, "");
                    var excerpt = isi.length > 250 ? isi.substr(0, 250) + '...' : isi;

                    var html = `<div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">
                <div class="row">
                    <div class="col-sm-4">
                        <img class="img-responsive" src="${item.gambar_src}" alt="${item.slug}">
                    </div>
                    <div class="col-sm-8">
                        <h5 style="margin-top: 5px; text-align: justify;"><b><a href="${item.link}">${item.judul}</a></b></h5>
                        <p style="font-size:11px;">
                            <i class="fa fa-calendar"></i>&ensp;${item.tanggal}&ensp;|&ensp;
                            <i class="fa fa-user"></i>&ensp;Administrator&ensp;
                            ${kategoriHtml}
                        </p>
                        <p style="text-align: justify;">${excerpt}</p>
                        <a href="${item.link}" class="btn btn-sm btn-primary" target="_blank">Selengkapnya</a>
                    </div>
                </div>
            </div>`;

                    return html;
                }).join('');
            }

        function loadArtikel(pageNumber) {
            var $container = $('#kecamatan .post.clearfix');            
            $container.html(`@include('components.placeholder')`);
            
            $.getJSON(apiBase + '&page[number]=' + pageNumber)
                .done(function(res) {
                    var items = res.data || res;
                    const includes = []
                    if(res.included){
                        res.included.forEach(item => {
                            if (!includes[item.type]) {
                                includes[item.type] = {};
                            }
                            includes[item.type][item.id] = item.attributes;
                        });    
                    }
                    
                    var html = renderArticles(items, includes);
                    var $container = $('#kecamatan .post.clearfix');

                    $container.html(html);
                    initPagination(res, function() {
                        $('.pagination').on('click', '.btn-page', function() {
                            var params = {};
                            var page = $(this).data('page');
                            loadArtikel(page);
                        });
                        $('.pagination').find('.btn-page').attr('href','#kecamatan')
                    });
                })
                .fail(function() {
                    console.error('Gagal mengambil data artikel dari API.');
                });
        }

        // initial load
        loadArtikel(1);
    });
</script>
@endpush