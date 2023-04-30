<?php
require_once("../classes/spn_verzuim.php");
require_once("../classes/spn_authentication.php");
require_once("../config/app.config.php");


$s = new spn_verzuim();
if(session_status() == PHP_SESSION_NONE)
session_start();

echo $_REQUEST["id"];
$studentid = ($_REQUEST["id"]);
print $s->list_verzuim_by_student($studentid,appconfig::GetDummy());
?>
