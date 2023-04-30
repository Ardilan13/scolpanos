<?php

require_once("../classes/spn_school.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
    session_start();

$c = new spn_school();

// $studentid = $_GET["0"];

// echo ("este es el student id :");
// echo $studentid;

print $c->get_dashboard_by_school_group($_SESSION["SchoolAdmin"], false);

?>
