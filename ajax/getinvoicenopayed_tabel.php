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

if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_SESSION["Class"]))
{
	if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
	{

		$s = new spn_paymentinvoice();
		//print $s->getinvoicenopaid($_SESSION["student_id"]);
		//print $s->getinvoicenopaid("","");
		print $s->liststudentbyschool(2);

	}
	else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
	{

		$s = new spn_paymentinvoice();
		//print $s->getinvoicenopaid($_SESSION["student_id"]);
		//print $s->getinvoicenopaid("","");
		print $s->liststudentbyschool(2);



	}
}


?>
