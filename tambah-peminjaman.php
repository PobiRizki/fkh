<?php
  session_start();
  include_once("../db_connect.php");
  include_once("session.php");

  //hanya pustakawan yang boleh akses
  if($userOnSession['status']!='pustakawan'){
    header('Location: pustaka.php');
  }

  date_default_timezone_set("Asia/Jakarta");
  $today_sql = date("Y-m-d");
  $minggu_depan_sql = date("Y-m-d", strtotime("+1 week"));

  if(isset($_POST['tambah'])){
    $nim = $_POST['nim'];
    $no_buku = $_POST['no_buku'];

    //cek ketersediaan NIM
    $query_info = "SELECT * FROM mahasiswa WHERE nim = '$nim'";
    $hasil = $conn->query($query_info);
    if($hasil->num_rows == 0){
      $_SESSION['error_peminjaman']['nim'] = $nim;
    }

    //cek ketersediaan buku
    $query_info = "SELECT * FROM buku WHERE no_buku = '$no_buku'";
    $hasil = $conn->query($query_info);
    if($hasil->num_rows == 0){
      $_SESSION['error_peminjaman']['buku'] = $no_buku;
    }

    if(isset($_SESSION['error_peminjaman'])){
      $_SESSION['error_peminjaman']['nim_old'] = $nim;
      $_SESSION['error_peminjaman']['buku_old'] = $no_buku;
      header('Location: pustaka.php');
    }
    else {
      $query = "INSERT INTO peminjaman_buku (nim, no_buku, tanggal_peminjaman, tanggal_pengembalian, tanggal_terima, selesai)
                VALUES ('$nim', '$no_buku', '$today_sql', '$minggu_depan_sql', NULL, 0)";
      $conn->query($query);
      header('Location: list-peminjaman.php');
    }

  }

?>
