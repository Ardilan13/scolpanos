<?php

// if(isset($_POST["remedial_school_year"]) && isset(($_POST["remedial_begin_date"]) && isset(($_POST["remedial_end_date"]) && isset($_POST["remedial_subject"]) && isset($_POST["remedial_class_value"]) && isset($_POST["remedial_docent_value"]) && isset($_POST["id_remedial"]))
// {
	require_once("../classes/spn_remedial.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$r = new spn_remedial();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $r->update_remedial(
														$_POST["remedial_school_year"],
														$u->converttomysqldate($_POST["remedial_begin_date"]),
														$u->converttomysqldate($_POST["remedial_end_date"]),
														$_POST["remedial_subject"],
														$_POST["remedial_class_value"],
														$_POST["remedial_docent_value"],
														$_POST["id_remedial"],
														appconfig::GetDummy());

// }


?>
