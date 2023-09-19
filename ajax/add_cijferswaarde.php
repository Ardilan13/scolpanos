<?php
if(session_status() == PHP_SESSION_NONE)
	session_start();
/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */
// if(isset($_POST["studentnummer"]) && isset($_POST["klas"]) && isset($_POST["achternaam"]) && isset($_POST["voornaam"]) && isset($_POST["geslacht"]) && isset($_POST["geboortedatum"]) && isset($_POST["geboorteplaats"]) && isset($_POST["adres"]) && isset($_POST["telefoon"]) && isset($_POST["status"]))
// {
	require_once("../classes/spn_cijfers.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");
    var_dump($_POST);
	$t = new spn_cijfers();
	$u = new spn_utils();

		if($_SESSION["UserRights"] == "TEACHER")
		{
			print  $t->createcijferswaarde($_POST["klas"],$_SESSION["SchoolJaar"],$_POST["rapnummer"],$_POST["vak"],$_POST["cijferswaarde1"],$_POST["cijferswaarde2"],$_POST["cijferswaarde3"],$_POST["cijferswaarde4"],$_POST["cijferswaarde5"],$_POST["cijferswaarde6"],$_POST["cijferswaarde7"],$_POST["cijferswaarde8"],$_POST["cijferswaarde9"],$_POST["cijferswaarde10"],$_POST["cijferswaarde11"],$_POST["cijferswaarde12"],$_POST["cijferswaarde13"],$_POST["cijferswaarde14"],$_POST["cijferswaarde15"],$_POST["cijferswaarde16"],$_POST["cijferswaarde17"],$_POST["cijferswaarde18"],$_POST["cijferswaarde19"],$_POST["cijferswaarde20"]);
		}
		else {
			print  $t->createcijferswaarde($_POST["klas"],$_POST["schooljaar"],$_POST["rapnummer"],$_POST["vak"],$_POST["cijferswaarde1"],$_POST["cijferswaarde2"],$_POST["cijferswaarde3"],$_POST["cijferswaarde4"],$_POST["cijferswaarde5"],$_POST["cijferswaarde6"],$_POST["cijferswaarde7"],$_POST["cijferswaarde8"],$_POST["cijferswaarde9"],$_POST["cijferswaarde10"],$_POST["cijferswaarde11"],$_POST["cijferswaarde12"],$_POST["cijferswaarde13"],$_POST["cijferswaarde14"],$_POST["cijferswaarde15"],$_POST["cijferswaarde16"],$_POST["cijferswaarde17"],$_POST["cijferswaarde18"],$_POST["cijferswaarde19"],$_POST["cijferswaarde20"]);

		}
