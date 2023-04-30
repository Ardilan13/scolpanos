<?php
require_once("../classes/spn_verzuim_mobile.php");
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


$v = new spn_verzuim_mobile();
print $v->list_verzuim_by_student($_SESSION["SchoolJaar"],$schoolID,$studentid, false);


?>
