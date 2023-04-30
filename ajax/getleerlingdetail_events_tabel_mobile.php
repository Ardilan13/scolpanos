<?php
require_once("../classes/spn_event_mobile.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();
$detailpage = "studentdetail_mobile.html";

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

$class= $_SESSION["Class"];
$schoolID =$_SESSION["SchoolID"];
$studentid = $_GET["id"];



$e = new spn_event_mobile();
print $e->list_event($_SESSION["SchoolJaar"],null,$studentid,null); //CODE CaribeDevelopers


?>
