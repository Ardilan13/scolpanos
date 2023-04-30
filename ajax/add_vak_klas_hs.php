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

$tutor = "";

if (isset($_POST["is_tutor"])){
    $tutor = $_POST["is_tutor"];
}
else{
    $tutor = "No";
}

print $a->add_klas_and_vack_hs_account($_POST["userGUID"],$_POST["cijfers_vakken_lijst"],$_POST["cijfers_klassen_lijst"],$tutor, appconfig::GetDummy());

?>
