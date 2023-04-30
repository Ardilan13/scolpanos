<?php
require_once("../classes/spn_authentication.php");
require_once("../classes/spn_docent.php");
require_once("../classes/spn_utils.php");
	$i = new spn_docent();
	$u = new spn_utils();
	// if(session_status() == PHP_SESSION_NONE)
	// {
  // 	session_start();
	// }
	if(session_status() == PHP_SESSION_NONE){
		session_start();
}

$userRights = "DOCENT";
	print  $i->listdocent($userRights, $_SESSION["SchoolID"], false);
// }
?>
