<?php
	session_start();

	//Includes the libraries to change the language of the page (english/spanish) and the navigational bar
	include_once 'common.php';
	include 'library.php';

	//If the logged in user is not an attorney the user will not be able to continue
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
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- This is the name of the page -->
		<title> Ver Caso Abogado</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<!-- This is to only permit the characters that we allow to input to the system -->
		<script type="text/JavaScript">
			function valid(f) {
				!(/^[A-z;0-9; ;.;,;#;&;-;ñ]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z;0-9; ;.;,;#;&;-;ñ]/ig,''):null;
			}
		</script>
		<script>
			function showUser(str) {
				if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
					}
				}
				xmlhttp.open("GET","getComment.php?q="+str,true);
				xmlhttp.send();
			}
		</script>
		<script>
			function showUserCopy(str) {
				if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("CopyHint").innerHTML = xmlhttp.responseText;
					}
				}
				xmlhttp.open("GET","getCopy.php?q="+str,true);
				xmlhttp.send();
			}
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
			<h1> <?php print "$lang[case]"; ?> </h1>

			<br></br>
			<form id="form_updateCase" action="updateCase.php" method="post" enctype="multipart/form-data">
				<?php
					/* Server
						$serverName = the name of the server to connect
						$connectionInfo = creates an array with the database name, the user id of the database and the user's password of the database
						$conn = sqlsrv_connect() = is the function to connect with the server
					*/			    
					$serverName = "127.0.0.1";
					$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
					$conn = sqlsrv_connect($serverName, $connectionInfo);

					/*
						$sql - query to fetch the information of a document in the database.
						$stmt = sqlsrv_query() = prepares and executes the query
						$row = sqlsrv_fetch_array() = returns the row as an array
					*/
					$sql = "SELECT DocNumber, CONVERT(VARCHAR(11),EntryDate,21) AS fechaEntrada, CONVERT(VARCHAR(11),CommunicationDate,21) AS fechaComu, 
									CONVERT(VARCHAR(11),Deadline,21) AS fechaLimite, Precedence, DocDescription, Appellant, Sender, DocType, DocStatus FROM Documents 
									WHERE DocNumber = '$_SESSION[docID]'";
					$stmt = sqlsrv_query($conn, $sql);
					if ($stmt === false) {
						die(print_r( sqlsrv_errors(), true));
					}
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$id = $row['DocNumber'];
						$eDate = $row['fechaEntrada'];
						$cDate = $row['fechaComu'];
						$deadline = $row['fechaLimite'];
						$Precedence = $row['Precedence'];
						$description = $row['DocDescription'];
						$appellant = $row['Appellant'];
						$titulo = $row['DocType'];
						$subcategory = $row['DocSubcategory'];
						$sender = $row['Sender'];
						$copia = $row['Copy'];
						$estado = $row['DocStatus'];
					}

					if ($titulo == 'Demanda'){
						$sql1 = "SELECT Causales FROM Lawsuit WHERE LawsuitID = '$_SESSION[docID]'";
						$stmt1 = sqlsrv_query($conn, $sql1);
						if ($stmt1 === false) {
							echo $sql1;
							die(print_r( sqlsrv_errors(), true));
						}
						while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
							$subcategory[] = $row1['Causales'];
						}	
					}
					else{
						$sql1 = "SELECT SubType FROM Others WHERE otherId = '$_SESSION[docID]'";
						$stmt1 = sqlsrv_query($conn, $sql1);
						if ($stmt1 === false) {
							echo $sql1;
							die(print_r( sqlsrv_errors(), true));
						}
						while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
							$subcategory[] = $row1['SubType'];
						}
					}
				?>

				<div class="row">
					<!-- This input is to select the document date -->
					<label class="col-md-2"> <?php echo $lang['date_issued']; ?>: <input type="date" class="form-control" value=<?php echo $cDate ?> disabled></label>
					<!-- This input is to select the date that the document was received -->
					<label class="col-md-2 col-md-offset-1"><?php echo $lang['date_received']; ?>: <input type="date" class="form-control" value=<?php echo $eDate ?> disabled></label>
					<!-- This input is to select the document deadline date -->
					<label class="col-md-2 col-md-offset-1"><?php echo $lang['date_due']; ?>: <input type="date" class="form-control" value=<?php echo $deadline ?> disabled></label>
				</div>

				<p></p>
				<div class="row">
					<!-- This input box is to insert the case number -->
					<div class="col-md-2">
						<label "control-label"><?php echo $lang['case_num']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['case_num']; ?>" onkeyup="valid(this)" onblur="valid(this)" value='<?php echo $id ?>' disabled>
					</div>
					<!-- This input box is to insert the name of the appellant -->
					<div class="col-md-5 col-md-offset-1">
						<label "control-label"><?php echo $lang['case_apellant']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['case_apellant']; ?>" onkeyup="valid(this)" onblur="valid(this)" value='<?php echo $appellant ?>' disabled>
					</div>
				</div>

				<p></p>
				<div class="row">
					<div class="col-md-3">
						<label "control-label"><?php echo $lang['doc_view']; ?>:</label>
						<!-- This dropdown is to select the type of document -->
						<input class="form-control" value='<?php echo $titulo ?>' disabled>	
					
					</div>
					
					<!-- This input box is for inserting the subcategory of the document -->
					<div class="col-md-5">
						<label "control-label"><?php echo $lang['genSubcategory']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['genSubcategory']; ?>" onkeyup="valid(this)" onblur="valid(this)" name="DocSubcategory" id="DocSubcategory" value="<?php foreach ($subcategory as $key) { echo $key . ", "; } ?>" disabled>
					</div>

					<?php
						if ($titulo == 'Demanda') {
							$sql = "SELECT DISTINCT(Amount) FROM Lawsuit WHERE LawsuitID = '$_SESSION[docID]'";
							$stmt = sqlsrv_query($conn, $sql);
							if ($stmt === false) {
								echo $sql;
								die(print_r( sqlsrv_errors(), true));
							}
							$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
							echo "<div class='col-md-2'>";
							echo "<label 'control-label'> $lang[case_amount]:</label><input type='number' step='0.01' class='form-control', placeholder='&#36 ".$row['Amount']."' value='".$row['Amount']."' name='quantity' id='quantity' disabled>";
							echo "</div>";
						}
					?>
				</div>

				

				<p></p>
				<?php
					/*
						$sql - query to fetch the information of the employee managing the document from the database.
						$stmt = sqlsrv_query() = prepares and executes the query
						$row = sqlsrv_fetch_array() = returns the row as an array
					*/
					$sql = "SELECT Name, MiddleName, LastName, MaidenName, Office FROM Employee, Manage WHERE Username = EmployeeName and DocId = '$_SESSION[docID]'";
					$stmt = sqlsrv_query($conn, $sql);
					if ($stmt === false) {
						die(print_r( sqlsrv_errors(), true));
					}
					$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

					/* Variables
						$nombre  - employee name
						$inicial - employee initial 
						$apellido - employee last name
						$segundo - employee maiden name
						$oficina - office			
					*/
					$nombre = $row['Name'];
					$inicial = $row['MiddleName'];
					$apellido = $row['LastName'];
					$segundo = $row['MaidenName'];
					$oficina = $row['Office'];
				?>

				<p></p>
				<div class="row">

					<!-- This input box is to insert name of the addressee -->
					<div class="col-md-3 ">
						<label "control-label"><?php echo $lang['case_receiver']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['case_receiver']; ?>" onkeyup="valid(this)" onblur="valid(this)" value='<?php echo $nombre . " " . $inicial . " " . $apellido . " " . $segundo ?>' disabled>
					</div>
					<!-- This input box is to insert name of the addressee region -->
					<div class="col-md-4 ">
						<label "control-label"><?php echo $lang['case_region']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['case_region']; ?>" onkeyup="valid(this)" onblur="valid(this)" value='<?php echo $oficina ?>' disabled>
					</div>
				</div>
				<p></p>
				<div class="row">
					<!-- This input box is to insert name of the lawyer that sends the document -->
					<div class="col-md-3">
						<label "control-label"><?php echo $lang['case_sender']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['case_sender']; ?>" onkeyup="valid(this)" onblur="valid(this)" value='<?php echo $sender ?>' disabled>
					</div>
					<div class="col-md-6">
						<label "control-label"><?php echo $lang['department']; ?>:</label>
						<!-- This dropdown is to select the name of the senders office -->
						<input class="form-control" value='<?php echo $Precedence ?>' disabled>
					</div>
				</div>

				<p></p>
				<label "control-label"><?php echo $lang['case_subject']; ?>:</label>
				<!-- This input box is to write some issue of the contract -->
				<textarea class="form-control .input-lg" rows="5" placeholder="<?php echo $lang['case_subject']; ?>" disabled><?php echo $description ?></textarea>

				<p></p>
				<!-- This input box is to write some comments of the contract -->
				<div class='row col-md-3'>
					<label "control-label"><?php echo $lang['case_comment']; ?>:</label>
					<?php
						/*
							$sql - query to fetch the comments made to the document from the database.
							$stmt = sqlsrv_query() = prepares and executes the query
							$row = sqlsrv_fetch_array() = returns the row as an array
						*/
						$sql = "SELECT CONVERT(VARCHAR(24),NoteDate,113) AS fecha, CONVERT(VARCHAR(11),NoteDate,106) AS ahcef FROM Comments WHERE DocId = '$_SESSION[docID]'";
						$stmt = sqlsrv_query($conn, $sql);
						if ($stmt === false) {
							die(print_r( sqlsrv_errors(), true));
						}
						echo "<select class='form-control' onchange='showUser(this.value)'>";
						echo "<option value=''>Select Comment</option>";
						while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
							echo "<option value='".$row['fecha']."'>".$row['ahcef']."</opton>";
						}
						echo "</select>";
					?>
				</div>

				<textarea class='form-control' .input-lg row='5' onkeyup='valid(this)' onblur='valid(this)' placeholder=<?php echo $lang['case_comment'] ?> id="txtHint" disabled></textarea>

			<div class="footer">
				<p></p>
				<!-- This input box is to write some comments of the contract -->
				<textarea class="form-control .input-lg" rows="5" onkeyup="valid(this)" onblur="valid(this)" placeholder="<?php echo $lang['case_comment']; ?>" name="caseCommentEdit" id="caseCommentEdit"></textarea>
			</div>
				<p></p>
				<!-- This check box is to know if the case is process or not process -->
				<label for="name"><?php echo $lang['status']; ?>:</label>
				<div> 
					<label class="checkbox-inline"> 
						<input type="radio" name="Estado" id="Concluido" value="Concluido" <?php if($estado =='Concluido') { echo 'checked'; } ?> > <?php echo $lang['status_processed']; ?>
					</label> 
					<label class="checkbox-inline">
						<input type="radio" name="Estado" id="Activo" value="Activo" <?php if($estado =='Activo') { echo 'checked'; } ?> > <?php echo $lang['status_not_processed']; ?> 
					</label> 
				</div>
				<p></p>
				<div class="footer">
					
						<!-- This button is for see the document of the case -->
						<?php
							$sql = "SELECT CONVERT(VARCHAR(24),CopyDate,113) AS fecha, CONVERT(VARCHAR(11),CopyDate,106) AS ahcef, Copies FROM Copy WHERE DocId = '$_SESSION[docID]'";
							$stmt = sqlsrv_query($conn, $sql);
							if ($stmt === false) {
								die(print_r( sqlsrv_errors(), true));
							}
							echo "<div class='row col-md-3'>";
							echo "<select class='form-control' onchange='showUserCopy(this.value)'>";
							echo "<option value=''>$lang[doc_view]</option>";
							while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								echo "<option value='".$row['fecha']."'>".$row['ahcef']."</opton>";

							}
							echo "</select>";
							echo "</div>";
							if($copia == "copies\\") {
								//This button is for uploading a case document to the system
								echo "<input type='file' class='btn btn-primary' style='margin-right: 4px' name='file' id='file'>";
							}
							else {

					            echo "<br></br>";
								echo "<div id='CopyHint'><a class='btn btn-primary pull-left' style='margin-right: 4px' href='$copia'  target='_blank' disabled>$lang[doc_view]</a></div>";
								//This button is for uploading a case document to the system 
								echo "<input type='file' class='btn btn-primary pull-left' style='margin-right: 4px' name='file' id='file'>";
								
							}
						?>
						
				<div class="row">
						<!-- This button is for canceling everithing and returns to the lawyer page -->
						<a class="btn btn-primary pull-right" style="margin-right: 4px" href="attorneyPagehtml.php"><?php echo $lang['eCancel']; ?></a>
						<!-- This button is for adding a case to the system after editing and returns to the lawyer page -->
						<button class="btn btn-primary pull-right" style="margin-right: 4px" type="submit" form="form_updateCase"><?php echo $lang['enter']; ?></button>
						<!-- This button is for printing a case -->
						<a class="btn btn-primary pull-right" style="margin-right: 4px" href="javascript:window.print()"> <?php echo $lang['doc_print']; ?> </a>
						
						
					</div>
				</div>
				<br></br>
			</form>
		</div>

		<?php
			$sql5 = "SELECT EmployeeName FROM Manage WHERE DocId = '$_SESSION[docID]'";
			$stmt5 = sqlsrv_query($conn, $sql5);
			$row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC);
			if($row5['EmployeeName'] <> $_SESSION['username']){	
				print "<script>";
				print "alert('You are not allowed to edit the information in this page. You only can comment.')";     
				print "</script>";
			}
		?>

		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
