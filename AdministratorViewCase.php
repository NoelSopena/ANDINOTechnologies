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

  //$newName = the name of the new employee that will be in charge of the document
  $newName = $_POST['caseReceiver'];
  if($newName <> ''){
    /* SQL
      $sql = query to insert the information of the employee who was in charge of the document and the employee who will be in charge of the same daocument
      $stmt = sqlsrv_query() = prepares and executes the query
      $row = sqlsrv_fetch_array() = returns the row as an array
      The function mysql_real_escape_string will clear the special characters from the variable.
    */
    $newName = mysql_real_escape_string($newName);
    $sql = "INSERT INTO History VALUES('$_SESSION[docID]', GETDATE(), '$_SESSION[oldName]', '$newName')";
    $stmt = sqlsrv_query($conn, $sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    //Verify if the query executed successfully
    if ($stmt === false) {
      echo "primer if";
      echo $sql;
      die(print_r( sqlsrv_errors(), true));
    }

    /* SQL
      $sql2 = query to change the employee who is managing a document
      $stmt2 = sqlsrv_query() = prepares and executes the query
      $row2 = sqlsrv_fetch_array() = returns the row as an array
      The function mysql_real_escape_string will clear the special characters from the variable.
    */
    $newName = mysql_real_escape_string($newName);
    $sql2 = "UPDATE Manage SET EmployeeName = '$newName' WHERE DocID = '$_SESSION[docID]'";
    $stmt2 = sqlsrv_query($conn, $sql2);
    $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);

    //to verify if the query executed successful
    if ($stmt2 === false) {
      echo "primer if";
      echo $sql2;
      die(print_r( sqlsrv_errors(), true));
    }
  }

  
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

  //redirect to the page adminPagehtml.php
  header("Location:adminPagehtml.php");

  //close the server connection
  sqlsrv_close($conn);
?>
