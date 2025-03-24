<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Detail Anggaran Desa (APBDes)</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="box-group" id="accordion">
            <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            4 - PENDAPATAN
                        </a>
                    </h4>
                    <div class="box-tools pull-right">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            <h4>@php
                                $pendapatan = $dataDetail->get('4');
                                $totalPendapatan = $pendapatan['attributes']['anggaran'];
                                echo format_number_id($totalPendapatan);
                            @endphp</h4>
                        </a>
                    </div>
                </div>
                <div id="collapseOne" class="panel-collapse collapse">
                    <div class="box-body">
                        <table class="table table-striped table-bordered" id="data-coa">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 100px">Nomor Akun</th>
                                    <th>Nama Akun</th>
                                    <th style="width: 150px; text-align: center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendapatan['children'] as $subCoa)
                                    <tr>
                                        <td class="icon-class"></td>
                                        <td><strong>{{ $subCoa['attributes']['template_uuid'] }}</strong></td>                                        
                                        <td><strong>{{ $subCoa['attributes']['uraian'] }}</strong></td>
                                        <td align="right">
                                            <strong>
                                             {{ format_number_id($subCoa['attributes']['anggaran']) }}
                                            </strong>
                                        </td>
                                    </tr>
                                    @foreach ($subCoa['children'] as $subSubCoa)
                                        <tr>
                                            <td class="icon-class"></td>
                                            <td><strong>{{ $subSubCoa['attributes']['template_uuid'] }}</strong></td>                                        
                                            <td style="padding-left:30px"><strong>{{ $subSubCoa['attributes']['uraian'] }}</strong></td>
                                            <td align="right">
                                                <strong>
                                                {{ format_number_id($subSubCoa['attributes']['anggaran']) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                            5 - BELANJA
                        </a>
                    </h4>
                    <div class="box-tools pull-right">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                            <h4>@php
                                $belanja = $dataDetail->get('5');                                
                                $totalBelanja = $belanja['attributes']['anggaran'];
                                echo format_number_id($totalBelanja);
                            @endphp</h4>
                        </a>
                    </div>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="box-body">
                        <table class="table table-striped table-bordered" id="data-coa">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 100px">Nomor Akun</th>
                                    <th>Nama Akun</th>
                                    <th style="width: 150px; text-align: center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($belanja['children'] as $subCoa)
                                    <tr>
                                        <td class="icon-class"></td>
                                        <td><strong>{{ $subCoa['attributes']['template_uuid'] }}</strong></td>                                        
                                        <td><strong>{{ $subCoa['attributes']['uraian'] }}</strong></td>
                                        <td align="right">
                                            <strong>
                                             {{ format_number_id($subCoa['attributes']['anggaran']) }}
                                            </strong>
                                        </td>
                                    </tr>
                                    @foreach ($subCoa['children'] as $subSubCoa)
                                        <tr>
                                            <td class="icon-class"></td>
                                            <td><strong>{{ $subSubCoa['attributes']['template_uuid'] }}</strong></td>                                        
                                            <td style="padding-left:30px"><strong>{{ $subSubCoa['attributes']['uraian'] }}</strong></td>
                                            <td align="right">
                                                <strong>
                                                {{ format_number_id($subSubCoa['attributes']['anggaran']) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                            6 - PEMBIAYAAN
                        </a>
                    </h4>
                    <div class="box-tools pull-right">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                            <h4>@php
                                $biaya = $dataDetail->get('6');                                
                                $totalBiaya = $biaya['attributes']['anggaran'];
                                echo format_number_id($totalBiaya);
                            @endphp</h4>
                        </a>
                    </div>
                </div>
                <div id="collapseThree" class="panel-collapse collapse">
                    <div class="box-body">
                        <table class="table table-striped table-bordered" id="data-coa">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 100px">Nomor Akun</th>
                                    <th>Nama Akun</th>
                                    <th style="width: 150px; text-align: center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($biaya['children'] as $subCoa)
                                    <tr>
                                        <td class="icon-class"></td>
                                        <td><strong>{{ $subCoa['attributes']['template_uuid'] }}</strong></td>                                        
                                        <td><strong>{{ $subCoa['attributes']['uraian'] }}</strong></td>
                                        <td align="right">
                                            <strong>
                                             {{ format_number_id($subCoa['attributes']['anggaran']) }}
                                            </strong>
                                        </td>
                                    </tr>
                                    @foreach ($subCoa['children'] as $subSubCoa)
                                        <tr>
                                            <td class="icon-class"></td>
                                            <td><strong>{{ $subSubCoa['attributes']['template_uuid'] }}</strong></td>                                        
                                            <td style="padding-left:30px"><strong>{{ $subSubCoa['attributes']['uraian'] }}</strong></td>
                                            <td align="right">
                                                <strong>
                                                {{ format_number_id($subSubCoa['attributes']['anggaran']) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
