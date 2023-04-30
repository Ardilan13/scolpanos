<?php

require_once("../classes/spn_avi.php");


if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			if(isset($_SESSION["Class"]))
			{
				$s = new spn_avi();
				print $s->liststudentbyclass($_SESSION["Class"],$_SESSION["SchoolID"],"",false);
			}
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{

			$s = new spn_avi();
			print $s->liststudentbyclass("",$_SESSION["SchoolID"],"",false);

		}
	}




?>
