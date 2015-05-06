<?php
	//Creates a session passed via a POST request
	session_start();

	//Includes the libraries to change the language of the page (english/spanish) and the navigational bar
	include_once 'common.php';
	include 'library.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Title of the page and CSS that have the style code of the page -->
		<title> Iniciar Sesión </title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<meta charset="UTF-8">
	</head>

	<body>
		<?php 
			/*
				This function displays the information in the navigation bar. It includes the system's header and the
				language selection dropdown buttons.
			*/
			navbar($lang['language']);
		?>

		<div class="container">
			<!-- Header of the page -->
			<h1 class="col-md-offset-1"> <?php print "$lang[signIn]"; ?> </h1>

			<br></br>
			<!-- Field to enter the username and the password -->
			<form class="form-horizontal col-md-offset-1" id="form-user" action="login.php" method="post">
				<!-- Here is the space for the username of the administrator, secretary or attorney -->
				<div class="form-group">
					<label class="col-sm-2 control-label"> <?php print $lang['userName']; ?>: </label>
					<div class='col-sm-3'>
						<div id='name1' class=''>
							<input type="text" class="form-control col-sm-3" id="userName" minlength="8" maxlength="15" name="userName" placeholder="<?php print $lang['userName']; ?>" required oninput="validateFormName(this, 'name_help', 'name1', 'icon_check1');">
							<span  id ='icon_check1' class=''></span>
							<span  id ='name_help' class=''></span>
						</div>
					</div>				
				</div>

				<p></p>
				<!-- Here is the space for the password of the user -->
				<div class="form-group">
					<label class="col-sm-2 control-label"> <?php print $lang['userPassword']; ?>: </label>
					<div class='col-sm-3'>
						<div id='name2' class=''>
							<input type="password" class="form-control" id="userPassword" minlength="8" maxlength="15"  name="userPassword" placeholder="<?php print $lang['userPassword'];	?>" required oninput="validateFormPwd(this, 'name2', 'icon_check2');">
							<span  id ='icon_check2' class=''></span>
							<span  id ='pwd_help' class=''></span>
						</div>
					</div>
				</div>

				<p></p>
				<!-- Button to start the session -->
				<div class="row">
					<button class="btn btn-primary col-md-offset-2" type="submit" form="form-user" > <?php echo $lang['signup']; ?></button>
					<a class="btn btn-primary" href="changehtml.php"><?php echo $lang['userChange']; ?></a>
					<a class="btn btn-primary" href="PasswordReset.php"> Forgot Password </a>
				</div>
			</form>
		</div>

		<?php
			//If a user tries to log in with an incorrect username or password this will prompt the user an error.
			if(isset($_GET['e']) && $_GET['e'] == 'error'){ 
				print "<script>";
				print "var helpText = document.getElementById('name_help');";
				print "helpText.style.color = 'red';"; //Prompt message character color
				print "helpText.innerHTML = 'Incorrect Username.<br>';";
				print "var helpText1 = document.getElementById('pwd_help');";
				print "helpText1.style.color = 'red';";
				print "helpText1.innerHTML = 'Incorrect Password';";      
				print "</script>";
			}
		?>

		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/logInValidation.js"></script>
	</body>
</html>
