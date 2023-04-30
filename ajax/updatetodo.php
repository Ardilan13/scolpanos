<?php


	require_once("../classes/spn_todo.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$r = new spn_todo();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $r->update_todo(
														$_POST["id_todo"],
														$_POST["status"],
														appconfig::GetDummy());




?>
