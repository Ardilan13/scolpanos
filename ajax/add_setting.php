<?php

	/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */
	// if(isset($_POST["studentnummer"]) && isset($_POST["klas"]) && isset($_POST["achternaam"]) && isset($_POST["voornaam"]) && isset($_POST["geslacht"]) && isset($_POST["geboortedatum"]) && isset($_POST["geboorteplaats"]) && isset($_POST["adres"]) && isset($_POST["telefoon"]) && isset($_POST["status"]))
	// {
	require_once("../classes/spn_setting.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$a = new spn_setting();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
	session_start();

	print $a->create_setting(
													$_SESSION["SchoolID"],
													$_POST["setting_school_jaar"],
													$_POST["setting_rapnumber_1_val"],
													$_POST["setting_rapnumber_2_val"],
													$_POST["setting_rapnumber_3_val"],
													$u->converttomysqldate($_POST["setting_begin_rap_1"]),
													$u->converttomysqldate($_POST["setting_end_rap_1"]),
													$u->converttomysqldate($_POST["setting_begin_rap_2"]),
													$u->converttomysqldate($_POST["setting_end_rap_2"]),
													$u->converttomysqldate($_POST["setting_begin_rap_3"]),
													$u->converttomysqldate($_POST["setting_end_rap_3"]),
													$_POST["setting_mj_val"],
													$_POST["setting_sort"],
													$_SESSION["UserGUID"],
													appconfig::GetDummy()
	);

	// print 1;
?>
