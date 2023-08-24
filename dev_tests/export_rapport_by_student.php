<?php

ob_start();

// require_once("../classes/spn_rapport_by_student.php");
require_once("../config/app.config.php");
$r = null;

if (session_status() == PHP_SESSION_NONE) {

	session_start();
}

if ($_SESSION["SchoolID"] == '4' || $_SESSION["SchoolID"] == '5' || $_SESSION["SchoolID"] == '6' || $_SESSION["SchoolID"] == '7' || $_SESSION["SchoolID"] == '8') {
	require_once("../classes/spn_rapport_by_student.php");
	$r = new spn_rapport_by_student();
} else if ($_SESSION["SchoolID"] == '9') {
	require_once("../classes/spn_rapport_by_student_s9_v2.php");
	$r = new spn_rapport_by_student_s9_v2();
} else if ($_SESSION["SchoolID"] == '10' || $_SESSION["SchoolID"] == '11') {
	require_once("../classes/spn_rapport_by_student_s10_s11_v2.php");
	$r = new spn_rapport_by_student_s10_s11_v2();
} else {
	require_once("../classes/spn_rapport_by_student_8.php");
	$r = new spn_rapport_by_student_8();
}



if (isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"])) {
	if ($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT") {
		$result = $r->createrapport($_SESSION["SchoolID"], $_GET["schooljaar_rapport"], $_GET["id_student"], true);
	} else if ($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING") {

		// print($_SESSION["SchoolID"]);
		// print($_GET["schooljaar_rapport"]);
		// print($_GET["id_student"]);
		$result = $r->createrapport($_SESSION["SchoolID"], $_GET["schooljaar_rapport"], $_GET["id_student"], true);
	}
}


ob_flush();
