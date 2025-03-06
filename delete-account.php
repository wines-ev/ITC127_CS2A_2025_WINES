<?php
	require_once "config.php";
	include "session-checker.php";

	if (isset($_GET['username'])) {

		$sql = "DELETE FROM tblaccounts WHERE username = ?";
		echo "123";

		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", trim($_GET['username']));

			if (mysqli_stmt_execute($stmt)) {
				$sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUE(?, ?, ?, ?, ?, ?)";

					if ($stmt = mysqli_prepare($link, $sql)) {
						$date = date("d/m/Y");
						$time = date("h:i:sa");
						$action = "delete";
						$module = "account-management";
						mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, $_GET['username'], $_SESSION['username']);


						if (mysqli_stmt_execute($stmt)) {
							echo "User account deleted";
							header("location: account-management.php?account-deleted&deleted-account=" . $_GET['username']);
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
?>