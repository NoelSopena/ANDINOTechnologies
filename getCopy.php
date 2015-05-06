<?php
	include_once 'common.php';
	include 'library.php';

	$q = $_GET['q'];

	$serverName = "127.0.0.1";
	$connectionInfo = array("Database"=>"PoliceTest", "UID"=>"sa", "PWD"=>"A06a30adr5d");
	$conn = sqlsrv_connect($serverName, $connectionInfo);
	if(!$conn) {
		die(print_r( sqlsrv_errors(), true));
	}
	if ($q == '') {
		echo "<a class='btn btn-primary pull-left' style='margin-right: 4px' href='$copia' target='_blank' disabled>$lang[doc_view]</a>";
	}
	else{

		//mysqli_select_db($con,"ajax_demo");
		$sql="SELECT Copies FROM Copy WHERE DocId = '$_SESSION[docID]' AND CopyDate = '".$q."'";
		$stmt = sqlsrv_query($conn, $sql);
		if ($stmt === false) {
			die(print_r( sqlsrv_errors(), true));
		}

		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
			
		$copia = $row['Copies'];

		$key = 'diego';
		//$plain_text = 'very important data';
        /* Open module, and create IV */
        $td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
        $key = substr($key, 0, mcrypt_enc_get_key_size($td));
        $iv_size = mcrypt_enc_get_iv_size($td);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        /* Initialize encryption handle */
        if (mcrypt_generic_init($td, $key, $iv) != -1) {
          /* Reinitialize buffers for decryption */
		  mcrypt_generic_init($td, $key, $iv);
		  $p_t = mdecrypt_generic($td, $copia);
		  $copia = strtok($p_t, '-');
          /* Clean up */
          mcrypt_generic_deinit($td);
          mcrypt_module_close($td);
		}

		//echo $copia;
		echo "<a class='btn btn-primary pull-left' style='margin-right: 4px' href='$copia' target='_blank'>$lang[doc_view]</a>";
	}
?>
