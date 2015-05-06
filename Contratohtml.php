<?php
	//Includes the libraries to change the language of the page (english/spanish) and the navigational bar
	include_once 'common.php';
	include 'library.php';

	//Verify if the user
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
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- This is the name of the page -->
		<title> Contratos </title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<!-- This is to only permit the characters that we allow to input to the system -->
		<script type="text/JavaScript">
			function valid(f) {
				!(/^[A-z;0-9; ;.;,;#;&;-]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z;0-9; ;.;,;#;&;-]/ig,''):null;
			} 
		</script>
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
				!(/^[A-z0-9; ;.;,;#;-]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z;0-9; ;.;,;&;#;-]/ig,''):null;
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
			<form id="anadirContrato" action="contrato.php" method="post" enctype="multipart/form-data">
				<div class="row">
					<!-- This input is to select the document date -->
					<label class="col-md-2"> <?php echo $lang['dateGrantd']; ?>: <input type="date" class="form-control" id="dateGrantd" name="dateGrantd"></label>
					<!-- This input is to select the date that the document was received -->
					<label class="col-md-2 col-md-offset-1"><?php echo $lang['effectiveDateFrom']; ?>:<input type="date" class="form-control" id="dateFrom" name="dateFrom"></label>
					<!-- This input is to select the document deadline date -->
					<label class="col-md-2 col-md-offset-1"><?php echo $lang['effectiveDateTo']; ?>: <input type="date" class="form-control" id="dateTo" name="dateTo"></label>
				</div>

				<p></p>
				<div class="row">
					<!-- This input box is to insert the contract number -->
					<div class="col-md-2">
						<label "control-label"><?php echo $lang['contract_num']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['contract_num']; ?> " onkeyup="valid(this)" onblur="valid(this)" id="contractNum" name="contractNum" required oninput="validateContractNumber(this);">
							<span  id ='contractNumber_help' class=''></span>
						</div>
					
					<!-- This input box is to insert the name of contractor the  -->
					<div class="col-md-5 col-md-offset-1">
						<label "control-label"><?php echo $lang['contName']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['contName']; ?>" onkeyup="valid(this)" onblur="valid(this)" id="contractorName" name="contractorName"></div>
				</div>

				<p></p>
				<div class="row">
					<div class="col-md-4" id="category">
						<label "control-label"><?php echo $lang['contType']; ?>:</label>
						<!-- This dropdown is to select the type of contract -->
						<?php
							$serverName = "127.0.0.1";
							$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
							$conn = sqlsrv_connect($serverName, $connectionInfo);
							/* SQL 
								$sql = query to fetch information from the emplyee in the database
								$stmt = sqlsrv_query() = prepares and executes the query
								$row = sqlsrv_fetch_array() = returns the row as an array
							*/
							$sql = "SELECT Codigo, Categoria FROM Categoria";
							$stmt = sqlsrv_query($conn, $sql);
							if ($stmt === false) {
								echo $sql;
								die(print_r( sqlsrv_errors(), true));
							}
							echo "<select class='form-control' name='category' onchange='showType(this.value)'>";
							echo "<option value=''> $lang[contType] </option>";
							while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								echo "<option value='" . $row['Codigo'] . "'>" . $row['Categoria'] . "</option>";
							} 
							echo "</select>";
						?>
					</div>

					<!-- This input box is to insert the type of service of the contract -->
					<div class="col-md-4" id="type">
						<label "control-label"><?php echo $lang['serviceType']; ?>:</label>
						<!-- This dropdown is to select the type of contract -->
						<div id="txtHint"><select class='form-control' name='type' required><option><?php echo $lang['serviceType']; ?></option></select></div>
					</div>
				</div>

				<p></p>
				<div class="row">
					<!-- This input box is to insert name of the addressee region -->
					<div class="col-md-3 ">
						<label "control-label"><?php echo $lang['case_region']; ?>:</label>
						<select class="form-control" name="caseRegion" id="caseRegion" onchange="showUser(this.value)" required>
							<option value="">Seleccione la region</option>
							<option value="Aibonito">Aibonito</option>
							<option value="Aguadilla">Aguadilla</option>
							<option value="Arecibo">Arecibo</option>
							<option value="Bayamon">Bayamón</option>
							<option value="Caguas">Caguas</option>
							<option value="Carolina">Carolina</option>
							<option value="Fajardo">Fajardo</option>
							<option value="Guayama">Guayama</option>
							<option value="Hato Rey">Hato Rey</option>
							<option value="Humacao">Humacao</option>
							<option value="Mayaguez">Mayagüez</option>
							<option value="Ponce">Ponce</option>
							<option value="San Juan">San Juan</option>
							<option value="Utuado">Utuado</option>
						</select>
					</div>

					<!-- This input box is to insert name of the addressee -->
					<div class="col-md-4" id="caseReceiver">
						
							<label "control-label"><?php echo $lang['case_receiver']; ?>:</label>
							<div id="txtHint2"><select class='form-control' name='caseReceiver' required><option>Select Receiver</option></select></div>
					</div>
				</div>

				<p></p>
				<div class="row">
					
					<div class="col-md-3 ">
						<label "control-label"><?php echo $lang['contAmount']; ?>:</label>
						<input type="number" step='0.01' class="form-control" placeholder="<?php echo $lang['contAmount']; ?>" onkeyup="valid(this)" onblur="valid(this)" id="quantity" name="quantity">
					</div>

					<div class="col-md-3">
						<label "control-label"><?php echo $lang['fondo']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['fondo']; ?> " onkeyup="valid(this)" onblur="valid(this)" id="fondo" name="fondo" required>
						</div>
					
					
					<div class="col-md-3 ">
						<label "control-label"><?php echo $lang['status']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['status']; ?>" onkeyup="valid(this)" onblur="valid(this)" id="status" name="status">
					</div>
				</div>

				<p></p>
				<label "control-label"><?php echo $lang['case_subject']; ?>:</label>
				<!-- This input box is to write some issue of the contract -->
				<textarea class="form-control .input-lg" rows="5" placeholder="<?php echo $lang['case_subject']; ?>" onkeyup="valid(this)" onblur="valid(this)" id="description" name="description"></textarea>

				<p></p>
				<label "control-label"><?php echo $lang['case_comment']; ?>:</label>
				<!-- This input box is to write some comments of the contract -->
				<textarea class="form-control .input-lg" rows="5" placeholder="<?php echo $lang['case_comment']; ?>" onkeyup="valid(this)" id="contractComment" name="contractName" onblur="valid(this)"></textarea>

				<br></br>
				<div class="footer">
					<div class="row">
						<!-- This button is for canceling everithing and returns to the secretary page -->
						<a class="btn btn-primary pull-right" href="secretaryPagehtml.php"><?php echo $lang['eCancel']; ?></a>
						<!-- This button is for adding a contract to the system and returns to the secretary page -->
						<button class="btn btn-primary pull-right" style="margin-right: 4px" type="submit" form="anadirContrato"><?php echo $lang['enter']; ?></button>
						<!-- This button is for printing a contract -->
						<a class="btn btn-primary pull-right" style="margin-right: 4px" href="javascript:window.print()"> <?php echo $lang['doc_print']; ?> </a>
						<!-- This button is for uploading a case document to the system -->
						<input type="file" class="btn btn-primary pull-right" style="margin-right: 4px" name="file" id="file">
					</div>
				</div>
				<br></br>
			</form>
		</div>


		<?php
			//If a user tries to input a contract number already in use this will prompt the user an error.
			if(isset($_GET['e']) && $_GET['e'] == 'error'){ 
				print "<script>";
				print "var helpText = document.getElementById('contractNumber_help');";
				print "helpText.style.color = 'red';";
				print "helpText.innerHTML = 'Contract Number Not Valid';";   
				print "</script>";
			}
		?>

		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/logInValidation.js"></script>
	</body>
</html>
