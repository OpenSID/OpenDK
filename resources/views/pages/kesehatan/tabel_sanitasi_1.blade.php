<div class="row">
    <div class="col-md-12">
        <legend>Tabel Jumlah Toilet & Sanitasi</legend>
        <table id="tbl_toilet" class="table table-bordered table-striped">
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
                <th class="text-center bg-maroon-active">Toilet</th>
                <td>{{ $data_tabel['quartal']['q1']['toilet'] }}</td>
                <td>{{ $data_tabel['quartal']['q2']['toilet'] }}</td>
                <td>{{ $data_tabel['quartal']['q3']['toilet'] }}</td>
                <td>{{ $data_tabel['quartal']['q4']['toilet'] }}</td>
            </tr>
            <tr>
                <th class="text-center bg-maroon-active">Sanitasi</th>
                <td>{{ $data_tabel['quartal']['q1']['sanitasi'] }}</td>
                <td>{{ $data_tabel['quartal']['q2']['sanitasi'] }}</td>
                <td>{{ $data_tabel['quartal']['q3']['sanitasi'] }}</td>
                <td>{{ $data_tabel['quartal']['q4']['sanitasi'] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <legend>Kuartal 1</legend>
        <table id="tbl_toilet_q1" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Jumlah Toilet</th>
                <th class="text-center">Jumlah Sanitasi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q1'] as $value)
            <tr>
                <td>{{ $value->nama }}</td>
                <td>{{ $value->toilet }}</td>
                <td>{{ $value->sanitasi }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <legend>Kuartal 2</legend>
        <table id="tbl_toilet_q2" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Jumlah Toilet</th>
                <th class="text-center">Jumlah Sanitasi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q2'] as $value)
                <tr>
                    <td>{{ $value->nama }}</td>
                    <td>{{ $value->toilet }}</td>
                    <td>{{ $value->sanitasi }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <legend>Kuartal 3</legend>
        <table id="tbl_toilet_q3" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Jumlah Toilet</th>
                <th class="text-center">Jumlah Sanitasi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q3'] as $value)
                <tr>
                    <td>{{ $value->nama }}</td>
                    <td>{{ $value->toilet }}</td>
                    <td>{{ $value->sanitasi }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <legend>Kuartal 4</legend>
        <table id="tbl_toilet_q4" class="table table-bordered table-striped">
            <thead class="bg-primary">
            <tr>
                <th class="text-center">Desa</th>
                <th class="text-center">Jumlah Toilet</th>
                <th class="text-center">Jumlah Sanitasi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_tabel['desa']['q4'] as $value)
                <tr>
                    <td>{{ $value->nama }}</td>
                    <td>{{ $value->toilet }}</td>
                    <td>{{ $value->sanitasi }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
