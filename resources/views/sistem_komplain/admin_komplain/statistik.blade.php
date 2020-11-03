@extends('layouts.dashboard_template')

@section('title') Data Umum @endsection

@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title or "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Berdasarkan Status</h3>
                </div>
                <div class="box-body">
                    <div id="chart_status"
                         style="width: 100%; height: 450px; overflow: visible; text-align: left;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Berdasarkan Kategori</h3>
                </div>
                <div class="box-body">
                    <div id="chart_kategori"
                         style="width: 100%; min-height: 350px; overflow: visible; text-align: left;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Berdasarkan Desa</h3>
                </div>
                <div class="box-body">
                    <div id="chart_desa"
                         style="width: 100%; height: 500px; overflow: visible; text-align: left;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
@include('partials.asset_amcharts')

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        create_chart_kategori();
        create_chart_status();
        create_chart_desa();
    });


    // Chart Komplain Kategori
    function create_chart_kategori() {
        AmCharts.addInitHandler( function ( chart_kategori ) {
            // set base values
            var categoryWidth = 45;

            // calculate bottom margin based on number of data points
            var chartHeight = categoryWidth * chart_kategori.dataProvider.length;

            // set the value
            chart_kategori.div.style.height = chartHeight + 'px';

        }, ['serial'] );

        var chart_kategori = AmCharts.makeChart("chart_kategori", {
            "theme": "light",
            "type": "serial",
            "startDuration": 1,
            "rotate": true,
            "dataProvider": {!!  json_encode($chart_kategori) !!},
            "valueAxes": [{
                "position": "left",
                "title": "Jumlah Komplain",
                "baseValue" : 0,
                "minimum": 0
            }],
            "allLabels": [{
                "text": "Statistik Komplain Berdasarkan Kategori",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": 10
            }],
            "graphs": [{
                "balloonText": "[[category]]: <b>[[value]]</b>",
                "fillAlphas": 1,
                "lineAlpha": 0.1,
                "type": "column",
                "valueField": "value"
            }],
            "depth3D": 5,
            "angle": 10,
            "chartCursor": {
                "categoryBalloonEnabled": false,
                "cursorAlpha": 0,
                "zoomable": false
            },
            "categoryField": "kategori",
            "categoryAxis": {
                "gridPosition": "start",
                "labelRotation": 60
            },
            "export": {
                "enabled": true,
                "pageOrigin": false,
                "fileName":"Statistik Komplain Berdasarkan Kategori",
            },
            "hideCredits": true,
            "marginTop" : 50
        });
    }

    //Chart Komplain Status
    function create_chart_status()
    {
        var chart_status = AmCharts.makeChart( "chart_status", {
            "type": "pie",
            "theme": "light",
            "dataProvider": {!!  json_encode($chart_status) !!},
            "valueField": "value",
            "titleField": "status",
            "colorField": "color",
            "balloon":{
                "fixedPosition":true
            },
            "export": {
                "enabled": true,
                "pageOrigin": false,
                "fileName":"Statistik Komplain Berdasarkan Status",
            },
            "hideCredits": true,
            "legend":{
                "position":"bottom",
                "marginRight":100,
                "autoMargins":false
            },
            "allLabels": [{
                "text": "Statistik Komplain Berdasarkan Status",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": 10
            }],
            "marginTop" : 50
        } );
    }

    // Chart Komplain Desa
    function create_chart_desa() {

        AmCharts.addInitHandler( function ( chart_desa ) {
            // set base values
            var categoryWidth = 45;

            // calculate bottom margin based on number of data points
            var chartHeight = categoryWidth * chart_desa.dataProvider.length;

            // set the value
            chart_desa.div.style.height = chartHeight + 'px';

        }, ['serial'] );

        var chart_desa = AmCharts.makeChart("chart_desa", {
            "theme": "light",
            "type": "serial",
            "startDuration": 1,
            "rotate": true,
            "dataProvider": {!!  json_encode($chart_desa) !!},
            "valueAxes": [{
                "position": "left",
                "title": "Jumlah Komplain",
                "baseValue" : 0,
                "minimum": 0
            }],
            "allLabels": [{
                "text": "Statistik Komplain Berdasarkan Desa",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": 10
            }],
            "graphs": [{
                "balloonText": "[[category]]: <b>[[value]]</b>",
                "fillAlphas": 1,
                "lineAlpha": 0.1,
                "type": "column",
                "valueField": "value"
            }],
            "depth3D": 5,
            "angle": 10,
            "chartCursor": {
                "categoryBalloonEnabled": false,
                "cursorAlpha": 0,
                "zoomable": false
            },
            "categoryField": "desa",
            "categoryAxis": {
                "gridPosition": "start",
                "labelRotation": 60
            },
            "export": {
                "enabled": true,
                "pageOrigin": false,
                "fileName": "Statistik Komplain Berdasarkan Desa",
            },
            "hideCredits": true,
            "marginTop" : 50
        });

    }

</script>
@endpush
