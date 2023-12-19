<?php
require_once("../classes/spn_houding_mobile.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();
$detailpage = "studentdetail_mobile.html";

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

$class = $_SESSION["Class"];
$schoolID = $_SESSION["SchoolID"];
$studentid = $_GET["id"];


$h = new spn_houding_mobile();
if ($_SESSION["SchoolType"] == 1) {
	print $h->listhoudingbystudent($_GET["schoolJaar"], $studentid);
} else {
	print $h->listhoudingbystudent_hs($_GET["schoolJaar"], $studentid);
}
