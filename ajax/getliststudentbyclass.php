<?php
	require_once("../classes/spn_authentication.php");
	require_once("../classes/spn_opmerking.php");
	require_once("../config/app.config.php");

	$a = new spn_opmerking();
	if(session_status() == PHP_SESSION_NONE)
		session_start();

	$class = $_POST["class"];
	$SchoolID = $_SESSION["SchoolID"];

	echo  $a->liststudentbyclass($class, $SchoolID,0, appconfig::GetDummy());
