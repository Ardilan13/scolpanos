<?php

require_once("../classes/spn_cijfers.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
    session_start();

$c = new spn_cijfers();

if($_SESSION["SchoolID"])
	$session_schoolid = $_SESSION["SchoolID"];
else
{
	print "-1";
	exit();
}
print $c->get_cijfers_graph($_SESSION['SchoolJaar'],$session_schoolid, false);

?>
