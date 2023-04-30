<?php
require_once("../classes/spn_authentication.php");
require_once("../classes/spn_le_vakken.php");

$i = new spn_le_vakken();
$m= new spn_authentication();
if(session_status() == PHP_SESSION_NONE)
session_start();


$school_id = $_GET["school_id"];
echo  $i->list_class_by_school($school_id, false);
?>
