<!-- This page is to logout the user, remove the value of the sessions variables -->
<?php
	session_start();
	session_unset();
	header("Location:IniciarSesionhtml.php");
?>
