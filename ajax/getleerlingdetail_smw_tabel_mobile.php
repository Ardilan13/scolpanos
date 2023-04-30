<?php
require_once("../classes/spn_social_work_mobile.php");
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


$s = new spn_social_work_mobile();
print $s->get_social_work($_SESSION["SchoolJaar"],null, $studentid, false);


?>
