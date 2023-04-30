<?php

ob_start();

require_once("../classes/spn_avi_file.php");
require_once("../config/app.config.php");
$r = new spn_avi_file();


if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["avi_klas"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["avi_klas"],true);
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["avi_klas"],true);
		}
	}


ob_flush();


?>
