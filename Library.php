<?php
	/*
		This file contains the functions that define the navigational toolbar. 
	*/
	function navbar($lang){
		echo"<nav class='navbar navbar-default'>";
		echo" <div class='container-fluid'>";
		echo"  <div class='navbar-header'>";
		echo"   <a class='navbar-brand'>ANDINO Legal Solution</a>";
		echo"   <!-- Brand and toggle get grouped for better mobile display -->";
		echo"   <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>";
		echo"    <span class='sr-only'>Toggle navigation</span>";
		echo"    <span class='icon-bar'></span>";
		echo"    <span class='icon-bar'></span>";
		echo"    <span class='icon-bar'></span>";
		echo"   </button>";
		echo"  </div>";
		echo"  <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>";
		echo"   <form class='navbar-form navbar-right	'>";
		echo"   <div class='btn-group'>";
		echo"    <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>";
		echo"     $lang"; 
		echo"     <span class='caret'></span>";
		echo"    </button>";
		echo"    <ul class='dropdown-menu' role='menu'>";
		echo"     <li><a href='?lang=en'>English</a></li>";
		echo"     <li><a href='?lang=es'>Español</a></li>";
		echo"    </ul>";
		echo"   </div>";
		echo"  </form>";
		echo" </div>";
		echo"</nav>";
	}
	//Admin Navbar
	function navbarAdmin($search,$lang,$logout){
		echo"<nav class='navbar navbar-default'>";
		echo" <div class='container-fluid'>";
		echo"  <div class='navbar-header'>";
		echo"   <!-- This part of the code is for the name of the application -->";
		echo"   <a class='navbar-brand'>ANDINO Legal Solution</a>";
		echo"   <!-- Brand and toggle get grouped for better mobile display -->";
		echo"   <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>";
		echo"    <span class='sr-only'>Toggle navigation</span>";
		echo"    <span class='icon-bar'></span>";
		echo"    <span class='icon-bar'></span>";
		echo"    <span class='icon-bar'></span>";
		echo"   </button>";
		echo"  </div>";
		echo"  <!-- Collect the nav links, forms, and other content for toggling -->";
		echo"  <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>";
		echo"   <form class='navbar-form navbar-right	' role='search'>";
		echo"    <a class='btn btn-primary' href='caseSearchhtml.php'>$search</a>";
		echo"    <div class='btn-group'>";
		echo"     <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>";
		echo"      $lang"; 
		echo"      <span class='caret'></span>";
		echo"     </button>";
		echo"     <ul class='dropdown-menu' role='menu'>";
		echo"      <li><a href='?lang=en'>English</a></li>";
		echo"      <li><a href='?lang=es'>Español</a></li>";
		echo"     </ul>";
		echo"    </div>";
		echo"    <a class='btn btn-primary' href='logout.php'>$logout</a>";
		echo"   </form>";
		echo"  </div><!-- /.navbar-collapse -->";
		echo" </div><!-- /.container-fluid -->";
		echo"</nav>";
	}
	//AdminEmployeeList Navbar
	function navbarEmployeeList($lang,$logout){
		echo"<nav class='navbar navbar-default'>";
		echo" <div class='container-fluid'>";
		echo"  <div class='navbar-header'>";
		echo"   <!-- This part of the code is for the name of the application -->";
		echo"   <a class='navbar-brand'>ANDINO Legal Solution</a>";
		echo"   <!-- Brand and toggle get grouped for better mobile display -->";
		echo"   <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>";
		echo"    <span class='sr-only'>Toggle navigation</span>";
		echo"    <span class='icon-bar'></span>";
		echo"    <span class='icon-bar'></span>";
		echo"    <span class='icon-bar'></span>";
		echo"   </button>";
		echo"  </div>";
		echo"  <!-- Collect the nav links, forms, and other content for toggling -->";
		echo"  <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>";   
		echo"   <form class='navbar-form navbar-right'>";
		echo"    <div class='btn-group'>";
		echo"     <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>";
		echo"      $lang"; 
		echo"      <span class='caret'></span>";
		echo"     </button>";
		echo"     <ul class='dropdown-menu' role='menu'>";
		echo"      <li><a href='?lang=en'>English</a></li>";
		echo"      <li><a href='?lang=es'>Español</a></li>";
		echo"     </ul>";
		echo"    </div>";
		echo"    <a class='btn btn-primary' href='logout.php'>$logout</a>";
		echo"   </form>";
		echo"  </div><!-- /.navbar-collapse -->";
		echo" </div><!-- /.container-fluid -->";
		echo"</nav>";
	}
	function navbarLogout($logout){
		echo"<nav class='navbar navbar-default'>";
		echo" <div class='container-fluid'>";
		echo"  <div class='navbar-header'>";
		echo"   <!-- This part of the code is for the name of the application -->";
		echo"   <a class='navbar-brand'>ANDINO Legal Solution</a>";
		echo"   <!-- Brand and toggle get grouped for better mobile display -->";
		echo"   <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>";
		echo"    <span class='sr-only'>Toggle navigation</span>";
		echo"    <span class='icon-bar'></span>";
		echo"    <span class='icon-bar'></span>";
		echo"    <span class='icon-bar'></span>";
		echo"   </button>";
		echo"  </div>";
		echo"  <!-- Collect the nav links, forms, and other content for toggling -->";
		echo"  <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>";   
		echo"   <form class='navbar-form navbar-right'>";
		echo"    <a class='btn btn-primary' href='logout.php'>$logout</a>";
		echo"   </form>";
		echo"  </div><!-- /.navbar-collapse -->";
		echo" </div><!-- /.container-fluid -->";
		echo"</nav>";
	}
?>
