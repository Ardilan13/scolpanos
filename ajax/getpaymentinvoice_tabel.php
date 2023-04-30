<?php


require_once("../classes/spn_paymentinvoice.php");
require_once("../config/app.config.php");

/*
	configuration for the detail table to be shown on screen
	the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();
$detailpage = "paymentinvoicedetail.php";


if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

		$s = new spn_paymentinvoice();
		//print $s->listpaymentinvoice($_SESSION["SchoolID"],$_SESSION["Class"],$baseurl,$detailpage);
		print $s->listpaymentinvoice($baseurl,$detailpage,$_SESSION["SchoolID"]);


?>
