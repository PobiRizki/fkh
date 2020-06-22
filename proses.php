<?php
                        session_start();
                        require_once 'DB.php';

                        $npm=$_POST['npm'];
                        $pass=md5($_POST['password']);
                        $sql = "SELECT * FROM karyawan WHERE nip='$npm' AND password='$pass' LIMIT 1";
                        $hasil = $db->query($sql);
                        $baris = $hasil->fetch_assoc();
                        if ($hasil->num_rows > 0) {
                              $_SESSION['status'] = $baris['status'];
                              $_SESSION['nip'] = $npm;
                              header("location:dashboard/");        
                            }else{
				                header("location:index.html");
                            }
?>