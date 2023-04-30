<?php



/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */

// if(isset($_POST["studentnummer"]) && isset($_POST["klas"]) && isset($_POST["achternaam"]) && isset($_POST["voornaam"]) && isset($_POST["geslacht"]) && isset($_POST["geboortedatum"]) && isset($_POST["geboorteplaats"]) && isset($_POST["adres"]) && isset($_POST["telefoon"]) && isset($_POST["status"]))

// {

 require_once("../classes/spn_user_hs_account.php");

require_once("../classes/spn_utils.php");

require_once("../config/app.config.php");





$a = new spn_user_hs_account();

$u = new spn_utils();



if(session_status() == PHP_SESSION_NONE)

session_start();



//$utils=new spn_utils();

$userguid = $u->CreateGUID();

$_salt = $a->CreateSalt();

$_password = $a->HashPassword($_POST["user_hs_password"],$_salt);



print $a->create_user_account(

$userguid,

$_POST["user_hs_email"],

$_password,

$_salt,

$_POST["user_hs_firts_name"],

$_POST["user_hs_last_name"],

5,

0,

0,

'0',

1,

$_POST["user_hs_rights"],

$_SESSION["SchoolID"],

"1Z", // User Class in this case, its empty

$u->getdatetime(),

$u->getdatetime(),

appconfig::GetDummy());



?>

