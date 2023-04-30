<?php


require_once("../classes/spn_documents.php");
require_once("../config/app.config.php");

/*
	configuration for the detail table to be shown on screen
	the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();


if(session_status() == PHP_SESSION_NONE)
	session_start();

$s = new spn_documents();

if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
{
	print $s->list_documents_woord_rapport($_SESSION["schoolname"],$_SESSION["SchoolID"],"");
}
if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
{
	print $s->list_documents_woord_rapport($_SESSION["schoolname"],$_SESSION["SchoolID"],$_SESSION["Class"]);

}

?>
