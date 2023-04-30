<?php

/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */
// if(isset($_POST["studentnummer"]) && isset($_POST["klas"]) && isset($_POST["achternaam"]) && isset($_POST["voornaam"]) && isset($_POST["geslacht"]) && isset($_POST["geboortedatum"]) && isset($_POST["geboorteplaats"]) && isset($_POST["adres"]) && isset($_POST["telefoon"]) && isset($_POST["status"]))
// {
 require_once("../classes/spn_user_account.php");
require_once("../classes/spn_utils.php");
require_once("../config/app.config.php");
// public DateTime DateTime::add ( DateInterval $interval )


$a = new spn_user_account();

$u = new spn_utils();

if(session_status() == PHP_SESSION_NONE)
session_start();

//$utils=new spn_utils();
$userguid = $u->CreateGUID();
$_salt = $a->CreateSalt();
$_password = $a->HashPassword($_POST["user_password"],$_salt);

// 0000-00-00 00:00:00
//
// date_add($date,date_interval_create_from_date_string("40 days"));





print $a->create_user_account(
$userguid,
$_POST["user_email"],
$_password,
$_salt,
$_POST["user_firts_name"],
$_POST["user_last_name"],
5,
0,
0,
'0',
1,
$_POST["user_rights"],
$_SESSION["SchoolID"],
$_POST["user_class"],
$u->getdatetime(),
$u->getdatetime(),
appconfig::GetDummy());
// print 1;

?>
