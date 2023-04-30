
<?php

require_once("../classes/spn_paymentinvoice.php");
require_once("../classes/spn_utils.php");
require_once("../config/app.config.php");

$i = new spn_paymentinvoice();
$u = new spn_utils();

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

switch ($_POST["invoice_type"])
{
	case "1":
	print $i->createinvoicebyclass($_POST["invoicepaymentnumber"], $_POST["invoicepaymentammount"], $u->converttomysqldate($_POST["invoicepaymentdate"]), $u->converttomysqldate($_POST["invoicepaymentduedate"]), $_POST["invoicepaymentmemo"],$_POST["invoicepaymentclass"],$_SESSION["SchoolID"], $_POST["invoicepaymentstatus"],$_SESSION["SchoolJaar"],appconfig::GetDummy());
	break;
	case "2":
	print $i->createinvoice($_POST["invoicepaymentnumber"], $_POST["invoicepaymentammount"], $u->converttomysqldate($_POST["invoicepaymentdate"]), $u->converttomysqldate($_POST["invoicepaymentduedate"]), $_POST["invoicepaymentmemo"], $_POST["data_student_by_class"], $_POST["invoicepaymentstatus"],$_SESSION["SchoolJaar"],appconfig::GetDummy());
	break;
	case "3":
	print $i->createinvoicebyschool($_POST["invoicepaymentnumber"], $_POST["invoicepaymentammount"], $u->converttomysqldate($_POST["invoicepaymentdate"]), $u->converttomysqldate($_POST["invoicepaymentduedate"]), $_POST["invoicepaymentmemo"],$_SESSION["SchoolID"], $_POST["invoicepaymentstatus"],$_SESSION["SchoolJaar"],appconfig::GetDummy());
	break;
	default:
	echo "";
}

?>
