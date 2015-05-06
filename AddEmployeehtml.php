<?php
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
		<title> Añadir Empleado </title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<meta charset="UTF-8">

		<!-- This is to only permit the characters that we allow to input to the system -->
		<script type="text/JavaScript">
			function valid(f) {
				!(/^[A-z&#209;&#241;0-9; ;.;,;-;@]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z&#209;&#241;0-9; ;.;,;-;@]/ig,''):null;
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
			<h1><?php print "$lang[eAdd]"; ?></h1>

			<br></br>
			<form class="form-horizontal" role="form" id="employeeInfo" action="addEmployee.php" method="post">
				<div class="form-group"> 
					<label for="employee_name" class="col-sm-2 control-label"> <?php print $lang['eFirstName']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the name of the employee -->
						<input type="text" class="form-control" id="employeeName" name="employeeName" maxlength="20" placeholder="<?php print $lang['eFirstName']; ?>" onkeyup="valid(this)" onblur="valid(this)" required> 
					</div>
				</div> 

				<div class="form-group">  
					<label for="employee_name" class="col-sm-2 control-label"><?php print $lang['eInitial']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the name of the employee -->
						<input type="text" class="form-control" id="employeeInitial" name="employeeInitial" maxlength="2" placeholder="<?php print $lang['eInitial']; ?>" onkeyup="valid(this)" onblur="valid(this)"> 
					</div>
				</div>

				<div class="form-group"> 
					<label for="employee_lastname" class="col-sm-2 control-label"><?php print $lang['eLastName']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the fisrt last name of the employee -->
						<input type="text" class="form-control" id="employeeLastname" name="employeeLastname" maxlength="30" placeholder="<?php print $lang['eLastName']; ?>" onkeyup="valid(this)" onblur="valid(this)" required> 
					</div> 
				</div> 

				<div class="form-group"> 
					<label for="employee_lastname2" class="col-sm-2 control-label"><?php print $lang['eMaidenName']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the second last name of the employee -->
						<input type="text" class="form-control" id="employeeLastname2" name="employeeLastname2" maxlength="30" placeholder="<?php print $lang['eMaidenName']; ?>" onkeyup="valid(this)" onblur="valid(this)"> 
					</div> 
				</div> 

				<div class="form-group"> 
					<label for="employee_lastname2" class="col-sm-2 control-label"><?php print $lang['email']; ?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the second last name of the employee -->
						<input type="email" class="form-control" id="email" name="email" maxlength="50" placeholder="<?php print $lang['email']; ?>" onkeyup="valid(this)" onblur="valid(this)" oninput="validateEmail(this);" required> 
						<span  id ='email_help' class=''></span>
					</div> 
				</div>

				<div class="form-group"> 
					<label for="Type_Employee" class="col-sm-2 control-label"><?php print   $lang['eType']; ?>:</label>
					<div class="col-sm-5" id="TypeEmployee">
						<!-- This dropdown is for selecting the type of employee -->
						<?php session_start();
							$serverName = "127.0.0.1";
							$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
							$conn = sqlsrv_connect($serverName, $connectionInfo);

							/* SQL 
								$sql = query to fetch information from the emplyee in the database
								$stmt = sqlsrv_query() = prepares and executes the query
								$row = sqlsrv_fetch_array() = returns the row as an array
							*/
							$sql = "SELECT Title, Job_Role FROM JobTitle WHERE Job_Role <> 'admin'";
							$stmt = sqlsrv_query($conn, $sql);
							if ($stmt === false) {
								die(print_r( sqlsrv_errors(), true));
							}
							echo "<select class='form-control' name='TypeEmployee' required>";
							echo "<option value=''> $lang[eType] </option>";
							while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								echo "<option value='" . $row['Title'] . "'>" . $row['Title'] . "</option>";
							}
							echo "</select>";
						?>
					</div>
				</div>

				<div class="form-group"> 
					<label for="employee_lastname2" class="col-sm-2 control-label"> <?php print $lang['eTemporaryUser'];?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the second last name of the employee -->
						<input type="text" class="form-control" id="Username" name="Username" minlength="8" maxlength="15" placeholder="<?php print $lang['eTemporaryUser'];?>" onkeyup="valid(this)" onblur="valid(this)" oninput="validateUsername(this);" required> 
						<span  id ='username_help' class=''></span>
					</div> 
				</div> 

				<div class="form-group"> 
					<label for="employee_lastname2" class="col-sm-2 control-label"><?php print $lang['eTemporaryPass'];?>:</label> 
					<div class="col-sm-5">
						<!-- This input text is for the second last name of the employee -->
						<input type="text" class="form-control" id="passWord" name="passWord" minlength="8" maxlength="15" placeholder="<?php print $lang['eTemporaryPass'];?>" onkeyup="valid(this)" onblur="valid(this)" required> 
					</div> 
				</div> 

				<br></br>
				<div class="row">
					<!-- This button is for adding a employee to the system -->
					<button class="btn btn-primary col-md-offset-5" type="submit" form="employeeInfo"><?php print $lang['eSubmit'];?></button>
					<!-- This button is for canceling everithing and returns to the administrators page -->
					<a class="btn btn-primary"  href="employeeListhtml.php"> <?php print $lang['eCancel'];?></a>
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
				print "var helpText1 = document.getElementById('username_help');";
				print "helpText1.style.color = 'red';";
				print "helpText1.innerHTML = 'Username already exists.';";        
				print "</script>";
			}
		?>

		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
	</body>
</html>
