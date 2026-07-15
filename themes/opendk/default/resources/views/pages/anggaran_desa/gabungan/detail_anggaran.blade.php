<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Detail Anggaran Desa (APBDes)</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="box-group" id="accordion">
            @php
                $sections = [
                    '4' => ['label' => '4 - PENDAPATAN', 'collapse' => 'collapseOne'],
                    '5' => ['label' => '5 - BELANJA', 'collapse' => 'collapseTwo'],
                    '6' => ['label' => '6 - PEMBIAYAAN', 'collapse' => 'collapseThree'],
                ];
            @endphp

            @foreach ($sections as $sectionKey => $section)
                @php
                    $sectionData = $dataDetail[$sectionKey] ?? null;
                    $sectionTotal = $sectionData ? ($sectionData['attributes']['anggaran_local'] ?? format_number_id(0)) : format_number_id(0);
                @endphp
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#{{ $section['collapse'] }}">
                                {{ $section['label'] }}
                            </a>
                        </h4>
                        <div class="box-tools pull-right">
                            <a data-toggle="collapse" data-parent="#accordion" href="#{{ $section['collapse'] }}">
                                <h4>{{ $sectionTotal }}</h4>
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
                                    @if ($sectionData && !empty($sectionData['children']))
                                        @foreach ($sectionData['children'] as $subCoa)
                                            @php
                                                $subParts = explode('.', $subCoa['id']);
                                                $subTypeId = $subParts[0] ?? '';
                                                $subId = $subParts[1] ?? '';
                                            @endphp
                                            <tr>
                                                <td class="icon-class"></td>
                                                <td><strong>{{ $subTypeId }}</strong></td>
                                                <td colspan="3"><strong>{{ $subId }}</strong></td>
                                                <td><strong>{{ $subCoa['attributes']['uraian'] ?? '' }}</strong></td>
                                                <td align="right">
                                                    <strong>{{ $subCoa['attributes']['anggaran_local'] ?? format_number_id(0) }}</strong>
                                                </td>
                                            </tr>
                                            @if (!empty($subCoa['children']))
                                                @foreach ($subCoa['children'] as $subSubCoa)
                                                    @php
                                                        $subSubParts = explode('.', $subSubCoa['id']);
                                                        $subSubTypeId = $subSubParts[0] ?? '';
                                                        $subSubSubId = $subSubParts[1] ?? '';
                                                        $subSubId = $subSubParts[2] ?? '';
                                                    @endphp
                                                    <tr>
                                                        <td class="icon-class"></td>
                                                        <td><strong>{{ $subSubTypeId }}</strong></td>
                                                        <td><strong>{{ $subSubSubId }}</strong></td>
                                                        <td colspan="2"><strong>{{ $subSubId }}</strong></td>
                                                        <td><strong>&emsp;&emsp;{{ $subSubCoa['attributes']['uraian'] ?? '' }}</strong></td>
                                                        <td align="right">
                                                            <strong>{{ $subSubCoa['attributes']['anggaran_local'] ?? format_number_id(0) }}</strong>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
