@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.pengurus.index') }}">Daftar Pengurus</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">

            {{-- loader --}}
            <div id="loader" style="
                display: none;
                text-align: center;
                margin-top: 20px;
            ">
                <img src="https://i.gifer.com/ZZ5H.gif" alt="Loading..." width="30">
            </div>

            {{-- visual --}}
            <div class="col-md-12" id="contentBox" style="display: none;">
                <div class="box box-primary">
                    <figure class="highcharts-figure">
                        <div id="container"></div>
                        <p class="highcharts-description"></p>
                    </figure>
                </div>
            </div>
        </div>
    </section>
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

            fetch('{{ route('data.pengurus.ajaxbagan') }}')
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
                    loader.style.display = 'none';
                });


        });
    </script>
@endpush
