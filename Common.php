<?php
	session_start();
 
	if(isSet($_GET['lang'])){
		$lang = $_GET['lang'];
		//Register the session and set the cookie
		$_SESSION['lang'] = $lang; 
		setcookie('lang', $lang, time() + (3600 * 24 * 30));
	}

	else if(isSet($_SESSION['lang'])){
		$lang = $_SESSION['lang'];
	}

	else if(isSet($_COOKIE['lang'])){
		$lang = $_COOKIE['lang'];
	}

	else{
		$lang = 'en';
	}

	//Selects which file is to be used. English language is set as the default language
	switch ($lang) {
		case 'en':
			$lang_file = 'lang.en.php';
			break;

		case 'es':
			$lang_file = 'lang.es.php';
			break;

		default:
			$lang_file = 'lang.en.php';
	}
	//Includes the files in the path
	include_once 'languages/'.$lang_file;
?>
