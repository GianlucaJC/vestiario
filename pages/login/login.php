<?php
session_start();

include_once '../../database.php';
$database = new Database();
$db = $database->getConnection();
include_once '../../MVC/Models/M_main.php';
include_once '../../MVC/Controllers/C_login.php';

if ($_GET['logout']=="1") {
	session_unset();
	session_destroy();
}
if (isset($_SESSION['user_vest'])) {
	header("location: ../../index.php");
	exit;
}	


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vestizione | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="login.php"><b>Vestizione</b> Liofilchem</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Inserire le credenziali per l'accesso</p>

      <form class='user needs-validation' novalidate id='form_login'>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="UserID" id='user' name='user' required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" id='pass' name='pass' required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-6">
            <button type="submit" id='btn_login' class="btn btn-primary btn-block">Login</button>
          </div>

          <!-- /.col -->
        </div>
      </form>

<div class="social-auth-links text-center mb-3">

<hr>
<div id='resp_login'></div>

<div>

	<?php if (1==2) {?>	
	 <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div>
      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p>
	<?php } ?>
	
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>

<script>
(function($) {
	check_validate_form()
})(jQuery); // End of use strict


function check_validate_form(){
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
			event.preventDefault();
			event.stopPropagation();
        } else {
			event.preventDefault();
			login()	
			
		}
        form.classList.add('was-validated');
      }, false);
    });

}


function login() {
	$("#btn_login").text( "Attendere..." );
	$("#btn_login").prop( "disabled", true );
	
	$("#resp_login").empty()
	operazione="login";
	user=$("#user").val();
	pass=$("#pass").val();
	
	var url = "login.php";
	$.ajax({
		type: "POST",
		url: url,
		data:{operazione:operazione,user:user,pass:pass},
		beforeSend:function(){
		
		},
		success: function (data){
			record = jQuery.parseJSON( data );
			if (record.header.login=="OK") {
				location.href='../../index.php';
			} else {
				$("#btn_login").text( "Login" );
				$("#btn_login").prop( "disabled", false );
				html=""
				html+="<div class='alert alert-warning' role='alert'>";
				  html+=record.header.error;
				html+="</div>";

				$("#resp_login").html(html)
			}
			
		}
	});	

	
}
</script>