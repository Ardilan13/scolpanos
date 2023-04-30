<?php

require_once("../classes/spn_message.php");
require_once("../classes/spn_utils.php");

$m = new spn_message();
$u = new spn_utils();

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

print $m->sendnotification("N",
$_POST["xtype_subject"], /**/
$_SESSION['UserRights'], /* DOCENT, BEHEER, ADMIN*/
$_SESSION["UserGUID"], /* Usuario que envia */
($_POST["xclass"] != "0" ? "Notification to Class ". $_POST["xclass"] : "Notification to ". $_POST["xtsubject"]), /* Asunto*/
$_POST['xmessage'], /* Mensaje */
$_POST["xoption_selected"], /* Option selected */
$_POST["xclass"], /* Class*/
$_POST["xschoolID"]);


?>
