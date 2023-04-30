<?php

ob_start();

require_once("../classes/spn_overzicht.php");
require_once("../config/app.config.php");
$r = new spn_overzicht();


if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["klas_list"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$result = $r->createrapport_basic_student_data($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["klas_list"]);
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$result = $r->createrapport_basic_student_data($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["klas_list"]);
		}
	}


ob_flush();


?>
