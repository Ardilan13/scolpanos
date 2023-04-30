<?php

	require_once("../classes/spn_leerling.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$m = new spn_leerling();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $m->change_leerling_class($_POST["id_student"],$_POST["from_class"], $_POST["to_class"], $_SESSION["SchoolJaar"],$_POST["comments"], appconfig::GetDummy());

?>
