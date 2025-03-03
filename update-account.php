<?php
	require_once "config.php";
	include "session-checker.php";

	if (isset($_POST["btnsubmit"])) {

		$sql = "UPDATE tblaccounts SET password = ?, usertype = ?, status = ? WHERE username = ?";

		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "ssss", $_POST['txtpassword'], $_POST['cmbtype'], $_POST['rbstatus'], $_GET['username']);

			if (mysqli_stmt_execute($stmt)) {
				$sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUE(?, ?, ?, ?, ?, ?)";

					if ($stmt = mysqli_prepare($link, $sql)) {
						$date = date("d/m/Y");
						$time = date("h:i:sa");
						$action = "update";
						$module = "account-management";
						mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, $_GET['username'], $_SESSION['username']);


						if (mysqli_stmt_execute($stmt)) {
							echo "User account updated";
							header("location: account-management.php");
							exit();
						}
					}
					else {
						echo "<font color='red'>Error on inserting logs.</font>";
					}
			}
		}
		else {
			echo "<font color='red>Error on updating account.</font>";
		}
	}
	else {
		if (isset($_GET["username"]) && !empty($_GET["username"])) {
			$sql = "SELECT * FROM tblaccounts WHERE username = ?";
			if ($stmt = mysqli_prepare($link, $sql)) {
				mysqli_stmt_bind_param($stmt, "s", $_GET["username"]);

				if (mysqli_stmt_execute($stmt)) {
					$result = mysqli_stmt_get_result($stmt);
					$account = mysqli_fetch_array($result);
				}
			}
		}
		else {
			echo "<font color='red'>Error on loading account data.</font>";
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Update Account</title>
</head>
<body>
	<p>Change the value on this form and submit to update the account</p>

	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST">
		Username: <?php echo $account['username']; ?> <br>
		Password: <input type="password" name="txtpassword" value="<?php echo $account['password']; ?>" required> <br>

		Current usertype: <?php echo $account['usertype']; ?><br>
		Change Usertype to: <select name="cmbtype" id="cmbtype" required>
			<option value="">--Select Account Type--</option>
			<option value="ADMINISTRATOR">Administrator</option>
			<option value="TEXHNICAL">Technical</option>
			<option value="STAFF">Staff</option>
		</select><br>

		Status:<br>

		<?php 
			$status = $account['status'];

			if ($status == 'ACTIVE') {
				?><input type="radio" name="rbstatus" value="ACTIVE" checked> Active<br>
				<input type="radio" name="rbstatus" value="INACTIVE">Inactive<br><?php
			}
			else {
				?><input type="radio" name="rbstatus" value="ACTIVE"> Active<br>
				<input type="radio" name="rbstatus" value="INACTIVE" checked>Inactive<br><?php
			}
		?>

		<input type="submit" name="btnsubmit" value="Submit">
		<a href="account-management.php">Cancel</a>
	</form>
</body>
</html>