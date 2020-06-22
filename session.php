<?php
// Memulai Session
session_start();
if(!isset($_SESSION['nip'])){
	header('Location: masuk.php');
}
else{
	$userCheck = $_SESSION['nip'];
	$ses_sql = $conn->query("SELECT * FROM karyawan WHERE nip='$userCheck'");
	if($ses_sql->num_rows > 0){
		$userOnSession = $ses_sql->fetch_assoc();
	}
	else {
		header('Location: keluar.php');
	}
}
?>
