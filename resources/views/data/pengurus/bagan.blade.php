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
                <div style="margin-bottom: 10px; text-align: right;">
                    <button type="button" class="btn btn-primary" id="btnDownload">
                        <i class="fa fa-download"></i> Download Gambar
                    </button>
                </div>
                <div class="box box-primary">
                    <div id="container"></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('partials.asset_orgchart')

@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {

            const loader = document.getElementById('loader');
            const contentBox = document.getElementById('contentBox');

            loader.style.display = 'block';
            contentBox.style.display = 'none';

            fetch('{{ route('data.pengurus.ajax-bagan') }}')
                .then(response => response.json())
                .then(result => {
                    loader.style.display = 'none';
                    contentBox.style.display = 'block';

                    $('#container').orgchart({
                        'data': {
                            children: result.children,
                            name: '',
                            title: ''
                        },
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
                html2canvas(chart, {
                    scale: 2,
                    useCORS: true
                }).then(function(canvas) {
                    var link = document.createElement('a');
                    link.download = 'struktur-organisasi.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            });


        });
    </script>
@endpush
