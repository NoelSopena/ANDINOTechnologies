<?php
	//This is to use the $_SESSION variables. This variables are to pass the values from page to page.
	session_start();

	/* Server
		$serverName = the name of the server to connect
		$connectionInfo = creates an array with the database name, the user id of the database and the user's password of the database
		$conn = sqlsrv_connect() = is the function to connect with the server
	*/
	$serverName = "127.0.0.1";
	$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
	$conn = sqlsrv_connect($serverName, $connectionInfo);                        

	//Verify if the connection with the server is successful
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
	/*
		If the administrator chooses the option to view a document
		$_POST['view'] has the value of the document's number
		list($View) = keep the value of the  document's number in the variable $View
		$_SESSION['docID'] keep the value of the document's number to pass to the other page
		header = redirect to the page adminViewCasehtml.php
	*/
	if(!empty($_POST['view']) and is_array($_POST['view'])) {
		list($View) = $_POST['view'];
		$_SESSION['docID'] = $View;
		header("Location:adminViewCasehtml.php");
	}

	//Includes the libraries to change the language of the page (english/spanish) and the navigational bar
	include_once 'common.php';
	include 'library.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- This is the name of the page -->
		<title> Pagina del Administrador </title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<meta charset="UTF-8">

		<!-- This is to only permit the characters that we allow to input to the system -->
		<script type="text/JavaScript">
			function valid(f) {
				!(/^[A-z;0-9; ;#;-]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z;0-9; ;#;-]/ig,''):null;
			} 
		</script>
	</head>

	<body>
		<?php 
			/*
				This function displays the information in the navigation bar. It includes the system's header, the search
				button, the	language selection dropdown and logout button.
			*/
			navbarAdmin($lang['searchButton'],$lang['language'],$lang['logout']);
		?>

		<div class="container">
			<!-- This is the name in the header of the page -->
			<h1> <?php print "$lang[adminPage]"; ?> </h1>

			<p></p>
			<h3>
				<?php 
					/*
						This outputs to the administrator page the name of the administrator ($_SESSION[name]), the 
						initial ($_SESSION[initial]), the last name ($_SESSION[last]) and the maiden name ($_SESSION[maiden]).
					*/
					echo $_SESSION[name] . " " . $_SESSION[initial] . " " . $_SESSION[last] . " " . $_SESSION[maiden];
				?>
			</h3>

			<!-- This button is to show the list of employee and will send the user to that page -->
			<form id="employees" action="employeeListhtml.php">
				<button class="btn btn-primary" type="submit" form="employees"> <?php echo $lang['eList']; ?> </button>
				<!-- This button is generated statistics and will send the user to that page -->
				<a class="btn btn-primary col-md-offset-1" href="GenerarEstadisticashtml.php"> <?php echo $lang['genStats']; ?> </a>
				<!-- This button is to add or change some aspects of the tables -->
				<a class="btn btn-primary col-md-offset-1" href="settingshtml.php"> Settings </a>
			</form>

			<br></br>
			<form method="post">
				<div class="row">
					<div class="col-md-3" id="selectDoc">
						<select class="form-control" name="selectDoc">
							<option value="near"><?php echo $lang['nearDocuments']; ?></option>
							<option value="all"><?php echo $lang['allDocuments']; ?></option>
						</select>		
					</div>		
					<button type="submit" class="btn btn-default" value="2"><?php echo $lang['showDocuments']; ?></button>
				</div>
			</form>

			<p></p>
			<div style='width:1123px;'>
				<table>
					<thead>
						<tr>	<!-- This will create a table with the case number, document type, deadline date and the status of the document    -->	
							<th class="col-md-2"> <?php echo $lang['case_num']; ?></th>
							<th class="col-md-3"><?php echo $lang['docType']; ?> </th>
							<th class="col-md-3"><?php echo $lang['dDate']; ?></th>
							<th class="col-md-2"><?php echo $lang['status']; ?></th>
							<th class="col-md-2"></th>
						</tr>
					</thead>
				</table>
			</div>

			<!-- This is the table to present all the cases that are closer to the deadline -->
			<form method="post">
				<div id="table-scroll">
					<div style='width:1123px;'>
						<table>
							<tbody>
								<?php
									$d=strtotime("+6 days");
									$limite =  date("Y-M-j", $d);
									$datetime2 = new DateTime("+5 days");
									$selectDoc = $_POST['selectDoc'];

									/*
										$sql - query to fetch the information of a document in the database.
										$stmt = sqlsrv_query() = prepares and executes the query
										$row = sqlsrv_fetch_array() = returns the row as an array
									*/
									if ($selectDoc == 'all') {
										$sql = "SELECT DocNumber, DocType, CONVERT(VARCHAR(11),Deadline,106) AS fecha, DocStatus FROM Documents, Manage
														WHERE DocStatus <> 'Procesado' AND DocNumber = DocId
														AND EmployeeName IN (SELECT Username FROM Employee WHERE Office = '$_SESSION[office]')";
									}
									else {
										$sql = "SELECT DocNumber, DocType, CONVERT(VARCHAR(11),Deadline,106) AS fecha, DocStatus FROM Documents, Manage
														WHERE DocStatus <> 'Procesado' AND Deadline <= '$limite' AND DocNumber = DocId
														AND EmployeeName IN (SELECT Username FROM Employee WHERE Office = '$_SESSION[office]')";
									}
									$stmt = sqlsrv_query($conn, $sql);
									while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
										$datetime3 = new DateTime($row['fecha']);
										$interval2 = $datetime2->diff($datetime3);
										echo "<tr>";
										echo "<td class='col-md-2'>" . $row['DocNumber'] . "</td>";
										echo "<td class='col-md-3'>" . $row['DocType'] . "</td>";
										if(is_null($row['fecha'])){
											echo "<td class='col-md-3'> None </td>";
										} else { 
											echo "<td class='col-md-3'>" . $row['fecha'] . "</td>";
										}
										echo "<td class='col-md-2'>" . $row['DocStatus'] . "</td>";
										echo "<td class='col-md-2'><p><p><button class='btn btn-primary' type='submit' name='view[]' value='".$row['DocNumber']."'>$lang[viewCase] </button></p></p></td>";
										echo "</tr>";
									}
									sqlsrv_close($conn);
								?>
							</tbody>
						</table>
					</div>
				</div>
			</form>	
		</div>

		<?php

			if(isset($_GET['p']) && $_GET['p'] == 'error'){ 
					print "<script>";
					print "alert('You are not allowed in the page you are trying to access.')";     
					print "</script>";
				}
		?>

		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<!-- This function is to make the rows of the table blink when the deadline of the document is near -->
		<script type="text/JavaScript">
			$(function() {
				var on = false;
				window.setInterval(function() {
					on = !on;
					if (on) {
						$('.invalid').addClass('invalid-blink')
					} else {
						$('.invalid-blink').removeClass('invalid-blink')
					}
				}, 800);
			});
		</script>
	</body>
</html>
