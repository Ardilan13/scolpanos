<?php

/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */
// if(isset($_POST["studentnummer"]) && isset($_POST["klas"]) && isset($_POST["achternaam"]) && isset($_POST["voornaam"]) && isset($_POST["geslacht"]) && isset($_POST["geboortedatum"]) && isset($_POST["geboorteplaats"]) && isset($_POST["adres"]) && isset($_POST["telefoon"]) && isset($_POST["status"]))
// {
	require_once("../classes/spn_todo.php");
	require_once("../config/app.config.php");

	$t = new spn_todo();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $t->delete_todo($_POST["id_todo"],appconfig::GetDummy());

?>
