<?php

if($_FILES['foto']['size'] != 0) {

  $errors = "";
  $target_dir = "buku/";
  $target_file = $target_dir . basename($_FILES["foto"]["name"]);
  $uploadOk = 1;
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  $filename = $_POST['no_buku'] . "." . $imageFileType;
  $real_target_file = $target_dir . $filename;
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["foto"]["tmp_name"]);
      if($check !== false) {
          //echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
      } else {
          $errors .= "File is not an image.<br>";
          $uploadOk = 0;
      }
  }
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
      $errors .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
      $uploadOk = 0;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
      $errors .= "Sorry, your file was not uploaded.<br>";
  // if everything is ok, try to upload file
  } else {
      if (move_uploaded_file($_FILES["foto"]["tmp_name"], $real_target_file)) {
          //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
      } else {
          $errors .= "Sorry, there was an error uploading your file.<br>";
      }
  }

}


?>
