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
