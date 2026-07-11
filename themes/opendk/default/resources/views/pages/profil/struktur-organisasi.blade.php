@extends('layouts.app')
@section('title', 'Struktur Pemerintahan')
@section('content')
    <div class="col-md-8">
        <div class="box box-warning">
            <div class="box-header with-border">
                <div class="box-header with-border text-bold">
                    <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> STRUKTUR ORGANISASI {{ strtoupper($sebutan_wilayah) }} {{ strtoupper($profil->nama_kecamatan) }}</h3>
                </div>
            </div>
            <div class="box-body">
                <div id="loader" style="
                    display: none;
                    text-align: center;
                    margin-top: 20px;
                ">
                    <img src="https://i.gifer.com/ZZ5H.gif" alt="Loading..." width="30">
                </div>

                <div id="contentBox" style="display: none;">
                    <div style="margin-bottom: 10px; text-align: right;">
                        <button type="button" class="btn btn-primary" id="btnDownload">
                            <i class="fa fa-download"></i> Download Gambar
                        </button>
                    </div>
                    <div id="container"></div>
                </div>
                
            </div>
        </div>
    </div>
@endsection

@include('partials.asset_orgchart')

@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {

            const loader = document.getElementById('loader');
            const contentBox = document.getElementById('contentBox');

            loader.style.display = 'block';
            contentBox.style.display = 'none';

            fetch('{{ route('profil.struktur-organisasi-ajax') }}',{
                method: 'GET',
                headers: {
                    'X-Requested-With': 'Fetch',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(result => {
                    loader.style.display = 'none';
                    contentBox.style.display = 'block';

                    $('#container').orgchart({
                        'data' : { children: result.children, name: '', title: '' },
                        'nodeContent': 'title',
                        'nodeId': 'id',
                        'nodeTitle': 'name',
                        'createNode': function($node, data) {
                            if (data.name === '') {
                                $node.closest('.hierarchy').addClass('root-wrapper');
                                return;
                            }

                            var $content = $node.find('.content').empty();

                            if (data.image) {
                                $content.append($('<img>', {
                                    src: data.image,
                                    class: 'bagan-foto'
                                }));
                            }

                            $content.append(
                                $('<div class="bagan-jabatan">').text(data.title)
                            );
                            $content.append(
                                $('<div class="bagan-nama">').text(data.name)
                            );

                            $node.find('.title').css('background-color', data.color || '#007ad0').empty();
                            $content.css('border', '2px solid ' + (data.color || '#007ad0'));
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    loader.style.display = 'none';
                });

            document.getElementById('btnDownload').addEventListener('click', function() {
                var chart = document.getElementById('container');
                html2canvas(chart, { scale: 2, useCORS: true }).then(function(canvas) {
                    var link = document.createElement('a');
                    link.download = 'struktur-organisasi.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            });


        });
    </script>
@endpush

