<?php

// if(isset($_POST["id_avi"]))
// {
	require_once("../classes/spn_avi.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$a = new spn_avi();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $a->delete_avi($_POST["id_avi"], appconfig::GetDummy());

// }

?>
