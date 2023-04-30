<?php
require_once("../classes/spn_houding.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

$class= $_SESSION["Class"];
$schoolID =$_SESSION["SchoolID"];
$studentid = $_GET["id"];


$h = new spn_houding();
print $h->listhoudingbystudent($_GET["schoolJaar"],$studentid);



?>
