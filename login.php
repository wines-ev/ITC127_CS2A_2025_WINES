<?php
if (isset($_POST['btnlogin'])) {
require_once "config.php";

$sql = "SELECT * FROM tblaccounts WHERE username = ? AND password = ? AND status = 'ACTIVE'";

if ($stmt = mysqli_prepare($link, $sql)) {
	mysqli_stmt_bind_param($stmt, "ss", $_POST['txtusername'], $_POST['txtpassword']);

	if(mysqli_stmt_execute($stmt)) {
		$result = mysqli_stmt_get_result($stmt);

		if (mysqli_num_rows($result) > 0) {
			$account = mysqli_fetch_array($result, MYSQLI_ASSOC);

			session_start();

			$_SESSION['username'] = $account['username'];
			$_SESSION['usertype'] = $account['usertype'];
			header("location: account-management.php");
		}
		else {
			echo "<font color = 'red'>Incorrect login detail or account is inactive.</font>";
			}
		}
	}
	else {
		echo "<font color = 'red'> Error on the login statement.</font>";
	}
} 
 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Technical Support Management System</title>
	<link rel="stylesheet" href="./css/style.css">
	<link rel="stylesheet" href="./plugins/bs/bootstrap.min.css">
	<script src="./plugins/bs/bootstrap.min.js"></script>
</head>
<body>
	<div class="hero row justify-content-center">
		<div class="col-3 px-0 login-con">
			<form class="login-form d-flex flex-column px-5 py-4 bg-white rounded-start" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">
				<p class="fs-1 mb-4 text-center">Welcome back</p>
				<label for="txtusername" class="fs-4">Username</label>
				<input type = "text" class="form-control p-2 ps-3 fs-4 mb-4" name = "txtusername" id = "txtusername">

				<div class="d-flex 	justify-content-between fs-4">
					<label for="txtusername">Password</label>
					<a href="#">Forgot Password?</a>
				</div>
				<input type = "password" class="form-control p-2 ps-3 fs-4 mb-3" name = "txtpassword">
				
				<div class="d-flex mb-5">
					<input type="checkbox" class="me-2" name="remember" id="remember" value="false">
					<label for="remember" class="fs-4">Remember me</label>
				</div>

				<input type = "submit" class="btn bg-blue text-light fs-5 py-3 fw-bold" name = "btnlogin" value = "LOGIN">

				<div class="d-flex my-2">
					<hr class="w-100"> 
					<p class="text-nowrap mx-2 pt-1 fs-6">or login with</p> 
					<hr class="w-100">
				</div>	

				<div class="d-flex justify-content-between other-login gap-3">
					<div class="w-100 py-3 d-flex justify-content-center align-items-center btn border border-secondary">
						<img src="./assets/img/google_logo.png" alt="">
						<p class="mb-0 ms-2 fs-4">Google</p>
					</div>

					<div class="w-100 py-3 d-flex justify-content-center align-items-center btn border border-secondary">
						<img src="./assets/img/fb_logo.png" alt="">
						<p class="mb-0 ms-2 fs-4">Facebook</p>
					</div>
				</div>

				<p class="redirect-signup fs-4 text-secondary text-center mt-4">Doesn't have an account yet? <a href="#">Sign Up</a></p>
			</form>
		</div>
		<div class=" col-3 img-con bg-cyan rounded-end">
			<div class="img-wrapper">
				<img src="./assets/img/Programmer.svg" alt="programmer.svg">
			</div>
		</div>
	</div>
 	
</body>
</html>