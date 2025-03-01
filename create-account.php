<?php
require_once "config.php";
include ("session-checker.php");
if(isset($_POST['btnsubmit']))
{
	$sql = "SELECT * FROM tblaccounts WHERE username = ?";
	if($stmt = mysqli_prepare($link, $sql))
	{
		mysqli_stmt_bind_param($stmt, "s", $_POST['txtusername']);
		if(mysqli_stmt_execute($stmt))
		{
			$result = mysqli_stmt_get_result($stmt);
			if(mysqli_num_rows($result) == 0)
			{
				//insert
				
				$sql = "INSERT INTO tblaccounts values(?, ?, ?, ?, ?, ?);";

				if ($stmt = mysqli_prepare($link, $sql)) {
					$username = $_POST['txtusername'];
					$password = $_POST['txtpassword'];
					$usertype = $_POST['cmbtype'];
					$status = "ACTIVE";
					$createdby = $_SESSION['username'];
					$datecreated = date("m/d/Y");

					mysqli_stmt_bind_param($stmt, "ssssss", $username, $password, $usertype, $status, $createdby, $datecreated);


					if (mysqli_stmt_execute($stmt)) {
						header("location: account-management.php");


					}
					else {
						echo "Error inserting account";
					}
				}
			}
			else
			{
				echo "Username already in use.";
			}
		}
	}
	else
	{
		echo "<font color = 'red'>ERROR on SELECT statement</font>";
	}
}
?>

<html>
<title>Create New Account Page - AU Technical Support Management System</title>
<body>
	<p>Fill up this form and submit to create a new account</p>
	<form acion = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">
	Username: <input type = "text" name = "txtusername" required><br>
	Password:  <input type = "password" name = "txtpassword" required><br>
	Account Type: <select name = "cmbtype" id = "cmbtype" required>
			<option value = "">--Select Account Type--</option>
			<option value = "ADMINISTRATOR">Administrator</option>
			<option value = "TECHNICAL">Technical</option>
			<option value = "STAFF">Staff</option>
		</select><br>
		<input type = "submit" name = "btnsubmit" value = "Submit">
		<a href = "accounts-management.php">Cancel</a>
	</form>
</body>
</html>