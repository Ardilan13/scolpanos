<?php

/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */
// if(isset($_POST["studentnummer"]) && isset($_POST["klas"]) && isset($_POST["achternaam"]) && isset($_POST["voornaam"]) && isset($_POST["geslacht"]) && isset($_POST["geboortedatum"]) && isset($_POST["geboorteplaats"]) && isset($_POST["adres"]) && isset($_POST["telefoon"]) && isset($_POST["status"]))
// {
	require_once("../classes/spn_social_work.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$m = new spn_social_work();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $m->update_social_work($_POST["id_social_work"],	$_POST["social_work_school_year"],	$u->converttomysqldate($_POST["social_work_date"]),$_POST["social_work_reason"],$_POST["social_work_class_hidden"],	$_POST["social_work_observation"],$_POST["social_work_pending_selected"],	appconfig::GetDummy());
	// print  $m->create_social_work("2016",	$u->getdatetime("UTC"),"Fight","8B",	"Observacion desde el modelo",1,2,false);

?>
