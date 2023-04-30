<?php
require_once("../classes/spn_remedial_mobile.php");
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


$r = new spn_remedial_mobile();
print $r->get_remedial($_SESSION["SchoolJaar"],null, $studentid, false);


?>
