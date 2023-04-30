<?php

require_once("classes/spn_daily.php");
require_once("config/app.config.php");

$d = new spn_daily();

if(session_status() == PHP_SESSION_NONE)
  session_start();

$docent = $_SESSION["UserGUID"];

print $d->read_daily_json($docent,appconfig::GetDummy());


?>
