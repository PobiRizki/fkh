<?php
  include_once("../db_connect.php");
  include_once("session.php");

  //hanya pustakawan yang boleh akses
  if($userOnSession['status']!='pustakawan'){
    header('Location: pustaka.php');
  }

  date_default_timezone_set("Asia/Jakarta");
  $today = date("d/m/Y");

  $today_sql = date("Y-m-d");

  $query_info = "SELECT * FROM peminjaman_buku WHERE selesai = 0";
  $hasil = $conn->query($query_info);
  $jml_dipinjam = $hasil->num_rows;

  $query_info = "SELECT * FROM peminjaman_buku WHERE selesai = 0 AND tanggal_pengembalian < '$today_sql'";
  $hasil = $conn->query($query_info);
  $jml_terlambat = $hasil->num_rows

?>

<html>
<head>
  <?php include "admin-header-meta.php" ?>
  <link rel="stylesheet" href="../dist/css/admin/style-perpus.css"></link>
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <!-- Main Header -->
    <?php include "admin-header.php" ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

      <?php
        include "sidebar-admin.php";
        sidebar("ruang baca");
      ?>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
      </section>

      <!-- Main content -->
      <section class="content">

        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Filter</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <form role="form" action="list-peminjaman.php" method="get">
                    <!-- text input -->
                    <div class="form-group">
                      <label>NIM</label>
                      <input type="text" class="form-control" placeholder="NIM ..." name="nim" <?php if(isset($_GET['nim'])&&$_GET['nim']!='') echo 'value="'.$_GET['nim'].'"' ?>>
                    </div>
                    <div class="form-group">
                      <label>No. Buku</label>
                      <input type="text" class="form-control" placeholder="No. Buku ..." name="no" <?php if(isset($_GET['no'])&&$_GET['no']!='') echo 'value="'.$_GET['no'].'"' ?>>
                    </div>
                    <div class="form-group">
                      <label>Status</label>
                      <select class="form-control" name="status">
                        <option value="semua" <?php if(isset($_GET['status'])&&$_GET['status']=='semua') echo 'selected' ?>>Semua Peminjaman</option>
                        <option value="dikembalikan" <?php if(isset($_GET['status'])&&$_GET['status']=='dikembalikan') echo 'selected' ?>>Dikembalikan</option>
                        <option value="dipinjam" <?php if(isset($_GET['status'])&&$_GET['status']=='dipinjam') echo 'selected' ?>>Dipinjam</option>
                        <option value="terlambat" <?php if(isset($_GET['status'])&&$_GET['status']=='terlambat') echo 'selected' ?>>Terlambat</option>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-info pull-right">Terapkan</button>
                  </form>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <ul class="list-group list-group-unbordered">
                    <a href="list-peminjaman.php?status=semua">
                      <li class="list-group-item">
                        <b>Semua Peminjaman</b> <a class="pull-right"></a>
                      </li>
                    </a>
                    <a href="list-peminjaman.php?status=dipinjam">
                      <li class="list-group-item">
                        <b>Sedang Dipinjam</b> <a class="pull-right"><?php echo $jml_dipinjam; ?></a>
                      </li>
                    </a>
                    <a href="list-peminjaman.php?status=terlambat">
                      <li class="list-group-item">
                        <b>Terlambat</b> <a class="pull-right"><?php echo $jml_terlambat; ?></a>
                      </li>
                    </a>
                  </ul>
                </div>
              </div>
            <!-- /.box -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">

            <div class="modal fade" id="hapusDialog" tabindex="-1" role="dialog" aria-labelledby="purchaseLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Hapus Peminjaman</h4>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin menghapus peminjaman ini?
                        </div>
                        <div class="modal-footer">
                          <form class="" action="hapus-peminjaman.php" method="post">
                            <input type="hidden" name="id_peminjaman" id="id_peminjaman" value=""/>
                            <a class="btn btn-default" data-dismiss="modal">Batal</a>
                            <button type="submit" name="hapus" class="btn btn-primary">Hapus</button>
                          </form>
                        </div>
                    </div>
                </div>
            </div>

            <section class="box box-primary" style="padding:10px;">
              <!-- title row -->
              <div class="row">
                <div class="col-xs-12">
                  <h2 class="page-header">
                    List Peminjaman
                    <small class="pull-right">Today: <?php echo $today; ?></small>
                  </h2>
                </div>
                <!-- /.col -->
              </div>

              <div class="row">
                <div class="col-xs-12 table-responsive">
                  <?php

                  if(isset($_GET['status'])&&$_GET['status']=='dikembalikan'){
                    echo '<h4 class="text-center">Semua peminjaman yang telah dikembalikan</h4>';
                  }
                  else if(isset($_GET['status'])&&$_GET['status']=='dipinjam'){
                    echo '<h4 class="text-center">Semua peminjaman yang masih dipinjam</h4>';
                  }
                  else if(isset($_GET['status'])&&$_GET['status']=='terlambat'){
                    echo '<h4 class="text-center">Semua peminjaman yang terlambat</h4>';
                  }
                  else{
                      echo '<h4 class="text-center">Semua Peminjaman</h4>';
                  }

                  ?>

                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>NIM</th>
                      <th>No. Buku</th>
                      <th>Tanggal Peminjaman</th>
                      <th>Tanggal Pengembalian</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>


                      <?php
                        // tampilin data sesuai pagination
                        $start = 0;
                        $limit = 15;
                        $page = 1;
                        $max_number = 10;

                        $no_buku = $_GET['no'];
                        $nim = $_GET['nim'];
                        $status = $_GET['status'];

                        $query = "SELECT * FROM peminjaman_buku WHERE 1";

                        if(isset($no_buku)&&$no_buku!=''){
                          $query .= " AND no_buku = '$no_buku'";
                        }
                        if(isset($nim)&&$nim!=''){
                          $query .= " AND nim = '$nim'";
                        }
                        if(isset($status)&&($status=='dikembalikan' || $status=='dipinjam' || $status=='terlambat')){
                          if($status=='dikembalikan'){
                            $query .= " AND selesai = 1";
                          }
                          else if($status=='dipinjam'){
                            $query .= " AND selesai = 0";
                          }
                          else if($status=='terlambat'){
                            $query .= " AND selesai = 0 AND tanggal_pengembalian < '$today_sql'";
                          }
                        }

                        $result = $conn->query($query);

                        $rows = $result->num_rows;
                        $total = ceil($rows/$limit);

                        if(isset($_GET['page']) && $_GET['page']>0){
                          $page = $_GET['page'];
                          if($page > $total){
                            $page = $total;
                          }
                          $start = ($page-1) * $limit;
                        }

                        $query .= " LIMIT $start, $limit";

                        $result = $conn->query($query);

                        while($row = $result->fetch_assoc()){
                          /*
                          $date = $row['tanggal_peminjaman'];
                          $date = str_replace('/', '-', $date);
                          $tanggal_peminjaman = date("d/m/Y", strtotime($date));
                          $date = $row['tanggal_pengembalian'];
                          $date = str_replace('/', '-', $date);
                          $tanggal_pengembalian = date("d/m/Y", strtotime($date));
                          */
                          $tanggal_peminjaman = date("d/m/Y", strtotime($row['tanggal_peminjaman']));
                          $tanggal_pengembalian = date("d/m/Y", strtotime($row['tanggal_pengembalian']));
                          $tgl_kembali_sql = $row['tanggal_pengembalian'];

                          if($row['selesai']==0){
                            if($today_sql > $tgl_kembali_sql){
                              $status_info = '<span class="label label-danger">Terlambat</span>';
                            }
                            else{
                              $status_info = '<span class="label label-warning">Dipinjam</span>';
                            }
                          }
                          else{
                            $status_info = '<span class="label label-success">Dikembalikan</span>';
                          }

                          echo '
                          <tr>
                            <td>'.$row['nim'].'</td>
                            <td>'.$row['no_buku'].'</td>
                            <td>'.$tanggal_peminjaman.'</td>
                            <td>'.$tanggal_pengembalian.'</td>
                            <td>'.$status_info.'</td>
                            <td>
                              <a href="edit-peminjaman.php?id='.$row['id'].'" class="btn btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                              <button type="button" title="hapus" class="btn btn-primary open-hapusDialog" data-id="'.$row['id'].'" data-toggle="modal"><i class="fa fa-trash"></i></button>
                            </td>
                          </tr>
                          ';
                        }
                      ?>


                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
                <center>
                  <ul class="pagination">
                    <?php
                      $link = "";
                      if(isset($nim)&&$nim!=''){
                        $link .= "&nim=$nim";
                      }
                      if(isset($no_buku)&&$no_buku!=''){
                        $link .= "&no=$no_buku";
                      }
                      if(isset($status)&&$status!=''){
                        $link .= "&status=$status";
                      }

                      if($page>1){
                        echo '<li class="paginate_button previous"><a href="?page='.($page-1).$link.'">Previous</a></li>';
                      }
                      else{
                        echo '<li class="paginate_button previous disabled"><a href="#!">Previous</a></li>';
                      }

                      $i = 1;
                      $setengah_max = $max_number/2;
                      if($page > $setengah_max && $total >= ($page+$setengah_max)){
                        $i = ceil(1+$page-$setengah_max);
                      }
                      else if($page > $setengah_max && $total > $max_number){
                        $i = (($total - $max_number) + 1);
                      }

                      for($i,$j=1; $i <= $total && $j <= $max_number; $i++,$j++){
                        if($i == $page){
                          echo '<li class="paginate_button active"><a href="#!">'.$i.'</a></li>';
                        }
                        else{
                          echo '<li class="paginate_button"><a href="?page='.$i.$link.'">'.$i.'</a></li>';
                        }
                      }

                      if($page!=$total){
                        echo '<li class="paginate_button next"><a href="?page='.($page+1).$link.'">Next</a></li>';
                      }
                      else{
                        echo '<li class="paginate_button next disabled"><a href="#!">Next</a></li>';
                      }
                    ?>
                  </ul>
                </center>
              </div>

            </section>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

      </section>
      <!-- /.content -->
      <div class="clearfix"></div>
    </div>
  </div>
  <!-- ./wrapper -->

  <?php include "admin-resources-js.php" ?>
  <script>
  $(document).on("click", ".open-hapusDialog", function () {
     var id_peminjaman = $(this).data('id');
     $("#id_peminjaman").val( id_peminjaman );
     //alert(id_peminjaman);
     // As pointed out in comments,
     // it is superfluous to have to manually call the modal.
     $('#hapusDialog').modal('show');
  });
  </script>
</body>
</html>
