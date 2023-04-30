<?php

ob_start();

require_once("../classes/spn_woord_rapport.php");
require_once("../config/app.config.php");
$r = new spn_woord_rapport();


if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["woord_rapport_klas"],$_GET["rapport"],true);
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["woord_rapport_klas"],$_GET["rapport"],true);
		}
	}


ob_flush();


?>
