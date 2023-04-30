<?php
require_once("../classes/spn_documents.php");
require_once("../config/app.config.php");
require_once ("../classes/spn_utils.php");


$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}
$d = new spn_documents();
$u = new spn_utils();

print $d->open_document($_GET["id_calendar"]);
?>
