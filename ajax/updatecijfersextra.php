<?php

/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */
// if(isset($_POST["studentnummer"]) && isset($_POST["klas"]) && isset($_POST["achternaam"]) && isset($_POST["voornaam"]) && isset($_POST["geslacht"]) && isset($_POST["geboortedatum"]) && isset($_POST["geboorteplaats"]) && isset($_POST["adres"]) && isset($_POST["telefoon"]) && isset($_POST["status"]))
// {
	require_once("../classes/spn_cijfers.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$t = new spn_cijfers();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $t->updatecijfersextra($_POST["id_cijfersextra"], $_POST["klas_cijfersextra"], $_SESSION["SchoolJaar"], $_POST["rapnummer_cijfersextra"], $_POST["vak_cijfersextra"], $_POST["index"], $_POST["extra"], $u->converttomysqldate($_POST["duedatum"]), $_SESSION["SchoolID"]);
	// print  $t->updatecijfersextra(1, '3A', 'AAA', 1, '66', 10, 'Janio', $u->converttomysqldate('24-03-2016'));

?>
