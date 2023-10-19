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

$IsMyVak = $u->check_is_docent_vak($_GET['klas'], $_SESSION["UserGUID"], $_GET["houding_vakken_lijst"], appconfig::GetDummy());



// print('Este es Vakid: '.$_GET["houding_vakken_lijst"]);

// print('Este es tutor: '.$IsTutor);

// print('Este es my vak: '.$IsMyVak);



if (isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["klas"]) && isset($_GET["rapport"])) {



	if ($_SESSION["UserRights"] == "DOCENT") {

		if (isset($_SESSION["Class"])) {
			if ($_GET['klas'] == '4') {
				$s->create_le_houding_student_hs_group($_SESSION["SchoolID"], $_GET["rapport"], $_GET["klas"], $_SESSION["SchoolJaar"], $_GET["group"]);
				print $s->listhouding_hs_group($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], $_GET["group"]);
			} else {
				$s->create_le_houding_student_hs($_SESSION["SchoolID"], $_GET["rapport"], $_GET["klas"], $_SESSION["SchoolJaar"], $_GET["houding_vakken_lijst"]);

				if ($IsTutor == 1) {

					if ($IsMyVak == 1) {

						print $s->listhouding_hs($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], $_GET["houding_vakken_lijst"]);
					} else {

						// print('entre aqui list houdig tutor');

						print $s->listhouding_tutor($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], $_GET["houding_vakken_lijst"]);
					}
				} else {

					// print('entre aqui normla');

					print $s->listhouding_hs($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], $_GET["houding_vakken_lijst"]);
				}
			}
		}
	} else if ($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING") {
		if ($_GET['klas'] == '4') {
			$s->create_le_houding_student_hs_group($_SESSION["SchoolID"], $_GET["rapport"], $_GET["klas"], $_SESSION["SchoolJaar"], $_GET["group"]);
			print $s->listhouding_hs_group($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], $_GET["group"]);
		} else {
			$s->create_le_houding_student_hs($_SESSION["SchoolID"], $_GET["rapport"], $_GET["klas"], $_SESSION["SchoolJaar"], $_GET["houding_vakken_lijst"]);
			print $s->listhouding_hs($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], $_GET["houding_vakken_lijst"]);
		}
	}
}
