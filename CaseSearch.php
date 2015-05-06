<?php
  //This is to use the $_SESSION variables. This variables are to pass the values from page to page.
  session_start();

  /* Server
    $serverName = the name of the server to connect
    $connectionInfo = creates an array with the database name, the user id of the database and the user's password of the database
    $conn = sqlsrv_connect() = is the function to connect with the server
  */
  $serverName = "127.0.0.1";
  $connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
  $conn = sqlsrv_connect($serverName, $connectionInfo);

  //$job = keep the position of the employee who is using the page
  $job = "$_SESSION[job]";

  //to verify if the connection with the server is successful
  if(!$conn) {
    die(print_r( sqlsrv_errors(), true));
  }

  //if the employee is a secretary go to the secretaryPagehtml.php
  if ($job == "secretary") {
    header("Location:secretaryPagehtml.php");
  }
  //if the employee is a attorney go to the attorneyPagehtml.php
  elseif ($job == "attorney") {
    header("Location:attorneyPagehtml.php");
  }
  //if the employee is the administrator go to the adminPagehtml.php
  elseif ($job == "admin") {
    header("Location:adminPagehtml.php");
  }

  //close the server connection
  sqlsrv_close($conn);
?>
