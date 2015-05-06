<?php
  //This is to use the $_SESSION variables. This variables are to pass the values from page to page.
  session_start();
  /* Variables
    $oldName = the current username of the employee in the system
    $newName = the new username of the employee in the system
    $oldPassword = the current password of the emplyee in the system
    $newPassword = the new password of the employee in the system
  */
  $oldName = $_POST['oldName'];
  $newName = $_POST['newName'];
  $oldPassword = $_POST['oldPassword'];
  $newPassword = $_POST['newPassword'];
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
    die( print_r( sqlsrv_errors(), true));
  }
  /* SQL
    $sql = query to insert the information of the employee in the database with the variable above
    $stmt = sqlsrv_query() = prepares and executes the query
    $row = sqlsrv_fetch_array() = returns the row as an array
    The function mysql_real_escape_string will clear the special characters from the variable.
  */
  $newUsername = mysql_real_escape_string($newName);
  $newPassword = mysql_real_escape_string($newPassword);
  $oldUsername = mysql_real_escape_string($oldName);
  $newCrypt = md5($newPassword);
  $oldCrypt = md5($oldPassword);

  $sql3 = "SELECT Username FROM Employee WHERE Username = '$oldUsername' and UserPassword = '$oldCrypt'";
  $stmt3 = sqlsrv_query($conn, $sql3);
  $row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC);

//  die($row3['Username'] == '');
  if ($row3['Username'] == '') {
    header("Location:changehtml.php?n=error");
    die('Esta mal o el username o el password :P');
  }

  if ($newUsername == '' && $newPassword != '') {
    $sql = "UPDATE Employee SET UserPassword = '$newCrypt' WHERE Username = '$oldUsername' AND UserPassword = '$oldCrypt'";
  } else if ($newUsername != '' && $newPassword == '') {
    $sql = "UPDATE Employee SET Username = '$newUsername' WHERE Username = '$oldUsername'";
  } else if ($newUsername != '' && $newPassword != '') {
    $sql = "UPDATE Employee SET Username = '$newUsername', UserPassword = '$newCrypt' WHERE Username = '$oldUsername' AND UserPassword = '$oldCrypt'";
  } else {
    header("Location:changehtml.php?e=error");
    die(print_r( sqlsrv_errors(), true));
  }
  $stmt = sqlsrv_query($conn, $sql);
  $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
  //Verify if the query executed successfully
  
  if ($stmt === false) {
    echo $sql;
    
    header("Location:changehtml.php?i=error");
    die('Username already exists.');
  }
  else {
    /* SQL
      $sql2 = query to insert the information of the employee in the database with the variable above
      $stmt2 = sqlsrv_query() = prepares and executes the query
      $row2 = sqlsrv_fetch_array() = returns the row as an array
      The function mysql_real_escape_string will clear the special characters from the variable.
    */
    //$newName = mysql_real_escape_string($newName);
    //$newPassword = mysql_real_escape_string($newPassword);
    $sql2 = "SELECT Username, Name, MiddleName, LastName, MaidenName, Job FROM Employee WHERE Username = '$newUsername' and UserPassword = '$newCrypt'";
    $stmt2 = sqlsrv_query($conn, $sql2);
    $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
    if ($stmt === false) {
      echo $sql2;
      die(print_r( sqlsrv_errors(), true));
    }
    /* Variables
      $_SESSION[] = to pass the variables through the pages
    */
    //die($row2 == '');
    $_SESSION['username'] = $row2['Username'];
    $_SESSION['name'] = $row2['Name'];
    $_SESSION['initial'] = $row2['MiddleName'];
    $_SESSION['last'] = $row2['LastName'];
    $_SESSION['maiden'] = $row2['MaidenName'];

    $sql3 = "SELECT Job_Role FROM JobTitle WHERE Title = '$row2[Job]'";
    $stmt3 = sqlsrv_query($conn, $sql3);
    $row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC);
    
    $_SESSION['job'] = $row3['Job_Role'];
    //Verify if the query executed successfully
    if ($stmt3 === false) {
      die(print_r( sqlsrv_errors(), true));
    }
    //if the employee is a secretary go to the secretaryPagehtml.php
/*    
    elseif ($row3['Job_Role'] == "secretary") {
      echo "Login OK";
      header("Location:secretaryPagehtml.php");
    }
    //if the employee is a attorney go to the attorneyPagehtml.php
    elseif ($row3['Job_Role'] == "attorney") {
      echo "Login OK";
      header("Location:attorneyPagehtml.php");
    }
    //if the employee is the administrator go to the adminPagehtml.php
    elseif ($row3['Job_Role'] == "admin") {
      echo "Login OK";
      header("Location:adminPagehtml.php");
    }
*/
    else {
      header("Location:IniciarSesionhtml.php");
    }
  }
  
  //close the server connection
  sqlsrv_close($conn);
?>
