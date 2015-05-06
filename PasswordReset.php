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
		<title> Resetear Password </title>
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
			<h1> <?php echo $lang['reset']; ?> </h1>
			<br></br>

	

			<h4><?php echo $lang['firstmsm']; ?></h4>
			

			<!-- Field to enter the username and the password -->
			<form class="col-sm-offset-1" action="" method="post">
				<!-- Here is the space for the username of the administrator, secretary or attorney -->
				<div class="form-group">
					
					<div class="row col-sm-14">
						
							<label class="col-sm-2 control-label"><?php echo $lang['email']; ?>:</label>
							<div class="col-sm-4">
							<input type="text" class="form-control" name="remail" size="50">
							</div>
							<button class="btn btn-primary" type="submit" name="submit" value="Get New Password" onkeyup="valid(this)" onblur="valid(this)"><?php echo $lang['getPassword']; ?></button>
							<a class="btn btn-primary" href="IniciarSesionhtml.php"><?php echo $lang['eCancel']; ?></a>
						<br></br>
					</div>
				</div>


				
			</form>			
		</div>

		<?php
			$serverName = "127.0.0.1";
			$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
			$conn = sqlsrv_connect($serverName, $connectionInfo);
			unset($error);

		//This code runs if the form has been submitted
			if (isset($_POST['submit'])){
				// check for valid email address
				$email = $_POST['remail'];
				// $pattern = '/^[^@]+@[^srn';',@%]+$/';
				// if (!preg_match($pattern, trim($email))) {
				//   $error[] = 'Please enter a valid email address';
				// }

				// checks if the username is in use
				$sql = "SELECT Email FROM Employee WHERE Email = '$email'";
				$stmt = sqlsrv_query($conn, $sql);
					if ($stmt === false) {
						die(print_r( sqlsrv_errors(), true));
					}
				$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

				//if the name exists it gives an error
				if ($row == 0) {
					$error[] = $lang['secondmsm'];
				}


				// if no errors then carry on
				if (!$error) {
					$sql3 = "SELECT Username FROM Employee WHERE Email = '$email'";
					$stmt3 = sqlsrv_query($conn, $sql3);
					if ($stmt3 === false) {
						die(print_r( sqlsrv_errors(), true));
					}
					$row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC);

					//create a new random password


					$password = substr(md5(uniqid(rand(),1)),3,10);
					$pass = md5($password); //encrypted version for database entry

					//send email
					$body = "Hi $row3[Username], you or someone else have requested your account details. Here is your account information please keep this as you may need this at a later stage.\r\nUsername: $row3[Username]\r\nPassword: $password\r\nYour password has been reset please login and change your password to something more rememberable. Best Regards Site Admin";
					//$additionalheaders = "From: <user@domain.com>" . '\r\n' . "Reply-To: noprely@domain.com";
					//die($email  . ', ' . $body );
					

		    		ini_set("SMTP", "smtps.ece.uprm.edu");
		    		ini_set("sendmail_from", "$email");

		    		$message = "The mail message was sent with the following mail setting:\r\nSMTP = aspmx.l.google.com\r\nsmtp_port = 25\r\nsendmail_from = YourMail@address.com";

		    		$headers = "From: YOURMAIL@gmail.com";


		    		mail("$email", "Account Details Recovery", $body, $headers);
		    		//echo "Check your email now....<BR/>";


					//update database
					$sql2 = "UPDATE Employee SET UserPassword = '$pass' WHERE Email = '$email'";
					$stmt2 = sqlsrv_query($conn, $sql2);
					if ($stmt2 === false) {
						die(print_r( sqlsrv_errors(), true));
					}
					$row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
					$rsent = true;
				}// close errors
			}// close if form sent

			//show any errors
			if (!empty($error)) {
				$i = 0;
				while ($i < count($error)){
					echo "<div class='container'><p>".$error[$i]."</p></div>";
					$i ++;
				}
			}// close if empty errors

			if ($rsent == true){
				echo "<p>You have been sent an email with your account details to $email</p>";
			}
		?>
				
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
	</body>
</html>
