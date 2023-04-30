<?php

// if(isset($_POST["id_avi"]))
// {
	require_once("../classes/spn_setting.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$a = new spn_setting();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $a->delete_setting($_POST["id_setting"], appconfig::GetDummy());

// }

?>
