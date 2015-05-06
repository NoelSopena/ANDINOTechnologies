<?php
  //Resume the current session based on a session identifier passed via a POST request
  session_start();

  /* Server
    $serverName = the name of the server to connect
    $connectionInfo = creates an array with the database name, the user id of the database and the user's password of the database
    $conn = sqlsrv_connect() = is the function to connect with the server
  */
  $serverName = "127.0.0.1";
  $connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
  $conn = sqlsrv_connect($serverName, $connectionInfo);

  /*
    $userName and $userPassword variables contain the username and the password of the users signed in the log in page.
    These variables are used to verify if the input information are part of the system and identify the employee's identity (administrator, attorney
    or secretary).
  */
  $userName = $_POST['userName'];
  $userPassword = $_POST['userPassword'];

  $userName = mysql_real_escape_string($userName);
  $userPassword = mysql_real_escape_string($userPassword);

  //Cryptographic hash function to encrypt the password
  $cryptUserPass = md5($userPassword);

  //Verifies if the connection with the server is successful
  if(!$conn) {
    die(print_r( sqlsrv_errors(), true));
  }

  /* SQL
    $sql = query to extract the information of the employee in the database with the username and password inserted in the login page
    $stmt = sqlsrv_query() = prepares and executes the query
    $row = sqlsrv_fetch_array() = returns the row as an array
  */
  $sql = "SELECT Username, Name, MiddleName, LastName, MaidenName, Office, Job_Role FROM Employee, JobTitle WHERE Username = '$userName' AND UserPassword = '$cryptUserPass' AND Title = Job";
  $stmt = sqlsrv_query($conn, $sql);
  $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

  /* Variables
    $_SESSION[] = to pass the value of the variables to the other pages
    username = the username of the employee who are logging the system
    name = the name of the employee
    initial = initial of the second name of the employee
    last = last name of the employee
    maiden = maiden name of the employee
    office = the regional office where the user work
    job = position of the employee in the system
  */
  $_SESSION['username'] = $row['Username'];
  $_SESSION['name'] = $row['Name'];
  $_SESSION['initial'] = $row['MiddleName'];
  $_SESSION['last'] = $row['LastName'];
  $_SESSION['maiden'] = $row['MaidenName'];
  $_SESSION['office'] = $row['Office'];
  $_SESSION['job'] = $row['Job_Role'];

  /*
    The following code will redirect the user depending on its function. If the logged user is a secretary it will redirect the user to
    the secretary's main page. If the first condition is false, then it will verify if the logged user is an attorney. If the condition is true,
    it will redirect the user to the attorney's main page. If the user is an administator then it redirects to the administrator's main page.
    If none of the conditions are met then it returns nothing. 
  */
  if ($stmt === false) {
    die(print_r( sqlsrv_errors(), true));
  }
  elseif ($row['Job_Role'] == "secretary") {
 //   echo "Login OK";
    header("Location:secretaryPagehtml.php");
  }
  elseif ($row['Job_Role'] == "attorney") {
 //   echo "Login OK";
    header("Location:attorneyPagehtml.php");
  }
  elseif ($row['Job_Role'] == "admin") {
  //  echo "Login OK";
    header("Location:adminPagehtml.php");
  }
  else {
    header("Location:IniciarSesionhtml.php?e=error");
  }

  //Close the server connection
  sqlsrv_close($conn);
?>
