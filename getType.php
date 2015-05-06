<?php
	include_once 'common.php';
	include 'library.php';

	$c = $_GET['c'];

	$serverName = "127.0.0.1";
	$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
	$conn = sqlsrv_connect($serverName, $connectionInfo);
	if(!$conn) {
		die(print_r( sqlsrv_errors(), true));
	}

	//mysqli_select_db($con,"ajax_demo");
	$sql="SELECT Servicio FROM Codigos WHERE Code = '".$c."'";
	$stmt = sqlsrv_query($conn, $sql);
	if ($stmt === false) {
		die(print_r( sqlsrv_errors(), true));
	}

	echo "<select class='form-control' name='type' required>";
	echo "<option value=''> Select Type of Services </option>";
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		echo "<option value='" . $row['Servicio'] . "'>" . $row['Servicio'] . "</option>";
	} 
	echo "</select>";
?>
