<?php
require_once("../classes/spn_cijfers_mobile.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();
$detailpage = "studentdetail_mobile.html";

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}
$vak = $_GET["vak"];
$rapp = $_GET["rapp"];
$klas= $_GET["klas"];

$c = new spn_cijfers_mobile();
print $c->get_oc_commnets($vak,$rapp,$klas);
?>
