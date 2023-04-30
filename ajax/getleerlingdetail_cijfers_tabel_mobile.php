<?php
require_once("../classes/spn_cijfers_mobile.php");
require_once("../classes/spn_cijfers.php");
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




$c = new spn_cijfers_mobile();
$cm = new spn_cijfers();
if($_GET["schoolJaar"]=="2022-2023" && $_SESSION["SchoolType"]==1 && $_SESSION["SchoolID"]!=8 && $_SESSION["SchoolID"]!=11){
	print $c->list_cijfers_by_student_ps($_GET["schoolJaar"],$studentid,null); //CODE CaribeDevelopers
} else {
	print $cm->list_cijfers_by_student($_GET["schoolJaar"],$studentid,null); //CODE CaribeDevelopers
}

?>
