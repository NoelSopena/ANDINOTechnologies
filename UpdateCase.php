<?php
  session_start();

  /* Server
    $serverName = the name of the server to connect
    $connectionInfo = creates an array with the database name, the user id of the database and the user's password of the database
    $conn = sqlsrv_connect() = is the function to connect with the server
  */
	$serverName = "127.0.0.1";
	$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
	$conn = sqlsrv_connect($serverName, $connectionInfo);

  //Verify if the connection with the server is successful
  if(!$conn) {
    die(print_r( sqlsrv_errors(), true));
  }

  /* Variables
    $documentoEstado - status of the document (processed or non processed)
    $comentario - comments made about the document
  */
  $documentoEstado = $_POST['Estado'];
  $comentario = $_POST['caseCommentEdit'];

  $sql5 = "SELECT EmployeeName FROM Manage WHERE DocId = '$_SESSION[docID]'";
  $stmt5 = sqlsrv_query($conn, $sql5);
  $row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC);
  if($row5['EmployeeName'] == $_SESSION['username']){ 
    //The function mysql_real_escape_string will clear the special characters from the variable.
    if(!is_null($documentoEstado)){
      $documentoEstado = mysql_real_escape_string($documentoEstado); 
      /* SQL
        $sql = query that modifies the information of the document in the database with the variables above
        $stmt = sqlsrv_query() = prepares and executes the query
        $row = sqlsrv_fetch_array() = returns the row as an array
      */      
      $sql = "UPDATE Documents SET DocStatus = '$documentoEstado' WHERE DocNumber ='$_SESSION[docID]'";
      $stmt = sqlsrv_query($conn, $sql);
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

      if ($stmt === false) {
        echo "primer if";
        echo $sql;
        die(print_r( sqlsrv_errors(), true));
      }
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

              $CopiaDe = $file_destination.'-';

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
                $caseCopy = mcrypt_generic($td, $CopiaDe);
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
              echo "Error uploading";
            }
          }
        }
      }
    }
  
  if ($comentario <> "") {
    /* SQL
      $sql2 = query to insert the comments to the document from the employee who add the comment and the date when is added
      $stmt2 = sqlsrv_query() = prepares and executes the query
      $row2 = sqlsrv_fetch_array() = returns the row as an array
      The function mysql_real_escape_string will clear the special characters from the variable.
    */
    $comentario = mysql_real_escape_string($comentario);
    $sql2 = "INSERT INTO Comments VALUES('$_SESSION[username]', '$_SESSION[docID]', GETDATE(), '$comentario')";
    $stmt2 = sqlsrv_query($conn, $sql2);
    $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
    if ($stmt2 === false) {
      echo "cuarto if";
      die(print_r( sqlsrv_errors(), true));
    }
  }

  //Redirect to the page attorneyPagehtml.php
  header("Location:attorneyPagehtml.php");

  //Close the server connection
  sqlsrv_close($conn);
?>
