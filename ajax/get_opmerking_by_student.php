<?php

require_once("../classes/spn_opmerking.php");
require_once("../config/app.config.php");

require_once("../classes/spn_utils.php");
$u = new spn_utils();

/*
configuration for the detail table to be shown 1 screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
session_start();


$a = new spn_opmerking();
$result="";
$result= $a->read_opmerking($_SESSION["SchoolJaar"],$_POST["opmerking_student_name"],$_SESSION["SchoolID"],$_POST["houding_klassen_lijst"]);
print json_encode($result);
?>
