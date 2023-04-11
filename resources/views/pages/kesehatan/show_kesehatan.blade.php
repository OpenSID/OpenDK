@extends('layouts.app')
@section('content')
<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-header with-border">
            <form class="form-horizontal">
                <div class="col-md-4 col-lg-4 col-sm-12">
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

                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <label for="list_year" class="col-sm-4 control-label">Tahun</label>

                        <div class="col-sm-8">
                            <select class="form-control" id="list_year">
                                <option value="Semua">Semua</option>
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
                    <li class="active"><a href="#aki_akb" data-toggle="tab">Data Kematian Ibu dan Bayi</a></li>
                    <li><a href="#imunisasi" data-toggle="tab">Cakupan Imunisasi</a></li>
                    <li><a href="#epidemi" data-toggle="tab">Epidemi Penyakit</a></li>
                    <li><a href="#toilet_sanitasi" data-toggle="tab">Toilet & Sanitasi</a></li>
                </ul>

                <div class="tab-content">
                    <div class="active tab-pane" id="aki_akb">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="chart_aki_akb" style="width: 100%; overflow: visible; text-align: left;"></div>
                            </div>
                            <div id="tabel_aki_akb" class="col-md-12">

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="imunisasi">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="chart_imunisasi" style="width: 100%; overflow: visible; text-align: left;"></div>
                            </div>
                            <div id="tabel_imunisasi" class="col-md-12">

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="epidemi">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="chart_penyakit" style="width: 100%; overflow: visible; text-align: left;"></div>
                            </div>
                            <div id="tabel_penyakit" class="col-md-12">

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="toilet_sanitasi">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="chart_sanitasi" style="width: 100%; overflow: visible; text-align: left;"></div>
                            </div>
                            <div id="tabel_sanitasi" class="col-md-12">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
        </div>
    </div>


