<?php
require_once("../classes/spn_avi_mobile.php");
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


$a = new spn_avi_mobile();
print $a->list_avi_by_student($_GET["schoolJaar"],$studentid, false);


?>
