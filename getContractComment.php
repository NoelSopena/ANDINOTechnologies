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
	if ($q == '') {
		echo 'Comments';
	}
	//mysqli_select_db($con,"ajax_demo");
	$sql="SELECT Notes FROM ContractComments WHERE DocId = '$_SESSION[docID]' AND NoteDate = '".$q."'";
	$stmt = sqlsrv_query($conn, $sql);
	if ($stmt === false) {
		die(print_r( sqlsrv_errors(), true));
	}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		echo $row['Notes'];
	}
?>
