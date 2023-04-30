<?php

require_once("../classes/spn_user_hs_account.php");
require_once("../classes/spn_houding.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl  will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

//Caribe Dev
$s = new spn_houding();
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

$u = new spn_user_hs_account;
$IsTutor = $u->check_mentor_in_klas($_GET["klas"], $_SESSION["UserGUID"], "Klas", appconfig::GetDummy());

if (isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["klas"]) && isset($_GET["rapport"])) {

	$level_klas = substr($_GET["klas"], 0, 1);

	if ($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT") {
		if (isset($_SESSION["Class"])) {

			$s->create_le_houding_student($_SESSION["SchoolID"], $_GET["rapport"], $_GET["klas"], $_SESSION["SchoolJaar"]);
			if ($_SESSION["SchoolID"] == 8) {
				print $s->listhouding_school_8_klas_5($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], "");
			} else if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 18) {
				print $s->listhouding_skoa($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], "");
			} else if ($IsTutor == 1) {
				print $s->listhouding_tutor($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], "");
			} else {
				print $s->listhouding($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], "");
			}
		}
	} else if ($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING") {

		$s->create_le_houding_student($_SESSION["SchoolID"], $_GET["rapport"], $_GET["klas"], $_SESSION["SchoolJaar"]);
		if ($_SESSION["SchoolID"] == 8) {
			print $s->listhouding_school_8_klas_5($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], "");
		} else if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 18) {
			print $s->listhouding_skoa($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], "");
		} else {
			print $s->listhouding($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], "");
		}
	}
}
