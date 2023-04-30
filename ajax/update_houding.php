<?php


require_once("../classes/spn_houding.php");
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

$s = new spn_houding();

if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_POST["studentid"]) && isset($_POST["houdingname"]) && isset($_POST["houdingvalue"]) && isset($_POST["klas"]) && isset($_POST["rapport"]))
{
	if ($_SESSION['SchoolType']>=2){
		print $s->savehouding_hs($_POST["houding_id"],$_SESSION["SchoolJaar"],$_SESSION["SchoolID"],$_POST["studentid"],$_POST["houdingname"],$_POST["houdingvalue"],$_POST["klas"],$_POST["rapport"]);

	}
	else{
		print $s->savehouding($_POST["houding_id"],$_SESSION["SchoolJaar"],$_SESSION["SchoolID"],$_POST["studentid"],$_POST["houdingname"],$_POST["houdingvalue"],$_POST["klas"],$_POST["rapport"]);

	}

}
else{
	print -2;
}


?>
