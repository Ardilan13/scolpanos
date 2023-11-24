<?php


require_once("../classes/spn_cijfers.php");
require_once("../config/app.config.php");

/*
	configuration for the detail table to be shown on screen
	the $baseurl  will be used to create the "View Details" link in the table
*/

$baseurl = appconfig::GetBaseURL();


if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if (isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_POST["studentid"]) && isset($_POST["klas"]) && isset($_POST["rapport"]) && isset($_POST["vak"])) {




	if ($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "TEACHER" || $_SESSION["UserRights"] == "ASSISTENT") {
		if (isset($_SESSION["Class"])) {
			$s = new spn_cijfers();
			//function savecijfer($schoolid,$studentid_in,$cijfer_number_in,$cijfer_value_in,$klas_in,$rap_in,$vak_in)
			if ($_SESSION["SchoolType"] == 1 && $_SESSION["ScoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
				print $s->_savecijfersgemiddelde_ps($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_POST["studentid"], $_POST["klas"], $_POST["rapport"], $_POST["vak"]);
			} else {
				print $s->_savecijfersgemiddelde($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_POST["studentid"], $_POST["klas"], $_POST["rapport"], $_POST["vak"]);
			}
			//	print $s->mysqlierror;
		}
	} else if ($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING") {
		$s = new spn_cijfers();
		//function savecijfer($schoolid,$studentid_in,$cijfer_number_in,$cijfer_value_in,$klas_in,$rap_in,$vak_in)
		if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
			print $s->_savecijfersgemiddelde_ps($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_POST["studentid"], $_POST["klas"], $_POST["rapport"], $_POST["vak"]);
		} else {
			print $s->_savecijfersgemiddelde($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_POST["studentid"], $_POST["klas"], $_POST["rapport"], $_POST["vak"]);
		}

		//	print $s->mysqlierror;
	}
}
