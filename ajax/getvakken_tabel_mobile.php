<?php


require_once("../classes/spn_cijfers_mobile.php");
require_once("../config/app.config.php");

if(session_status() == PHP_SESSION_NONE)
    session_start();

/*
configuration for the detail table to be shown on screen
the $baseurl  will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

$s = new spn_cijfers_mobile();
//function listhouding ($schoolid,$klas_in,$datum_in,$sort_order)

	print $s->listcijfers_mobile($_SESSION["SchoolID"],$_GET["cijfers_klassen_lijst"],$_GET["cijfers_vakken_lijst"],$_GET["cijfers_rapporten_lijst"],"",$_SESSION["SchoolJaar"]);


?>
