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
                                $query_pendapatan = \App\Models\AnggaranDesa::where('no_akun', 'LIKE', '4%');
                                if($did != 'Semua'){
                                    $query_pendapatan->where('desa_id', $did);
                                }
                                if($mid != 'Semua'){
                                    $query_pendapatan->where('bulan', $mid);
                                }
                                if($year != 'Semua'){
                                    $query_pendapatan->where('tahun', $year);
                                }
                                $total_pendapatan = $query_pendapatan->sum('jumlah');
                                echo number_format($total_pendapatan, 2);
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
                                <th style="width: 100px" colspan="4">Nomor Akun</th>
                                <th>Nama Akun</th>
                                <th style="width: 150px; text-align: center">Jumlah</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\Models\SubCoa::where('type_id', 4)->orderBy('type_id')->get() as $sub_coa)
                                <tr>
                                    <td class="icon-class"></td>
                                    <td><strong>{{ $sub_coa->type_id }}</strong></td>
                                    <td colspan="3"><strong>{{ $sub_coa->id }}</strong></td>
                                    <td><strong>{{ $sub_coa->sub_name }}</strong></td>
                                    <td align="right">
                                        <strong><?php
                                            $query_pendapatan_sub = \App\Models\AnggaranDesa::where('no_akun', 'LIKE', '4'.$sub_coa->id.'%');
                                            if($did != 'Semua'){
                                                $query_pendapatan_sub->where('desa_id', $did);
                                            }
                                            if($mid != 'Semua'){
                                                $query_pendapatan_sub->where('bulan', $mid);
                                            }
                                            if($year != 'Semua'){
                                                $query_pendapatan_sub->where('tahun', $year);
                                            }
                                            $total_pendapatan_sub = $query_pendapatan_sub->sum('jumlah');
                                            echo number_format($total_pendapatan_sub, 2);
                                            ?>
                                        </strong>
                                    </td>
                                </tr>
                                @foreach(\App\Models\SubSubCoa::where('type_id', $sub_coa->type_id)->where('sub_id', $sub_coa->id)->orderBy('sub_id')->get() as $sub_sub_coa)
                                    <tr>
                                        <td class="icon-class"></td>
                                        <td><strong>{{ 4 }}</strong></td>
                                        <td><strong>{{ $sub_sub_coa->sub_id }}</strong></td>
                                        <td colspan="2"><strong>{{ $sub_sub_coa->id }}</strong></td>
                                        <td><strong>&emsp;&emsp;{{ $sub_sub_coa->sub_sub_name }}</strong></td>
                                        <td align="right">
                                            <strong><?php
                                                $query_pendapatan_sub_sub = \App\Models\AnggaranDesa::where('no_akun', 'LIKE', '4'.$sub_coa->id.$sub_sub_coa->id.'%');
                                                if($did != 'Semua'){
                                                    $query_pendapatan_sub_sub->where('desa_id', $did);
                                                }
                                                if($mid != 'Semua'){
                                                    $query_pendapatan_sub_sub->where('bulan', $mid);
                                                }
                                                if($year != 'Semua'){
                                                    $query_pendapatan_sub_sub->where('tahun', $year);
                                                }
                                                $total_pendapatan_sub_sub = $query_pendapatan_sub_sub->sum('jumlah');
                                                echo number_format($total_pendapatan_sub_sub, 2);
                                                ?>
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
                                $query_belanja = \App\Models\AnggaranDesa::where('no_akun', 'LIKE', '5%');
                                if($did != 'Semua'){
                                $query_belanja->where('desa_id', $did);
                                }
                                if($mid != 'Semua'){
                                $query_belanja->where('bulan', $mid);
                                }
                                if($year != 'Semua'){
                                $query_belanja->where('tahun', $year);
                                }
                                $total_belanja = $query_belanja->sum('jumlah');
                                echo number_format($total_belanja, 2);
                                @endphp
                            </h4>
                        </a>
                    </div>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="box-body">
                        <table class="table table-striped table-bordered" id="data-coa">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th style="width: 100px" colspan="4">Nomor Akun</th>
                                <th>Nama Akun</th>
                                <th style="width: 150px; text-align: center">Jumlah</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\Models\SubCoa::where('type_id', 5)->orderBy('id')->get() as $sub_coa)
                                <tr>
                                    <td class="icon-class"></td>
                                    <td><strong>{{ $sub_coa->type_id }}</strong></td>
                                    <td colspan="3"><strong>{{ $sub_coa->id }}</strong></td>
                                    <td><strong>{{ $sub_coa->sub_name }}</strong></td>
                                    <td align="right">
                                        <strong><?php
                                            $query_belanja_sub = \App\Models\AnggaranDesa::where('no_akun', 'LIKE', '5'.$sub_coa->id.'%');
                                            if($did != 'Semua'){
                                                $query_belanja_sub->where('desa_id', $did);
                                            }
                                            if($mid != 'Semua'){
                                                $query_belanja_sub->where('bulan', $mid);
                                            }
                                            if($year != 'Semua'){
                                                $query_belanja_sub->where('tahun', $year);
                                            }
                                            $total_belanja_sub = $query_belanja_sub->sum('jumlah');
                                            echo number_format($total_belanja_sub, 2);
                                            ?>
                                        </strong>
                                    </td>
                                </tr>
                                @foreach(\App\Models\SubSubCoa::where('type_id', $sub_coa->type_id)->where('sub_id', $sub_coa->id)->orderBy('sub_id')->get() as $sub_sub_coa)
                                    <tr>
                                        <td class="icon-class"></td>
                                        <td><strong>{{ $sub_coa->type_id }}</strong></td>
                                        <td><strong>{{ $sub_sub_coa->sub_id }}</strong></td>
                                        <td colspan="2"><strong>{{ $sub_sub_coa->id }}</strong></td>
                                        <td><strong>&emsp;&emsp;{{ $sub_sub_coa->sub_sub_name }}</strong></td>
                                        <td align="right">
                                            <strong><?php
                                                $query_belanja_sub_sub = \App\Models\AnggaranDesa::where('no_akun', 'LIKE', '5'.$sub_coa->id.$sub_sub_coa->id.'%');
                                                if($did != 'Semua'){
                                                    $query_belanja_sub_sub->where('desa_id', $did);
                                                }
                                                if($mid != 'Semua'){
                                                    $query_belanja_sub_sub->where('bulan', $mid);
                                                }
                                                if($year != 'Semua'){
                                                    $query_belanja_sub_sub->where('tahun', $year);
                                                }
                                                $total_belanja_sub_sub = $query_belanja_sub_sub->sum('jumlah');
                                                echo number_format($total_belanja_sub_sub, 2);
                                                ?>
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
                                $query_biaya = \App\Models\AnggaranDesa::where('no_akun', 'LIKE', '6%');
                                if($did != 'Semua'){
                                $query_biaya->where('desa_id', $did);
                                }
                                if($mid != 'Semua'){
                                $query_biaya->where('bulan', $mid);
                                }
                                if($year != 'Semua'){
                                $query_biaya->where('tahun', $year);
                                }
                                $total_biaya = $query_biaya->sum('jumlah');
                                echo number_format($total_biaya, 2);
                                @endphp
                            </h4>
                        </a>
                    </div>
                </div>
                <div id="collapseThree" class="panel-collapse collapse">
                    <div class="box-body">
                        <table class="table table-striped table-bordered" id="data-coa">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th style="width: 100px" colspan="4">Nomor Akun</th>
                                <th>Nama Akun</th>
                                <th style="width: 150px; text-align: center">Jumlah</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\Models\SubCoa::where('type_id', 6)->orderBy('id')->get() as $sub_coa)
                                <tr>
                                    <td class="icon-class"></td>
                                    <td><strong>{{ $sub_coa->type_id }}</strong></td>
                                    <td colspan="3"><strong>{{ $sub_coa->id }}</strong></td>
                                    <td><strong>{{ $sub_coa->sub_name }}</strong></td>
                                    <td align="right">
                                        <strong><?php
                                            $query_biaya_sub = \App\Models\AnggaranDesa::where('no_akun', 'LIKE', '6'.$sub_coa->id.'%');
                                            if($did != 'Semua'){
                                                $query_biaya_sub->where('desa_id', $did);
                                            }
                                            if($mid != 'Semua'){
                                                $query_biaya_sub->where('bulan', $mid);
                                            }
                                            if($year != 'Semua'){
                                                $query_biaya_sub->where('tahun', $year);
                                            }
                                            $total_biaya_sub = $query_biaya_sub->sum('jumlah');
                                            echo number_format($total_biaya_sub, 2);
                                            ?>
                                        </strong>
                                    </td>
                                </tr>
                                @foreach(\App\Models\SubSubCoa::where('type_id', $sub_coa->type_id)->where('sub_id', $sub_coa->id)->orderBy('sub_id')->get() as $sub_sub_coa)
                                    <tr>
                                        <td class="icon-class"></td>
                                        <td><strong>{{ $sub_coa->type_id }}</strong></td>
                                        <td><strong>{{ $sub_sub_coa->sub_id }}</strong></td>
                                        <td colspan="2"><strong>{{ $sub_sub_coa->id }}</strong></td>
                                        <td><strong>&emsp;&emsp;{{ $sub_sub_coa->sub_sub_name }}</strong></td>
                                        <td align="right">
                                            <strong><?php
                                                $query_biaya_sub_sub = \App\Models\AnggaranDesa::where('no_akun', 'LIKE', '6'.$sub_coa->id.$sub_sub_coa->id.'%');
                                                if($did != 'Semua'){
                                                    $query_biaya_sub_sub->where('desa_id', $did);
                                                }
                                                if($mid != 'Semua'){
                                                    $query_biaya_sub_sub->where('bulan', $mid);
                                                }
                                                if($year != 'Semua'){
                                                    $query_biaya_sub_sub->where('tahun', $year);
                                                }
                                                $total_biaya_sub_sub = $query_biaya_sub_sub->sum('jumlah');
                                                echo number_format($total_biaya_sub_sub, 2);
                                                ?>
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