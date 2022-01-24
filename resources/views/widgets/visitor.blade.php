<div class="box-header text-center  with-border">
  <h2 class="box-title">PENGUNJUNG</h2>
</div>
<div class="box-footer no-padding">
  <ul class="nav nav-stacked">
    <li><a href="#">Hari Ini <span class="pull-right badge bg-blue">{{ Counter::allVisitors(1) }} </span></a></li>
    <li><a href="#">7 hari yang lalu <span class="pull-right badge bg-aqua">{{ Counter::allVisitors(7) }}</span></a></li>
    <li><a href="#">Total <span class="pull-right badge bg-green">{{ Counter::allVisitors() }} </span></a></li>
    <li><a href="#">IP Address {{ Request::ip() }}</a></li>
  </ul>
</div>