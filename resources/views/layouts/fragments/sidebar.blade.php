@php $user = auth()->user(); @endphp
@php $user = auth()->user(); @endphp


<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <center>
        <!-- <div class="user-panel"> -->
                <img class="user-image" src="{{ is_logo($profil->file_logo) }}" alt="OpenDK" width="42px" style="margin: 5px;">
                <p style="font-size: 12px; color:white">
                    {{ strtoupper('Pemerintah Kab. ' . $profil->nama_kabupaten) }}<br>
                    {{  strtoupper('Kecamatan ' . $profil->nama_kecamatan) }}<br>
                </p>
        <!-- </div> -->
        </center>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            @if(isset($user) && $user->can('view'))
                <li class="header">MENU ADMINISTRATOR</li>
                @if($user->can('view'))
                <li class=" ">
                    <a href="@if(isset($user) && $user->hasAnyRole('super_admin')){{ route('dashboard') }}@else {{ '#' }} @endif" title="Dashboard">
                        <i class="fa fa-dashboard"></i><span>Dashboard</span>
                    </a>
                </li>
                 
                @endif

              
            @endif
            <li class="header">VISITOR COUNTER</li>
            
            
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
