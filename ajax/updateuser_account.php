<?php

/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */
// if(isset($_POST["studentnummer"]) && isset($_POST["klas"]) && isset($_POST["achternaam"]) && isset($_POST["voornaam"]) && isset($_POST["geslacht"]) && isset($_POST["geboortedatum"]) && isset($_POST["geboorteplaats"]) && isset($_POST["adres"]) && isset($_POST["telefoon"]) && isset($_POST["status"]))
// {
	require_once("../classes/spn_user_account.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$a = new spn_user_account();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

		//$_UserGUID, $Email, $Passwd, $Salt, $FirstName, $LastName, $AuthRetriesLeft, $LockedOut, $ChangePwdOnLogin, $PasswordResetToken, $AccountEnabled, $UserRights, $SchoolID, $Class, $LastPwdChangeDateTime, $PasswordResetTokenExpiration,$dummy)

	print $a->update_user_account(
																$_POST["user_GUID"],
																$_POST["user_rights"],
																$_SESSION["SchoolID"],
																$_POST["user_class"],
																$_POST["user_firts_name"],
																$_POST["user_last_name"],
																appconfig::GetDummy());
	// print 1;
?>
