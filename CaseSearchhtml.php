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

	if($_SESSION[job] == 'admin' || $_SESSION[job] == 'attorney' || $_SESSION[job] == 'secretary'){
	}
	else{
		die("You are not allow in this page. :P");
	}

	/*
		If the employee choose the option to see a document
		$_POST['view'] has the value of the document's number
		list($View) = keep the value of the  document's number in the variable $View
		$_SESSION['docID'] keep the value of the document's number to pass to the other page
		header = redirect to the corresponding page given the position of the employee
	*/
	if(!empty($_POST['view']) and is_array($_POST['view'])) {
		list($View) = $_POST['view'];
		$_SESSION['docID'] = $View;
		$job = "$_SESSION[job]";
		if ($job == "secretary") {
			header("Location:secretaryViewCasehtml.php");
		}
		elseif ($job == "attorney") {
			header("Location:attorneyViewCasehtml.php");
		}
		elseif ($job == "admin") {
			header("Location:adminViewCasehtml.php");
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
		<title> Búsqueda de Casos</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<!-- This is to only permit the characters that we allow to input to the system -->
		<script type="text/JavaScript">
			function valid(f) {
				!(/^[A-z;0-9; ;.;-]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z;0-9; ;.;-]/ig,''):null;
			} 
		</script>
		<script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
		<script>
			webshims.setOptions('forms-ext', {types: 'date'});
			webshims.polyfill('forms forms-ext');
		</script>
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
			<h1><?php print "$lang[searchCaseHeader]"; ?>:</h1>

			<br></br>
			<form class="form-horizontal" role="form" method = "post">
				<div class="row">
					<div class="col-md-2">
						<label><?php echo $lang['case_num']; ?>:
							<input type="text" class="form-control" placeholder="<?php echo $lang['case_num']; ?>" onkeyup="valid(this)" onblur="valid(this)" id="caseNumber" name="caseNumber">
						</label>
					</div>

					<div class="col-md-2">
						<label><?php echo $lang['case_apellant']; ?>:
							<input type="text" class="form-control" placeholder="<?php echo $lang['eFirstName']; ?>" onkeyup="valid(this)" onblur="valid(this)" id="nameSearch" name="nameSearch">
						</label>
					</div>

					<div class="col-md-2"> 
						<label "control-label"><?php echo $lang['doc_view']; ?>:
						<!-- This could be read from the database -->
						<!-- This dropdown is to select the type of document -->
						<?php
							/* SQL 
								$sql = query to fetch information from the emplyee in the database
								$stmt = sqlsrv_query() = prepares and executes the query
								$row = sqlsrv_fetch_array() = returns the row as an array
							*/
							$sql2 = "SELECT Tipo FROM Doctype";
							$stmt2 = sqlsrv_query($conn, $sql2);
							if ($stmt2 === false) {
								die(print_r( sqlsrv_errors(), true));
							}
							echo "<select class='form-control' name='documentType' id='documentType'>";
							echo "<option value=''> $lang[docType] </option>";
							while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
								echo "<option value='" . $row2['Tipo'] . "'>" . $row2['Tipo'] . "</option>";
							} 
							echo "</select>";
						?>
						</label>
					</div>

					<!-- This input is to select the document date -->
					<label class="col-md-2"><?php echo $lang['sDate']; ?>:<input type="date" class="form-control" id="documentDate" name="documentDate"></label>
					<label class="col-md-2" ><?php echo $lang['eDate']; ?>:<input type="date" class="form-control" id="secondDate" name="secondDate"></label>

					<br></br>
					<!-- This button is for canceling everithing and returning to the lawyer page -->
					<a class="btn btn-primary pull-right" name="cancel" value=<?php echo $_SESSION[job] ?> href="caseSearch.php"><?php echo $lang['eCancel']; ?></a>
					<!-- This button is for generating the statistics -->
					<button class="btn btn-primary pull-right" style="margin-right: 4px" type="submit" name="search"><?php echo $lang['searchButton']; ?></button>
				</div>
			</form>

			<br></br>
			<div style='width:1123px;'>
				<table>
					<thead>
						<tr>
							<th class="col-md-2"><?php echo $lang['case_num']; ?></th>
							<th class="col-md-2"><?php echo $lang['apellant_name']; ?></th>
							<th class="col-md-2"><?php echo $lang['docType']; ?></th>
							<th class="col-md-2"><?php echo $lang['search_date']; ?> </th>
							<th class="col-md-2"><?php echo $lang['status']; ?></th>
							<th class="col-md-1"></th>
						</tr> 
					</thead>
				</table>
			</div>

			<!-- This is the table to present all the cases searched for -->
			<form method="post">
				<div id="table-scroll">
					<div style='width:1123px;'>
						<table>
							<tbody>
								<?php
									/* Variables
										$caseNumber - case number to be searched
										$nameSearch - name of the person to be searched
										$documentType - type of document selection
										$documentDate - date of document to be searched
									*/
									$caseNumber = $_POST['caseNumber'];
									$nameSearch = $_POST['nameSearch'];
									$documentType = $_POST['documentType'];
									$documentDate = $_POST['documentDate'];
									$secondDate = $_POST['secondDate'];

									//Conditions to search for a document
									//If a case number (not zero) is entered the $sql will fetch from the database the information of the document
									if($caseNumber <> ''){
										$sql = "SELECT DocNumber, Appellant, DocType, CONVERT(VARCHAR(11),CommunicationDate,106) AS Fecha, DocStatus FROM Documents WHERE DocNumber = '$caseNumber'";
									} 
									//If a name (not null) is entered the $sql will fetch from the database the information of the document
									elseif ($nameSearch <> '') {
										$sql = "SELECT DocNumber, Appellant, DocType, CONVERT(VARCHAR(11),CommunicationDate,106) AS Fecha, DocStatus FROM Documents WHERE Appellant LIKE '%$nameSearch%'";
									} 
									//If a document type (not null) is selected the $sql will fetch from the database the information of the document				      		
									elseif ($documentType <> '') {
										$sql = "SELECT DocNumber, Appellant, DocType, CONVERT(VARCHAR(11),CommunicationDate,106) AS Fecha, DocStatus FROM Documents WHERE DocType = '$documentType'";
									}	
									//If a date (not null) is selected the $sql will fetch from the database the information of the document
									elseif ($documentDate <> '') {
										$sql = "SELECT DocNumber, Appellant, DocType, CONVERT(VARCHAR(11),CommunicationDate,106) AS Fecha, DocStatus FROM Documents WHERE EntryDate between '$documentDate' AND '$secondDate'";
									}

									$stmt = sqlsrv_query($conn, $sql);
									if ($stmt === false) {
										die(print_r( sqlsrv_errors(), true));
									}

									while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
										echo "<tr>";
										echo "<td class='col-md-2'>" . $row['DocNumber'] . "</td>";
										echo "<td class='col-md-2'>" . $row['Appellant'] . "</td>";
										echo "<td class='col-md-2'>" . $row['DocType'] . "</td>";
										if(is_null($row['Fecha'])){
											echo "<td class='col-md-2'> None </td>";
										} 
										else { 
											echo "<td class='col-md-2'>" . $row['Fecha'] . "</td>";
										}
										echo "<td class='col-md-2'>" . $row['DocStatus'] . "</td>";
										echo "<td class='col-md-1'><p><p><button class='btn btn-primary' type='submit' name='view[]' value='".$row['DocNumber']."'>View</button></p></p>";
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

		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
