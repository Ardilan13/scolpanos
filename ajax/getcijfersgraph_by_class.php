<?php

require_once("../classes/spn_cijfers.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
    session_start();

$c = new spn_cijfers();

$studentid = $_GET["0"];

// echo ("este es el student id :");
// echo $studentid;

print $c->get_cijfers_graph_by_class($_SESSION["SchoolJaar"],$_SESSION["SchoolID"],$studentid, false);

?>
