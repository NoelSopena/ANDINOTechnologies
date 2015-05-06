<?php
  session_start();

  //Includes the libraries to change the language of the page (english/spanish) and the navigational bar
  include_once 'common.php';
  include 'library.php';

  if($_SESSION[job] == 'secretary'){
    header("Location:secretaryPagehtml.php?p=error");
  }
  if($_SESSION[job] == 'admin' || $_SESSION[job] == 'attorney'){
  }
  else{
    die("You are not allow in this page. :P");
  }

  /* Server
    $serverName = the name of the server to connect
    $connectionInfo = creates an array with the database name, the user id of the database and the user's password of the database
    $conn = sqlsrv_connect() = is the function to connect with the server
  */
	$serverName = "127.0.0.1";
	$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
	$conn = sqlsrv_connect($serverName, $connectionInfo);

  if(!$conn) {
    die( print_r( sqlsrv_errors(), true));
  }

  /* Variables
    $documentType - the document type
    $documentSubcategory - the document subcategory
    $amountFrom - amount or financial quantity
    $amountTo - amount or financial quantity
    $initialDate - initial date (from when)
    $endDate - end date (until when)
  */
  $documentType = $_POST['documentType'];
  $documentSubcategory = $_POST['documentSubcategory'];
  $amountFrom = $_POST['amountFrom'];
  $amountTo = $_POST['amountTo'];
  $initialDate = $_POST['initialDate'];
  $endDate = $_POST['endDate'];


  //The function mysql_real_escape_string will clear the special characters from the variable.
  $documentType = mysql_real_escape_string($documentType);
  $initialDate = mysql_real_escape_string($initialDate);
  $endDate = mysql_real_escape_string($endDate);
  $documentSubcategory = mysql_real_escape_string($documentSubcategory);

  if ($documentType == 'Demanda') {
    if ($documentSubcategory == '' && $amountFrom == '' && $amountTo == '') {
      /*SQL
        $sql - query to fetch the quantity of such document in the database.
        $stmt = sqlsrv_query() = prepares and executes the query
        $row = sqlsrv_fetch_array() = returns the row as an array
      */
      $sql = "SELECT COUNT(DISTINCT(LawsuitID)) AS num FROM Lawsuit, Documents
              WHERE DocNumber = LawsuitID AND EntryDate BETWEEN '$initialDate' AND '$endDate'";
    }
    elseif ($documentSubcategory == '') {
      /*SQL
        $sql - query to fetch the quantity of such document in the database.
        $stmt = sqlsrv_query() = prepares and executes the query
        $row = sqlsrv_fetch_array() = returns the row as an array
      */
      $sql = "SELECT COUNT(DISTINCT(LawsuitID)) AS num FROM Lawsuit, Documents
              WHERE DocNumber = LawsuitID AND EntryDate BETWEEN '$initialDate' AND '$endDate'
              AND Amount BETWEEN '$amountFrom' AND '$amountTo'";
      
    }
    elseif ($amountFrom == '' && $amountTo == '') {
      /*SQL
        $sql - query to fetch the quantity of such document in the database.
        $stmt = sqlsrv_query() = prepares and executes the query
        $row = sqlsrv_fetch_array() = returns the row as an array
      */
      $sql = "SELECT COUNT(DISTINCT(LawsuitID)) AS num FROM Lawsuit, Documents
              WHERE DocNumber = LawsuitID AND Causales = '$documentSubcategory' AND EntryDate BETWEEN '$initialDate' AND '$endDate'";
    }
    else {
      /*SQL
        $sql - query to fetch the quantity of such document in the database.
        $stmt = sqlsrv_query() = prepares and executes the query
        $row = sqlsrv_fetch_array() = returns the row as an array
      */
      $sql = "SELECT COUNT(DISTINCT(LawsuitID)) AS num FROM Lawsuit, Documents
              WHERE DocNumber = LawsuitID AND Causales = '$documentSubcategory' AND EntryDate BETWEEN '$initialDate' AND '$endDate'
              AND Amount BETWEEN '$amountFrom' AND '$amountTo'";
    }
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
      echo $sql;
      die(print_r( sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $cantidad = $row['num'];
  }
  elseif($documentType == 'Contrato'){
    if($documentSubcategory == ''){
      $sql = "SELECT COUNT(ContractNumber) AS num FROM Contracts WHERE DateGranted  BETWEEN '$initialDate' AND '$endDate'";
      $stmt = sqlsrv_query($conn, $sql);
      if ($stmt === false) {
        echo $sql;
        die(print_r( sqlsrv_errors(), true));
      }
    }
    else{
      $sql = "SELECT COUNT(ContractNumber) AS num FROM Contracts WHERE ServiceCategory = '$documentSubcategory' AND  DateGranted  BETWEEN '$initialDate' AND '$endDate'";
      $stmt = sqlsrv_query($conn, $sql);
      if ($stmt === false) {
        echo $sql;
        die(print_r( sqlsrv_errors(), true));
      }
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $cantidad = $row['num'];
  }
  else {
    if ($documentSubcategory == '') {
      /*SQL
        $sql - query to fetch the quantity of such document in the database.
        $stmt = sqlsrv_query() = prepares and executes the query
        $row = sqlsrv_fetch_array() = returns the row as an array
      */
      $sql = "SELECT COUNT(DocNumber) AS num FROM Documents
              WHERE DocType = '$documentType' AND EntryDate  BETWEEN '$initialDate' AND '$endDate'";
    }
    else {
      /*SQL
        $sql - query to fetch the quantity of such document in the database.
        $stmt = sqlsrv_query() = prepares and executes the query
        $row = sqlsrv_fetch_array() = returns the row as an array
      */
      $sql = "SELECT COUNT(DocNumber) AS num FROM Documents
              WHERE DocType = '$documentType' AND DocSubcategory = '$documentSubcategory' AND EntryDate  BETWEEN '$initialDate' AND '$endDate'";
    }
    $stmt = sqlsrv_query($conn, $sql);
      if ($stmt === false) {
        echo $sql;
        die(print_r( sqlsrv_errors(), true));
      }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $cantidad = $row['num'];
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- This is the name of the page -->
    <title> Generar Estadísticas</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/ANDINOstyleSheet.css">
    <!-- This is to only permit the characters that we allow to input to the system -->
    <script type="text/JavaScript">
      function valid(f) {
        !(/^[A-z;0-9; ;.;-]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z;0-9; ;.;-]/ig,''):null;
      } 
    </script>
  </head>

  <body>
    <?php 
      /*
        This function displays the information in the navigation bar. It includes the system's header, the
        language selection dropdown and logout buttons.
      */
      navbarLogout($lang['logout']);
    ?>

    <div class="container">
      <!-- This is the name in the header of the page -->
      <h1> <?php print " $lang[stats]"; ?> </h1> 

      <br></br>
      <h2> <?php echo "$lang[documentAmount] : $cantidad"; ?> </h2>

      <br></br>
      <div style='width:1200px;'>
      <table>
        <thead>
          <tr>
            <th class="col-md-3"> <?php echo $lang['case_num']; ?></th>
            <th class="col-md-3"><?php echo $lang['docType']; ?> </th>
            <th class="col-md-3"  style="padding-right:0px" width="10px"><?php echo $lang['date_received']; ?></th>
          </tr>
        </thead>
      </table>
    </div>
      <!-- This is the table to present all the cases that are closer to the deadline -->
      <form method="post">
        <div style='width:1200px;'>
          <table>
            <tbody>
              <?php
                if ($documentType == 'Demanda') {
                  if ($documentSubcategory == '' && $amountFrom == '' && $amountTo == '') {
                    $sql2 = "SELECT DISTINCT(DocNumber), DocType, CONVERT(VARCHAR(11),EntryDate,106) AS fecha, DocStatus FROM Documents, Lawsuit
                             WHERE DocNumber = LawsuitID AND EntryDate BETWEEN '$initialDate' AND '$endDate'";
                  }
                  elseif ($documentSubcategory == '') {
                    $sql2 = "SELECT DISTINCT(DocNumber), DocType, CONVERT(VARCHAR(11),EntryDate,106) AS fecha, DocStatus FROM Documents, Lawsuit
                             WHERE DocNumber = LawsuitID AND EntryDate BETWEEN '$initialDate' AND '$endDate'
                             AND Amount BETWEEN '$amountFrom' AND '$amountTo'";
                  }
                  elseif ($amountFrom == '' && $amountTo == '') {
                    $sql2 = "SELECT DISTINCT(DocNumber), DocType, CONVERT(VARCHAR(11),EntryDate,106) AS fecha, DocStatus FROM Documents, Lawsuit
                             WHERE DocNumber = LawsuitID AND Causales = '$documentSubcategory' AND EntryDate BETWEEN '$initialDate' AND '$endDate'";
                  }
                  else {
                    $sql2 = "SELECT DISTINCT(DocNumber), DocType, CONVERT(VARCHAR(11),EntryDate,106) AS fecha, DocStatus FROM Documents, Lawsuit
                             WHERE DocNumber = LawsuitID AND Causales = '$documentSubcategory' AND EntryDate BETWEEN '$initialDate' AND '$endDate'
                             AND Amount BETWEEN '$amountFrom' AND '$amountTo'";
                  }
                  $stmt2 = sqlsrv_query($conn, $sql2);
                }
                elseif($documentType == 'Contrato'){
                  if($documentSubcategory == ''){
                    $sql2 = "SELECT ContractNumber AS DocNumber, ServiceCategory AS DocType, CONVERT(VARCHAR(11),DateGranted,106) AS fecha FROM Contracts WHERE DateGranted  BETWEEN '$initialDate' AND '$endDate' ";
                  $stmt2 = sqlsrv_query($conn, $sql2);
                  }
                  else{
                    $sql2 = "SELECT ContractNumber AS DocNumber, ServiceCategory AS DocType, CONVERT(VARCHAR(11),DateGranted,106) AS fecha FROM Contracts WHERE ServiceCategory = '$documentSubcategory' AND DateGranted  BETWEEN '$initialDate' AND '$endDate' ";
                    $stmt2 = sqlsrv_query($conn, $sql2);
                  }
                }
                else {
                  if ($documentSubcategory == '') {
                    $sql2 = "SELECT DocNumber, DocType, CONVERT(VARCHAR(11),EntryDate,106) AS fecha, DocStatus FROM Documents
                             WHERE DocType = '$documentType' AND EntryDate  BETWEEN '$initialDate' AND '$endDate'";
                  }
                  else {
                    $sql2 = "SELECT DocNumber, DocType, CONVERT(VARCHAR(11),EntryDate,106) AS fecha, DocStatus FROM Documents
                             WHERE DocType = '$documentType' AND DocSubcategory = '$documentSubcategory' AND EntryDate  BETWEEN '$initialDate'
                             AND '$endDate'";
                  }
                  $stmt2 = sqlsrv_query($conn, $sql2);
                }
                while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                  echo "<td class='col-md-3'>" . $row2['DocNumber'] . "</td>";
                  echo "<td class='col-md-3'>" . $row2['DocType'] . "</td>";
                  if(is_null($row2['fecha'])){
                    echo "<td class='col-md-3'> None </td>";
                  } else { 
                    echo "<td class='col-md-3'>" . $row2['fecha'] . "</td>";
                  }
                  echo "</tr>";
                }
              ?>
            </tbody>
          </table>
        </div>
      </form>

      <p></p>
      <div class="footer">
        <div class="row">
          <!-- This button is for canceling everithing and returning to the lawyer page -->
          <a class="btn btn-primary pull-right" href="caseSearch.php"><?php echo $lang['eCancel']; ?></a>
          <!-- This button is for printing a the case -->
          <a class="btn btn-primary pull-right" style="margin-right: 4px" href="javascript:window.print()"> <?php echo $lang['doc_print']; ?> </a>
        </div>
      </div>
    </div>

    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
