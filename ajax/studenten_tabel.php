<?php


require_once("../classes/leerling.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();
$detailpage = "studentdetail.php";

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_SESSION["Class"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{

			$s = new leerling();
			print $s->liststudents($_SESSION["SchoolID"],$_SESSION["Class"],$baseurl,$detailpage);

		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{

			$s = new leerling();
			print $s->liststudents($_SESSION["SchoolID"],"ALL",$baseurl,$detailpage);

		}
	}
	

?>