<?php

require_once("../classes/spn_rapport_verzuim.php");
require_once("../config/app.config.php");

require_once("../classes/spn_utils.php");
$u = new spn_utils();

/*
configuration for the detail table to be shown 1 screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
session_start();

$rappor_te_laat = 0;
$rappor_inhalen = 0;
$rappor_absent = 0;
$rappor_uitsturen=0;
$rappor_huiswerk =0;

if( isset($_GET['rappor_te_laat']) )
{
	if ($_GET["rappor_te_laat"]=="1")
	{
		$rappor_te_laat = 1;
	}
	else {
		$rappor_te_laat = 0;
	}
}
if( isset($_GET['rappor_inhalen']) )
{
	if ($_GET["rappor_inhalen"]=="1")
	{
		$rappor_inhalen = 1;
	}
	else {
		$rappor_inhalen = 0;
	}
}
if( isset($_GET['rappor_absent']) )
{
	if ($_GET["rappor_absent"]=="1")
	{
		$rappor_absent = 1;
	}
	else {
		$rappor_absent = 0;
	}
}
if( isset($_GET['rappor_uitsturen']) )
{
	if ($_GET["rappor_uitsturen"]=="1")
	{
		$rappor_uitsturen = 1;
	}
	else {
		$rappor_uitsturen = 0;
	}
}
if( isset($_GET['rappor_huiswerk']) )
{
	if ($_GET["rappor_huiswerk"]=="1")
	{
		$rappor_huiswerk = 1;
	}
	else {
		$rappor_huiswerk = 0;
	}
}

$a = new spn_rapport_verzuim();
$result="";
$result= $a->get_verzuim_graph_by_class_and_date($u->converttomysqldatetime($_GET["start_date"]), $u->converttomysqldatetime($_GET["end_date"]), $_GET["rapport_klassen_lijst"],$_SESSION["SchoolID"],$_SESSION["SchoolJaar"],$rappor_te_laat, $rappor_absent, $rappor_inhalen, $rappor_uitsturen, $rappor_huiswerk ,appconfig::GetDummy());
print json_encode($result);
?>
