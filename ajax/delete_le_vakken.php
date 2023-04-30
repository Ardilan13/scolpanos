<?php

  require_once("../classes/spn_le_vakken.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$m = new spn_le_vakken();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

$id_le_vakken = $_GET["volledigenaamvak_id"];
	print  $m->delete_le_vakken($id_le_vakken,appconfig::GetDummy());

?>
