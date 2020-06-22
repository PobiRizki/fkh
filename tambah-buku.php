<?php
  include_once("../db_connect.php");
  include_once("session.php");

  //hanya pustakawan yang boleh akses
  if($userOnSession['status']!='pustakawan'){
    header('Location: pustaka.php');
  }

  if(isset($_POST['submit'])){
    $no_buku = $_POST['no_buku'];
    $sql = "SELECT * FROM buku WHERE no_buku = '$no_buku'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {

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
        $foto = "";

        if(isset($filename) && $filename != ""){
          $foto = $filename;
        }

        $sql = "INSERT INTO buku (no_buku, judul, pengarang, edisi, jilid, isbn_issn, bahasa, penerbit, tahun_terbit, tempat_terbit, keterangan, foto)
                VALUES ('$no_buku', '$judul', '$pengarang', '$edisi', '$jilid', '$isbn_issn', '$bahasa', '$penerbit', '$tahun_terbit', '$tempat_terbit', '$keterangan', '$foto')";

        if ($conn->query($sql) === TRUE) {
            header('Location: list-buku.php');
        } else {
            $errors = "Gagal tambah buku. <br>";
        }
      }

    } else {
        $errors = "No. Buku yang sama telah digunakan pada buku yang lain. <br>";
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
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <i class="fa fa-plus"></i> Tambah Buku
            </h2>
          </div>
          <!-- /.col -->
        </div>
        <!-- info row -->
        <?php

        if(isset($errors) && $errors != ""){
          echo '
          <div class="row">
            <h3 class="text-center">Error</h3>
            <p class="text-center">
              '.$errors.'
            </p>
          </div>';
        }

        ?>


        <div class="row">
          <form class="form-horizontal" action="tambah-buku.php" method="post" enctype="multipart/form-data">
            <div class="box-body">
              <div class="form-group">
                <label for="no_buku" class="col-sm-2 control-label">No. Buku</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="no_buku" name="no_buku" placeholder="Nomor Buku" <?php if(isset($_POST['no_buku']) && $_POST['no_buku']!='') echo 'value="'.$_POST['no_buku'].'"'; ?> required>
                </div>
              </div>

              <div class="form-group">
                <label for="judul" class="col-sm-2 control-label">Judul Buku</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul Buku" <?php if(isset($_POST['judul']) && $_POST['judul']!='') echo 'value="'.$_POST['judul'].'"'; ?> required>
                </div>
              </div>

              <div class="form-group">
                <label for="pengarang" class="col-sm-2 control-label">Pengarang</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="pengarang" name="pengarang" placeholder="Pengarang Buku" <?php if(isset($_POST['pengarang']) && $_POST['pengarang']!='') echo 'value="'.$_POST['pengarang'].'"'; ?>>
                </div>
              </div>

              <div class="form-group">
                <label for="edisi" class="col-sm-2 control-label">Edisi</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="edisi" name="edisi" placeholder="Edisi Buku" <?php if(isset($_POST['edisi']) && $_POST['edisi']!='') echo 'value="'.$_POST['edisi'].'"'; ?>>
                </div>
              </div>

              <div class="form-group">
                <label for="jilid" class="col-sm-2 control-label">Jilid</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="jilid" name="jilid" placeholder="Jilid Buku" <?php if(isset($_POST['jilid']) && $_POST['jilid']!='') echo 'value="'.$_POST['jilid'].'"'; ?>>
                </div>
              </div>

              <div class="form-group">
                <label for="isbn_issn" class="col-sm-2 control-label">ISBN/ISSN</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="isbn_issn" name="isbn_issn" placeholder="ISBN/ISSN Buku" <?php if(isset($_POST['isbn_issn']) && $_POST['isbn_issn']!='') echo 'value="'.$_POST['isbn_issn'].'"'; ?>>
                </div>
              </div>

              <div class="form-group">
                <label for="bahasa" class="col-sm-2 control-label">Bahasa</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="bahasa" name="bahasa" placeholder="Bahasa Buku" <?php if(isset($_POST['bahasa']) && $_POST['bahasa']!='') echo 'value="'.$_POST['bahasa'].'"'; ?>>
                </div>
              </div>

              <div class="form-group">
                <label for="penerbit" class="col-sm-2 control-label">Penerbit</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Penerbit Buku" <?php if(isset($_POST['penerbit']) && $_POST['penerbit']!='') echo 'value="'.$_POST['penerbit'].'"'; ?>>
                </div>
              </div>

              <div class="form-group">
                <label for="tahun_terbit" class="col-sm-2 control-label">Tahun Terbit</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit" placeholder="Tahun Terbit Buku" <?php if(isset($_POST['tahun_terbit']) && $_POST['tahun_terbit']!='') echo 'value="'.$_POST['tahun_terbit'].'"'; ?>>
                </div>
              </div>

              <div class="form-group">
                <label for="tempat_terbit" class="col-sm-2 control-label">Tempat Terbit</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tempat_terbit" name="tempat_terbit" placeholder="Tempat Terbit Buku" <?php if(isset($_POST['tempat_terbit']) && $_POST['tempat_terbit']!='') echo 'value="'.$_POST['tempat_terbit'].'"'; ?>>
                </div>
              </div>

              <div class="form-group">
                <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan Buku" rows="5"><?php if(isset($_POST['keterangan']) && $_POST['keterangan']!='') echo $_POST['keterangan'];?></textarea>
                </div>
              </div>

              <div class="form-group">
                <label for="foto" class="col-sm-2 control-label">Foto</label>
                <div class="col-sm-10">
                  <label class="btn btn-primary" for="my-file-selector">
                      <input id="my-file-selector" name="foto" type="file" accept="image/*" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                      Browse
                  </label>
                  <span class="label label-info" id="upload-file-info"></span>
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
