<?php
	require_once("../classes/spn_paymentinvoice.php");
	require_once("../classes/spn_utils.php");
	$i = new spn_paymentinvoice();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}

//	echo '<script language="javascript">alert(.$studentid.");</script>';
		$studentid = $_POST["idStudentInvoice"];

        echo  $i->getinvoicenopaidbystudent($studentid);

	?>
