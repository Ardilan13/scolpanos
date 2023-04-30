<?php

  require_once("../classes/spn_user_account.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$m = new spn_user_account();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print $m->delete_user_account($_POST["user_GUID"],appconfig::GetDummy());

?>
