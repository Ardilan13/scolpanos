<?php

	require_once("../classes/spn_paymentinvoice.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");
	$p = new spn_paymentinvoice();
  $u = new spn_utils();
	if(session_status() == PHP_SESSION_NONE)
{
	session_start();
	}
print $p->createpayment($_POST["invoicepaymentnumber"], $_POST["invoicepaymentammount"], $u->converttomysqldate($_POST["invoicepaymentdate"]), $_POST["invoicepaymentmemo"], $_POST["invoice_id"], $_POST["invoicepaymentstatus"],$_SESSION["SchoolJaar"],appconfig::GetDummy());

?>
