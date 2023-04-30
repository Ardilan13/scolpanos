<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
require_once("../classes/spn_verzuim_mobile.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();
$detailpage = "studentdetail_mobile.html";

$class = $_SESSION["Class"];
$schoolID = $_SESSION["SchoolID"];
$studentid = $_GET["id"];
$SchoolType = $_SESSION["SchoolType"];

$v = new spn_verzuim_mobile();

if ($SchoolType == 1 && $schoolID != 8 && $schoolID != 18) {
	print $v->list_verzuim_by_student($_GET["schoolJaar"], $schoolID, $studentid, false);
}
if ($SchoolType == 2 || $schoolID == 8 || $schoolID == 18) {
	print $v->list_verzuim_by_student_hs($_GET["schoolJaar"], $schoolID, $studentid, false);
}
