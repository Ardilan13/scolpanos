<?php

	require_once("../classes/spn_user_hs_account.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$a = new spn_user_hs_account();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();
	print $a->update_klas_vak_user_hs_account($_POST["user_access_id"], $_POST["cijfers_vakken_lijst"],$_POST["cijfers_klassen_lijst"],$_POST["is_tutor_hidden"], appconfig::GetDummy());
?>
