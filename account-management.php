<html>
<title>Accounts Management Page - AU Technical Support Management System</title>
<body>
	<?php
		session_start();
		if(isset($_SESSION['username']))
		{
			echo "<h1>Welcome, " . $_SESSION['username'] . "</h1>";
			echo "<h4>Account Type:  " . $_SESSION['usertype'] . "</h4>";
		}
		else
		{
			header("location: login.php");
		}
	?>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">
		<a href = "create-account.php">Create new account</a>
		<a href = "logout.php">Logout</a><br></br>
		<br>Search: <input type="text" name="txtsearch">
		<input type="submit" name="btnsearch" value="Search">
	</form>
</body>
</html>

<?php
	include("config.php");

	if(!isset($_POST["txtsearch"])) {
		$sql = "SELECT * FROM tblaccounts";

		if($stmt = mysqli_prepare($link, $sql)) {
			if (mysqli_stmt_execute($stmt)) {
				$result = mysqli_stmt_get_result($stmt);
				buildtable($result);
			}
		}
	}
	else {
		$sql = "SELECT * FROM tblaccounts WHERE username LIKE ? OR usertype LIKE ? ORDER BY username";

		if($stmt = mysqli_prepare($link, $sql)) {
			$text_value = "%" . $_POST["txtsearch"] . "%";
			
			
			mysqli_stmt_bind_param($stmt, "ss", $text_value, $text_value);
			if (mysqli_stmt_execute($stmt)) {
				$result = mysqli_stmt_get_result($stmt);
				buildtable($result);
			}
		}
	}
	function buildtable($result)
	{
		if(mysqli_num_rows($result) > 0)
		{
			echo "<table>";
			echo "<tr>";
			echo "<th>Username</th><th>Usertype</th><th>Status</th><th>Created By</th>
			<th>Date Created</th><th>Action</th>";
			echo "</tr>";
			echo "<br>";

		while($row = mysqli_fetch_array($result))
		{
			echo "<tr>";
			echo "<td>" . $row['username'] . "</td>";
			echo "<td>" . $row['usertype'] . "</td>";
			echo "<td>" . $row['status'] . "</td>";
			echo "<td>" . $row['createdby'] . "</td>";
			echo "<td>" . $row['datecreated'] . "</td>";
			echo "<td>";
			echo "<a href = 'update-account.php?username=" . $row['username'] . "'>Update</a>";
			echo "<a href = 'delete0-account.php?username=" . $row['username'] . "'>Delete</a>";
			echo "</td>";
			echo "</tr>";
		}
			echo "</table>";
	}
	else
	{
		echo "No rercord/s found.";
	}
}
require_once "config.php";
?>