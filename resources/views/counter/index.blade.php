@extends('layouts.dashboard_template')
@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                @include('forms.btn-social', [
                    'print' => route('counter.cetak'),
                    'print_text' => 'Cetak',
                    'print_new_tab' => true,
                ])
                @include('forms.btn-social', [
                    'export_url' => route('counter.export.excel'),
                    'export_text' => 'Unduh',
                ])
            </div>
            <div class="box-body no-padding">
                <!-- Statistik -->
                <div class="row">
                    <div class="pad">
                        @php
                            $visitorArr = [
                                [
                                    'bg' => 'bg-red',
                                    'label' => 'Hari Ini',
                                    'value' => $visitors['todayVisitors']->page_views ?? 0,
                                    'filter' => \App\Enums\VisitorFilterEnum::TODAY,
                                ],
                                [
                                    'bg' => 'bg-purple',
                                    'label' => 'Kemarin',
                                    'value' => $visitors['yesterdayVisitors']->page_views ?? 0,
                                    'filter' => \App\Enums\VisitorFilterEnum::YESTERDAY,
                                ],
                                [
                                    'bg' => 'bg-green',
                                    'label' => 'Minggu Ini',
                                    'value' => $visitors['weeklyVisitors']->page_views ?? 0,
                                    'filter' => \App\Enums\VisitorFilterEnum::THIS_WEEK,
                                ],
                                [
                                    'bg' => 'bg-yellow',
                                    'label' => 'Bulan Ini',
                                    'value' => $visitors['monthlyVisitors']->page_views ?? 0,
                                    'filter' => \App\Enums\VisitorFilterEnum::THIS_MONTH,
                                ],
                                [
                                    'bg' => 'bg-gray color-palette',
                                    'label' => 'Tahun Ini',
                                    'value' => $visitors['yearlyVisitors']->page_views ?? 0,
                                    'filter' => \App\Enums\VisitorFilterEnum::THIS_YEAR,
                                ],
                                [
                                    'bg' => 'bg-blue',
                                    'label' => 'Jumlah',
                                    'value' => $visitors['totalVisitors']->page_views ?? 0,
                                    'filter' => \App\Enums\VisitorFilterEnum::ALL,
                                ],
                            ];
                        @endphp
                        @foreach ($visitorArr as $stat)
                            <div class="col-sm-4 col-xs-6 col-md-2">
                                <div class="small-box {{ $stat['bg'] }}">
                                    <div class="inner">
                                        <h3>{{ $stat['value'] }}</h3>
                                        <p>{{ $stat['label'] }}</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="{{ route('counter.index', ['filter' => $stat['filter']]) }}" class="small-box-footer">
                                        Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="pad">
                        <div class="mailbox-read-info ">
                            <h4 class="">Statistik Pengunjung Website Berdasarkan Waktu</h4>
                        </div> <br>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                            <figure class="highcharts-figure">
                                <div id="container"></div>
                            </figure>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Page views</th>
                                        <th>Unique visitors</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailyStats as $index => $stat)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @if (request()->get('filter') === \App\Enums\VisitorFilterEnum::THIS_YEAR)
                                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $stat->date)->translatedFormat('F Y') }}
                                                @elseif (request()->get('filter') === \App\Enums\VisitorFilterEnum::ALL)
                                                    {{ \Carbon\Carbon::createFromFormat('Y', $stat->date)->translatedFormat('Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($stat->date)->translatedFormat('d F Y') }}
                                                @endif
                                            </td>
                                            <td>{{ $stat->page_views }}</td>
                                            <td>{{ $stat->unique_visitors }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Halaman Populer -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="mailbox-read-info">
                            <h4 class="">Top 10 Halaman Terpopuler</h4>
                        </div>
                        <div class="">
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
                </div>
            </div>
        </div>
    </section>
    @include('partials.asset_highcharts')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Statistik Pengunjung'
                },
                xAxis: {
                    categories: {!! json_encode($chartData['labels']) !!},
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah'
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                        name: 'Page Views',
                        data: {!! json_encode($chartData['page_views']) !!}
                    },
                    {
                        name: 'Unique Visitors',
                        data: {!! json_encode($chartData['unique_visitors']) !!}
                    }
                ]

            });
        });
    </script>
@endpush
