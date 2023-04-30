<?php

require_once("classes/spn_planner.php");
require_once("config/app.config.php");

$p = new spn_planner();

if(session_status() == PHP_SESSION_NONE)
  session_start();

$docent = $_SESSION["UserGUID"];

print $p->read_planner_json($docent,appconfig::GetDummy());


?>
