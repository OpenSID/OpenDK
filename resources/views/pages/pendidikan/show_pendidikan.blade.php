@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-header with-border">
            <form class="form-horizontal">
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="list_desa" class="col-sm-4 control-label">Desa</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="list_desa">
                                <option value="Semua">Semua Desa</option>
                                @foreach($list_desa as $desa)
                                    <option value="{{ $desa->desa_id}}">{{$desa->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="list_year" class="col-sm-4 control-label">Tahun</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="list_year">
                                @foreach($year_list as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#jumlah_penduduk" data-toggle="tab">Tingkat Pendidikan</a></li>
                    <li><a href="#jumlah_putus_sekolah" data-toggle="tab">Jumlah Siswa Putus Sekolah</a></li>
                    <li><a href="#jumlah_fasilitas" data-toggle="tab">Jumlah Fasilitas PAUD</a></li>
                    {{-- <li><a href="#jumlah_siswa_fasilitas" data-toggle="tab">Jumlah Siswa dan Fasilitas</a></li> --}}
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="jumlah_penduduk">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="chart_penduduk_pendidikan" style="width: 100%; overflow: auto; text-align: left;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="jumlah_putus_sekolah">
                        <div id="chart_putus_sekolah" style="width:100%; overflow: visible; text-align: left; padding: 10px;;">
                        </div>
                    </div>
                    <div class="tab-pane" id="jumlah_fasilitas">
                        <div id="chart_fasilitas" style="width:100%;  overflow: visible; text-align: left; padding: 10px;;">
                        </div>
                    </div>
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.asset_amcharts')
@include('partials.asset_select2')
@push('scripts')
<script>
    $(function () {

        // Select 2 Kecamatan
        $('#list_desa').select2();
        $('#list_year').select2();

        var did = $('#list_desa').find(":selected").val();
        var year = $('#list_year').find(":selected").val();

        /*
         * Initial Chart Dashboard Pendidikan
         */
        change_das_pendidikan(did, year);
        /*
         * End Initial
         */

        // Change div das_kependudukan when Kecamatan changed

        $('#list_desa').on('select2:select', function (e) {
            var did = $('#list_desa').find(":selected").val();
            var year = $('#list_year').find(":selected").val();
            change_das_pendidikan(did, year);
        });

        $('#list_year').on('select2:select', function (e) {
            var did = $('#list_desa').find(":selected").val();
            var year = $('#list_year').find(":selected").val();

            change_das_pendidikan(did, year);
        });
    });

    function change_das_pendidikan(did, year) {

        $.ajax('{!! route('statistik.pendidikan.chart-tingkat-pendidikan') !!}', {
            data: {did: did, y: year}
        }).done(function (data) {
            create_chart_tingkat_pendidikan(data['grafik']);
        });

        $.ajax('{!! route('statistik.pendidikan.chart-putus-sekolah') !!}', {
            data: {did: did, y: year}
        }).done(function (data) {
            create_chart_putus_sekolah(data['grafik']);
        });

        $.ajax('{!! route('statistik.pendidikan.chart-fasilitas-paud') !!}', {
            data: {did: did, y: year}
        }).done(function (data) {
            create_chart_fasilitas_sekolah(data['grafik']);
        });
    }


    function create_chart_tingkat_pendidikan(data) {
        /**
         * Define data for each year
         */

        /**
         * Create the chart
         */
        AmCharts.addInitHandler( function ( chart_penduduk_pendidikan ) {
            // set base values
            var categoryWidth = 120;

            var did = $('#list_desa').find(":selected").val();
            var year = $('#list_year').find(":selected").val();
            if(did == "Semua" && year == "Semua"){
                chart_penduduk_pendidikan.categoryAxis.title = "Tahun";
                categoryWidth = 250;
            }
            if(did != "Semua" && year == "Semua"){
                chart_penduduk_pendidikan.categoryAxis.title = "Tahun";
                categoryWidth = 250;
            }
            if(did == "Semua" && year != "Semua"){
                chart_penduduk_pendidikan.categoryAxis.title = "Desa";
                categoryWidth = 190;
            }
            if(did != "Semua" && year != "Semua"){
                chart_penduduk_pendidikan.categoryAxis.title = "Semester";
                categoryWidth = 280;
            }
            // calculate bottom margi95based on number of data points
            var chartHeight = categoryWidth * chart_penduduk_pendidikan.dataProvider.length;

            // set the value
            chart_penduduk_pendidikan.div.style.height = chartHeight + 'px';

            //method to handle removing/adding columns when the marker is toggled
            function handleCustomMarkerToggle(legendEvent) {
                var dataProvider = legendEvent.chart.dataProvider;
                var itemIndex; //store the location of the removed item

                //Set a custom flag so that the dataUpdated event doesn't fire infinitely, in case you have
                //a dataUpdated event of your own
                legendEvent.chart.toggleLegend = true;
                // The following toggles the markers on and off.
                // The only way to "hide" a column and reserved space on the axis is to remove it
                // completely from the dataProvider. You'll want to use the hidden flag as a means
                // to store/retrieve the object as needed and then sort it back to its original location
                // on the chart using the dataIdx property in the init handler
                if (undefined !== legendEvent.dataItem.hidden && legendEvent.dataItem.hidden) {
                    legendEvent.dataItem.hidden = false;
                    dataProvider.push(legendEvent.dataItem.storedObj);
                    legendEvent.dataItem.storedObj = undefined;
                    //re-sort the array by dataIdx so it comes back in the right order.
                    dataProvider.sort(function(lhs, rhs) {
                        return lhs.dataIdx - rhs.dataIdx;
                    });
                } else {
                    // toggle the marker off
                    legendEvent.dataItem.hidden = true;
                    //get the index of the data item from the data provider, using the
                    //dataIdx property.
                    for (var i = 0; i < dataProvider.length; ++i) {
                        if (dataProvider[i].dataIdx === legendEvent.dataItem.dataIdx) {
                            itemIndex = i;
                            break;
                        }
                    }
                    //store the object into the dataItem
                    legendEvent.dataItem.storedObj = dataProvider[itemIndex];
                    //remove it
                    dataProvider.splice(itemIndex, 1);
                }
                legendEvent.chart.validateData(); //redraw the chart
            }

            //check if legend is enabled and custom generateFromData property
            //is set before running
            if (!chart_penduduk_pendidikan.legend || !chart_penduduk_pendidikan.legend.enabled || !chart_penduduk_pendidikan.legend.generateFromData) {
                return;
            }

            var categoryField = chart_penduduk_pendidikan.categoryField;
            var colorField = chart_penduduk_pendidikan.graphs[0].lineColorField || chart_penduduk_pendidikan.graphs[0].fillColorsField || chart_penduduk_pendidikan.graphs[0].colorField;
            var legendData =  chart_penduduk_pendidikan.dataProvider.map(function(data, idx) {
                var markerData = {
                    "title": data[categoryField] + ": " + data[chart_penduduk_pendidikan.graphs[0].valueField],
                    "color": data[colorField],
                    "dataIdx": idx //store a copy of the index of where this appears in the dataProvider array for ease of removal/re-insertion
                };
                if (!markerData.color) {
                    markerData.color = chart_penduduk_pendidikan.graphs[0].lineColor;
                }
                data.dataIdx = idx; //also store it in the dataProvider object itself
                return markerData;
            });

            chart_penduduk_pendidikan.legend.data = legendData;

            //make the markers toggleable
            chart_penduduk_pendidikan.legend.switchable = true;
            chart_penduduk_pendidikan.legend.addListener("clickMarker", handleCustomMarkerToggle);

        }, ['serial'] );

        var chart_penduduk_pendidikan = AmCharts.makeChart("chart_penduduk_pendidikan", {
            "theme": "light",
            "type": "serial",
            "hideCredits": true,
            "rotate": true,
            "startDuration": 1,
            "dataProvider": data,
            "graphs": [{
                "balloonText": "[[title]]: <b>[[value]]</b>",
                //"fillColorsField": "color",
                //"fillColors" : "#86abf8",
                "fillAlphas": 1,
                "lineAlpha": 0.1,
                "type": "column",
                "title": "Tidak Tamat Sekolah",
                "valueField": "tidak_tamat_sekolah",
                "labelText" : "[[value]]",
                "labelPosition": "middle"
            },{
                "balloonText": "[[title]]: <b>[[value]]</b>",
                //"fillColorsField": "color",
                //"fillColors" : "#6e9af7",
                "fillAlphas": 1,
                "lineAlpha": 0.1,
                "type": "column",
                "title": "Tamat SD",
                "valueField": "tamat_sd",
                "labelText" : "[[value]]",
                "labelPosition": "middle"
            },{
                "balloonText": "[[title]]: <b>[[value]]</b>",
                //"fillColorsField": "color",
                //"fillColors" : "#5689f5",
                "fillAlphas": 1,
                "lineAlpha": 0.1,
                "type": "column",
                "title": "Tamat SMP",
                "valueField": "tamat_smp",
                "labelText" : "[[value]]",
                "labelPosition": "middle"
            },{
                "balloonText": "[[title]]: <b>[[value]]</b>",
                //"fillColorsField": "color",
                //"fillColors" : "#3e78f4",
                "fillAlphas": 1,
                "lineAlpha": 0.1,
                "type": "column",
                "title": "Tamat SMA",
                "valueField": "tamat_sma",
                "labelText" : "[[value]]",
                "labelPosition": "middle"
            },{
                "balloonText": "[[title]]: <b>[[value]]</b>",
                //"fillColorsField": "color",
                //"fillColors" : "#2667f3",
                "fillAlphas": 1,
                "lineAlpha": 0.1,
                "type": "column",
                "title": "Tamat Diploma/Sederajat",
                "valueField": "tamat_diploma_sederajat",
                "labelText" : "[[value]]",
                "labelPosition": "middle"
            }],
            "depth3D": 5,
            "angle": 15,
            /*"chartCursor": {
                "categoryBalloonEnabled": false,
                "cursorAlpha": 0,
                "zoomable": false
            },*/
            "categoryField": "year",
            "categoryAxis": {
                "gridPosition": "start",
            },
            "export": {
                "enabled": true,
                "pageOrigin" : false,
                "fileName":"Jumlah Penduduk Berdasarkan Tingkat Pendidikan",
            },
            "legend": {
                /*"enabled": true,
                "useGraphSettings": true*/
                "enabled":true
            },
            "marginTop":50,
            "allLabels": [{
                "text": "Jumlah Penduduk Berdasarkan Tingkat Pendidikan",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": 10
            }],
            "valueAxes": [{
                "baseValue" : 0,
                "minimum": 0,
                "title":"Jumlah Penduduk"
            }],
            "numberFormatter": {
                "precision": -1,
                "decimalSeparator": ",",
                "thousandsSeparator": "."
            }
        });
    }

    //Create Chart Jumlah Siswa
    function create_chart_putus_sekolah(data) {
        AmCharts.addInitHandler( function ( chart_putus_sekolah ) {
            // set base values
            var categoryWidth = 250;

            var did = $('#list_desa').find(":selected").val();
            var year = $('#list_year').find(":selected").val();
            if(did == "Semua" && year == "Semua"){
                chart_putus_sekolah.categoryAxis.title = "Tahun";
                categoryWidth = 350;
            }
            if(did == "Semua" && year != "Semua"){
                chart_putus_sekolah.categoryAxis.title = "Desa";
                categoryWidth = 350;
            }
            if(did != "Semua" && year == "Semua"){
                chart_putus_sekolah.categoryAxis.title = "Desa";
                categoryWidth = 350;
            }
            if(did != "Semua" && year != "Semua"){
                chart_putus_sekolah.categoryAxis.title = "Semester";
                categoryWidth = 390;
            }
            // calculate bottom margi95based on number of data points
            var chartHeight = categoryWidth * chart_putus_sekolah.dataProvider.length;

            // set the value
            chart_putus_sekolah.div.style.height = chartHeight + 'px';

            //method to handle removing/adding columns when the marker is toggled
            function handleCustomMarkerToggle(legendEvent) {
                var dataProvider = legendEvent.chart.dataProvider;
                var itemIndex; //store the location of the removed item

                //Set a custom flag so that the dataUpdated event doesn't fire infinitely, in case you have
                //a dataUpdated event of your own
                legendEvent.chart.toggleLegend = true;
                // The following toggles the markers on and off.
                // The only way to "hide" a column and reserved space on the axis is to remove it
                // completely from the dataProvider. You'll want to use the hidden flag as a means
                // to store/retrieve the object as needed and then sort it back to its original location
                // on the chart using the dataIdx property in the init handler
                if (undefined !== legendEvent.dataItem.hidden && legendEvent.dataItem.hidden) {
                    legendEvent.dataItem.hidden = false;
                    dataProvider.push(legendEvent.dataItem.storedObj);
                    legendEvent.dataItem.storedObj = undefined;
                    //re-sort the array by dataIdx so it comes back in the right order.
                    dataProvider.sort(function(lhs, rhs) {
                        return lhs.dataIdx - rhs.dataIdx;
                    });
                } else {
                    // toggle the marker off
                    legendEvent.dataItem.hidden = true;
                    //get the index of the data item from the data provider, using the
                    //dataIdx property.
                    for (var i = 0; i < dataProvider.length; ++i) {
                        if (dataProvider[i].dataIdx === legendEvent.dataItem.dataIdx) {
                            itemIndex = i;
                            break;
                        }
                    }
                    //store the object into the dataItem
                    legendEvent.dataItem.storedObj = dataProvider[itemIndex];
                    //remove it
                    dataProvider.splice(itemIndex, 1);
                }
                legendEvent.chart.validateData(); //redraw the chart
            }

            //check if legend is enabled and custom generateFromData property
            //is set before running
            if (!chart_putus_sekolah.legend || !chart_putus_sekolah.legend.enabled || !chart_putus_sekolah.legend.generateFromData) {
                return;
            }

            var categoryField = chart_putus_sekolah.categoryField;
            var colorField = chart_putus_sekolah.graphs[0].lineColorField || chart_putus_sekolah.graphs[0].fillColorsField || chart_putus_sekolah.graphs[0].colorField;
            var legendData =  chart_putus_sekolah.dataProvider.map(function(data, idx) {
                var markerData = {
                    "title": data[categoryField] + ": " + data[chart_putus_sekolah.graphs[0].valueField],
                    "color": data[colorField],
                    "dataIdx": idx //store a copy of the index of where this appears in the dataProvider array for ease of removal/re-insertion
                };
                if (!markerData.color) {
                    markerData.color = chart_putus_sekolah.graphs[0].lineColor;
                }
                data.dataIdx = idx; //also store it in the dataProvider object itself
                return markerData;
            });

            chart_putus_sekolah.legend.data = legendData;

            //make the markers toggleable
            chart_putus_sekolah.legend.switchable = true;
            chart_putus_sekolah.legend.addListener("clickMarker", handleCustomMarkerToggle);

        }, ['serial'] );

        // Chart Perbandingan Jumlah Siswa berdasarkan TIngkat Pendidikan
        var chart_putus_sekolah = AmCharts.makeChart("chart_putus_sekolah", {
            "theme": "light",
            "type": "serial",
            "hideCredits": true,
            "rotate": true,
            "startDuration": 1,
            "dataProvider": data,
            "graphs": [{
                "balloonText": "[[title]]: <b>[[value]]</b>",
                //"fillColorsField": "color",
                "fillColors" : "#937ff3",
                "fillAlphas": 1,
                "lineAlpha": 0.1,
                "type": "column",
                "title": "Siswa PAUD",
                "valueField": "siswa_paud",
                "labelText" : "[[value]]",
                "labelPosition": "middle"
            },{
                "balloonText": "[[title]]: <b>[[value]]</b>",
                //"fillColorsField": "color",
                "fillColors" : "#2d198d",
                "fillAlphas": 1,
                "lineAlpha": 0.1,
                "type": "column",
                "title": "Anak Usia PAUD",
                "valueField": "anak_usia_paud",
                "labelText" : "[[value]]",
                "labelPosition": "middle"
            },
                {
                    "balloonText": "[[title]]: <b>[[value]]</b>",
                    //"fillColorsField": "color",
                    "fillColors" : "#eb6a7c",
                    "fillAlphas": 1,
                    "lineAlpha": 0.1,
                    "type": "column",
                    "title": "Siswa SD",
                    "valueField": "siswa_sd",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },
                {
                    "balloonText": "[[title]]: <b>[[value]]</b>",
                    //"fillColorsField": "color",
                    "fillColors" : "#b2061e",
                    "fillAlphas": 1,
                    "lineAlpha": 0.1,
                    "type": "column",
                    "title": "Anak Usia SD",
                    "valueField": "anak_usia_sd",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },{
                    "balloonText": "[[title]]: <b>[[value]]</b>",
                    //"fillColorsField": "color",
                    "fillColors" : "#77cc74",
                    "fillAlphas": 1,
                    "lineAlpha": 0.1,
                    "type": "column",
                    "title": "Siswa SMP",
                    "valueField": "siswa_smp",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },
                {
                    "balloonText": "[[title]]: <b>[[value]]</b>",
                    //"fillColorsField": "color",
                    "fillColors" : "#178813",
                    "fillAlphas": 1,
                    "lineAlpha": 0.1,
                    "type": "column",
                    "title": "Anak Usia SMP",
                    "valueField": "anak_usia_smp",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },{
                    "balloonText": "[[title]]: <b>[[value]]</b>",
                    //"fillColorsField": "color",
                    "fillColors" : "#c5c4c6",
                    "fillAlphas": 1,
                    "lineAlpha": 0.1,
                    "type": "column",
                    "title": "Siswa SMA",
                    "valueField": "siswa_sma",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },
                {
                    "balloonText": "[[title]]: <b>[[value]]</b>",
                    //"fillColorsField": "color",
                    "fillColors" : "#7f7d80",
                    "fillAlphas": 1,
                    "lineAlpha": 0.1,
                    "type": "column",
                    "title": "Anak Usia SMA",
                    "valueField": "anak_usia_sma",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                }],
            "depth3D": 5,
            "angle": 15,
            /*"chartCursor": {
                "categoryBalloonEnabled": false,
                "cursorAlpha": 0,
                "zoomable": false
            },*/
            "categoryField": "year",
            "categoryAxis": {
                "gridPosition": "start",
            },
            "export": {
                "enabled": true,
                "pageorigin": false,
                "fileName":"Jumlah Siswa Putus Sekolah",
            },
            "legend": {
                "enabled": true,
                "useGraphSettings": true
            },
            "allLabels": [{
                "text": "Jumlah Siswa Putus Sekolah",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": 10
            }],
            "marginTop":50,
            "valueAxes": [{
                "baseValue" : 0,
                "minimum": 0
            }],
            "numberFormatter": {
                "precision": -1,
                "decimalSeparator": ",",
                "thousandsSeparator": "."
            }
        });
    }

    //Create Chart Jumlah Anak TIdak Sekolah

    function create_chart_fasilitas_sekolah(data) {
        AmCharts.addInitHandler( function ( chart_fasilitas ) {
            // set base values
            var categoryWidth = 50;

            var did = $('#list_desa').find(":selected").val();
            var year = $('#list_year').find(":selected").val();
            if(did == "Semua" && year == "Semua"){
                chart_fasilitas.categoryAxis.title = "Tahun";
                categoryWidth = 150;
            }
            if(did == "Semua" && year != "Semua"){
                chart_fasilitas.categoryAxis.title = "Desa";
                categoryWidth = 230;
            }
            if(did != "Semua" && year == "Semua"){
                chart_fasilitas.categoryAxis.title = "Desa";
                categoryWidth = 150;
            }
            if(did != "Semua" && year != "Semua"){
                chart_fasilitas.categoryAxis.title = "Semester";
                categoryWidth = 230;
            }
            // calculate bottom margi95based on number of data points
            var chartHeight = categoryWidth * chart_fasilitas.dataProvider.length;

            // set the value
            chart_fasilitas.div.style.height = chartHeight + 'px';


        }, ['serial'] );

        // Chart Perbandingan Jumlah Anak Tidak Sekolah
        var chart_fasilitas = AmCharts.makeChart("chart_fasilitas", {
            "type": "serial",
            "theme": "light",
            "legend": {
                "horizontalGap": 10,
                "maxColumns": 1,
                "position": "bottom",
                "useGraphSettings": true,
                "markerSize": 10
            },
            "dataProvider": data,
            "valueAxes": [{
                "stackType": "regular",
                "axisAlpha": 0.3,
                "gridAlpha": 0
            }],
            "graphs": [{
                "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
                "fillAlphas": 0.8,
                "labelText": "[[value]]",
                "lineAlpha": 0.3,
                "title": "Jumlah PAUD",
                "type": "column",
                "color": "#000000",
                "valueField": "jumlah_paud",
                "labelText" : "[[value]]",
                "labelPosition": "middle"
            }, {
                "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
                "fillAlphas": 0.8,
                "labelText": "[[value]]",
                "lineAlpha": 0.3,
                "title": "Jumlah Guru PAUD",
                "type": "column",
                "color": "#000000",
                "valueField": "jumlah_guru_paud",
                "labelText" : "[[value]]",
                "labelPosition": "middle"
            }, {
                "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
                "fillAlphas": 0.8,
                "labelText": "[[value]]",
                "lineAlpha": 0.3,
                "title": "Jumlah Siswa PAUD",
                "type": "column",
                "color": "#000000",
                "valueField": "jumlah_siswa_paud",
                "labelText" : "[[value]]",
                "labelPosition": "middle"
            }],
            "categoryField": "year",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "gridAlpha": 0,
                "position": "left"
            },
            "export": {
                "enabled": true,
                "pageOrigin":false,
                "fileName":"Perbandingan Jumlah Siswa dan Jumlah Fasilitas PAUD",
            },
            "hideCredits": true,
            "allLabels": [{
                "text": "Perbandingan Jumlah Siswa dan Jumlah Fasilitas PAUD",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": -4
            }],
            "numberFormatter": {
                "precision": -1,
                "decimalSeparator": ",",
                "thousandsSeparator": "."
            }
        });
    }
</script>

@endpush
