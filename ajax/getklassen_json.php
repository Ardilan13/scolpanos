<?php

require_once("../classes/spn_controls.php");

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]))
	{
		if($_SESSION["UserRights"] == "DOCENT")
		{
			if(isset($_SESSION["Class"]))
			{
				$s = new spn_controls();
				print $s->getdistinctklassen_json($_SESSION["SchoolID"],$_SESSION["Class"]);
			}
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING"  || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$s = new spn_controls();
			print $s->getdistinctklassen_json($_SESSION["SchoolID"],"ALL");

		}
	}


?>