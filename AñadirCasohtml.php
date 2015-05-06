<?php
	//This is to use the $_SESSION variables. This variables are to pass the values from page to page.
	session_start();

	//to includes the library to change the language of the page
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
		<title> Añadir Caso </title>
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
		<link rel="stylesheet" href="css/jquery-ui.css">
		<script src="js/jquery-1.11.2.js"></script>
		<script src="js/jquery-ui-1.11.4.js"></script>
		<script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
		<script>
   			 webshims.setOptions('forms-ext', {types: 'date'});
			webshims.polyfill('forms forms-ext');
		</script>
		<script>
			$(function() {
				var availableTags = [
					<?php 
						$serverName = "127.0.0.1";
						$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
						$conn = sqlsrv_connect($serverName, $connectionInfo);
						/* SQL 
							$sql = query to fetch information from the emplyee in the database
							$stmt = sqlsrv_query() = prepares and executes the query
							$row = sqlsrv_fetch_array() = returns the row as an array
						*/
						$sql = "SELECT Subcat FROM Subcategory";
						$stmt = sqlsrv_query($conn, $sql);
						if ($stmt === false) {
							die(print_r( sqlsrv_errors(), true));
						}
						while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
							echo "'$row[Subcat]'".",";
						}
					?>
				];
				function split( val ) {
					return val.split( /,\s*/ );
				}
				function extractLast( term ) {
					return split( term ).pop();
				}
				$( "#tags" )
				// don't navigate away from the field on tab when selecting an item
				.bind( "keydown", function( event ) {
					if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).autocomplete( "instance" ).menu.active ) {
						event.preventDefault();
					}
				})
				.autocomplete({
					minLength: 0,
					source: function( request, response ) {
						// delegate back to autocomplete, but extract the last term
						response( $.ui.autocomplete.filter(
						availableTags, extractLast( request.term ) ) );
					},
					focus: function() {
						// prevent value inserted on focus
						return false;
					},
					select: function( event, ui ) {
						var terms = split( this.value );
						// remove the current input
						terms.pop();
						// add the selected item
						terms.push( ui.item.value );
						// add placeholder to get the comma-and-space at the end
						terms.push( "" );
						this.value = terms.join( ", " );
						return false;
					}
				});
			});
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
				xmlhttp.open("GET","getuser.php?q="+str,true);
				xmlhttp.send();
				//  }
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
			<h1> <?php print "$lang[add_case]"; ?> </h1>

			<br></br>
			<form id="form_anadirCaso" action="anadirCaso.php" method="post" enctype="multipart/form-data">
				<div class="row">
					<!-- This input is to select the document date -->
					<label class="col-md-2"> <?php echo $lang['date_issued']; ?>: <input type="date" class="form-control" id="dateDocument" name="dateDocument"></label>
					
					<!-- This input is to select the date that the document was received -->
					<label class="col-md-2 col-md-offset-1"><?php echo $lang['date_received']; ?>: <input type="date" class="form-control" id="dateReceived" name="dateReceived" required></label>
					
					<!-- This input is to select the document deadline date -->
					<label class="col-md-2 col-md-offset-1"><?php echo $lang['date_due']; ?>: <input type="date" class="form-control" id="dateDue" name="dateDue"></label>

				</div>

				<p></p>
				<div class="row">
					<div class="col-md-2">
						<label "control-label"><?php echo $lang['case_num']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['case_num']; ?>" maxlength="15" onkeyup="valid(this)" onblur="valid(this)" id="caseNumber" name="caseNumber" required oninput="validateCaseNumber(this);">
						<span  id ='caseNumber_help' class=''></span>
					</div>


					<!-- This input box is to insert the name of the appellant -->
					<div class="col-md-5 col-md-offset-1">
						<label "control-label"><?php echo $lang['case_apellant']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['case_apellant']; ?>" maxlength="30" onkeyup="valid(this)" onblur="valid(this)" id="caseAppellant" name="caseAppellant" required>
					</div>
				</div>

				<p></p>
				<div class="row">
					<div class="col-md-3"> 
						<label "control-label"><?php echo $lang['doc_view']; ?>:</label>
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
							echo "<select class='form-control' name='documentType' id='documentType' required>";
							echo "<option value=''> $lang[docType] </option>";
							while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
								echo "<option value='" . $row2['Tipo'] . "'>" . $row2['Tipo'] . "</option>";
							} 
							echo "</select>";
						?>
					</div>

					<!-- This input box is for inserting the subcategory of the document -->
					<div class="col-md-5">
						<label "control-label"><?php echo $lang['genSubcategory']; ?>:</label>
						<input class="form-control" placeholder="<?php echo $lang['genSubcategory']; ?>" onkeyup="valid(this)" onblur="valid(this)" name="documentSubcategory" id="tags" oninput="validateCausal(this);">
						<span  id ='causal_help' class=''></span>
					</div>

					<!-- Amount in lawsuit -->
					<div class="col-md-2">
						<label "control-label"><?php echo $lang['case_amount']; ?>:</label>
						<input type="number" max="9999999999.99" step='0.01' class="form-control" placeholder="<?php echo $lang['case_amount']; ?>" name="quantity" id="quantity">
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
						<div id="txtHint"><select class='form-control' name='caseReceiver' required><option>Select Receiver</option></select></div>
					</div>
				</div>

				<p></p>
				<div class="row">
					<!-- This input box is to insert name of the lawyer that sends the document -->
					<div class="col-md-3">
						<label "control-label"><?php echo $lang['case_sender']; ?>:</label>
						<input type="text" class="form-control" placeholder="<?php echo $lang['case_sender']; ?>" maxlength="30" onkeyup="valid(this)" onblur="valid(this)" name="caseSender" id="caseSender">
					</div>
					<div class="col-md-4" id="department">
						<label "control-label"><?php echo $lang['department']; ?>:</label>
						<!-- This dropdown is to select the name of the senders office -->
						<?php
							/* SQL 
								$sql = query to fetch information from the emplyee in the database
								$stmt = sqlsrv_query() = prepares and executes the query
								$row = sqlsrv_fetch_array() = returns the row as an array
							*/
							$sql = "SELECT Foro FROM Forums";
							$stmt = sqlsrv_query($conn, $sql);
							if ($stmt === false) {
								die(print_r( sqlsrv_errors(), true));
							}
							echo "<select class='form-control' name='department' required>";
							echo "<option value=''> $lang[department] </option>";
							while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								echo "<option value='" . $row['Foro'] . "'>" . $row['Foro'] . "</option>";
							} 
							echo "</select>";
						?>
					</div>
				</div>

				<p></p>
				<label "control-label"><?php echo $lang['case_subject']; ?>:</label>
				<!-- This input box is to write some issue of the contract -->
				<textarea class="form-control .input-lg" rows="5" maxlength="300" onkeyup="valid(this)" onblur="valid(this)" placeholder="<?php echo $lang['case_subject']; ?>" name="caseSubject" id="caseSubject"></textarea>

				<p></p>
				<label "control-label"><?php echo $lang['case_comment']; ?>:</label>
				<!-- This input box is to write some comments of the contract -->
				<textarea class="form-control .input-lg" rows="5" maxlength="300" onkeyup="valid(this)" onblur="valid(this)" placeholder="<?php echo $lang['case_comment']; ?>" name="caseComment" id="caseComment"></textarea>

				<br></br>
				<div class="footer">
					<div class="row">
						<!-- This button is for canceling everithing and returns to the secretary page -->
						<a class="btn btn-primary pull-right" href="secretaryPagehtml.php"><?php echo $lang['eCancel']; ?></a>
						<!-- This button is for adding a case to the system and returns to the secretary page -->
						<button class="btn btn-primary pull-right" style="margin-right: 4px" type="submit" form="form_anadirCaso"><?php echo $lang['enter']; ?></button>
						<!-- This button is for printing a case -->
						<a class="btn btn-primary pull-right" style="margin-right: 4px" href="javascript:window.print()"> <?php echo $lang['doc_print']; ?> </a>
						<!-- This button is for uploading a case document to the system -->
						<input type="file" class="btn btn-primary pull-right" style="margin-right: 4px" name="file" id="file">
					</div>
				</div>
				<br></br>
			</form>
		</div>


		<?php
			//If a user tries to input a case number already in use this will prompt the user an error.
			if(isset($_GET['e']) && $_GET['e'] == 'error'){ 
				print "<script>";
				print "var helpText = document.getElementById('caseNumber_help');";
				print "helpText.style.color = 'red';";
				print "helpText.innerHTML = 'Case Number Not Valid';";    
				print "</script>";
			}
		?>

		<?php
			//If a user tries to input a case number already in use this will prompt the user an error.
			if(isset($_GET['n']) && $_GET['n'] == 'error'){ 
				print "<script>";
				print "var helpText = document.getElementById('causal_help');";
				print "helpText.style.color = 'red';";
				print "helpText.innerHTML = 'Causal required.';";    
				print "</script>";
			}
		?>

		<script src="js/bootstrap.min.js"></script>
		
	</body>
</html>
