<?php
require_once("../classes/spn_test_mobile.php");
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


$t = new spn_test_mobile();
print $t->get_test(null, $studentid, false);
 

?>
