<div class="row">
    <div class="col-md-12">
        <legend>Tabel Angka Cakupan Imunisasi</legend>
        <table id="tbl_imunisasi" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Kuartal 1</th>
                <th class="text-center">Kuartal 2</th>
                <th class="text-center">Kuartal 3</th>
                <th class="text-center">Kuartal 4</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="text-center bg-maroon-active">Cakupan Imunisasi</th>
                <td>{{ $data_tabel['quartal']['q1']['cakupan_imunisasi'] }}</td>
                <td>{{ $data_tabel['quartal']['q2']['cakupan_imunisasi'] }}</td>
                <td>{{ $data_tabel['quartal']['q3']['cakupan_imunisasi'] }}</td>
                <td>{{ $data_tabel['quartal']['q4']['cakupan_imunisasi'] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <legend>Kuartal 1</legend>
        <table id="tbl_imunisasi_q1" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Cakupan Imunisasi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q1'] as $value)
            <tr>
                <td>{{ $value->nama }}</td>
                <td>{{ $value->cakupan_imunisasi }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-3">
        <legend>Kuartal 2</legend>
        <table id="tbl_imunisasi_q2" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Cakupan Imunisasi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q2'] as $value)
            <tr>
                <td>{{ $value->nama }}</td>
                <td>{{ $value->cakupan_imunisasi }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-3">
        <legend>Kuartal 3</legend>
        <table id="tbl_imunisasi_q3" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Cakupan Imunisasi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q2'] as $value)
            <tr>
                <td>{{ $value->nama }}</td>
                <td>{{ $value->cakupan_imunisasi }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-3">
        <legend>Kuartal 4</legend>
        <table id="tbl_imunisasi_q4" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Cakupan Imunisasi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q4'] as $value)
            <tr>
                <td>{{ $value->nama }}</td>
                <td>{{ $value->cakupan_imunisasi }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
