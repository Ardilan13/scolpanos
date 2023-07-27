<?php
require_once "../classes/DBCreds.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$schoolid = $_SESSION["SchoolID"];
$datum = date('Y-m-d');
$schooljaar = $_POST["schooljaar"];
$klas = $_POST["klas"];
$rapport = $_POST["rap"];
$studentid = $_POST["studentid"];
$opmerking = $_POST["opmerking"];
$definitiet = $_POST["definitiet"];

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$sql = "SELECT * FROM opmerking WHERE SchoolID = '$schoolid' AND klas = '$klas' AND studentid = '$studentid' AND rapport = $rapport AND schooljaar ='$schooljaar';";
$result = mysqli_query($mysqli, $sql);
if ($result->num_rows > 0) {
    $sql = "UPDATE opmerking SET opmerking1 = '$opmerking', opmerking3 = '$definitiet' WHERE SchoolID = '$schoolid' AND klas = '$klas' AND studentid = '$studentid' AND rapport = $rapport AND schooljaar ='$schooljaar';";
    $result = mysqli_query($mysqli, $sql);
    if ($result) {
        echo "Opmerkingen Updated";
    } else {
        echo "Error";
    }
} else {
    $sql = "INSERT INTO opmerking (SchoolID, klas, studentid, rapport, schooljaar, opmerking1, opmerking3) VALUES ('$schoolid', '$klas', '$studentid', $rapport, '$schooljaar', '$opmerking', '$definitiet');";
    $result = mysqli_query($mysqli, $sql);
    if ($result) {
        echo "Opmerkingen Saved";
    } else {
        echo "Error";
    }
}
