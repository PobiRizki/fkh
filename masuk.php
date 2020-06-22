<?php
  session_start();

  if(isset($_SESSION['error_login'])){
    $error_login = $_SESSION['error_login'];
    unset($_SESSION['error_login']);
  }

  if(isset($_SESSION['nip'])){
    header('Location: index.php');
  }

?>

<!DOCTYPE html>
<html>
<head>
  <?php include "admin-header-meta.php" ?>
  <style type="text/css">
        body {
            background-image:
              url("https://subtlepatterns.com/patterns/eight_horns.png");
        }
  </style>
</head>

<body>
<div class="login-box">
  <div class="login-logo">
    <h1 href="#"><i class="fa fa-user text-blue"></i> <b>Login</b> Admin</h1>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg" ></p>

    <form action="proses.php" method="post">
      <div class="row">
        <div class="col-sm-12">
          <p style="color:red"><?php if(isset($error_login)) echo $error_login; ?></p>
        </div>
      </div>
      <div class="form-group has-feedback">
        <input class="form-control" placeholder="NIP" name="nip" type="text" size="18" alt="nip" required >
        <span class="glyphicon glyphicon-barcode form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password" alt="password"  required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4 col-xs-offset-8">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="masuk" alt="masuk" value="MASUK">Masuk</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <br>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<?php include "admin-resources-js.php" ?>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
