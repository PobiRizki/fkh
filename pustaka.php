<?php
  include_once("../db_connect.php");
  include_once("session.php");

  // TODO: hanya pustakawan yang boleh akses
  if($userOnSession['status']!='pustakawan'){
    header('Location: list-buku.php');
  }

  date_default_timezone_set("Asia/Jakarta");
  $today = date("d/m/Y");
  $today_sql = date("Y-m-d");

  $query_info = "SELECT no_buku FROM buku";
  $hasil = $conn->query($query_info);
  $jml_buku = $hasil->num_rows;

  $query_info = "SELECT * FROM peminjaman_buku WHERE selesai = 0";
  $hasil = $conn->query($query_info);
  $jml_dipinjam = $hasil->num_rows;

  $query_info = "SELECT * FROM peminjaman_buku WHERE selesai = 0 AND tanggal_pengembalian < '$today_sql'";
  $hasil = $conn->query($query_info);
  $jml_terlambat = $hasil->num_rows;


  if(isset($_SESSION['error_peminjaman'])){
    $nim_old = $_SESSION['error_peminjaman']['nim_old'];
    $buku_old = $_SESSION['error_peminjaman']['buku_old'];

    if(isset($_SESSION['error_peminjaman']['nim'])){
      $error_peminjaman['nim'] = $nim_old;
    }
    if(isset($_SESSION['error_peminjaman']['buku'])){
      $error_peminjaman['buku'] = $buku_old;
    }

    unset($_SESSION['error_peminjaman']);
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
          <div class="col-lg-4 col-xs-6">
            <div class="small-box bg-blue">
              <div class="inner text-center">
                <p>Buku</p>
                <h3><?php echo $jml_buku; ?></h3>
                <small>Jumlah Buku</small>
              </div>
              <a href="list-buku.php" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-4 col-xs-6">
            <div class="small-box bg-green">
              <div class="inner text-center">
                <p>Peminjaman</p>
                <h3><?php echo $jml_dipinjam; ?></h3>
                <small>Sedang Dipinjam</small>
              </div>
              <a href="list-peminjaman.php?status=dipinjam" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-4 col-xs-6">
            <div class="small-box bg-red">
              <div class="inner text-center">
                <p>Peminjaman</p>
                <h3><?php echo $jml_terlambat; ?></h3>
                <small>Terlambat</small>
              </div>
              <a href="list-peminjaman.php?status=terlambat" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <div class="row">

          <div class="col-md-8">
            <div class="col-md-12">
              <div class="box box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Tambah Peminjaman</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">

                    <?php

                    if(isset($error_peminjaman)){
                      echo '
                      <div class="callout callout-danger">
                        <ul style="margin-bottom:0">';

                        if(isset($error_peminjaman['nim'])){
                          echo '<li>NIM <strong>'.$error_peminjaman['nim'].'</strong> belum terdaftar dalam sistem FKH</li>';
                        }
                        if(isset($error_peminjaman['buku'])){
                          echo '<li>No. Buku <strong>'.$error_peminjaman['buku'].'</strong> tidak ditemukan</li>';
                        }

                      echo '
                        </ul>
                      </div>';
                    }

                    ?>


                    <form role="form" action="tambah-peminjaman.php" method="post">
                      <!-- text input -->
                      <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim" class="form-control" placeholder="NIM ..." required value="<?php if(isset($nim_old)){ echo $nim_old; } ?>">
                      </div>
                      <div class="form-group">
                        <label>No. Buku</label>
                        <input type="text" name="no_buku" class="form-control" placeholder="No. Buku ..." required value="<?php if(isset($buku_old)){ echo $buku_old; } ?>">
                      </div>
                      <button type="submit" name="tambah" class="btn btn-primary pull-right">Tambah</button>
                    </form>
                  </div>
                  <!-- /.box-body -->
                </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->

            <div class="col-md-12">
              <div class="box box-success">
                  <div class="box-header with-border">
                    <h3 class="box-title">Pengembalian Buku</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <form role="form" action="pengembalian.php" method="get">
                      <!-- text input -->
                      <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim" class="form-control" placeholder="NIM ..." required>
                      </div>
                      <div class="form-group">
                        <label>No. Buku</label>
                        <input type="text" name="no" class="form-control" placeholder="No. Buku ..." required>
                      </div>
                      <button type="submit" class="btn btn-success pull-right">Cari</button>
                    </form>
                  </div>
                  <!-- /.box-body -->
                </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
          </div>

          <div class="col-md-4">
            <div class="box box-danger">
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <strong>Informasi Ruang Baca</strong><br><br>
                    <div class="text-center">
                      <a href="tambah-buku.php" class="btn btn-lg btn-primary"><span class="fa fa-plus" style="color:white"></span> Tambah Buku</a>
                    </div>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.box-body -->
              <div class="box-footer no-padding">
                <ul class="nav nav-pills nav-stacked">
                  <li><a href="list-buku.php">List Semua Buku
                    <span class="pull-right text-green"><i class="fa fa-angle-right"></i></span></a>
                  </li>
                  <li><a href="list-peminjaman.php">List Semua Peminjaman
                    <span class="pull-right text-green"><i class="fa fa-angle-right"></i></span></a>
                  </li>
                </ul>
              </div>
              <!-- /.footer -->
            </div>
          </div>

        </div>
        <!-- /.row -->

      </section>
      <!-- /.content -->
      <div class="clearfix"></div>
    </div>
  </div>
  <!-- ./wrapper -->


  <?php include "admin-resources-js.php" ?>
</body>
</html>
