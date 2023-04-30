<?php


require_once("../classes/spn_avi.php");
require_once("../config/app.config.php");

/* 
	configuration for the detail table to be shown on screen 
	the $baseurl  will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();


if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_SESSION["Class"]) && isset($_GET["klas"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$s = new spn_avi();		
			//function listhouding ($schoolid,$klas_in,$datum_in,$sort_order)	
			print $s->listavi("",$_GET["klas"],"");
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$s = new spn_avi();			
			//function listhouding ($schoolid,$klas_in,$datum_in,$sort_order)	
			print $s->listavi("",$_GET["klas"],"");

		}
	}
	

?>