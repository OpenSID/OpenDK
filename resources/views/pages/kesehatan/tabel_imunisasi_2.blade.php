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
