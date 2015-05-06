<?php
	include_once 'common.php';
	include 'library.php';

	$q = $_GET['q'];

	$serverName = "127.0.0.1";
	$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
	$conn = sqlsrv_connect($serverName, $connectionInfo);
	if(!$conn) {
		die(print_r( sqlsrv_errors(), true));
	}

	//mysqli_select_db($con,"ajax_demo");
	$sql="SELECT Username, Name, MiddleName, LastName, MaidenName FROM Employee, JobTitle WHERE Job = Title AND Job_Role = 'attorney' AND Office = '".$q."'";
	$stmt = sqlsrv_query($conn, $sql);
	if ($stmt === false) {
		die(print_r( sqlsrv_errors(), true));
	}

	echo "<select class='form-control' name='caseReceiver' required>";
	echo "<option value=''> $lang[case_receiver] </option>";
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		echo "<option value='" . $row['Username'] . "'>" . $row['Name'] . " " . $row['MiddleName'] . " " . $row['LastName'] . " " . $row['MaidenName'] . "</option>";
	} 
	echo "</select>";
?>
