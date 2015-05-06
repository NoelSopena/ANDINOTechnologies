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

	if($_SESSION[job] <> 'attorney'){
		if($_SESSION[job] == 'secretary'){
			header("Location:secretaryPagehtml.php?p=error");
		}
		elseif ($_SESSION[job] == "admin") {
			header("Location:adminPagehtml.php?p=error");
		}
		else{
			die("You are not allow in this page. :P");
		}
	}

	/*
		If the attorney chooses the option to view a document
		$_POST['view'] contains the value of the document's number
		list($View) = keeps the value of the  document's number in the variable $View
		$_SESSION['docID'] keeps the value of the document's number to pass to the other page
		header = redirects to the page adminViewCasehtml.php
	*/
	if(!empty($_POST['view']) and is_array($_POST['view'])) {
		list($View) = $_POST['view'];
		list($number, $doc) = explode(',', $View);
		$_SESSION['docID'] = $number;
		$tipo = $doc;

		if($tipo == 'Contract'){
			header("Location:attorneyViewContract.php");
		}
		else{ 
			header("Location:attorneyViewCasehtml.php");	
		}	
	}
	//Includes the libraries to change the language of the page (english/spanish) and the navigational bar
	include_once 'common.php';
	include 'library.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- This is the name of the page -->
		<title> Perfil del Abogado </title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<!-- This is to only permit the characters that we allow to input to the system -->
		<script type="text/JavaScript">
			function valid(f) {
				!(/^[A-z&#209;&#241;0-9; ;.;,;-]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z&#209;&#241;0-9; ;.;,;-]/ig,''):null;
			}
		</script>
	</head>

	<body>
		<!-- Static navbar -->
		<?php 
			/*
				This function displays the information in the navigation bar. It includes the system's header, the
				search button, the language selection dropdown and logout buttons.
			*/
			navbarAdmin($lang['searchButton'],$lang['language'],$lang['logout']);
		?>

		<div class="container">
			<!-- This is the name in the header of the page -->
			<h1> <?php print "$lang[l_profile]"; ?>	</h1>

			<p></p>
			<!-- This text box will show the name of the lawyer -->
			<h3><?php echo $_SESSION[name] . " " . $_SESSION[initial] . " " . $_SESSION[last] . " " . $_SESSION[maiden]; ?></h3>

			<p></p>
			<!-- This button is generated statistics and will send the user to that page -->
			<a class="btn btn-primary" href="GenerarEstadisticashtml.php">  <?php echo $lang['genStats']; ?> </a>

			<br></br>
			<form method="post">
				<div class="row">
					<div class="col-md-3" id="selectDoc">
						<select class="form-control" name="selectDoc">
							<option value="my"><?php echo $lang['l_docs']; ?></option>
							<option value="all"><?php echo $lang['allDocs']; ?></option>
							<option value="contract"><?php echo $lang['contractHeader']; ?></option>
						</select>		
					</div>		
					<button type="submit" class="btn btn-default" value="2"><?php echo $lang['showDocuments']; ?></button>
				</div>
			</form>

			<p></p>
			<form method = "post">
				<div style='width:1123px;'>
				<!-- A table is created with the header values of the case number, document type, deadline date and status of the document-->
					<table>
						<thead>
							<tr>
								<th class="col-md-2"><?php echo $lang['case_num']; ?></th>
								<th class="col-md-3"><?php echo $lang['docType']; ?></th>
								<th class="col-md-3"><?php echo $lang['dDate']; ?></th>
								<th class="col-md-2"><?php echo $lang['status']; ?></th>
								<th class="col-md-2"></th>
							</tr>
						</thead>
					</table>
				</div>
				<!-- This is the table to present all the cases that are closer to the deadline -->
				<div id="table-scroll">
					<div style='width:1123px;'>
						<table>
							<tbody>
								<?php
									$datetime2 = new DateTime("+5 days");
									$selectDoc = $_POST['selectDoc'];
									/* SQL
				      			$sql = query to fetch the information of the documents that aren't processed and were added by the employee
													 who is logged in the system
										$stmt = sqlsrv_query() = prepares and executes the query
										$row = sqlsrv_fetch_array() = returns the row as an array
									*/
									if ($selectDoc == 'all') {
										$sql = "SELECT DocNumber, DocType, CONVERT(VARCHAR(11),Deadline,106) AS fecha, DocStatus FROM Documents
														WHERE DocStatus <> 'Concluido' ORDER BY Deadline ASC";
									}
									elseif($selectDoc == 'contract'){
										$sql = "SELECT ContractNumber AS DocNumber, ServiceCategory AS DocType, CONVERT(VARCHAR(11),EffectiveUntil,106) AS fecha, Estado AS DocStatus FROM Contracts ORDER BY fecha ASC";
									}
									else {
										$sql = "SELECT DocNumber, DocType, CONVERT(VARCHAR(11),Deadline,106) AS fecha, DocStatus FROM Documents
														WHERE DocStatus <> 'Concluido'
														AND DocNumber IN (SELECT DocId FROM Manage WHERE EmployeeName = '$_SESSION[username]') ORDER BY Deadline ASC";
									}
									$stmt = sqlsrv_query($conn, $sql);
									if ($stmt === false) {
										die(print_r( sqlsrv_errors(), true));
									}
									//The while is to go throughout the table
									while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
										//$datetime3 = is to format the date from the table
										$datetime3 = new DateTime($row['fecha']);
										//$interval2 = give the diference between the date from the table and the date ahead in the calendar
										$interval2 = $datetime2->diff($datetime3);
										if(is_null($row['fecha']) || $interval2->format('%R%a days') > 0 || $row['DocStatus'] == 'Concluido') {
											echo "<tr>";
										} else{
											echo "<tr class='invalid'>";
										}
										echo "<td class='col-md-2'>" . $row['DocNumber'] . "</td>";
										echo "<td class='col-md-3'> " . $row['DocType'] . "</td>";
										if(is_null($row['fecha'])){
											echo "<td class='col-md-3'> None </td>";
										} else { 
											echo "<td class='col-md-3'>" . $row['fecha'] . "</td>";
										}
										echo "<td class='col-md-2'>" . $row['DocStatus'] . "</td>";
										if($selectDoc == 'contract'){
											echo "<td class='col-md-2'><p><p><button class='btn btn-primary' type='submit' name='view[]' value='".$row['DocNumber'].','.'Contract'."'>$lang[viewCase]</button></p></p>";
										}
										else{
											echo "<td class='col-md-2'><p><p><button class='btn btn-primary' type='submit' name='view[]' value='".$row['DocNumber'].','.'Case'."'>$lang[viewCase]</button></p></p>";
										}
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
		<!-- This function is to make the row blink -->
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
