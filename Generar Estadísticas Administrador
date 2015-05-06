<?php
	session_start();

	//Includes the libraries to change the language of the page (english/spanish) and the navigational bar
	include_once 'common.php';
	include 'library.php';
	
	if($_SESSION[job] == 'secretary'){
		header("Location:secretaryPagehtml.php?p=error");
	}
	if($_SESSION[job] == 'admin' || $_SESSION[job] == 'attorney'){
	}
	else{
		die("You are not allow in this page. :P");
	}
	
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- This is the name of the page -->
		<title> Generar Estadísticas </title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<!-- This is to only permit the characters that we allow to input to the system -->
		<script type="text/JavaScript">
			function valid(f) {
				!(/^[A-z;0-9; ;.;-;,]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z;0-9; ;.;-;,]/ig,''):null;
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
						this.value = terms.join( "" );
						return false;
					}
				});
			});
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
			<h1> <?php print "$lang[genStats]"; ?> </h1>

			<br></br>
			<form class="form-horizontal" role="form" id="generateOutputStatisticshtml" action="generarOutputEstadisticashtml.php" method = "post">
				<div class="form-group" > 
					<label for="documentType" class="col-sm-2 control-label"><?php echo $lang['genBy']; ?>:</label> 
					<div class="col-sm-5" id="document_type">
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
				</div> 

				<div class="form-group" > 
					<label for="documentSubcategory" class="col-sm-2 control-label"><?php echo $lang['genSubcategory']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text box is to write the document subcategory or type of service for which the the user want the statistics -->
						<input type="text" class="form-control" name="documentSubcategory" id="tags" placeholder="<?php echo $lang['genSubcategory']; ?>" onkeyup="valid(this)" onblur="valid(this)"> 
					</div> 
				</div>

				<div class="form-group" > 
					<label for="amountFrom" class="col-sm-2 control-label">From <?php echo $lang['genQuantity']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text box is to write the amount of the contracts that the user wants the statistics -->
						<input type="text" class="form-control" name = "amountFrom" id="amountFrom" placeholder="<?php echo $lang['genQuantity']; ?>" onkeyup="valid(this)" onblur="valid(this)"> 
					</div> 
				</div> 

				<div class="form-group" > 
					<label for="amountTo" class="col-sm-2 control-label">To <?php echo $lang['genQuantity']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text box is to write the amount of the contracts that the user wants the statistics -->
						<input type="text" class="form-control" name = "amountTo" id="amountTo" placeholder="<?php echo $lang['genQuantity']; ?>" onkeyup="valid(this)" onblur="valid(this)"> 
					</div> 
				</div> 

				<div class="form-group" > 
					<label for="initialDate" class="col-sm-2 control-label"><?php echo $lang['sDate']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text box is to select the starting date that the user wants the statistics -->
						<input type="Date" class="form-control" name = "initialDate" id="initialDate" required> 
					</div> 
				</div> 

				<div class="form-group" > 
					<label for="endDate" class="col-sm-2 control-label"><?php echo $lang['eDate']; ?>:</label>
					<div class="col-sm-5">
						<!-- This input text box is to select the deadline date that the user wants the statistics --> 
						<input type="Date" class="form-control" name = "endDate" id="endDate" required> 
					</div> 
				</div>
			</form>

			<br></br>
			<div class="row">
				<!-- This button is for generating the statistics -->
				<button class="btn btn-primary col-md-offset-5" type = "submit" form ="generateOutputStatisticshtml"><?php echo $lang['generate']; ?></button>
				<!-- This button is for canceling everything and returning to the lawyer page -->
				<a class="btn btn-primary" href="caseSearch.php"><?php echo $lang['eCancel']; ?></a>
			</div>
		</div>

		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
