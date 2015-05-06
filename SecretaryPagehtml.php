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
		die(print_r( sqlsrv_errors(), true));
	}

	//Verify if the user is a secretary
	if($_SESSION[job] <> 'secretary'){
		if($_SESSION[job] == 'attorney'){
			header("Location:attorneyPagehtml.php?p=error");
		}
		elseif ($_SESSION[job] == "admin") {
			header("Location:adminPagehtml.php?p=error");
		}
		else{
			die("You are not allow in this page. :P");
		}
	}

	/*
		If the secreaty chooses the option to view a document
		$_POST['view'] contains the value of the document's number
		list($View) = keeps the value of the  document's number in the variable $View
		$_SESSION['docID'] keeps the value of the document's number to pass to the other page
		header = redirects to the page secretaryViewCasehtml.php
	*/
	if(!empty($_POST['view']) and is_array($_POST['view'])) {
		//list($View) = $_POST['view'];
		//$_SESSION['docID'] = $View;
		//header("Location:secretaryViewCasehtml.php");


		list($View) = $_POST['view'];
		list($number, $doc) = explode(',', $View);
		$_SESSION['docID'] = $number;
		$tipo = $doc;

		if($tipo == 'Contract'){
			//die('No existe esta pagina :P');
			header("Location:secretaryViewContract.php");
		}
		else{ 
			header("Location:secretaryViewCasehtml.php");	
		}
	}

	//Includes the libraries to change the language of the page (english/spanish) and the navigational bar
	include_once 'common.php';
	include 'library.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- This is the name of the page and CSS that have the style code of the page -->
		<title> Perfil de la Secretaria </title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<meta charset="utf-8">
		<!-- This is to only permit the characters that we allow to input to the system -->
		<script type="text/JavaScript">
			function valid(f) {
				!(/^[A-z;0-9; ;#;-]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z0-9; ;#;-]/ig,''):null;
			}
		</script>
	</head>

	<body>
		<!-- This function displays the information in the navigation bar. It includes the system's header, the search button, the
		language selection dropdown and logout buttons. -->
		<?php navbarAdmin($lang['searchButton'],$lang['language'],$lang['logout']); ?>

		<div class="container">
			<!-- This is the name in the header of the page -->
			<h1> <?php print "$lang[s_profile]"; ?> </h1>

			<p></p>
			<!-- This text box will show the name of the lawyer -->
			<h3>
				<?php echo $_SESSION[name]." ".$_SESSION[initial]." ".$_SESSION[last]." ".$_SESSION[maiden]; ?>
			</h3>

			<!-- This button is for adding a new case and will send the user to that page-->
			<a class="btn btn-primary" href="anadirCasohtml.php"><?php echo $lang['add_case']; ?> </a>
			<!-- This button is for adding a new contract and will send the user to that page-->
			<a class="btn btn-primary col-md-offset-1" href="contratohtml.php"><?php echo $lang['addContract']; ?> </a>

			<br></br>
			<form method="post">
				<div class="row">
					<div class="col-md-3" id="selectDoc">
						<select class="form-control" name="selectDoc">
							<option value="my"><?php echo $lang['l_docs']; ?></option>
							<option value="contract"><?php echo $lang['contractHeader']; ?></option>
						</select>		
					</div>		
					<button type="submit" class="btn btn-default" value="2"><?php echo $lang['showDocuments']; ?></button>
				</div>
			</form>

			<br></br>
			<div style='width:1123px;'>
				<!-- A table is created with the header values of the case number, document type, deadline date and status of the document-->
				<table>
					<thead>
						<tr>
							<th class="col-md-2"><?php echo $lang['case_num']; ?></th>
							<th class="col-md-3"><?php echo $lang['docType']; ?>  </th>
							<th class="col-md-3"><?php echo $lang['dDate']; ?></th>
							<th class="col-md-2"><?php echo $lang['status']; ?></th>
							<th class="col-md-3"></th>
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
									$datetime2 = new DateTime("+5 days");
									$selectDoc = $_POST['selectDoc'];
									/* SQL
										$sql = query to fetch the information of the documents that aren't processed and were added by the employee who is logged in the system
										$stmt = sqlsrv_query() = prepares and executes the query
										$row = sqlsrv_fetch_array() = returns the row as an array
									*/
									if($selectDoc == 'contract'){
										$sql = "SELECT ContractNumber AS DocNumber, Categoria AS DocType, CONVERT(VARCHAR(11),EffectiveUntil,106) AS fecha, Estado AS DocStatus FROM Contracts, Categoria WHERE ServiceCategory = Codigo ORDER BY fecha ASC";			
							
									}
									else{
										$sql = "SELECT DocNumber, DocType, CONVERT(VARCHAR(11),Deadline,106) AS fecha, DocStatus FROM Documents
													WHERE DocStatus <> 'Procesado'
													AND DocNumber IN (SELECT DocId FROM AddDoc WHERE EmployeeName = '$_SESSION[username]') ORDER BY Deadline ASC";
									}				
									$stmt = sqlsrv_query($conn, $sql);
									if ($stmt === false) {
										die(print_r( sqlsrv_errors(), true));
									}
									//The while is to go throughout the table
									while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
										$datetime3 = new DateTime($row['fecha']);
										$interval2 = $datetime2->diff($datetime3);
										if(is_null($row['fecha']) || $interval2->format('%R%a days') > 0 || $row['DocStatus'] == 'Procesado') {
											echo "<tr>";
										} else{
											echo "<tr class='invalid'>";
										}
										echo "<td class='col-md-2'>" . $row['DocNumber'] . "</td>";
										echo "<td class='col-md-3'>" . $row['DocType'] . "</td>";
										if(is_null($row['fecha'])){
											echo "<td class='col-md-3'> None </td>";
										} 
										else { 
											echo "<td class='col-md-3'>" . $row['fecha'] . "</td>";
										}
										echo "<td class='col-md-2'>" . $row['DocStatus'] . "</td>";
										if($selectDoc == 'contract'){
											echo "<td class='col-md-3'><p><p><button class='btn btn-primary' type='submit' name='view[]' value='".$row['DocNumber'].','.'Contract'."'>$lang[viewCase]</button></p></p>";
										}
										else{
											echo "<td class='col-md-3'><p><p><button class='btn btn-primary' type='submit' name='view[]' value='".$row['DocNumber'].','.'Case'."'>$lang[viewCase]</button></p></p>";
										}
										//echo "<td class='col-md-3'><p><p><button class='btn btn-primary' type='submit' name='view[]' value='".$row['DocNumber']."'> $lang[viewCase]</button></p></p>";
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
