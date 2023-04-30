<?php

	require_once("../classes/spn_leerling.php");
	require_once("../config/app.config.php");

	$checkSecurePIN = "";

	$t = new spn_leerling();
	if(session_status() == PHP_SESSION_NONE)
		session_start();

		$checkSecurePIN = $t->check_student_securepin($_POST["studentid"],$_POST["old_secure_pin"]);
		if ($checkSecurePIN == 1){
			print  $t->update_securepin_student($_POST["studentid"],$_POST["new_secure_pin"],appconfig::GetDummy());
		}
		else{
			print($checkSecurePIN);
		}


?>
