@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> Persentase Anggaran Desa
                </h3>
            </div>
            <div class="box box-header">
                <form class="form-horizontal">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label for="list_desa" class="col-sm-4 control-label">Desa</label>
                            <div class="col-sm-8">
                                <input type="hidden" id="defaultProfil" value="{{ $defaultProfil }}">
                                <select class="form-control" id="list_desa">
                                    <option value="ALL">ALL</option>
                                    @foreach ($list_desa as $desa)
                                        <option value="{{ $desa->desa_id }}">{{ $desa->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label for="bulan" class="col-sm-4 control-label">Bulan</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="list_months" name="m">
                                    <option value="ALL">ALL</option>
                                    @foreach (months_list() as $key => $month)
                                        <option value="{{ $key }}">{{ $month }}</option>
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
                                    <option value="ALL">ALL</option>
                                    @foreach ($year_list as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box box-widget">
                <div class="box-header">
                    {{-- <h3 class="box-title">Persentase Anggaran Desa</h3> --}}
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div id="chartdiv" style="width: 100%; height: 400px; overflow: hidden; text-align: left;">
                    </div>
                    <!-- /.row -->
                </div>
                <!-- ./box-body -->
            </div>
            <div id="detail_anggaran">
            </div>
        </div>
    </div>
    <!-- /.content -->
    @endsection @include('partials.asset_amcharts') @include('partials.asset_select2') @push('scripts')
    <script>
        $(function() {

            // Select 2 Kecamatan
            $('#list_desa').select2();
            $('#list_months').select2();
            $('#list_year').select2();


            var did = $('#list_desa').find(":selected").val();
            var mid = $('#list_months').find(":selected").val();
            var year = $('#list_year').find(":selected").val();

            /*
             Initial Chart Dashboard Pendidikan
             */
            das_chart_anggaran(mid, did, year);
            /*
             End Initial
             */


            $('#list_desa').on('select2:select', function(e) {
                var did = $('#list_desa').find(":selected").val();
                var mid = $('#list_months').find(":selected").val();
                var year = $('#list_year').find(":selected").val();
                das_chart_anggaran(mid, did, year);
            });

            $('#list_months').on('select2:select', function(e) {
                var did = $('#list_desa').find(":selected").val();
                var mid = $('#list_months').find(":selected").val();
                var year = $('#list_year').find(":selected").val();
                das_chart_anggaran(mid, did, year);
            });

            $('#list_year').on('select2:select', function(e) {
                var did = $('#list_desa').find(":selected").val();
                var mid = $('#list_months').find(":selected").val();
                var year = $('#list_year').find(":selected").val();
                das_chart_anggaran(mid, did, year);
            });
        });

        function das_chart_anggaran(mid, did, year) {

            $.ajax('{!!  route('statistik.chart-anggaran-desa') !!}', {
                    data: {
                        mid: mid,
                        did: did,
                        y: year
                    }
                }).done(function(data) {
                create_chart_anggaran(data.grafik);
                alert
                $('#detail_anggaran').html(data.detail);
            });

        }


        function create_chart_anggaran(data) {

            /**
             * Create the chart
             */
            /*AmCharts.addInitHandler( function ( chart ) {

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
                if (!chart.legend || !chart.legend.enabled || !chart.legend.generateFromData) {
                    return;
                }

                var categoryField = chart.categoryField;
                var colorField = chart.graphs.lineColorField || chart.graphs.fillColorsField || chart.graphs.colorField;
                var legendData =  chart.dataProvider.map(function(data, idx) {
                    var markerData = {
                        "title": data[categoryField] + ":as " + data[chart.graphs.valueField],
                        "color": data[colorField],
                        "marginRight":20,
                        "autoMargins":false,
                        "dataIdx": idx //store a copy of the index of where this appears in the dataProvider array for ease of removal/re-insertion
                    };
                    if (!markerData.color) {
                        markerData.color = chart.graphs.lineColor;
                    }
                    data.dataIdx = idx; //also store it in the dataProvider object itself
                    return markerData;
                });

                chart.legend.data = legendData;

                //make the markers toggleable
                chart.legend.switchable = true;
                chart.legend.addListener("clickMarker", handleCustomMarkerToggle);

            }, ['pie'] );*/

            var chart = AmCharts.makeChart("chartdiv", {
                "hideCredits": true,
                "type": "pie",
                "theme": "light",
                "dataProvider": data,
                "valueField": "jumlah",
                "titleField": "anggaran",
                "outlineAlpha": 0.4,
                "depth3D": 15,
                "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                "angle": 30,
                "export": {
                    "enabled": true,
                    "pageOrigin": false,
                    "fileName": "Persentase Anggaran Desa (APBDes)",
                },
                "allLabels": [{
                    "text": "Persentase Anggaran Desa (APBDes)",
                    "align": "center",
                    "bold": true,
                    "size": 20,
                    "y": 10
                }],
                /*"legend": {
                    "generateFromData": true //custom property for the plugin
                },*/
                "legend": {
                    "position": "bottom",
                    "marginRight": 20,
                    "marginLeft": 100,
                    "autoMargins": true,
                    "valueWidth": 90
                },
                "marginTop": 50
            });

            chart.addListener("init", handleInit);

            chart.addListener("rollOverSlice", function(e) {
                handleRollOver(e);
            });

            function handleInit() {
                chart.legend.addListener("rollOverItem", handleRollOver);
            }

            function handleRollOver(e) {
                var wedge = e.dataItem.wedge.node;
                wedge.parentNode.appendChild(wedge);
            }
        }

    </script>

@endpush
