<?php
	//This is to use the $_SESSION variables. This variables are to pass the values from page to page.
	session_start();
	//Includes the libraries to change the language of the page (english/spanish) and the navigational bar
	include_once 'common.php';
	include 'library.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- This is the name of the page -->
		<title> Iniciar Sesión </title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<meta charset="UTF-8">
	</head>

	<body>
		<!-- This is the navbar of the system -->
		<?php
			navbar($lang['language'])
		?>

		<div class="container">
			<h1> <?php echo $lang['changeUser']; ?> </h1>
			<br></br>
			<!-- Field to enter the username and the password -->
			<form class="form-horizontal col-md-offset-1" id="changeUser" action="change.php" method="post">
				<!-- Here is the space for the username of the administrator, secretary or attorney -->
				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo $lang['oldUserName']; ?>:</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="oldName" minlength="8" maxlength="15" name="oldName" placeholder="<?php echo $lang['oldUserName']; ?>" required oninput="validateUsername(this);">
						<span  id ='username_help' class=''></span>
					</div>
				</div>

				<p></p>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo $lang['newUserName']; ?>:</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="newName" minlength="8" maxlength="15" name="newName" placeholder="<?php echo $lang['newUserName']; ?>" oninput="validateNewUsername(this);">
						<span  id ='newUsername_help' class=''></span>
					</div>
				</div>

				<p></p>
				<!-- Here is the space for the password of the user -->
				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo $lang['oldPassword']; ?>:</label>
					<div class="col-sm-5">
						<input type="password" class="form-control" id="oldPassword" minlength="8" maxlength="12"  name="oldPassword" placeholder="<?php echo $lang['oldPassword']; ?>" required oninput="validatePassword(this);">
						<span  id ='password_help' class=''></span>
					</div>
				</div>

				<p></p>
				<!-- Here is the space for the password of the user -->
				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo $lang['newPassword']; ?>:</label>
					<div class="col-sm-5">
						<input type="password" class="form-control" id="newPassword" minlength="8" maxlength="12"  name="newPassword" placeholder="<?php echo $lang['newPassword']; ?>" oninput="validateNewPassword(this);">
						<span  id ='newPassword_help' class=''></span>
					</div>
				</div>

				<p></p>
				<!-- Here is the space for the password of the user -->
				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo $lang['confirmPassword']; ?>:</label>
					<div class="col-sm-5">
						<input type="password" class="form-control" id="confirmPassword" minlength="8" maxlength="12"  name="confirmPassword" placeholder="<?php echo $lang['confirmPassword']; ?>">
					</div>
				</div>

				<p></p>
				<!-- checkbox to remember the username and the password of the user -->
				<div>
					<!-- Button to start the session -->
					<div class="row">
						<button class="btn btn-primary col-md-offset-6" type="submit" form="changeUser"><?php echo $lang['change']; ?></button>
						<a class="btn btn-primary" href="IniciarSesionhtml.php"><?php echo $lang['eCancel']; ?></a>
					</div>
				</div>
			</form>			
		</div>
				
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<!-- this function is to corroborate that the password confirmation is identical to the password -->
		<script> var password = document.getElementById("newPassword"), confirm_password = document.getElementById("confirmPassword");
			function validatePassword(){
			  if(password.value != confirm_password.value) {
			    confirm_password.setCustomValidity("Passwords Don't Match");
			  } else {
			    confirm_password.setCustomValidity('');
			  }
			}
			password.onchange = validatePassword;
			confirm_password.onkeyup = validatePassword;
		</script>

		<?php
			//If a user tries to input a case number already in use this will prompt the user an error.
			if(isset($_GET['e']) && $_GET['e'] == 'error'){ 
				print "<script>";
				print "var helpText = document.getElementById('newUsername_help');";
				print "helpText.style.color = 'red';";
				print "helpText.innerHTML = 'You should input a username.';";   
				print "var helpText = document.getElementById('newPassword_help');";
				print "helpText.style.color = 'red';";
				print "helpText.innerHTML = 'You should input a password.';";     
				print "</script>";
			}

			if(isset($_GET['i']) && $_GET['i'] == 'error'){ 
				print "<script>";
				print "var helpText = document.getElementById('newUsername_help');";
				print "helpText.style.color = 'red';";
				print "helpText.innerHTML = 'Username already exists.';";       
				print "</script>";
			}

			if(isset($_GET['n']) && $_GET['n'] == 'error'){ 
				print "<script>";
				print "var helpText = document.getElementById('username_help');";
				print "helpText.style.color = 'red';";
				print "helpText.innerHTML = 'Incorrect Username.';";
				print "var helpText = document.getElementById('password_help');";
				print "helpText.style.color = 'red';";
				print "helpText.innerHTML = 'Incorrect password.';";     
				print "</script>";
			}
		?>

	</body>
</html>
