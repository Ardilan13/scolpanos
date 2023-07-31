<?php
session_start();
include_once '../classes/DBCreds.php';
$id = $_POST["id"];
$opmerking = $_POST["opmerking"];
$schoolid = $_SESSION["SchoolID"];
$schooljaar = $_SESSION["SchoolJaar"];

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$update_opmerking = "UPDATE personalia SET opmerking = '$opmerking' WHERE studentid = $id AND schoolid = '$schoolid' AND schooljaar = '$schooljaar';";
$result = mysqli_query($mysqli, $update_opmerking);
if ($result) {
    echo "Opmerking saved";
} else {
    echo "Error saving opmerking";
}
