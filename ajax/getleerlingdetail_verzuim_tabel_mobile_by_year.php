<?php
require_once("../classes/spn_verzuim.php");
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


$v = new spn_verzuim();
// print($_GET["schoolJaar"]);
print $v->list_verzuim_by_student($_GET["schoolJaar"],$studentid, appconfig::GetDummy());


?>
