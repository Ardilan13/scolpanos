<?php
require_once("../classes/spn_leerling_mobile.php");
require_once("../config/app.config.php");



if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

// $class= $_SESSION["Class"];
// $schoolID =$_SESSION["SchoolID"];
$studentid = $_GET["id"];


$l = new spn_leerling_mobile();
print $l->list_students_parent_mobile($studentid,appconfig::GetDummy());
?>
