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

  //to verify if the connection with the server is successful
  if(!$conn) {
    die(print_r( sqlsrv_errors(), true));
  }

  /* Variables
    $dateGrantd = fecha en que se consedio el contrato
    $dateFrom = fecha en la cual empieza el contrato
    $dateTo = fecha en la que finaliza el contrato
    $contractNum = numero del contrato
    $contractorName = nombre del contratista
    $category = categoria del servicio del contrato
    $type = tipo de servicio del contrato
    $quantity = cantidad por la cual se va a hacer el contrato
    $status = estado del contrato
    $description = descripcion del contrato
  */
  $dateGrantd = $_POST['dateGrantd'];
  $dateFrom = $_POST['dateFrom'];
  $dateTo = $_POST['dateTo'];
  $contractNum = $_POST['contractNum'];
  $contractorName = $_POST['contractorName'];
  $category = $_POST['category'];
  $type = $_POST['type'];
  $quantity = $_POST['quantity'];
  $status = $_POST['status'];
  $description = $_POST['description'];
  $Receiver = $_POST['caseReceiver'];
  $fondo  = $_POST['fondo'];
  $contractComment = $_POST['contractComment'];


  // The function mysql_real_escape_string will clear the special characters from the variable.
  $dateGrantd = mysql_real_escape_string($dateGrantd);
  $dateFrom = mysql_real_escape_string($dateFrom);
  $dateTo = mysql_real_escape_string($dateTo);
  $contractNum = mysql_real_escape_string($contractNum);
  $contractorName = mysql_real_escape_string($contractorName);
  $category = mysql_real_escape_string($category);
  $type = mysql_real_escape_string($type);
  $quantity = mysql_real_escape_string($quantity);
  $status = mysql_real_escape_string($status);
  $description = mysql_real_escape_string($description);
  $Receiver = mysql_real_escape_string($Receiver);
  $fondo = mysql_real_escape_string($fondo);
  $contractComment = mysql_real_escape_string($contractComment);

  $sql2 = "SELECT Categoria FROM Categoria WHERE Codigo = '$category'";
  $stmt2 = sqlsrv_query($conn, $sql2);
  $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);

  if(isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // File properties
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    // Work out the file extension
    $file_ext = explode('.', $file_name);
    $file_ext = strtolower(end($file_ext));

    $allowed = array('txt', 'pdf');

    if(in_array($file_ext, $allowed)) {
      if($file_error === 0) {
        if($file_size <= 5000000) {
          $file_name_new = uniqid('', true) . '.' . $file_ext;
          $file_destination = 'copies\\' . $file_name_new;

          if(move_uploaded_file($file_tmp, $file_destination)) {
            $caseCopy = $file_destination;
          }
        }
      }
     }
    else {
      $caseCopy = "copies\\";
    }
  }

  /* SQL
    $sql = query to insert the information of the document in the database with the variable above
    $stmt = sqlsrv_query() = prepares and executes the query
    $row = sqlsrv_fetch_array() = returns the row as an array
  */
  $sql = "INSERT INTO Contracts VALUES('$contractNum', '$dateGrantd', CASE WHEN '$dateFrom' = '' THEN NULL ELSE '$dateFrom' END,
          CASE WHEN '$dateTo' = '' THEN NULL ELSE '$dateTo' END, '$contractorName', '$category', '$type', '$quantity', '$fondo', '$description', '$status')";
  $stmt = sqlsrv_query($conn, $sql);
  $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

  //Verify if the query executed successfully
  if ($stmt === false) {
    header("Location:contratohtml.php?e=error");
  }

  /* SQL
    $sql5 = query to insert the document number added to the system and the username from the employee who will be in charge of the document
    $stmt5 = sqlsrv_query() = prepares and executes the query
    $row5 = sqlsrv_fetch_array() = returns the row as an array
    The function mysql_real_escape_string will clear the special characters from the variable.
  */
  $sql5 = "INSERT INTO ManageContract VALUES('$contractNum', '$Receiver')";
  $stmt5 = sqlsrv_query($conn, $sql5);
  $row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC);

  //Verify if the query executed successfully
  if ($stmt5 === false) {
    echo "quinto if";
    echo $sql5;
    die(print_r( sqlsrv_errors(), true));
  }

  //if there are some comment of the document execute the query inside the if
  if ($caseComment <> "") {
    /* SQL
      $sql4 = query to insert the comments to the document from the employee who add the comment and the date when is added
      $stmt4 = sqlsrv_query() = prepares and executes the query
      $row4 = sqlsrv_fetch_array() = returns the row as an array
      The function mysql_real_escape_string will clear the special characters from the variable.
    */
    $caseNumber = mysql_real_escape_string($caseNumber);
    $caseComment = mysql_real_escape_string($caseComment);
    $sql4 = "INSERT INTO ContractComments VALUES('$_SESSION[username]', '$contractNumber', GETDATE(), '$contractComment')";
    $stmt4 = sqlsrv_query($conn, $sql4);
    $row4 = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC);

    //to verify if the query executed successful
    if ($stmt4 === false) {
      echo "cuarto if";
      die(print_r( sqlsrv_errors(), true));
    }
  }

  //redirect to the page secretaryPagehtml.php
  header("Location:secretaryPagehtml.php");

  //close the server connection
  sqlsrv_close($conn);
?>
