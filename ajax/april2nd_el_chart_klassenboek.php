<?php


require_once("../classes/spn_dashboard.php");
require_once("../config/app.config.php");

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

$table_klassenboek = "";

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			if(isset($_SESSION["Class"]))
			{
				$s = new spn_dashboard();
				// function dash_klassenboek($schoolid_in,$klas_in)
				$table_klassenboek = $s->dash_klassenboek($_SESSION['SchoolJaar'],$_SESSION["SchoolID"],$_SESSION["Class"]);
				//print $s->mysqlierror;
				print $table_klassenboek;
			}
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$s = new spn_dashboard();
				// function dash_klassenboek($schoolid_in,$klas_in)
				$table_klassenboek = $s->dash_klassenboek($_SESSION['SchoolJaar'],$_SESSION["SchoolID"],"ALL");
				//print $s->mysqlierror;
				print $table_klassenboek;
		}
	}

?>
