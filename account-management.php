<?php	
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Accounts Management Page - AU Technical Support Management System</title>
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
						<pq class="navtab-text text-light fs-4 mb-0">Logout</p>
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

				<hr class="mt-0">
				
				<div class="d-flex justify-content-between mx-5">
					<p class="fs-4 mb-0">Accounts / All accounts</p>
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">
						<div class="d-flex align-items-center">
							<input class="fs-5 ps-2" type="text" name="txtsearch" placeholder="Seach here...">
							<input class="fs-5 px-2" type="submit" name="btnsearch" value="Search">
						</div>
					</form>
				</div>
				
				<div class="accout-table-con mx-5 fs-5">
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

						function buildtable($result) {
							if(mysqli_num_rows($result) > 0) {
								echo "<table id='account-table'>";
								echo "<tr>";
								echo "<th>Username</th><th>Usertype</th><th>Status</th><th>Created By</th>
								<th>Date Created</th><th>Action</th>";
								echo "</tr>";
								echo "<br>";

							while($row = mysqli_fetch_array($result)) {
								echo "<tr id='data-row' >";
								echo "<td>" . $row['username'] . "</td>";
								echo "<td>" . $row['usertype'] . "</td>";
								echo "<td>" . $row['status'] . "</td>";
								echo "<td>" . $row['createdby'] . "</td>";
								echo "<td>" . $row['datecreated'] . "</td>";
								echo "<td>";
								echo "<a href = 'update-account.php?username=" . $row['username'] . "'>Update</a> ";
								echo "<a href = 'delete0-account.php?username=" . $row['username'] . "'>Delete</a>";
								echo "</td>";
								echo "</tr>";
							}
								echo "</table>";
							}
							else {
								echo "No rercord/s found.";
							}
						}
					?>
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
