<?php


require_once("../classes/spn_leerling.php");
require_once("../config/app.config.php");

/*
	configuration for the detail table to be shown on screen
	the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();


if(session_status() == PHP_SESSION_NONE)
	session_start();

$l = new spn_leerling();
print $l->birthday_student_list($_SESSION["SchoolID"], $_SESSION["Class"], 7, appconfig::GetDummy());



?>
