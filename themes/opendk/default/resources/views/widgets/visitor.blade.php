<div class="box-header text-center  with-border bg-blue">
    <h2 class="box-title text-bold">PENGUNJUNG</h2>
</div>
<div class="pad text-bold bg-white">
    <ul class="nav">
        <li>Hari Ini <span class="pull-right badge bg-red">{{ Counter::visitors('today') }} Kunjungan</span> </li>
        <li>Kemarin <span class="pull-right badge bg-purple">{{ Counter::visitors('yesterday') }} Kunjungan</span> </li>
        <li>Minggu Ini <span class="pull-right badge bg-green">{{ Counter::visitors('week') }} Kunjungan</span> </li>
        <li>Bulan Ini <span class="pull-right badge bg-yellow">{{ Counter::visitors('month') }} Kunjungan</span> </li>
        <li>Tahun Ini <span class="pull-right badge bg-gray">{{ Counter::visitors('year') }} Kunjungan</span> </li>
        <li>Total <span class="pull-right badge bg-blue">{{ Counter::visitors('all') }} Kunjungan</span> </li>
    </ul>
</div>
<!-- /.col -->
