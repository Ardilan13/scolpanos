<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("../classes/DBCreds.php");
$DBCreds = new DBCreds();
date_default_timezone_set("America/Aruba");
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

$day = $_POST["day"];
$klas = $_POST["klas"];
$school = $_SESSION["SchoolID"];
$schooljaar = $_POST["schooljaar"];
$vak = $_POST["vak"];
$p = $_POST["p"];
$x = "p" . $p;

$insert = "UPDATE `klassenboek_vak` SET " . $x . "='$vak' WHERE day = '$day' AND schooljaar = '$schooljaar' and klas = '$klas' and schoolid = '$school'";
if ($mysqli->query($insert)) {
    echo $insert;
} else {
    echo "0";
}
