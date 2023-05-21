<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("../classes/DBCreds.php");
$DBCreds = new DBCreds();
date_default_timezone_set("America/Aruba");
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

$date = $_POST["datum"];
$klas = $_POST["klas"];
$school = $_SESSION["SchoolID"];
$vak = $_POST["vak"];
$p = $_POST["p"];
$x = "p" . $p;

$insert = "UPDATE `klassenboek_vak` SET " . $x . "='$vak' WHERE datum = '$date' and klas = '$klas' and schoolid = '$school'";
if ($mysqli->query($insert)) {
    echo "1";
} else {
    echo "0";
}
