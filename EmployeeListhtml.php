<?php
	session_start();
	$serverName = "127.0.0.1";
	$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");

	$conn = sqlsrv_connect($serverName, $connectionInfo);
	if( !$conn ) {
		die( print_r( sqlsrv_errors(), true));
	}

	if($_SESSION[job] <> 'admin'){
		if($_SESSION[job] == 'secretary'){
			header("Location:secretaryPagehtml.php?p=error");
		}
		elseif ($_SESSION[job] == "attorney") {
			header("Location:attorneyPagehtml.php?p=error");
		}
		else{
			die("You are not allow in this page. :P");
		}
	}

	if(!empty($_POST['edit']) and is_array($_POST['edit'])) {
		list($Edit) = $_POST['edit'];
		$_SESSION['username'] = $Edit;
		header("Location:editEmployeehtml.php");
	}

	if(!empty($_POST['borrar']) and is_array($_POST['borrar'])) {
		list($Borrar) = $_POST['borrar'];
		$target = $Borrar;
		$sql = "UPDATE Employee SET Job = 'ex empleado' WHERE Username = '$target'";
		$stmt = sqlsrv_query($conn, $sql);
		header("Location:employeeListhtml.php");
	}

	include_once 'common.php';
	include 'library.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- This is the name of the page -->
		<title> Lista de Empleados </title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
	</head>

	<body>
		<?php 
			/*
				This function displays the information in the navigation bar. It includes the system's header, the
				language selection dropdown and logout buttons.
			*/
			navbarEmployeeList($lang['language'],$lang['logout']);
		?>

		<div class="container">	
			<!-- This is the name in the header of the page -->
			<h1> <?php print "$lang[eListHeader]"; ?> </h1>

			<br></br>
			<form id="addEmployee" action="addEmployeehtml.php">
				<button class="btn btn-primary" type="submit" form="addEmployee"> <?php echo $lang['eAdd']; ?></button>
			</form>

			<p></p>
			<div style='width:1122px;'>
				<table>
					<thead>
						<tr>
							<th class="col-md-2"><?php echo $lang['eName']; ?></th>
							<th class="col-md-4"><?php echo $lang['eLastNames']; ?></th>
							<th class="col-md-2"><?php echo $lang['ePosition']; ?></th>
							<th class="col-md-3"></th>
						</tr>
					</thead>
				</table>
			</div>

			<!-- This is the table to present all the employees on the system  -->
			<form method="post">
				<div id="table-scroll">
					<div style='width:1122px;'>
						<table>
							<tbody>
								<?php
									/*
										$sql - query to fetch the information of the employees in the database.
										$stmt = sqlsrv_query() = prepares and executes the query
										$row = sqlsrv_fetch_array() = returns the row as an array
									*/
									$sql = "SELECT Username, Name, LastName, MaidenName, Job FROM Employee, JobTitle WHERE Job = Title AND Job_Role <> 'admin' AND Job_Role <> 'ExEmployee' AND Office = '$_SESSION[office]'";
									$stmt = sqlsrv_query($conn, $sql);
									while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
										echo "<tr>";
										echo "<td class='col-md-2'>" . $row['Name'] . "</td>";
										echo "<td class='col-md-2'>" . $row['LastName'] . "</td>";
										echo "<td class='col-md-2'>" . $row['MaidenName'] . "</td>";
										echo "<td class='col-md-2'>" . $row['Job'] . "</td>";
										echo "<td class='col-md-3'><p><p><button class='btn btn-primary' type='submit' name='edit[]' value='".$row['Username']."'>$lang[eEdit] </button>
													<button class='btn btn-primary' type='submit' name='borrar[]' value='".$row['Username']."'>$lang[eDelete] </button></p></p></td>";
										echo "</tr>";
									}
									sqlsrv_close($conn);
								?>
							</tbody>
						</table>
					</div>
				</div>
			</form>

			<p></p>
			<!-- This button is for canceling everithing and returns to the administrators page -->
			<a class="btn btn-primary pull-right" href="adminPagehtml.php"><?php echo $lang['eCancel']; ?></a>
		</div>	

		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
