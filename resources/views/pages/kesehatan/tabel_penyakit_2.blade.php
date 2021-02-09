<div class="row">
    <div class="col-md-12">
        <legend>Tabel Jumlah Penyakit</legend>
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
