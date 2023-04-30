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
print $a->check_verzuim_tracker_exist_by_docent( $_GET["group_name"],$_GET["datum"], $_GET["period_hs"], $_SESSION['SchoolJaar'], $_SESSION['SchoolID'], $_SESSION['UserGUID'],appconfig::GetDummy());

?>
