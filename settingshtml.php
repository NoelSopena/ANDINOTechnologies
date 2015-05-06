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
		<title> Añadir Otras Cosas jaja!!! </title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/ANDINOstyleSheet.css">
		<meta charset="UTF-8">

		<!-- This is to only permit the characters that we allow to input to the system -->
		<script type="text/JavaScript">
			function valid(f) {
				!(/^[A-z&#209;&#241;0-9; ;.;,;-]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z&#209;&#241;0-9; ;.;,;-]/ig,''):null;
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
			<h1>Settings</h1>

			<br></br>
			<form class="form-horizontal" role="form" id="settings" action="settings.php" method="post">
				<div class="form-group">
					<fieldset class="col-md-5">
						<legend>Job Title</legend>
						<input type="text" class="form-control" id="jobTitle" name="jobTitle" placeholder="Job Title" onkeyup="valid(this)" onblur="valid(this)" required>
						<p></p>
						<select class="form-control" id="role" name="role" required>
							<option value="">Select Role of the job</option>
							<option value="secretary">Add documents</option>
							<option value="attorney">Manage documents</option>
						</select>
					</fieldset>
				</div>
				<button class="btn btn-primary col-md-offset-5" type="submit" form="settings"><?php print $lang['eSubmit'];?></button>
			</form>
			<br></br>
			<form class="form-horizontal" role="form" id="settings2" action="settings.php" method="post">
				<div class="form-group">
					<fieldset class="col-md-5">
						<legend>Forums</legend>
						<input type="text" class="form-control" id="forumName" name="forumName" placeholder="Forum's Name" onkeyup="valid(this)" onblur="valid(this)" required>
					</fieldset>
				</div>
				<button class="btn btn-primary col-md-offset-5" type="submit" form="settings2"><?php print $lang['eSubmit'];?></button>
			</form>
			<br></br>
			<form class="form-horizontal" role="form" id="settings3" action="settings.php" method="post">
				<div class="form-group">
					<fieldset class="col-md-5">
						<legend>Subcategory</legend>
						<input type="text" class="form-control" id="Subcategory" name="Subcategory" placeholder="Subcategory" onkeyup="valid(this)" onblur="valid(this)" required>
					</fieldset>
				</div>
				<button class="btn btn-primary col-md-offset-5" type="submit" form="settings3"><?php print $lang['eSubmit'];?></button>
			</form>
			<br></br>
			<!-- This button is for canceling everything and returning to the lawyer page -->
			<a class="btn btn-primary col-md-offset-5" href="adminPagehtml.php"><?php echo $lang['eCancel']; ?></a>
		</div>
	</body>
</html>
