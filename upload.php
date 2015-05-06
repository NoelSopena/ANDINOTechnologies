<?php
	/* Data 
	$key = 'diego';
	$plain_text = 'very important data';

	/* Open module, and create IV 
	$td = mcrypt_module_open('des', '', 'ecb', '');
	$key = substr($key, 0, mcrypt_enc_get_key_size($td));
	$iv_size = mcrypt_enc_get_iv_size($td);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

	/* Initialize encryption handle 
	if (mcrypt_generic_init($td, $key, $iv) != -1) {
		/* Encrypt data 
		$c_t = mcrypt_generic($td, $plain_text);
		mcrypt_generic_deinit($td);

		/* Reinitialize buffers for decryption 
		mcrypt_generic_init($td, $key, $iv);
		$p_t = mdecrypt_generic($td, $c_t);

		/* Clean up 
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
	}
die($p_t);
	if (strncmp($p_t, $plain_text, strlen($plain_text)) == 0) {
		echo "ok\n";
	}
	else {
		echo "error\n";
	}
*/

	$text2 = 'copies\\5547ac35392ea0.12829915.pdf*$*&$^*&^%(*^*)^)';
	$trimiar = trim($text2);
	$segundoTrimeo = rtrim($text2, "!@#$%^&*()_+");

	//echo $trimiar;
	var_dump($trimiar);
	echo "<br>";
	//echo $segundoTrimeo;
	var_dump($segundoTrimeo);
	echo "<br>";
	$mejorTrimeo = strtok($text2, 'f').'f';
	echo $mejorTrimeo;
	echo "<br>";
	echo "<br>";

	$text   = "\t\tThese are a few words :) ...  ";
	$binary = "\x09Example string\x0A";
	$hello  = "Hello World";
	var_dump($text, $binary, $hello);

	echo "<br>";
	echo "<br>";
	//print "\n";

	$trimmed = trim($text);
	var_dump($trimmed);

	echo "<br>";

	$trimmed = trim($text, " \t.");
	var_dump($trimmed);

	echo "<br>";

	$trimmed = trim($hello, "Hdle");
	var_dump($trimmed);

	echo "<br>";

	$trimmed = trim($hello, 'HdWr');
	var_dump($trimmed);

	echo "<br>";

	// trim the ASCII control characters at the beginning and end of $binary
	// (from 0 to 31 inclusive)
	$clean = trim($binary, "\x00..\x1F");
	var_dump($clean);
?>
