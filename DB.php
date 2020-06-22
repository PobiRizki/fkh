<?php
	
	$host ='localhost';
	$user ='root';
	$pass ='';
	$database='tutorial';

	$db =new mysqli($host,$user,$pass,$database);

	if($db->connect_errno !=0){
		die('eror : '.$db->connect_error);		
	}	

?>