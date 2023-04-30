<?php
require_once("../classes/spn_avi.php");
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
$schoolJaar = $_GET["schoolJaar"];

$a = new spn_avi();
print $a->list_avi_by_student($studentid,$schoolJaar,appconfig::GetDummy());


?>
