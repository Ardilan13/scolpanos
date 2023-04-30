<?php
require_once("../classes/spn_authentication.php");
require_once("../classes/spn_leerling.php");
require_once("../config/app.config.php");

$a = new spn_leerling();
if(session_status() == PHP_SESSION_NONE)
session_start();

$name_leerling = str_replace("%20",' ',$_GET["name"]);
$SchoolID = $_SESSION["SchoolID"];
$baseurl = appconfig::GetBaseURL();
$class="";

if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
{
	print  $a->get_leerling_by_full_name($name_leerling, $SchoolID,$_SESSION["Class"],$baseurl, appconfig::GetDummy());

}
else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
{
	print  $a->get_leerling_by_full_name($name_leerling, $SchoolID,$class,$baseurl, appconfig::GetDummy());
}


?>
