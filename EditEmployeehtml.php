<?php
	session_start();

	//Includes the libraries to change the language of the page (english/spanish) and the navigational bar
	include_once 'common.php';
	include 'library.php';

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
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- This is the name of the page -->
		<title> Empleado </title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<meta charset="UTF-8">
		<!-- This is to only permit the characters that we allow to input to the system -->
		<script type="text/JavaScript">
			function valid(f) {
				!(/^[A-z&#209;&#241;0-9; ;.;,;@]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z&#209;&#241;0-9; ;.;,;@]/ig,''):null;
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
			<h1> <?php print "$lang[eEdit]"; ?> </h1>

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

				/* SQL
					$sql = query to fetch the information of the employee in the database from the variables below
					$stmt = sqlsrv_query() = prepares and executes the query
					$row = sqlsrv_fetch_array() = returns the row as an array
				*/
				$sql = "SELECT Name, MiddleName, LastName, MaidenName, Job, Email FROM Employee WHERE Username = '$_SESSION[username]'";
				$stmt = sqlsrv_query($conn, $sql);
				while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					$nombre = $row['Name'];
					$inicial = $row['MiddleName'];
					$apellido = $row['LastName'];
					$apellido2 = $row['MaidenName'];
					$trabajo = $row['Job'];
					$email 	= $row['Email'];
				}
			?>

			<form class="form-horizontal" role="form" id="editEmployee" action="editEmployee.php" method="post">
				<div class="form-group"> 
					<label for="employee_name" class="col-sm-2 control-label"><?php echo $lang['eFirstName']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the name of the employee -->
						<input type="text" class="form-control" id="employeeName" name="employeeName" placeholder="Primer Nombre del Empleado" onkeyup="valid(this)" onblur="valid(this)" value=<?php echo $nombre ?>>
					</div>
				</div> 

				<div class="form-group"> 
					<label for="employee_name" class="col-sm-2 control-label"><?php echo $lang['eInitial']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the initial of the employee -->
						<input type="text" class="form-control" id="employeeInitial" name="employeeInitial" placeholder="Inicial del empleado" onkeyup="valid(this)" onblur="valid(this)" value=<?php echo $inicial ?>>
					</div>
				</div>

				<div class="form-group"> 
					<label for="employee_lastname" class="col-sm-2 control-label"><?php echo $lang['eLastName']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the fisrt last name of the employee -->
						<input type="text" class="form-control" id="employeeLastname" name="employeeLastname" placeholder="Primer Apellido del Empleado" onkeyup="valid(this)" onblur="valid(this)" value=<?php echo $apellido ?>>
					</div> 
				</div> 

				<div class="form-group"> 
					<label for="employee_lastname2" class="col-sm-2 control-label"><?php echo $lang['eMaidenName']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the maiden name of the employee -->
						<input type="text" class="form-control" id="employeeLastname2" name="employeeLastname2" placeholder="Segundo Apellido del Empleado" onkeyup="valid(this)" onblur="valid(this)" value=<?php echo $apellido2 ?>>
					</div> 
				</div>

				<div class="form-group"> 
					<label for="employee_lastname2" class="col-sm-2 control-label"><?php print $lang['email']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the second last name of the employee -->
						<input type="email" class="form-control" id="email" name="email" placeholder="<?php print $lang['email']; ?>" onkeyup="valid(this)" onblur="valid(this)" oninput="validateEmail(this);" value=<?php echo $email ?>> 
						<span  id ='email_help' class=''></span>
					</div> 
				</div>

				<div class="form-group"> 
					<label for="Type_Employee" class="col-sm-2 control-label"><?php echo $lang['eType']; ?>:</label>
					<div class="col-sm-5" id="TypeEmployee">
						<!-- This dropdown is for select the type of employee -->
						<select class="form-control" name="TypeEmployee">
							<option><?php echo $trabajo ?></option>
							<option value="Técnicas de Sistemas de Oficina III"><?php print $lang['eType_adm_office_techs'];?> </option>
							<option value="Administradora del Sistema I"><?php print $lang['eType_adm_system'];?> </option>
							<option value="Asistente Administrativa"><?php print $lang['eType_adm_assistant'];?> </option>
							<option value="Cadete"><?php print $lang['eType_cadet'];?> </option>
							<option value="Agente"><?php print $lang['eType_agent'];?> </option>
							<option value="Sargento"><?php print $lang['eType_sargent'];?> </option>
							<option value="Teniente"><?php print $lang['eType_lieutenant'];?>  </option>
							<option value="Capitan"><?php print $lang['eType_captain'];?>  </option>
							<option value="Inspector"><?php print $lang['eType_inspector'];?>  </option>
							<option value="Comandante"><?php print $lang['eType_commander'];?>  </option>
							<option value="Teniente Coronel"><?php print $lang['eType_lieutenant_colonel'];?> </option>
							<option value="Coronel"><?php print $lang['eType_colonel'];?> </option>
							<option value="Reservista"><?php print $lang['eType_reservist'];?>  </option>
							<option value="attorney">Abogado</option>
						</select>
					</div>
				</div>

				<br></br>
				<div class="row">
					<!-- This button is for adding a employee to the system -->
					<button class="btn btn-primary col-md-offset-5" type="submit" form="editEmployee"><?php echo $lang['enter']; ?></button>
					<!-- This button is for canceling everithing and returns to the administrators page -->
					<a class="btn btn-primary" href="employeeListhtml.php"><?php echo $lang['eCancel']; ?></a>
				</div>
			</form>
		</div>

		<?php
			//If a user tries to input a case number already in use this will prompt the user an error.
			if(isset($_GET['e']) && $_GET['e'] == 'error'){ 
				print "<script>";
				print "var helpText = document.getElementById('email_help');";
				print "helpText.style.color = 'red';";
				print "helpText.innerHTML = 'Email already exists.';";       
				print "</script>";
			}
		?>

		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
