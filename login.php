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
</head>
<body>
	<div class="hero">
		<div class="login-con">
			<h3>Login</h3>
			<p class="redirect-signup">Doesn't have an account yet? <a href="#">Sign Up</a></p>
		</div>
		<div class="img-con">

		</div>
	</div>
 	<form class="login-form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">
 		<label for="txtusername">Username: </label><br>
		<input type = "text" name = "txtusername" id = "txtusername"><br>

 		<div>
			<label for="txtusername">Password: </label>
			<a href="#">Forgot Password?</a>
		</div><br>

		<label for="remember">Remember me</label>
		<input type="checkbox" name="remember"><br>

		<input type = "password" name = "txtpassword"><br>
 		<input type = "submit" name = "btnlogin" value = "login">
 	</form>
</body>
</html>