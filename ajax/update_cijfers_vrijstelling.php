<?php
require_once "../classes/DBCreds.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$schoolid = $_SESSION["SchoolID"];
$datum = date('Y-m-d');
$schooljaar = $_SESSION["SchoolJaar"];
$studentid = $_POST["studentid"];
$cijfer = $_POST["cijfer"];
$value = $_POST["value"];

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$sql = "UPDATE le_cijfers SET c1 = '$value',c2 = '$value',c5 = '$value',c6 = '$value',c9 = '$value',c10 = '$value' WHERE studentid = '$studentid' AND rapnummer = 4 AND schooljaar ='$schooljaar' AND ID = $cijfer;";
$result = mysqli_query($mysqli, $sql);
if ($result) {
    echo "GSE Updated";
} else {
    echo "Error update";
}
