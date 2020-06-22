<?php
  include_once("../db_connect.php");
  include_once("session.php");

  $no_buku = $_GET['no'];
  if(isset($no_buku) && $no_buku != ''){
    $query = "SELECT * FROM buku WHERE no_buku = '$no_buku' LIMIT 1";
    $result = $conn->query($query);
  }
  $jml = 0;
  $jml = $result->num_rows;
  if($jml==1){
    $buku = $result->fetch_assoc();
    $query = "SELECT * FROM peminjaman_buku WHERE no_buku = '$no_buku' AND selesai = 0";
    $result = $conn->query($query);
    $dipinjam = $result->num_rows;
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
              <i class="fa fa-book"></i> Detail Buku
            </h2>
          </div>
          <!-- /.col -->
        </div>

        <?php
          if($jml!=1){
            echo '
            <div class="row">
              <div class="col-sm-12" style="margin-bottom: 30px;">
                <h3 class="text-center">Buku tidak ditemukan </h3>
              </div>
            </div>';
          }
          else {
            echo '
            <!-- info row -->
            <div class="row">
              <div class="col-sm-12" style="margin-bottom: 30px;">
                <h3>'.$buku['judul'].'</h3>
              </div>
            </div>
            <!-- /.row -->
            ';

            echo '
            <!-- Table row -->
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                  <tbody>
                  <tr>
                    <td>Pengarang</td>
                    <td class="absorbing-column">: '.$buku['pengarang'].'</td>
                  </tr>
                  <tr>
                    <td>Edisi</td>
                    <td class="absorbing-column">: '.$buku['edisi'].'</td>
                  </tr>
                  <tr>
                    <td>Jilid</td>
                    <td class="absorbing-column">: '.$buku['jilid'].'</td>
                  </tr>
                  <tr>
                    <td>ISBN/ISSN</td>
                    <td class="absorbing-column">: '.$buku['isbn_issn'].'</td>
                  </tr>
                  <tr>
                    <td>Bahasa</td>
                    <td class="absorbing-column">: '.$buku['bahasa'].'</td>
                  </tr>
                  <tr>
                    <td>Penerbit</td>
                    <td class="absorbing-column">: '.$buku['penerbit'].'</td>
                  </tr>
                  <tr>
                    <td>Tahun Terbit</td>
                    <td class="absorbing-column">: '.$buku['tahun_terbit'].'</td>
                  </tr>
                  <tr>
                    <td>Tempat Terbit</td>
                    <td class="absorbing-column">: '.$buku['tempat_terbit'].'</td>
                  </tr>
                  <tr>
                    <td>Keterangan</td>
                    <td class="absorbing-column">: '.$buku['keterangan'].'</td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->';

            echo '
            <div class="row">
              <!-- accepted payments column -->
              <div class="col-xs-12 col-md-6 col-md-offset-6">
                <p class="lead">Ketersediaan</p>
                 <p class="text-center">';
                 if($dipinjam == 0){
                   echo '<span class="label label-success" style="font-size: x-large">Tersedia</span>';
                 }
                 else {
                   echo '<span class="label label-danger" style="font-size: x-large">Sedang Dipinjam</span>';
                 }
                 echo '
                 </p>
                No. Buku
                <p class="text-muted well well-sm no-shadow text-center" style="margin-top: 10px; font-size: x-large;">
                  '.$buku['no_buku'].'
                </p>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->';

            if($userOnSession['status']=='pustakawan'){
              echo '
              <div class="row no-print">
                <div class="col-xs-12">
                  <a href="edit-buku.php?no='.$buku['no_buku'].'" class="btn btn-default">
                    <i class="fa fa-edit"></i> Edit
                  </a>
                  <button type="button" title="hapus" style="margin-right: 5px;" class="btn btn-danger open-hapusDialog" data-id="'.$buku['no_buku'].'" data-toggle="modal"><i class="fa fa-trash"></i> Hapus</button>
                </div>
              </div>';
            }
          }
        ?>

        <div class="modal fade" id="hapusDialog" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Hapus Buku</h4>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus buku ini?
                    </div>
                    <div class="modal-footer">
                      <form class="" action="hapus-buku.php" method="post">
                        <input type="hidden" name="no_buku" id="no_buku" value="<?php echo $no_buku ?>"/>
                        <a class="btn btn-default" data-dismiss="modal">Batal</a>
                        <button type="submit" name="submit" class="btn btn-primary">Hapus</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>

      </section>
      <!-- /.content -->
      <div class="clearfix"></div>
    </div>
  </div>
  <!-- ./wrapper -->

  <?php include "admin-resources-js.php" ?>
  <script>
  $(document).on("click", ".open-hapusDialog", function () {
     $('#hapusDialog').modal('show');
  });
  </script>
</body>
</html>
