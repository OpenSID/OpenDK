<div class="box-header text-center  with-border bg-blue">
    <h2 class="box-title text-bold">PENGUNJUNG</h2>
</div>
   <div class="pad text-bold bg-white" >
    <ul class="nav">
        <li>Hari Ini <span class="pull-right badge bg-blue">{{ Counter::allVisitors(1) }} Kunjungan</span> </li>
        <li>7 hari yang lalu <span class="pull-right badge bg-aqua">{{ Counter::allVisitors(7) }} Kunjungan</span> </li>
        <li>Total <span class="pull-right badge bg-red">{{ Counter::allVisitors() }} Kunjungan</span> </li>
      </ul>
    </div>
<!-- /.col -->