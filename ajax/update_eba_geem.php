<?php
require_once "../classes/DBCreds.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$value = $_POST["value"] >= 6 ? 1 : 0;
$id = $_POST["id"];
$schooljaar = $_SESSION["SchoolJaar"];
$schoolid = $_SESSION["SchoolID"];

$sql = "UPDATE personalia SET her = '$value' WHERE studentid = $id and schooljaar = '$schooljaar' and schoolid = $schoolid;";
$result = mysqli_query($mysqli, $sql);
if ($result) {
    echo "CKV Updated";
} else {
    echo "Error update";
}
