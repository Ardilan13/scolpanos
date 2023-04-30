<?php

/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */
// if(isset($_POST["studentnummer"]) && isset($_POST["klas"]) && isset($_POST["achternaam"]) && isset($_POST["voornaam"]) && isset($_POST["geslacht"]) && isset($_POST["geboortedatum"]) && isset($_POST["geboorteplaats"]) && isset($_POST["adres"]) && isset($_POST["telefoon"]) && isset($_POST["status"]))
// {
	require_once("../classes/spn_test.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$t = new spn_test();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $t->update_test($_POST["id_test"], $u->converttomysqldate($_POST["test_date"]), $_POST["test_type"], $_POST["test_class_value"],$_POST["test_observation"], appconfig::GetDummy());
	//print  $t->update_test(2, $u->converttomysqldate("3-3-2023"), "TYPE", "7B", "Obs", appconfig::GetDummy());

?>
