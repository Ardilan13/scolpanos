<?php


	require_once("../classes/spn_contact.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$t = new spn_contact();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $t->update_contact(
		$_POST["tutor"],
	$_POST["type"],
	$_POST["id_number_contact"],
	$_POST["full_name"],
	$_POST["address"],
	$_POST["email"],
	$_POST["mobile_phone"],
	$_POST["home_phone"],
	$_POST["work_phone"],
	$_POST["work_phone_ext"],
	$_POST["company"],
	$_POST["position_company"],
	$_POST["observation"],
	$_POST["id_contact"],appconfig::GetDummy());
?>
