<?php
  include_once("../db_connect.php");
  include_once("session.php");

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
    <div class="content-wrapper" style="min-height: 100vh">

      <section class="content">

        <div class="row">

          <div class="col-sm-12" style="padding-top: 15px;padding-bottom: 15px;">
            <div class="panel panel-default" >
              <div class="panel-heading" style="background-color: #2C3E50;">
                <span class="fa fa-book" style="color:white"></span><b style="color:white">&nbsp;&nbsp; List Buku</b>
                <button style="visibility: hidden;" class="btn btn-success">.</button>
                <?php
                  //hanya pustakawan yang boleh akses
                  if($userOnSession['status']=='pustakawan'){
                    echo '<a href="tambah-buku.php" class="btn btn-success pull-right"><span class="fa fa-plus" style="color:white"></span> Tambah Buku</a>';
                  }
                ?>
              </div>
              <div class="row">
                <center>
                  <div class="col-md-8 col-md-offset-2" style="margin-top: 20px;">
                    <form class="form-horizontal" action="list-buku.php" method="get">
                      <div class="box-body">
                        <div class="input-group input-group-lg">
                          <input type="text" class="form-control" name="q" placeholder="kata kunci" <?php if(isset($_GET['q']) && $_GET['q']!='') echo 'value="'.$_GET['q'].'"'; ?>>
                            <span class="input-group-btn">
                              <button type="submit" class="btn btn-info pull-right"><span class="fa fa-search" style="color:white"></span> Cari</button>
                            </span>
                        </div>
                      </div>
                    </form>
                  </div>
                </center>
              </div>
              <div class="row" >
                <div class="col-sm-12">
                  <div class="box-body">
                    <table class="table table-hover">
                      <tbody class="products-list product-list-in-box">


                        <?php
                          // tampilin data sesuai pagination
                          $start = 0;
                          $limit = 10;
                          $page = 1;
                          $max_number = 10;

                          $q = $_GET['q'];
                          if(isset($q) && $q!='')
                            $query = "SELECT no_buku, judul, pengarang, foto FROM buku WHERE judul LIKE '%$q%'";
                          else
                            $query = "SELECT no_buku, judul, pengarang, foto FROM buku";

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

                          if(isset($q) && $q!='')
                            $query = "SELECT no_buku, judul, pengarang, foto FROM buku WHERE judul LIKE '%$q%' LIMIT $start, $limit";
                          else
                            $query = "SELECT no_buku, judul, pengarang, foto FROM buku LIMIT $start, $limit";

                          $result = $conn->query($query);

                          while($row = $result->fetch_assoc()){
                            echo '
                            <tr class="list">
                              <td class="item">
                                <div class="product-img">';
                            if($row['foto']!='')
                                  echo '<img src="../buku/'.$row['foto'].'">';
                            else
                                  echo '<img src="../buku/default.png">';
                            echo '
                                </div>
                                <div class="product-info">
                                  <a href="detail-buku.php?no='.$row['no_buku'].'" class="product-title">'.$row['judul'].'</a>
                                      <span class="product-description">
                                        <small>Pengarang : '.$row['pengarang'].'</small>
                                      </span>
                                </div>
                              </td>
                            </tr>
                            ';
                          }
                        ?>


                      </tbody>
                    </table>
                    <center>
                    <ul class="pagination">
                      <?php
                        if($page>1){
                          echo '<li class="paginate_button previous"><a href="?'.(isset($q)&&$q!=''?'q='.$q.'&':'').'page='.($page-1).'">Previous</a></li>';
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
                            echo '<li class="paginate_button"><a href="?'.(isset($q)&&$q!=''?'q='.$q.'&':'').'page='.$i.'">'.$i.'</a></li>';
                          }
                        }

                        if($page!=$total){
                          echo '<li class="paginate_button next"><a href="?'.(isset($q)&&$q!=''?'q='.$q.'&':'').'page='.($page+1).'">Next</a></li>';
                        }
                        else{
                          echo '<li class="paginate_button next disabled"><a href="#!">Next</a></li>';
                        }
                      ?>
                    </ul>
                    </center>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

    </div>
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->

  <?php include "admin-resources-js.php" ?>
</body>
</html>
