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

  if(isset($_POST['selesai'])){
    $id_peminjaman = $_POST['id_peminjaman'];
    $sql = "UPDATE peminjaman_buku SET selesai=1, tanggal_terima='$today_sql' WHERE id=$id_peminjaman";
    $conn->query($sql);
    header('Location: list-peminjaman.php');
  }

  $nim = "";
  $no_buku = "";
  if(isset($_GET['nim'])){
    $nim = $_GET['nim'];
  }
  if(isset($_GET['no'])){
    $no_buku = $_GET['no'];
  }

  $status_peminjaman = 0;
  $jml_terlambat = 0;

  if($nim != "" && $no_buku != ""){
    $query = "SELECT * FROM peminjaman_buku WHERE selesai = 0 AND nim = '$nim' AND no_buku = '$no_buku'";
    $hasil = $conn->query($query);
    $status_peminjaman = $hasil->num_rows;
    if($hasil->num_rows){
      $peminjaman = $hasil->fetch_assoc();

      $now = new DateTime();
      $tgl_kembali = new DateTime($peminjaman['tanggal_pengembalian']);
      if($today_sql>$peminjaman['tanggal_pengembalian']){
        $jml_terlambat = $now->diff($tgl_kembali)->d;
      }

      $tgl_peminjaman = DateTime::createFromFormat('Y-m-d', $peminjaman['tanggal_peminjaman'])->format("d/m/Y");
      $tgl_pengembalian = DateTime::createFromFormat('Y-m-d', $peminjaman['tanggal_pengembalian'])->format("d/m/Y");
    }

    if($status_peminjaman == 1){
      $query = "SELECT * FROM buku WHERE no_buku = '".$peminjaman['no_buku']."'";
      $hasil = $conn->query($query);
      if($hasil->num_rows){
        $buku = $hasil->fetch_assoc();
      }

      $query = "SELECT * FROM mahasiswa WHERE nim = '".$peminjaman['nim']."'";
      $hasil = $conn->query($query);
      if($hasil->num_rows){
        $peminjam = $hasil->fetch_assoc();
      }
    }
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
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Pencarian</h3>
                </div>
                <!-- /.box-header -->
                <form role="form" action="pengembalian.php" method="get">
                  <div class="box-body">
                    <!-- text input -->
                    <div class="form-group">
                      <label>NIM</label>
                      <input type="text" name="nim" class="form-control" placeholder="NIM ..." required value="<?php echo $nim ?>">
                    </div>
                    <div class="form-group">
                      <label>No. Buku</label>
                      <input type="text" name="no" class="form-control" placeholder="No. Buku ..." required value="<?php echo $no_buku ?>">
                    </div>

                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right">Cari</button>
                  </div>
                </form>
              </div>
            <!-- /.box -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">

            <section class="box box-primary" style="padding:10px;">
              <!-- title row -->
              <div class="row">
                <div class="col-xs-12">
                  <h2 class="page-header">
                    Pengembalian
                    <small class="pull-right">Today: <?php echo $today; ?></small>
                  </h2>
                </div>
                <!-- /.col -->
              </div>

              <?php

              if($status_peminjaman == 1){
                echo '
                <div class="row">
                  <div class="col-md-12">
                    <!-- Table row -->
                    <div class="row">';

                if($peminjaman['tanggal_pengembalian'] < $today_sql){
                  echo '
                      <div class="col-md-12">
                        <div class="no-print">
                          <div class="callout callout-danger">
                            <h4>Terlambat</h4>
                            <p>
                              Pengembalian buku <strong>'.$buku['judul'].'</strong> dengan No. Buku <strong>'.$buku['no_buku'].'</strong> terlambat selama <strong>'.$jml_terlambat.'</strong> Hari
                            </p>
                          </div>
                        </div>
                      </div>';
                  }

                      echo '
                      <div class="col-xs-12 table-responsive">
                        <table class="table table-striped">
                          <tbody>
                          <tr>
                            <td>Nama Peminjam</td>
                            <td class="absorbing-column">: '.$peminjam['nama'].'</td>
                          </tr>
                          <tr>
                            <td>NIM Peminjam</td>
                            <td class="absorbing-column">: '.$peminjam['nim'].'</td>
                          </tr>
                          <tr>
                            <td>No. Buku</td>
                            <td class="absorbing-column">: '.$buku['no_buku'].'</td>
                          </tr>
                          <tr>
                            <td>Judul Buku</td>
                            <td class="absorbing-column">: '.$buku['judul'].'</td>
                          </tr>
                          <tr>
                            <td>Tanggal Peminjaman</td>
                            <td class="absorbing-column">: '.$tgl_peminjaman.'</td>
                          </tr>
                          <tr>
                            <td>Tanggal Pengembalian</td>
                            <td class="absorbing-column">: '.$tgl_pengembalian.'</td>
                          </tr>
                          <tr>
                            <td>Terlambat</td>
                            <td class="absorbing-column">: '.$jml_terlambat.' hari</td>
                          </tr>
                          </tbody>
                        </table>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row no-print">
                      <div class="col-xs-12">
                        <form action="pengembalian.php" method="post">
                          <input type="hidden" name="id_peminjaman" value="'.$peminjaman['id'].'">
                          <button type="submit" name="selesai" class="btn btn-success pull-right"><i class="fa fa-check"></i> Selesai</button>
                          <a href="pustaka.php" class="btn btn-default pull-right" style="margin-right: 5px;">
                            <i class="fa fa-close"></i> Batal
                          </a>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>';
              }
              else {
                echo '
                <div class="row">
                  <div class="col-md-12">
                    <h1 class="text-center"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></h1><br>
                    <p class="text-center">
                      Transaksi peminjaman buku dengan NIM <strong>'.$nim.'</strong> dan No. Buku <strong>'.$no_buku.'</strong> tidak ditemukan.
                      <br><br>
                      <a href="list-peminjaman.php" class="btn btn-info">List Peminjaman</a>
                    </p>
                  </div>
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
