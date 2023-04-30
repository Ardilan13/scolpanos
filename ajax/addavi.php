<?php

/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */
// if(isset($_POST["studentnummer"]) && isset($_POST["klas"]) && isset($_POST["achternaam"]) && isset($_POST["voornaam"]) && isset($_POST["geslacht"]) && isset($_POST["geboortedatum"]) && isset($_POST["geboorteplaats"]) && isset($_POST["adres"]) && isset($_POST["telefoon"]) && isset($_POST["status"]))
// {
	require_once("../classes/spn_avi.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$a = new spn_avi();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print $a->create_avi(
														$_POST["class_hidden"],
														$_POST["period_hidden"],
														$_POST["level_hidden"],
														$_POST["promoted_hidden"],
														$_POST["mistakes"],
														$_POST["time_length"],
														$_POST["observation"],
														$_POST["data_student_by_class_hidden"],
														$_SESSION["SchoolJaar"],
														appconfig::GetDummy());

	// print 1;
?>
