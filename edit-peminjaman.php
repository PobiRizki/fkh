<?php
  include_once("../db_connect.php");
  include_once("session.php");
  date_default_timezone_set("Asia/Jakarta");

  //hanya pustakawan yang boleh akses
  if($userOnSession['status']!='pustakawan'){
    header('Location: pustaka.php');
  }

  if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $nim = $_POST['nim'];
    $no_buku = $_POST['no_buku'];

    $date = $_POST['tanggal_peminjaman'];
    $date = str_replace('/', '-', $date);
    $tanggal_peminjaman = date('Y-m-d', strtotime($date));

    $date = $_POST['tanggal_pengembalian'];
    $date = str_replace('/', '-', $date);
    $tanggal_pengembalian = date('Y-m-d', strtotime($date));

    $selesai = 0;

    if (isset($_POST['selesai'])) {
      $selesai = 1;
    }

    $sql = "UPDATE peminjaman_buku SET
    nim='$nim',
    no_buku='$no_buku',
    tanggal_peminjaman='$tanggal_peminjaman',
    tanggal_pengembalian='$tanggal_pengembalian',
    selesai='$selesai'
    WHERE id=$id";

    $conn->query($sql);
    header('Location: list-peminjaman.php');
  }


  $today = date("d/m/Y");
  $today_sql = date("Y-m-d");

  $id = $_GET['id'];
  if(isset($id)&&$id!=''){
    $query = "SELECT * FROM peminjaman_buku WHERE id = $id";
    $hasil = $conn->query($query);
    $peminjaman = $hasil->fetch_assoc();
  }

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
          <div class="col-md-12">

            <section class="box box-primary" style="padding:10px;">
              <!-- title row -->
              <div class="row">
                <div class="col-xs-12">
                  <h2 class="page-header">
                    Edit Peminjaman
                    <small class="pull-right">Today: <?php echo $today; ?></small>
                  </h2>
                </div>
                <!-- /.col -->
              </div>

              <?php

              if(isset($peminjaman)&&!empty($peminjaman)){
                $tanggal_peminjaman = date("d/m/Y", strtotime($peminjaman['tanggal_peminjaman']));
                $tanggal_pengembalian = date("d/m/Y", strtotime($peminjaman['tanggal_pengembalian']));
                $status_pinjam ='';
                if($peminjaman['selesai'] == 1){
                  $status_pinjam = 'checked';
                }

                echo '
                <div class="row">
                  <div class="col-md-12">
                    <form class="form-horizontal" action="edit-peminjaman.php" method="post">
                      <input type="hidden" class="form-control" name="id" value="'.$id.'">
                      <div class="box-body">
                        <div class="form-group">
                          <label for="nim" class="col-sm-2 control-label">NIM</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" placeholder="NIM" value="'.$peminjaman['nim'].'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="no_buku" class="col-sm-2 control-label">No. Buku</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="no_buku" name="no_buku" placeholder="No. Buku" value="'.$peminjaman['no_buku'].'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="tgl_peminjaman" class="col-sm-2 control-label">Tanggal Peminjaman</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="tgl_peminjaman" name="tanggal_peminjaman" placeholder="dd/mm/YYYY" value="'.$tanggal_peminjaman.'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="tgl_pengembalian" class="col-sm-2 control-label">Tanggal Pengembalian</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="tgl_pengembalian" name="tanggal_pengembalian" placeholder="dd/mm/YYYY" value="'.$tanggal_pengembalian.'">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                              <label>
                                <input type="checkbox" name="selesai" value="1" '.$status_pinjam.'> Buku telah dikembalikan
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <button type="submit" name="edit" class="btn btn-info pull-right">Simpan</button>
                        <a href="list-peminjaman.php" class="btn btn-default pull-right" style="margin-right:10px">Batal</a>
                      </div>
                      <!-- /.box-footer -->
                    </form>
                  </div>
                </div>';
              }
              else {
                echo '
                <div class="row">
                  <h3 class="text-center">Peminjaman tidak ditemukan</h3>
                  <p class="text-center">
                    <a href="list-peminjaman.php" class="btn btn-primary">Back</a>
                  </p>
                </div>';
              }

              ?>


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
    //Date picker
    $('#tgl_peminjaman').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    });
    $('#tgl_pengembalian').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    });
  </script>
</body>
</html>
