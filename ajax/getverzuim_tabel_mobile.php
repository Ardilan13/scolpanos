<?php


require_once("../classes/spn_verzuim_mobile.php");
require_once("../config/app.config.php");

if(session_status() == PHP_SESSION_NONE)
    session_start();

/*
configuration for the detail table to be shown on screen
the $baseurl  will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

$s = new spn_verzuim_mobile();
//function listhouding ($schoolid,$klas_in,$datum_in,$sort_order)

print $s->listverzuim_mobile($_SESSION["SchoolID"],$_GET["klas"],$_GET["datum"],"");


?>
