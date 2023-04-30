<?php

  require_once("../classes/spn_social_work.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$m = new spn_social_work();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $m->delete_social_work($_POST["id_social_work"],	appconfig::GetDummy());

?>