</div>
@endsection
@include('partials.asset_datatables')
@include('partials.asset_amcharts')
@include('partials.asset_select2')
@push('scripts')
<script>
    $(function () {
        // Select 2 Kecamatan
        $('#list_desa').select2();
        $('#list_year').select2();
        // Change Dashboard when Lsit Desa changed
        $('#list_desa').on('select2:select', function (e) {
            var did = e.params.data;
            var year = $('#list_year').find(":selected").text();

            change_das_kesehatan(did.id, year);
        });

        // Change Dashboard when List Year changed
        $('#list_year').on('select2:select', function (e) {
            var did = $('#list_desa').find(":selected").val();
            var year = this.value;
            change_das_kesehatan(did, year);
        });


        /*
         * Initial Dashboard
         */
        var did = $('#list_desa').find(":selected").val();
        var year = $('#list_year').find(":selected").text();

        change_das_kesehatan(did, year);
        /*
         * End Initial Dashboard
         */
    });

    function change_das_kesehatan(did, year) {
        $.ajax('{!! route('statistik.kesehatan.chart-akiakb') !!}', {
            data: {did: did, y: year}
        }).done(function (data) {
            create_chart_akiakb(data['grafik']);
            $('#tabel_aki_akb').html(data['tabel']);
            $('#tbl_aki_akb').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_aki_akb_q1').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_aki_akb_q2').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_aki_akb_q3').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_aki_akb_q4').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
        });

        $.ajax('{!! route('statistik.kesehatan.chart-imunisasi') !!}', {
            data: {did: did, y: year}
        }).done(function (data) {
            create_chart_imunisasi(data['grafik']);
            $('#tabel_imunisasi').html(data['tabel']);
            $('#tbl_imunisasi').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_imunisasi_q1').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_imunisasi_q2').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_imunisasi_q3').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_imunisasi_q4').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
        });

        $.ajax('{!! route('statistik.kesehatan.chart-penyakit') !!}', {
            data: {did: did, y: year}
        }).done(function (data) {
            if (year == 'Semua') {
                create_chart_penyakit(data['grafik']);
            } else {
                create_chart_penyakit2(data['grafik']);
            }

            $('#tabel_penyakit').html(data['tabel']);
        });

        $.ajax('{!! route('statistik.kesehatan.chart-sanitasi') !!}', {
            data: {did: did, y: year}
        }).done(function (data) {
            create_chart_sanitasi(data['grafik']);
            $('#tabel_sanitasi').html(data['tabel']);
            $('#tbl_toilet').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_toilet_q1').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_toilet_q2').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_toilet_q3').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
            $('#tbl_toilet_q4').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false
            });
        });
    }


    /**
     * Create the chart
     */
    function create_chart_akiakb(data) {

        AmCharts.addInitHandler( function ( chart_aki_akb ) {
            // set base values
            var categoryWidth = 145;

            // calculate bottom margin based on number of data points
            var chartHeight = categoryWidth * chart_aki_akb.dataProvider.length;

            // set the value
            chart_aki_akb.div.style.height = chartHeight + 'px';

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
            if (!chart_aki_akb.legend || !chart_aki_akb.legend.enabled || !chart_aki_akb.legend.generateFromData) {
                return;
            }

            var categoryField = chart_aki_akb.categoryField;
            var colorField = chart_aki_akb.graphs[0].lineColorField || chart_aki_akb.graphs[0].fillColorsField || chart_aki_akb.graphs[0].colorField || chart_aki_akb.graphs[0].fillColors;
            var legendData =  chart_aki_akb.dataProvider.map(function(data, idx) {
                var markerData = {
                    "title": data[categoryField] + ": " + data[chart_aki_akb.graphs[0].valueField],
                    "color": data[colorField],
                    "dataIdx": idx //store a copy of the index of where this appears in the dataProvider array for ease of removal/re-insertion
                };
                if (!markerData.color) {
                    markerData.color = chart_aki_akb.graphs[0].lineColor;
                }
                data.dataIdx = idx; //also store it in the dataProvider object itself
                return markerData;
            });

            chart_aki_akb.legend.data = legendData;

            //make the markers toggleable
            chart_aki_akb.legend.switchable = true;
            chart_aki_akb.legend.addListener("clickMarker", handleCustomMarkerToggle);

        }, ['serial'] );

        var chart_aki_akb = AmCharts.makeChart("chart_aki_akb", {
            "type": "serial",
            "theme": "light",
            "categoryField": "year",
            "hideCredits": true,
            "rotate": true,
            "legend": {
                "enabled": true,
            },
            "startDuration": 1,
            "categoryAxis": {
                "gridPosition": "start",
                //"position": "left",
                "title": "Kuartal"
            },
            //"trendLines": [],
            "graphs": [
                {
                    "balloonText": "AKI:[[value]]",
                    "fillAlphas": 0.8,
                    //"fillColors": "#07749f",
                    "id": "AmGraph-1",
                    "lineAlpha": 0.2,
                    "title": "AKI",
                    "type": "column",
                    "valueField": "aki",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },
                {
                    "balloonText": "AKB:[[value]]",
                    "fillAlphas": 0.8,
                    //"fillColors": "#025777",
                    "id": "AmGraph-2",
                    "lineAlpha": 0.2,
                    "title": "AKB",
                    "type": "column",
                    "valueField": "akb",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                }
            ],
            "depth3D": 5,
            "angle": 15,
            "guides": [],
            "marginTop": 50,
            "valueAxes": [{
                "position": "left",
                "title": "Jumlah Angka Kematian",
                "baseValue" : 0,
                "minimum": 0
            }],
            "allLabels": [{
                "text": "Jumlah Angka Kematian Ibu & Bayi",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": 10
            }],
            "balloon": {},
            "titles": [],
            "dataProvider": data,
            "export": {
                "enabled": true,
                "pageOrigin": false,
                "fileName" :"Jumlah Angka Kematian Ibu & Bayi",
            },
            "numberFormatter": {
                "precision": -1,
                "decimalSeparator": ",",
                "thousandsSeparator": "."
            }
        });
    }

    function create_chart_imunisasi(data) {
        AmCharts.addInitHandler( function ( chart_imunisasi ) {
            // set base values
            var categoryWidth = 95;

            // calculate bottom margin based on number of data points
            var chartHeight = categoryWidth * chart_imunisasi.dataProvider.length;

            // set the value
            chart_imunisasi.div.style.height = chartHeight + 'px';

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
            if (!chart_imunisasi.legend || !chart_imunisasi.legend.enabled || !chart_imunisasi.legend.generateFromData) {
                return;
            }

            var categoryField = chart_imunisasi.categoryField;
            var colorField = chart_imunisasi.graphs[0].lineColorField || chart_imunisasi.graphs[0].fillColorsField || chart_imunisasi.graphs[0].colorField || chart_imunisasi.graphs[0].fillColors;
            var legendData =  chart_imunisasi.dataProvider.map(function(data, idx) {
                var markerData = {
                    "title": data[categoryField] + ": " + data[chart_imunisasi.graphs[0].valueField],
                    "color": data[colorField],
                    "dataIdx": idx //store a copy of the index of where this appears in the dataProvider array for ease of removal/re-insertion
                };
                if (!markerData.color) {
                    markerData.color = chart_imunisasi.graphs[0].lineColor;
                }
                data.dataIdx = idx; //also store it in the dataProvider object itself
                return markerData;
            });

            chart_imunisasi.legend.data = legendData;

            //make the markers toggleable
            chart_imunisasi.legend.switchable = true;
            chart_imunisasi.legend.addListener("clickMarker", handleCustomMarkerToggle);

        }, ['serial'] );

        var chart_imunisasi = AmCharts.makeChart("chart_imunisasi", {
            "type": "serial",
            "theme": "light",
            "categoryField": "year",
            "hideCredits": true,
            "rotate": true,
            "legend": {
                "enabled": true,
            },
            "startDuration": 1,
            "categoryAxis": {
                "gridPosition": "start",
                //"position": "left",
                "title": "Kuartal"
            },
            //"trendLines": [],
            "graphs": [
                {
                    "balloonText": "Cakupan Imunisasi:[[value]]",
                    "fillAlphas": 0.8,
                    //"fillColors": "#03749f",
                    "id": "AmGraph-1",
                    "lineAlpha": 0.2,
                    "title": "Cakupan Imunisasi",
                    "type": "column",
                    "valueField": "cakupan_imunisasi",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },
            ],
            "depth3D": 5,
            "angle": 15,
            "guides": [],
            "marginTop": 50,
            "valueAxes": [{
                "position": "left",
                "title": "Jumlah Cakupan Imunisasi (%)",
                "baseValue" : 0,
                "minimum": 0
            }],
            "allLabels": [{
                "text": "Jumlah Persentase Cakupan Imunisasi",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": 10
            }],
            "balloon": {},
            "titles": [],
            "dataProvider": data,
            "export": {
                "enabled": true,
                "pageOrigin": false,
                "fileName" : "Jumlah Persentase Cakupan Imunisasi",
            },
            "numberFormatter": {
                "precision": -1,
                "decimalSeparator": ",",
                "thousandsSeparator": "."
            }
        });
    }

    function create_chart_penyakit(data) {

        var chart_penyakit = AmCharts.makeChart("chart_penyakit", {
            "type": "serial",
            "theme": "light",
            "categoryField": "year",
            "hideCredits": true,
            "rotate": true,
            "legend": {
                "enabled": true,
            },
            "startDuration": 1,
            "categoryAxis": {
                "gridPosition": "start",
                //"position": "left",
                "title": "Kuartal"
            },
            //"trendLines": [],
            "graphs": [
                {
                    "balloonText": "Jumlah Penderita:[[value]]",
                    "fillAlphas": 0.8,
                    //"fillColors": "#03749f",
                    "id": "AmGraph-1",
                    "lineAlpha": 0.2,
                    "title": "Jumlah Penderita",
                    "type": "column",
                    "valueField": "jumlah",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },
            ],
            "depth3D": 5,
            "angle": 15,
            "guides": [],
            "marginTop": 50,
            "valueAxes": [{
                "position": "left",
                "title": "Jumlah",
                "baseValue" : 0,
                "minimum": 0
            }],
            "allLabels": [{
                "text": "Jumlah Epidemi Penyakit",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": 10
            }],
            "balloon": {},
            "titles": [],
            "dataProvider": data,
            "export": {
                "enabled": true,
                "pageOrigin": false,
                "fileName":"Jumlah Toilet & Sanitasi",
            },
            "numberFormatter": {
                "precision": -1,
                "decimalSeparator": ",",
                "thousandsSeparator": "."
            }
        });
    }

    function create_chart_penyakit2(data) {
        AmCharts.addInitHandler( function ( chart_penyakit2 ) {
            // set base values
            var categoryWidth = 145;

            // calculate bottom margin based on number of data points
            var chartHeight = categoryWidth * chart_penyakit2.dataProvider.length;

            // set the value
            chart_penyakit2.div.style.height = chartHeight + 'px';

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
            if (!chart_penyakit2.legend || !chart_penyakit2.legend.enabled || !chart_penyakit2.legend.generateFromData) {
                return;
            }

            var categoryField = chart_penyakit2.categoryField;
            var colorField = chart_penyakit2.graphs[0].lineColorField || chart_penyakit2.graphs[0].fillColorsField || chart_penyakit2.graphs[0].colorField || chart_penyakit2.graphs[0].fillColors;
            var legendData =  chart_penyakit2.dataProvider.map(function(data, idx) {
                var markerData = {
                    "title": data[categoryField] + ": " + data[chart_penyakit2.graphs[0].valueField],
                    "color": data[colorField],
                    "dataIdx": idx //store a copy of the index of where this appears in the dataProvider array for ease of removal/re-insertion
                };
                if (!markerData.color) {
                    markerData.color = chart_penyakit2.graphs[0].lineColor;
                }
                data.dataIdx = idx; //also store it in the dataProvider object itself
                return markerData;
            });

            chart_penyakit2.legend.data = legendData;

            //make the markers toggleable
            chart_penyakit2.legend.switchable = true;
            chart_penyakit2.legend.addListener("clickMarker", handleCustomMarkerToggle);

        }, ['serial'] );

        var chart_penyakit2 = AmCharts.makeChart("chart_penyakit", {
            "type": "serial",
            "theme": "light",
            "categoryField": "year",
            "rotate": true,
            "hideCredits" :true,
            "startDuration": 1,
            "categoryAxis": {
                "gridPosition": "start",
                "position": "left",
                "title": "Semester"
            },
            "trendLines": [],
            "graphs": [
                {
                    "balloonText": "Jumlah Penderita:[[value]]",
                    "fillAlphas": 0.8,
                    //"fillColors": "#03749f",
                    "id": "AmGraph-1",
                    "lineAlpha": 0.2,
                    "title": "Jumlah Penderita",
                    "type": "column",
                    "valueField": "penyakit1",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },
                {
                    "balloonText": "Jumlah Penderita:[[value]]",
                    "fillAlphas": 0.8,
                    //"fillColors": "#03749f",
                    "id": "AmGraph-1",
                    "lineAlpha": 0.2,
                    "title": "Jumlah Penderita",
                    "type": "column",
                    "valueField": "penyakit2",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },
                {
                    "balloonText": "Jumlah Penderita:[[value]]",
                    "fillAlphas": 0.8,
                    //"fillColors": "#03749f",
                    "id": "AmGraph-1",
                    "lineAlpha": 0.2,
                    "title": "Jumlah Penderita",
                    "type": "column",
                    "valueField": "penyakit3",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },
                {
                    "balloonText": "Jumlah Penderita:[[value]]",
                    "fillAlphas": 0.8,
                    //"fillColors": "#03749f",
                    "id": "AmGraph-1",
                    "lineAlpha": 0.2,
                    "title": "Jumlah Penderita",
                    "type": "column",
                    "valueField": "penyakit4",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                }
            ],
            "guides": [],
            "valueAxes": [{
                "position": "left",
                "title": "Jumlah",
                "baseValue" : 0,
                "minimum": 0
            }],
            "allLabels": [{
                "text": "Jumlah Epidemi Penyakit",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": 10
            }],
            "marginTop":50,
            "dataProvider": data,
            "export": {
                "enabled": true,
                "pageOrigin" : false,
                "fileName":"Jumlah Epidemi Penyakit",
            },
            "legend": {
                "enabled" : true
            },
            "numberFormatter": {
                "precision": -1,
                "decimalSeparator": ",",
                "thousandsSeparator": "."
            }
        });

    }

    function create_chart_sanitasi(data) {
        AmCharts.addInitHandler( function ( chart_sanitasi ) {
            // set base values
            var categoryWidth = 145;

            // calculate bottom margin based on number of data points
            var chartHeight = categoryWidth * chart_sanitasi.dataProvider.length;

            // set the value
            chart_sanitasi.div.style.height = chartHeight + 'px';

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
            if (!chart_sanitasi.legend || !chart_sanitasi.legend.enabled || !chart_sanitasi.legend.generateFromData) {
                return;
            }

            var categoryField = chart_sanitasi.categoryField;
            var colorField = chart_sanitasi.graphs[0].lineColorField || chart_sanitasi.graphs[0].fillColorsField || chart_sanitasi.graphs[0].colorField || chart_sanitasi.graphs[0].fillColors;
            var legendData =  chart_sanitasi.dataProvider.map(function(data, idx) {
                var markerData = {
                    "title": data[categoryField] + ": " + data[chart_sanitasi.graphs[0].valueField],
                    "color": data[colorField],
                    "dataIdx": idx //store a copy of the index of where this appears in the dataProvider array for ease of removal/re-insertion
                };
                if (!markerData.color) {
                    markerData.color = chart_sanitasi.graphs[0].lineColor;
                }
                data.dataIdx = idx; //also store it in the dataProvider object itself
                return markerData;
            });

            chart_sanitasi.legend.data = legendData;

            //make the markers toggleable
            chart_sanitasi.legend.switchable = true;
            chart_sanitasi.legend.addListener("clickMarker", handleCustomMarkerToggle);

        }, ['serial'] );

        var chart_sanitasi= AmCharts.makeChart("chart_sanitasi", {
            "type": "serial",
            "theme": "light",
            "categoryField": "year",
            "hideCredits": true,
            "rotate": true,
            "legend": {
                "enabled": true,
            },
            "startDuration": 1,
            "categoryAxis": {
                "gridPosition": "start",
                //"position": "left",
                "title": "Kuartal"
            },
            //"trendLines": [],
            "graphs": [
                {
                    "balloonText": "Toilet:[[value]]",
                    "fillAlphas": 0.8,
                    //"fillColors": "#07749f",
                    "id": "AmGraph-1",
                    "lineAlpha": 0.2,
                    "title": "Toilet",
                    "type": "column",
                    "valueField": "toilet",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                },
                {
                    "balloonText": "Sanitasi:[[value]]",
                    "fillAlphas": 0.8,
                    //"fillColors": "#025777",
                    "id": "AmGraph-2",
                    "lineAlpha": 0.2,
                    "title": "Sanitasi",
                    "type": "column",
                    "valueField": "sanitasi",
                    "labelText" : "[[value]]",
                    "labelPosition": "middle"
                }
            ],
            "depth3D": 5,
            "angle": 15,
            "guides": [],
            "marginTop": 50,
            "valueAxes": [{
                "position": "left",
                "title": "Jumlah",
                "baseValue" : 0,
                "minimum": 0
            }],
            "allLabels": [{
                "text": "Jumlah Toilet & Sanitasi",
                "align": "center",
                "bold": true,
                "size": 20,
                "y": 10
            }],
            "balloon": {},
            "titles": [],
            "dataProvider": data,
            "export": {
                "enabled": true,
                "pageOrigin": false,
                "fileName":"Jumlah Toilet & Sanitasi",
            },
            "numberFormatter": {
                "precision": -1,
                "decimalSeparator": ",",
                "thousandsSeparator": "."
            }
        });
    }
</script>
@endpush
