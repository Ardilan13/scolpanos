<?php
require_once("../classes/spn_cijfers_mobile.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();
$detailpage = "studentdetail_mobile.html";

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

$class= $_SESSION["Class"];
$schoolID =$_SESSION["SchoolID"];

$c = new spn_cijfers_mobile();
if($_SESSION["SchoolType"]==1 && $_SESSION["SchoolID"]!=8 && $_SESSION["SchoolID"]!=11){
print $c->list_last_cijfers_by_student_ps($_GET["schoolJaar"],$_GET["id"],$schoolID,null); //CODE CaribeDevelopers
} else {
print $c->list_last_cijfers_by_student($_GET["schoolJaar"],$_GET["id"],$schoolID,null); //CODE CaribeDevelopers
}


?>
