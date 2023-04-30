<?php

require_once("../classes/spn_verzuim_tracker.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
session_start();
$a = new spn_verzuim_tracker();
print $a->create_verzuim_tracker(
	$_POST["group_name"],
	$_POST["datum"],
	$_POST["period_hs"],
	$_SESSION["SchoolJaar"],
	$_SESSION["SchoolID"],
	$_SESSION["UserGUID"],
	appconfig::GetDummy());

	?>
