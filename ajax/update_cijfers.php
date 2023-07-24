<?php
session_start();

require_once("../classes/spn_cijfers.php");
require_once("../config/app.config.php");
require_once("../classes/spn_audit.php");

$spn_audit = new spn_audit();
$UserGUID = $_SESSION['UserGUID'];

/*
	configuration for the detail table to be shown on screen
	the $baseurl  will be used to create the "View Details" link in the table
*/

$baseurl = appconfig::GetBaseURL();


if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if (isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_POST["studentid"]) && isset($_POST["cijfername"]) && isset($_POST["cijfervalue"]) && isset($_POST["klas"]) && isset($_POST["rapport"]) && isset($_POST["vak"])) {


	/* Add logic to check if cijfer is a numeric value and is between 0 and 10 */
	if (is_numeric($_POST["cijfervalue"]) && $_POST["cijfervalue"] >= 0 && $_POST["cijfervalue"] <= 10) {
		//print "debug: is a numberic value between 0 and 10";
	} else {
		//print "debug: is not a numeric value or not between 0 and 10";
	}



	if ($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "TEACHER"  || $_SESSION["UserRights"] == "ASSISTENT") {
		if (isset($_SESSION["Class"])) {
			$spn_audit->create_audit($UserGUID, 'cijfers', 'update cijfer:' . $_POST["id_cijfer"] . ' -' . $_POST['cijfervalue'] . ' extra:' . $_POST['extra_info'], appconfig::GetDummy());

			$s = new spn_cijfers();
			//function savecijfer($schoolid,$studentid_in,$cijfer_number_in,$cijfer_value_in,$klas_in,$rap_in,$vak_in)
			print $s->savecijfer($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_POST["id_cijfer"], $_POST["studentid"], $_POST["cijfername"], $_POST["cijfervalue"], $_POST["klas"], $_POST["rapport"], $_POST["vak"]);
			//	print $s->mysqlierror;
		}
	} else if ($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING") {
		$spn_audit->create_audit($UserGUID, 'cijfers', 'update cijfer:' . $_POST["id_cijfer"] . ' -' . $_POST['cijfervalue'] . ' extra:' . $_POST['extra_info'], appconfig::GetDummy());

		$s = new spn_cijfers();
		//function savecijfer($schoolid,$studentid_in,$cijfer_number_in,$cijfer_value_in,$klas_in,$rap_in,$vak_in)
		print $s->savecijfer($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_POST["id_cijfer"], $_POST["studentid"], $_POST["cijfername"], $_POST["cijfervalue"], $_POST["klas"], $_POST["rapport"], $_POST["vak"]);

		//print $s->mysqlierror;
	}
}
