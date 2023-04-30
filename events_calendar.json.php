<?php

require_once("classes/spn_calendar.php");
require_once("config/app.config.php");

$c = new spn_calendar();

if(session_status() == PHP_SESSION_NONE)
  session_start();


$docent = $_SESSION["UserGUID"];
// print('Este es Session Klas: '.$_SESSION["Class"]);

if ($_SESSION['SchoolType']==1 &&   $_SESSION['UserRights'] != 'BEHEER' || $_SESSION['UserRights'] == 'PARENTS'){
  print $c->read_calendar_json($_SESSION["Class"],appconfig::GetDummy());
}
else{
  print $c->read_calendar_json_hs(appconfig::GetDummy());
}
?>
