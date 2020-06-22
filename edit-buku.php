<?php
  include_once("../db_connect.php");
  include_once("session.php");

  //hanya pustakawan yang boleh akses
  if($userOnSession['status']!='pustakawan'){
    header('Location: pustaka.php');
  }

  if(isset($_POST['submit'])){
    include("upload-foto-buku.php");

    if(!isset($errors) || $errors==""){
      $no_buku = $_POST['no_buku'];
      $judul = $_POST['judul'];
      $pengarang = $_POST['pengarang'];
      $edisi = $_POST['edisi'];
      $jilid = $_POST['jilid'];
      $isbn_issn = $_POST['isbn_issn'];
      $bahasa = $_POST['bahasa'];
      $penerbit = $_POST['penerbit'];
      $tahun_terbit = $_POST['tahun_terbit'];
      $tempat_terbit = $_POST['tempat_terbit'];
      $keterangan = $_POST['keterangan'];
      if(isset($filename) && $filename != ""){
        $sql = "UPDATE buku SET
        judul='$judul',
        pengarang='$pengarang',
        edisi='$edisi',
        jilid='$jilid',
        isbn_issn='$isbn_issn',
        bahasa='$bahasa',
        penerbit='$penerbit',
        tahun_terbit='$tahun_terbit',
        tempat_terbit='$tempat_terbit',
        keterangan='$keterangan',
        foto='$filename'
        WHERE no_buku='$no_buku'";
      } else {
        $sql = "UPDATE buku SET
        judul='$judul',
        pengarang='$pengarang',
        edisi='$edisi',
        jilid='$jilid',
        isbn_issn='$isbn_issn',
        bahasa='$bahasa',
        penerbit='$penerbit',
        tahun_terbit='$tahun_terbit',
        tempat_terbit='$tempat_terbit',
        keterangan='$keterangan'
        WHERE no_buku='$no_buku'";
      }

      if ($conn->query($sql) === TRUE) {
          header('Location: detail-buku.php?no='.$no_buku);
      } else {
          $errors = "Error updating record: " . $conn->error . "<br>";
      }
    }
  }

  $no_buku = $_GET['no'];
  if(isset($no_buku) && $no_buku != ''){
    $query = "SELECT * FROM buku WHERE no_buku = '$no_buku' LIMIT 1";
    $result = $conn->query($query);
  }
  $jml = 0;
  $jml = $result->num_rows;
  if($jml==1){
    $buku = $result->fetch_assoc();
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
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <i class="fa fa-edit"></i> Edit Buku
            </h2>
          </div>
          <!-- /.col -->
        </div>
        <!-- info row -->

        <?php

          if($jml!=1){
            echo '
            <div class="row">
              <h3 class="text-center">Buku tidak ditemukan</h3>
              <p class="text-center">
                <a href="list-buku.php" class="btn btn-primary">Back</a>
              </p>
            </div>';
          }
          else {
            if(isset($errors) && $errors != ""){
              echo '
              <div class="row">
                <h3 class="text-center">Error</h3>
                <p class="text-center">
                  '.$errors.'
                </p>
              </div>';
            }

            echo '
            <div class="row">
              <form class="form-horizontal" action="edit-buku.php?no='.$_GET['no'].'" method="post" enctype="multipart/form-data">
                <input type="hidden" name="no_buku" value="'.$buku['no_buku'].'" required>
                <div class="box-body">
                  <div class="form-group">
                    <label for="judul" class="col-sm-2 control-label">Judul Buku</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul Buku" value="'.$buku['judul'].'" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="pengarang" class="col-sm-2 control-label">Pengarang</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="pengarang" name="pengarang" placeholder="Pengarang Buku" value="'.$buku['pengarang'].'">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="edisi" class="col-sm-2 control-label">Edisi</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="edisi" name="edisi" placeholder="Edisi Buku" value="'.$buku['edisi'].'">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="jilid" class="col-sm-2 control-label">Jilid</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="jilid" name="jilid" placeholder="Jilid Buku" value="'.$buku['jilid'].'">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="isbn_issn" class="col-sm-2 control-label">ISBN/ISSN</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="isbn_issn" name="isbn_issn" placeholder="ISBN/ISSN Buku" value="'.$buku['isbn_issn'].'">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="bahasa" class="col-sm-2 control-label">Bahasa</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="bahasa" name="bahasa" placeholder="Bahasa Buku" value="'.$buku['bahasa'].'">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="penerbit" class="col-sm-2 control-label">Penerbit</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Penerbit Buku" value="'.$buku['penerbit'].'">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="tahun_terbit" class="col-sm-2 control-label">Tahun Terbit</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit" placeholder="Tahun Terbit Buku" value="'.$buku['tahun_terbit'].'">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="tempat_terbit" class="col-sm-2 control-label">Tempat Terbit</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="tempat_terbit" name="tempat_terbit" placeholder="Tempat Terbit Buku" value="'.$buku['tempat_terbit'].'">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan Buku" rows="5">'.$buku['keterangan'].'</textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="foto" class="col-sm-2 control-label">Foto</label>
                    <div class="col-sm-10">
                      <label class="btn btn-primary" for="my-file-selector">
                          <input id="my-file-selector" name="foto" type="file" accept="image/*" style="display:none;" onchange="$(\'#upload-file-info\').html($(this).val());">
                          Browse
                      </label>
                      <span class="label label-info" id="upload-file-info">'.$buku['foto'].'</span>
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <button type="submit" name="submit" class="btn btn-info pull-right">Simpan</button>
                  <a href="list-buku.php" class="btn btn-default pull-right" style="margin-right: 5px;">Batal</a>
                </div>
                <!-- /.box-footer -->
              </form>
            </div>';
          }

        ?>
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
