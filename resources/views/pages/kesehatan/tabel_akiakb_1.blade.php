<div class="row">
    <div class="col-md-12">
        <legend>Tabel Angka Kematian Ibu & Bayi</legend>
        <table id="tbl_aki_akb" class="table table-bordered table-striped">
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
                <th class="text-center bg-maroon-active">AKI</th>
                <td>{{ $data_tabel['quartal']['q1']['aki'] }}</td>
                <td>{{ $data_tabel['quartal']['q2']['aki'] }}</td>
                <td>{{ $data_tabel['quartal']['q3']['aki'] }}</td>
                <td>{{ $data_tabel['quartal']['q4']['aki'] }}</td>
            </tr>
            <tr>
                <th class="text-center bg-maroon-active">AKB</th>
                <td>{{ $data_tabel['quartal']['q1']['akb'] }}</td>
                <td>{{ $data_tabel['quartal']['q2']['akb'] }}</td>
                <td>{{ $data_tabel['quartal']['q3']['akb'] }}</td>
                <td>{{ $data_tabel['quartal']['q4']['akb'] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <legend>Kuartal 1</legend>
        <table id="tbl_aki_akb_q1" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Jumlah AKI</th>
                <th class="text-center">Jumlah AKB</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q1'] as $value)
            <tr>
                <td>{{ $value->nama }}</td>
                <td>{{ $value->aki }}</td>
                <td>{{ $value->akb }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <legend>Kuartal 2</legend>
        <table id="tbl_aki_akb_q2" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Jumlah AKI</th>
                <th class="text-center">Jumlah AKB</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q2'] as $value)
                <tr>
                    <td>{{ $value->nama }}</td>
                    <td>{{ $value->aki }}</td>
                    <td>{{ $value->akb }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <legend>Kuartal 3</legend>
        <table id="tbl_aki_akb_q3" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Jumlah AKI</th>
                <th class="text-center">Jumlah AKB</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q2'] as $value)
                <tr>
                    <td>{{ $value->nama }}</td>
                    <td>{{ $value->aki }}</td>
                    <td>{{ $value->akb }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <legend>Kuartal 4</legend>
        <table id="tbl_aki_akb_q4" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Jumlah AKI</th>
                <th class="text-center">Jumlah AKB</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q4'] as $value)
                <tr>
                    <td>{{ $value->nama }}</td>
                    <td>{{ $value->aki }}</td>
                    <td>{{ $value->akb }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>