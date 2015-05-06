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
    $dateDocument = date of the document when it is made
    $dateReceived = date when the document is received in the office
    $dateDue = deadline of the document
    $caseNumber = number id of the document
    $caseAppellant = appellant of the case in the document
    $caseReceiver = the attorney of the office that will work with the case
    $caseRegion = office where the document will be process
    $caseSender = attorney who defend the appellant of the case
    $caseSubject = brief description of the case
    $caseComment = comments added by the secretary or the attorney of the office
    $documentType = type of document
    $department = department where the document comes
    $documentSubcategory = specific type of document
    $caseCopy = digital copy scanned of the document
  */
  $dateDocument = $_POST['dateDocument'];
  $dateReceived = $_POST['dateReceived'];
  $dateDue = $_POST['dateDue'];
  $caseNumber = $_POST['caseNumber'];
  $caseAppellant = $_POST['caseAppellant'];
  $caseReceiver = $_POST['caseReceiver'];
  $caseRegion = $_POST['caseRegion'];
  $caseSender = $_POST['caseSender'];
  $caseSubject = $_POST['caseSubject'];
  $caseComment = $_POST['caseComment'];
  $documentType = $_POST['documentType'];
  $department = $_POST['department'];
  $documentSubcategory = $_POST['documentSubcategory'];
  $quantity = $_POST['quantity'];

  // The function mysql_real_escape_string will clear the special characters from the variable.
  $caseNumber = mysql_real_escape_string($caseNumber);
  $dateReceived = mysql_real_escape_string($dateReceived);
  $dateDocument = mysql_real_escape_string($dateDocument);
  $dateDue = mysql_real_escape_string($dateDue);
  $caseAppellant = mysql_real_escape_string($caseAppellant);
  $caseSender = mysql_real_escape_string($caseSender);
  $department = mysql_real_escape_string($department);
  $documentType = mysql_real_escape_string($documentType);
  $documentSubcategory = mysql_real_escape_string($documentSubcategory);
  $caseSubject = mysql_real_escape_string($caseSubject);
  $quantity = mysql_real_escape_string($quantity);
  $causales = explode(", ", $documentSubcategory);

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
            $Copy = $file_destination.'-';

            $key = 'diego';
            //$plain_text = 'very important data';

            /* Open module, and create IV */
            $td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
            $key = substr($key, 0, mcrypt_enc_get_key_size($td));
            $iv_size = mcrypt_enc_get_iv_size($td);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

            /* Initialize encryption handle */
            if (mcrypt_generic_init($td, $key, $iv) != -1) {
              /* Encrypt data */
              $caseCopy = mcrypt_generic($td, $Copy);
              mcrypt_generic_deinit($td);

              /* Clean up */
              mcrypt_generic_deinit($td);
              mcrypt_module_close($td);
            }
            /* SQL
                $sql = query to update the information of the case in the database with the variables above
                $stmt = sqlsrv_query() = prepares and executes the query
                $row = sqlsrv_fetch_array() = returns the row as an array
              */
              $sql3 = "INSERT INTO Copy VALUES( '$_SESSION[docID]', GETDATE(), '$caseCopy')";
              $stmt3 = sqlsrv_query($conn, $sql3);
              $row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC);

              if ($stmt3 === false) {
                echo "tercer if";
                echo $sql3;
                die(print_r( sqlsrv_errors(), true));
              }
          }
          else {
                $caseCopy = "copies\\";
              }
          }
      }
    }
    
  }
  

  /* SQL
    $sql = query to insert the information of the document in the database with the variable above
    $stmt = sqlsrv_query() = prepares and executes the query
    $row = sqlsrv_fetch_array() = returns the row as an array
  */
    //die($caseCopy);
  $sql = "INSERT INTO Documents VALUES('$caseNumber', '$dateReceived', CASE WHEN '$dateDocument' = '' THEN NULL ELSE '$dateDocument' END,
          CASE WHEN '$dateDue' = '' THEN DATEADD(day,30,GETDATE()) ELSE '$dateDue' END, '$caseAppellant', '$caseSender', '$department', '$documentType',
          '$caseSubject', DEFAULT)";
  $stmt = sqlsrv_query($conn, $sql);
  $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

  //Verify if the query executed successfully
  if ($stmt === false) {
    echo "primer if";
   
    echo $sql;
   die(print_r( sqlsrv_errors(), true));
    header("Location:anadirCasohtml.php?e=error");
  }

  /* SQL
    $sql3 = query to insert the document number added to the system and the username from the employee who added the document
    $stmt3 = sqlsrv_query() = prepares and executes the query
    $row3 = sqlsrv_fetch_array() = returns the row as an array
    The function mysql_real_escape_string will clear the special characters from the variable.
  */
  $caseNumber = mysql_real_escape_string($caseNumber);
  $sql3 = "INSERT INTO AddDoc VALUES('$caseNumber', '$_SESSION[username]')";
  $stmt3 = sqlsrv_query($conn, $sql3);
  $row3 = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

  //to verify if the query executed successful
  if ($stmt3 === false) {
    echo "tercer if";
    echo $sql3;
    die(print_r( sqlsrv_errors(), true));
  }

  /* SQL
    $sql5 = query to insert the document number added to the system and the username from the employee who will be in charge of the document
    $stmt5 = sqlsrv_query() = prepares and executes the query
    $row5 = sqlsrv_fetch_array() = returns the row as an array
    The function mysql_real_escape_string will clear the special characters from the variable.
  */
  $caseNumber = mysql_real_escape_string($caseNumber);
  $caseReceiver = mysql_real_escape_string($caseReceiver);
  $sql5 = "INSERT INTO Manage VALUES('$caseNumber', '$caseReceiver')";
  $stmt5 = sqlsrv_query($conn, $sql5);
  $row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC);

  //Verify if the query executed successfully
  if ($stmt5 === false) {
    echo "quinto if";
    echo $sql5;
    die(print_r( sqlsrv_errors(), true));
  }

  //if the document is a lawsuit execute the query inside the if
  if($documentType == 'Demanda'){
    /* SQL
      $sql2 = query to insert the document number and the monetary amount of the lawsuit
      $stmt2 = sqlsrv_query() = prepares and executes the query
      $row2 = sqlsrv_fetch_array() = returns the row as an array
      The function mysql_real_escape_string will clear the special characters from the variable.
    */
    $caseNumber = mysql_real_escape_string($caseNumber);
    foreach ($causales as $key) {
      if ($key <> '') {
        $sql2 = "INSERT INTO Lawsuit VALUES ('$caseNumber', '$key', '$quantity')";
        $stmt2 = sqlsrv_query($conn, $sql2);
        $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);

        //Verify if the query executed successfully
        if ($stmt2 === false) {
          echo "seugndo if";
          die(print_r( sqlsrv_errors(), true));
        }
      }
    }
  }
  else{
    foreach ($causales as $key) {
      if ($key <> '') {
        $sql2 = "INSERT INTO Others VALUES ('$caseNumber', '$key', '$documentType')";
        $stmt2 = sqlsrv_query($conn, $sql2);
        $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);

        //Verify if the query executed successfully
        if ($stmt2 === false) {
          echo "seugndo if";
          die(print_r( sqlsrv_errors(), true));
        }
      }
    }
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
    $sql4 = "INSERT INTO Comments VALUES('$_SESSION[username]', '$caseNumber', GETDATE(), '$caseComment')";
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
