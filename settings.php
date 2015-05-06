<?php
	//This is to use the $_SESSION variables. This variables are to pass the values from page to page.
	session_start();

	//This function will clear the special characters from the variable.
	function mysql_escape_mimic($inp) {
		if(is_array($inp))
			return array_map(__METHOD__, $inp);
			if(!empty($inp) && is_string($inp)) {
				return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
			}
		return $inp;
	}

	/* Variables
		$Name = employee name
		$Initial = initial of the second name of the employee
		$lastName = last name of the employee
	*/
	$Job = $_POST['jobTitle'];
	$Role = $_POST['role'];
	$Forum = $_POST['forumName'];
	$Subcategory = $_POST['Subcategory'];

	/* Server
		$serverName = the name of the server to connect
		$connectionInfo = creates an array with the database name, the user id of the database and the user's password of the database
		$conn = sqlsrv_connect() = is the function to connect with the server
	*/
	$serverName = "127.0.0.1";
	$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
	$conn = sqlsrv_connect($serverName, $connectionInfo);

	//to verify if the connection with the server is successful
	if(!$conn) {
		die(print_r( sqlsrv_errors(), true));
	}

	//The function mysql_real_escape_string will clear the special characters from the variable.
	$Job = mysql_real_escape_string($Job);
	$Role = mysql_real_escape_string($Role);
	$Forum = mysql_real_escape_string($Forum);
	$Subcategory = mysql_real_escape_string($Subcategory);

	if ($Job <> '') {
		/* SQL
			$sql = query to insert the information of the employee in the database with the variable above
			$stmt = sqlsrv_query() = prepares and executes the query
			$row = sqlsrv_fetch_array() = returns the row as an array
		*/
		$sql = "INSERT INTO JobTitle VALUES('$Job', '$Role')";
		$stmt = sqlsrv_query($conn, $sql);
		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

		//Verifies if the query is executed successfully
		if ($stmt === false) {
			die($sql);
		}
	}

	if ($Forum <> '') {
		/* SQL
			$sql = query to insert the information of the employee in the database with the variable above
			$stmt = sqlsrv_query() = prepares and executes the query
			$row = sqlsrv_fetch_array() = returns the row as an array
		*/
		$sql = "INSERT INTO Forums VALUES('$Forum')";
		$stmt = sqlsrv_query($conn, $sql);
		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

		//Verifies if the query is executed successfully
		if ($stmt === false) {
			die($sql);
		}
	}

	if ($Subcategory <> '') {
		/* SQL
			$sql = query to insert the information of the employee in the database with the variable above
			$stmt = sqlsrv_query() = prepares and executes the query
			$row = sqlsrv_fetch_array() = returns the row as an array
		*/
		$sql = "INSERT INTO Subcategory VALUES('$Subcategory')";
		$stmt = sqlsrv_query($conn, $sql);
		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

		//Verifies if the query is executed successfully
		if ($stmt === false) {
			die($sql);
		}
	}
	//Redirect to the page employeeListhtml.php
	header("Location:employeeListhtml.php");

	//Close the server connection
	sqlsrv_close($conn);
?>
