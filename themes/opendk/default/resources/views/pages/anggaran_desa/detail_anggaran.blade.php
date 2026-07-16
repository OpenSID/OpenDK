<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Detail Anggaran Desa (APBDes)</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="box-group" id="accordion">
            @foreach ($detailData as $section)
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#{{ $section['collapse'] }}">
                                {{ $section['label'] }}
                            </a>
                        </h4>
                        <div class="box-tools pull-right">
                            <a data-toggle="collapse" data-parent="#accordion" href="#{{ $section['collapse'] }}">
                                <h4>{{ $section['total'] }}</h4>
                            </a>
                        </div>
                    </div>
                    <div id="{{ $section['collapse'] }}" class="panel-collapse collapse">
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
                                    @foreach ($section['sub_coas'] as $sub)
                                        <tr>
                                            <td class="icon-class"></td>
                                            <td><strong>{{ $sub['type_id'] }}</strong></td>
                                            <td colspan="3"><strong>{{ $sub['id'] }}</strong></td>
                                            <td><strong>{{ $sub['sub_name'] }}</strong></td>
                                            <td align="right">
                                                <strong>{{ $sub['jumlah'] }}</strong>
                                            </td>
                                        </tr>
                                        @foreach ($sub['sub_sub_coas'] as $subSub)
                                            <tr>
                                                <td class="icon-class"></td>
                                                <td><strong>{{ $subSub['type_id'] }}</strong></td>
                                                <td><strong>{{ $subSub['sub_id'] }}</strong></td>
                                                <td colspan="2"><strong>{{ $subSub['id'] }}</strong></td>
                                                <td><strong>&emsp;&emsp;{{ $subSub['sub_sub_name'] }}</strong></td>
                                                <td align="right">
                                                    <strong>{{ $subSub['jumlah'] }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
