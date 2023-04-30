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

	if(isset($_GET["id"]))
	{
		$s = new spn_houding();
		//function listhouding($schoolid,$klas_in,$rap_in,$sort_order)
		print $s->listhoudingbystudent($_GET["id"]);
	}

	// $s = new spn_houding();
	// //function listhouding($schoolid,$klas_in,$rap_in,$sort_order)
	// print $s->listhoudingbystudent(1);

?>
