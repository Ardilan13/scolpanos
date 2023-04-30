<?php

  require_once("../classes/spn_contact.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$m = new spn_contact();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $m->delete_contact($_POST["id_contact"]);

?>
