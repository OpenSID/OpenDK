@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
    </section>

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ number_format($data['desa'] ?? 0, 0, ',', '.') }}</h3>
                        <p>Desa</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-building-o"></i>
                    </div>
                    <a href="{{ route('data.data-desa.index') }}" class="small-box-footer">
                        Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ number_format($data['penduduk'] ?? 0, 0, ',', '.') }}</h3>
                        <p>Penduduk</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user"></i>
                    </div>
                    <a href="{{ route('data.penduduk.index') }}" class="small-box-footer">
                        Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ number_format($data['keluarga'] ?? 0, 0, ',', '.') }}</h3>
                        <p>Keluarga</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="{{ route('data.keluarga.index') }}" class="small-box-footer">
                        Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ number_format($data['program_bantuan'] ?? 0, 0, ',', '.') }}</h3>
                        <p>Program Bantuan</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-heart-o"></i>
                    </div>
                    <a href="{{ route('data.program-bantuan.index') }}" class="small-box-footer">
                        Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li class=""><a href="#tab_1-1" data-toggle="tab">User Agent</a></li>
                <li class="active"><a href="#tab_2-2" data-toggle="tab">Top 10 Halaman Terpopuler</a></li>
                <li class="pull-left header">
                    <i class="fa fa-pie-chart"></i>
                    Statistik Pengunjung Website
                    <small>[ <a href="{{ route('counter.index') }}">Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                        </a> ]</small>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="tab_1-1">
                    <div class="row">
                        <!-- Browser Chart -->
                        <div class="col-md-4">
                            <figure class="highcharts-figure">
                                <div id="browser-chart"></div>
                            </figure>
                        </div>

                        <!-- Device Chart -->
                        <div class="col-md-4">
                            <figure class="highcharts-figure">
                                <div id="device-chart"></div>
                            </figure>
                        </div>

                        <!-- Platform Chart -->
                        <div class="col-md-4">
                            <figure class="highcharts-figure">
                                <div id="platform-chart"></div>
                            </figure>
                        </div>
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane active" id="tab_2-2">
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                            <tr>
                                <th>URL</th>
                                <th>Page Views</th>
                                <th>Unique Visitors</th>
                                <th>Bounce Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($top_pages_visited as $index => $page)
                                <tr>
                                    <td>{{ $page->url }} <a href="{{ $page->url }}" target="_blank"><i class="fa fa-fw fa-link"></i></a> </td>
                                    <td>{{ $page->total_views }}</td>
                                    <td>{{ $page->unique_visitors }}</td>
                                    <td>
                                        @if (isset($page->bounces))
                                            {{ round(($page->bounces / $page->total_views) * 100, 1) }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
    </section>
    @include('partials.asset_highcharts')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const browserData = @json($browserData);
            const deviceData = @json($deviceData);
            const platformData = @json($platformData);

            // Browser Chart
            Highcharts.chart('browser-chart', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Browser Usage Distribution'
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Browsers',
                    colorByPoint: true,
                    data: browserData
                }]
            });

            // Device Chart
            Highcharts.chart('device-chart', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Device Usage Distribution'
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Devices',
                    colorByPoint: true,
                    data: deviceData
                }]
            });

            // Platform Chart
            Highcharts.chart('platform-chart', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Platform Usage Distribution'
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Platforms',
                    colorByPoint: true,
                    data: platformData
                }]
            });
        });
    </script>
@endpush
