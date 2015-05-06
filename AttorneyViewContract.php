<?php
	session_start();
	//Includes the libraries to change the language of the page (english/spanish) and the navigational bar
	include_once 'common.php';
	include 'library.php';

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
		<title> Ver Contrato </title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
		<script>
			webshims.setOptions('forms-ext', {types: 'date'});
			webshims.polyfill('forms forms-ext');
		</script>
		<script>
			function showType(str) {
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
				xmlhttp.open("GET","getType.php?c="+str,true);
				xmlhttp.send();
			}
		</script>
		<!-- This is to only permit the characters that we allow to input to the system -->
		<script type="text/JavaScript">
			function valid(f) {
				!(/^[A-z0-9; ;.;,;#;ñ]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z;0-9; ;.;,;&;#;ñ]/ig,''):null;
			}
		</script>
		<script>
			function showCommentUser(str) {
				if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("txtHint3").innerHTML = xmlhttp.responseText;
					}
				}
				xmlhttp.open("GET","getContractComment.php?q="+str,true);
				xmlhttp.send();
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
						document.getElementById("txtHint2").innerHTML = xmlhttp.responseText;
					}
				}
				xmlhttp.open("GET","getuser.php?q="+str,true);
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
				xmlhttp.open("GET","getContractCopy.php?q="+str,true);
				xmlhttp.send();
			}
		</script>
	</head>

	<body>
		<!-- Static navbar -->
		<?php 
			navbarEmployeeList($lang['language'],$lang['logout']);
		?>

		<div class="container">
			<!-- This is the name in the header of the page -->
			<h1> <?php echo $lang['contractHeader']; ?> </h1>

			<br></br>
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
				$sql = "SELECT ContractNumber, CONVERT(VARCHAR(11),DateGranted,21) AS DateGranted, CONVERT(VARCHAR(11),EffectiveFrom,21) AS EffectiveFrom, 
								CONVERT(VARCHAR(11),EffectiveUntil,21) AS EffectiveUntil, ContractorName, ServiceCategory, ServiceType, Quantity, Fund, ContractSubject, Estado FROM Contracts WHERE ContractNumber = '$_SESSION[docID]'";
				$stmt = sqlsrv_query($conn, $sql);
				if ($stmt === false) {
					echo $sql;
					die(print_r( sqlsrv_errors(), true));
				}
				while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					$id = $row['ContractNumber']; 
					$eDate = $row['DateGranted'];
					$cDate = $row['EffectiveFrom'];
					$deadline = $row['EffectiveUntil'];
					$contractorName = $row['ContractorName'];
					$serviceCategory = $row['ServiceCategory'];
					$serviceType = $row['ServiceType'];
					$quantity = $row['Quantity'];
					$description = $row['ContractSubject'];
					$status = $row['Estado'];
					$copia = $row['Copy'];
					$fondo = $row['Fund'];
				}
			?>
			<form id="anadirContrato" action="attorneyViewContractUpdate.php" method="post" enctype="multipart/form-data">
				<div class="row">
					<!-- This input is to select the document date -->
					<label class="col-md-2"> <?php echo $lang['dateGrantd']; ?>: <input type="date" class="form-control" value=<?php echo $eDate ?> disabled></label>
					<!-- This input is to select the date that the document was received -->
					<label class="col-md-2 col-md-offset-1"><?php echo $lang['effectiveDateFrom']; ?>:<input type="date" class="form-control" id="dateFrom" name="dateFrom" value=<?php echo $cDate ?> disabled></label>
					<!-- This input is to select the document deadline date -->
					<label class="col-md-2 col-md-offset-1"><?php echo $lang['effectiveDateTo']; ?>: <input type="date" class="form-control" id="dateTo" name="dateTo" value=<?php echo $deadline ?> disabled></label>
				</div>

				<p></p>
				<div class="row">
									<!-- This input box is to insert the contract number -->
					<div class="col-md-2">
						<label "control-label"><?php echo $lang['contract_num']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['contract_num']; ?> " onkeyup="valid(this)" onblur="valid(this)" id="contractNum" name="contractNum" value='<?php echo $id ?>' disabled>
					</div>
					
					<!-- This input box is to insert the name of contractor the  -->
					<div class="col-md-5 col-md-offset-1">
						<label "control-label"><?php echo $lang['contName']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['contName']; ?>" onkeyup="valid(this)" onblur="valid(this)" id="contractorName" name="contractorName" value='<?php echo $contractorName ?>' disabled></div>
				</div>

				<p></p>
				<div class="row">
					<div class="col-md-3" id="category">
						<label "control-label"><?php echo $lang['contType']; ?>:</label>
						<!-- This dropdown is to select the type of contract -->
						<?php
							  $sql1 = "SELECT Categoria FROM Categoria WHERE Codigo = '$serviceCategory'";
							  $stmt1 = sqlsrv_query($conn, $sql1);
							  $row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);


							/* SQL 
								$sql = query to fetch information from the emplyee in the database
								$stmt = sqlsrv_query() = prepares and executes the query
								$row = sqlsrv_fetch_array() = returns the row as an array
							*/
							$sql2 = "SELECT Codigo, Categoria FROM Categoria";
							$stmt2 = sqlsrv_query($conn, $sql2);
							if ($stmt2 === false) {
								echo $sql2;
								die(print_r( sqlsrv_errors(), true));
							}
							echo "<select class='form-control' name='category' onchange='showType(this.value)' value='<?php echo $serviceCategory ?>' disabled>";
							echo "<option> $row1[Categoria] </option>";
							while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
								echo "<option value='" . $row2['Codigo'] . "'>" . $row2['Categoria'] . "</option>";
							} 
							echo "</select>";
						?>
				</div>

					<!-- This input box is to insert the type of service of the contract -->
					<div class="col-md-3" id="type">
						<label "control-label"><?php echo $lang['serviceType']; ?>:</label>
						<!-- This dropdown is to select the type of contract -->
						<div id="txtHint"><select class='form-control' name='type' value=<?php echo $serviceType ?> disabled><?php echo "<option>" . $serviceType . "</option>" ?></select></div>
					</div>
				</div>

				<p></p>
				<?php
					/*
						$sql - query to fetch the information of the employee managing the document from the database.
						$stmt = sqlsrv_query() = prepares and executes the query
						$row = sqlsrv_fetch_array() = returns the row as an array
					*/
					$sql = "SELECT Name, MiddleName, LastName, MaidenName, Office FROM Employee, ManageContract WHERE Username = EmployeeName and ContractID = '$_SESSION[docID]'";
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
				<div class="row">
					<!-- This input box is to insert name of the addressee region -->
					<div class="col-md-3 ">
						<label "control-label"><?php echo $lang['case_region']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['case_region']; ?>" onkeyup="valid(this)" onblur="valid(this)" value='<?php echo $oficina ?>' disabled>
					</div>

					<!-- This input box is to insert name of the addressee -->
					<div class="col-md-4" id="caseReceiver">
						<label "control-label"><?php echo $lang['case_receiver']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['case_receiver']; ?>" onkeyup="valid(this)" onblur="valid(this)" value='<?php echo $nombre . " " . $inicial . " " . $apellido . " " . $segundo ?>' disabled>
					</div>
				</div>

				<p></p>
				<div class="row">
					<div class="col-md-3 ">
						<label "control-label"><?php echo $lang['contAmount']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['contAmount']; ?>" onkeyup="valid(this)" onblur="valid(this)" id="quantity" name="quantity" value='<?php echo $quantity ?>' disabled>
					</div>

					<div class="col-md-3">
						<label "control-label"><?php echo $lang['fondo']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['fondo']; ?> " onkeyup="valid(this)" onblur="valid(this)" id="fondo" name="fondo" value='<?php echo $fondo ?>' disabled>
						</div>

					<div class="col-md-3 ">
						<label "control-label"><?php echo $lang['status']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['status']; ?>" onkeyup="valid(this)" onblur="valid(this)" id="status" name="status" value='<?php echo $status ?>' disabled>
					</div>
				</div>

				<p></p>
				<label "control-label"><?php echo $lang['case_subject']; ?>:</label>
				<!-- This input box is to write some issue of the contract -->
				<textarea class="form-control .input-lg" rows="5" placeholder="<?php echo $lang['case_subject']; ?>" disabled><?php echo $description ?></textarea>
				<p></p>
			
				<div class='row col-md-3'>
					<label "control-label"><?php echo $lang['case_comment']; ?>:</label>
					<?php
						/*
							$sql - query to fetch the comments made to the document from the database.
							$stmt = sqlsrv_query() = prepares and executes the query
							$row = sqlsrv_fetch_array() = returns the row as an array
						*/
						$sql = "SELECT CONVERT(VARCHAR(24),NoteDate,113) AS fecha, CONVERT(VARCHAR(11),NoteDate,106) AS ahcef FROM ContractComments WHERE DocId = '$_SESSION[docID]'";
						$stmt = sqlsrv_query($conn, $sql);
						if ($stmt === false) {
							die(print_r( sqlsrv_errors(), true));
						}
						echo "<select class='form-control' onchange='showCommentUser(this.value)'> disabled";
						echo "<option value=''>Select Comment</option>";
						while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
							echo "<option value='".$row['fecha']."'>".$row['ahcef']."</opton>";
						}
						echo "</select>";
					?>
				</div>

				<textarea class='form-control' .input-lg row='5' onkeyup='valid(this)' onblur='valid(this)' placeholder=<?php echo $lang['case_comment'] ?> id="txtHint3" disabled></textarea>

				<p></p>
				

				
				<div class="footer">
					
					<label "control-label"><?php echo $lang['case_comment']; ?>:</label>
					<!-- This input box is to write some comments of the contract -->
					<textarea class="form-control .input-lg" rows="5" placeholder="<?php echo $lang['case_comment']; ?>" id="comentario" name="comentario" onkeyup="valid(this)" onblur="valid(this)"></textarea>
				<p></p>
						<?php
							$sql = "SELECT CONVERT(VARCHAR(24),CopyDate,113) AS fecha, CONVERT(VARCHAR(11),CopyDate,106) AS ahcef, Copies FROM CopyContract WHERE DocId = '$_SESSION[docID]'";
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
						<!-- This button is for canceling everithing and returns to the secretary page -->
						<a class="btn btn-primary pull-right" href="attorneyPagehtml.php"><?php echo $lang['eCancel']; ?></a>
						<!-- This button is for adding a contract to the system and returns to the secretary page -->
						<button class="btn btn-primary pull-right" style="margin-right: 4px" type="submit" form="anadirContrato"><?php echo $lang['enter']; ?></button>
						<!-- This button is for printing a contract -->
						<a class="btn btn-primary pull-right" style="margin-right: 4px" href="javascript:window.print()"> <?php echo $lang['doc_print']; ?> </a>
					</div>
				</div>
			</form>
		</div>

		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
