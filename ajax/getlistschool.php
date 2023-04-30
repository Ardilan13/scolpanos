<?php
require_once("../classes/spn_authentication.php");
require_once("../classes/spn_paymentinvoice.php");
require_once("../classes/spn_utils.php");
	$i = new spn_paymentinvoice();
	$u = new spn_utils();
	// if(session_status() == PHP_SESSION_NONE)
	// {
  // 	session_start();
	// }
	if(session_status() == PHP_SESSION_NONE){
		session_start();
}

	$schoolid = $_SESSION["SchoolID"];

	print  $i->listschool($schoolid);
// }
?>
