<?php

// if(isset($_POST["id_remedial_detail"]))
// {
	require_once("../classes/spn_remedial.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$r = new spn_remedial();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $r->delete_remedial_detail($_POST["id_remedial_detail"],appconfig::GetDummy());

// }

?>
