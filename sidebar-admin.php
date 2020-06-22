<?php

  function sidebar($active){
    $actives = array("","","","","");
    switch($active){
      case 'dpna' : $actives[0] = 'class = "active"'; break;
      case 'list mahasiswa' : $actives[1] = 'class = "active"'; break;
      case 'ruang baca' : $actives[2] = 'class = "active"'; break;
      case 'karyawan' : $actives[3] = 'class = "active"'; break;
      case 'setting admin' : $actives[4] = 'class = "active"'; break;
    }

    echo '
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <ul class="sidebar-menu">
        <li class="header">Feature</li>
        <li '.$actives[0].'><a href="dpna.php"><i class="fa fa-book"></i> <span>Proses DPNA</span></a></li>
        <li '.$actives[1].'><a href="#"><i class="fa fa-users"></i> <span>List Mahasiswa</span></a></li>
        <li '.$actives[2].'><a href="pustaka.php"><i class="fa fa-book"></i> <span>Ruang Baca</span></a></li>
        <li '.$actives[3].'><a href="karyawan.php"><i class="fa fa-user-md"></i> <span>Karyawan</span></a></li>
        <li '.$actives[4].'><a href="konfigurasi.php"><i class="fa fa-cog"></i> <span>Seting admin</span></a></li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->';
  }

?>
