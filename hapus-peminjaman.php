<?php
  include_once("../db_connect.php");
  include_once("session.php");

  // hanya pustakawan yang boleh akses
  if($userOnSession['status']!='pustakawan'){
    header('Location: pustaka.php');
  }

  if(isset($_POST['hapus'])){

    $id = $_POST['id_peminjaman'];

    $sql = "DELETE FROM peminjaman_buku WHERE id=$id";
    $conn->query($sql);
    header('Location: list-peminjaman.php');

  }

?>
