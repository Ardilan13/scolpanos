<?php

require_once("../classes/spn_rapport_verzuim.php");
require_once("../config/app.config.php");

require_once("../classes/spn_utils.php");
$u = new spn_utils();

/*
configuration for the detail table to be shown on screen
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

if( isset($_POST['rappor_te_laat']) )
{
	if ($_POST["rappor_te_laat"]=="on")
	{
		$rappor_te_laat = 1;
	}
	else {
		$rappor_te_laat = 0;
	}
}
if( isset($_POST['rappor_inhalen']) )
{
	if ($_POST["rappor_inhalen"]=="on")
	{
		$rappor_inhalen = 1;
	}
	else {
		$rappor_inhalen = 0;
	}
}
if( isset($_POST['rappor_absent']) )
{
	if ($_POST["rappor_absent"]=="on")
	{
		$rappor_absent = 1;
	}
	else {
		$rappor_absent = 0;
	}
}
if( isset($_POST['rappor_uitsturen']) )
{
	if ($_POST["rappor_uitsturen"]=="on")
	{
		$rappor_uitsturen = 1;
	}
	else {
		$rappor_uitsturen = 0;
	}
}
if( isset($_POST['rappor_huiswerk']) )
{
	if ($_POST["rappor_huiswerk"]=="on")
	{
		$rappor_huiswerk = 1;
	}
	else {
		$rappor_huiswerk = 0;
	}
}

$a = new spn_rapport_verzuim();

print $a->list_rapport_verzuim_table1($u->converttomysqldate($_POST["start_date"]), $u->converttomysqldate($_POST["end_date"]), $_POST["rapport_klassen_lijst"],$_SESSION["SchoolID"],$_SESSION["SchoolJaar"],$rappor_te_laat, $rappor_absent, $rappor_inhalen, $rappor_uitsturen, $rappor_huiswerk ,appconfig::GetDummy());

?>
<script type="text/javascript">

</script>
