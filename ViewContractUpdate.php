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
  $comentario = $_POST['comentario'];


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
  $comentario = mysql_real_escape_string($comentario);

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
              $sql3 = "INSERT INTO CopyContract VALUES( '$_SESSION[docID]', GETDATE(), '$caseCopy')";
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
    $sql2 = "INSERT INTO ContractComments VALUES('$_SESSION[username]', '$_SESSION[docID]', GETDATE(), '$comentario')";
    $stmt2 = sqlsrv_query($conn, $sql2);
    $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
    if ($stmt2 === false) {
      echo "sexto if";
      die(print_r( sqlsrv_errors(), true));
    }
  }

  //redirect to the page secretaryPagehtml.php
  header("Location:secretaryPagehtml.php");

  //close the server connection
  sqlsrv_close($conn);
?>
