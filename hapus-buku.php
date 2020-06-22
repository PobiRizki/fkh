<?php
  include_once("../db_connect.php");
  include_once("session.php");

  //hanya pustakawan yang boleh akses
  if($userOnSession['status']!='pustakawan'){
    header('Location: pustaka.php');
  }

  if(isset($_POST['submit'])){

    $no_buku = $_POST['no_buku'];

    $sql = "SELECT * FROM buku WHERE no_buku='$no_buku'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $buku = $result->fetch_assoc();
      $sql = "DELETE FROM buku WHERE no_buku='$no_buku'";

      if ($conn->query($sql) === TRUE) {
        $file = "buku/".$buku['foto'];
        // Check if file already exists
        if (file_exists($file)) {
          unlink($file);
        }
        header('Location: list-buku.php');
      }
      else {
        header('Location: detail-buku.php?no='.$no_buku);
      }
    }
    else {
      header('Location: list-buku.php');
    }

  }

?>
