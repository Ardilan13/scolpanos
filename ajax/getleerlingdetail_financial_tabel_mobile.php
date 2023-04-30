<?php
require_once("../classes/spn_paymentinvoice_mobile.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();
$detailpage = "studentdetail_mobile.html";

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

$class= $_SESSION["Class"];
$schoolID =$_SESSION["SchoolID"];
$studentid = $_GET["id"];


$i = new spn_paymentinvoice_mobile();
print $i->list_invoice_by_student($studentid, false);
 

?>
