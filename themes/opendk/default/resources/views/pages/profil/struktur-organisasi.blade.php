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
                    <figure class="highcharts-figure">
                        <div id="container"></div>
                        <p class="highcharts-description"></p>
                    </figure>
                </div>
                
            </div>
        </div>
    </div>
@endsection

@include('partials.asset_highcharts')

@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {

            const loader = document.getElementById('loader');
            const contentBox = document.getElementById('contentBox');

            // Loader tetap terlihat, content disembunyikan
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
                    /// Setelah data diterima, tampilkan konten & sembunyikan loader
                    loader.style.display = 'none';
                    contentBox.style.display = 'block';

                    // Pastikan setiap node memiliki column
                    const cleanedNodes = result.nodes.map(node => ({
                        ...node,
                        column: node.column ?? 0
                    }));

                    Highcharts.chart('container', {
                        chart: {
                            height: 600,
                            inverted: true
                        },
                        title: {
                            text: 'Struktur Organisasi'
                        },
                        series: [{
                            type: 'organization',
                            name: 'Struktur Organisasi',
                            keys: ['from', 'to'],
                            data: result.data,
                            levels: [{
                                level: 0,
                                color: 'silver',
                                dataLabels: {
                                    color: 'white'
                                },
                                height: 25
                            }, {
                                level: 1,
                                color: 'silver',
                                dataLabels: {
                                    color: 'white'
                                },
                                height: 25
                            }, {
                                level: 2,
                                color: '#980104'
                            }, {
                                level: 4,
                                color: '#359154'
                            }],
                            // nodes: cleanedNodes,
                            nodes: result.nodes,
                            colorByPoint: false,
                            color: '#007ad0',
                            dataLabels: {
                                color: 'white'
                            },
                            borderColor: 'white',
                            nodeWidth: 80
                        }],
                        tooltip: {
                            outside: true
                        },
                        exporting: {
                            allowHTML: true,
                            sourceWidth: 800,
                            sourceHeight: 600
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    loader.style.display = 'none'; // Sembunyikan loader jika ada error
                });


        });
    </script>
@endpush

