<?php
require_once("../classes/spn_le_vakken.php");
require_once("../classes/spn_utils.php");
require_once("../config/app.config.php");


$a = new spn_le_vakken();
$u = new spn_utils();

if(session_status() == PHP_SESSION_NONE)
session_start();


print $a->create_le_vakken($_POST["le_vakken_schools"],$_POST["le_vakken_class"],0,$_POST["volledigenaamvak_name"],$_POST["le_vakken_volgorde"],$_POST["le_vakken_xindex"],appconfig::GetDummy());

?>
