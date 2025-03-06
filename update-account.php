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
							header("location: account-management.php?account-updated&updated-account=" . $_GET['username']);
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
	<link rel="stylesheet" href="./css/style.css">
	<link rel="stylesheet" href="./plugins/bs/bootstrap.min.css">
	<script src="./plugins/bs/bootstrap.min.js"></script>
	<script src="https://kit.fontawesome.com/acb62c1ffe.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container-fluid mx-0 px-0">
		<div class="accounts-hero d-flex align-items-start">
			<div>
				<div id="sidenav" class="sidenav position-relative d-flex flex-column gap-4 bg-blue z-1 overflow-hidden">
					<div class="d-flex align-items-center justify-content-between w-100 mb-5">
						<div class="d-flex align-items-center" style="height: 6rem;">
							<img class="au_logo" src="./assets/img/au_logo.png" alt="" style="width: 4rem; height: 4rem;">
							<p id="sidenav_title" class="fs-2 ms-2 mb-0 text-light">TSMS</p>
						</div>
						<span id="close-nav-icon" class="text-light fs-2" style="cursor:pointer" onclick="closeNav()">&#9776;</span>
					</div>



					<a class="d-flex align-items-center mb-4" href="#">
						<i class="fa-solid fa-chart-simple fs-1 text-light text-center" style="width: 5rem;"></i>
						<p class="navtab-text text-light fs-4 mb-0">Dashboard</p>
					</a>
					
					<a class="d-flex align-items-center mb-4" href="#">
						<i class="fa-solid fa-users fs-1 text-light text-center" style="width: 5rem;"></i>
						<p class="navtab-text text-light fs-4 mb-0" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
							Accounts
						</p>
					</a>
					
					<div class="collapse" id="collapseExample">
						<div class="ms-3 d-flex flex-column">
							<a class="text-light" href="#">All accounts</a>
							<a class="text-light" href="#">Administrator</a>
							<a class="text-light" href="#">Technical</a>
							<a class="text-light" href="#">Staff</a>
							<a class="text-light" href = "create-account.php">Create new account</a>
						</div>
					</div>
					<a class="position-absolute d-flex align-items-center" style="bottom: 2rem;" href="logout.php">
						<i class="fa-solid fa-door-open fs-1 text-light text-center" style="width: 4rem;"></i>
						<p class="navtab-text text-light fs-4 mb-0">Logout</p>
					</a>
				</div>
			</div>
			
			<div class="accounts-con">
				<div class="account-header d-flex align-items-center" style="height: 6rem;">
					
				
					<span id="open-nav-icon" class="ms-5 fs-2" style="font-size:2rem; cursor:pointer;" onclick="openNav()">&#9776;</span>
					<div class="ms-5">
						<?php
							if(isset($_SESSION['username']))
							{
								echo "<p class='mb-0 fs-1'>Welcome, " . $_SESSION['username'] . "</p>";
								echo "<p class='mb-0 fs-4'>Account Type:  " . $_SESSION['usertype'] . "</p>";
							}
							else
							{
								header("location: login.php");
							}
						?>
					</div>
					
				
				</div>

				<hr class="mt-2">
				
				<div class="d-flex justify-content-between mx-5">
					<p class="fs-4 mb-0">Accounts / Delete account</p>
					
				</div>

				<div class="mx-5">
					<p>Change the value on this form and submit to update the account</p>

					<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST">
						Username: <?php echo $account['username']; ?> <br>
						Password: <input type="password" name="txtpassword" value="<?php echo $account['password']; ?>" required> <br>

						Current usertype: <?php echo $account['usertype']; ?><br>
						Change Usertype to: <select name="cmbtype" id="cmbtype" required>
							<option value="ADMINISTRATOR" <?php if ($account['usertype'] == "ADMINISTRATOR") {echo "selected";} ?>>Administrator</option>
							<option value="TECHNICAL" <?php if ($account['usertype'] == "TECHNICAL") {echo "selected";} ?>>Technical</option>
							<option value="STAFF" <?php if ($account['usertype'] == "STAFF") {echo "selected";} ?>>Staff</option>
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

				</div>

				
			</div>
		</div>
	</div>
</body>
<script>
	const open_nav_icon = document.getElementById("open-nav-icon");
	const close_nav_icon = document.getElementById("close-nav-icon");
	const sidenav_title = document.getElementById("sidenav_title");
	const navtab_texts = document.getElementsByClassName("navtab-text");

	function openNav() {
		document.getElementById("sidenav").style.width = "20rem";
		open_nav_icon.style.display = "none";
		close_nav_icon.style.display = "block";
		sidenav_title.style.display = "block";		

		document.getElementById("sidenav").style.padding = "0 2rem";

		for(let text of navtab_texts) {
			text.style.display = "block";
		}
	}

	function closeNav() {
		if (screen.width > 767) {
			document.getElementById("sidenav").style.width = "7rem";
		}
		else {
			document.getElementById("sidenav").style.width = "0";
			document.getElementById("sidenav").style.padding = "0";
		}
		
		open_nav_icon.style.display = "block";
		close_nav_icon.style.display = "none";
		sidenav_title.style.display = "none";

		for(let text of navtab_texts) {
			text.style.display = "none";
		}
	}

	const rows = document.getElementById("account-table").childNodes[0].childNodes;
	
	for (var i = 0; i < rows.length; i++) {
		if (i%2 == 0) {
			rows[i].classList.add("bg-blue-50");
		}
	}
</script>
</html>