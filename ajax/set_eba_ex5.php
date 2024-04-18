<?php
session_start();
include_once '../classes/DBCreds.php';
$student = $_POST["student"];
$ex = $_POST["ex"];
$value = $_POST["value"];
$schooljaar = $_SESSION["SchoolJaar"];

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$select = "SELECT e.id FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id WHERE p.studentid = $student AND e.type = 5 AND e.schooljaar = '$schooljaar';";
$result = mysqli_query($mysqli, $select);
if ($result->num_rows > 0) {
    $personalia = mysqli_fetch_assoc($result)['id'];
    $update_opmerking = "UPDATE eba_ex SET $ex = '$value' WHERE id = $personalia AND type = 5;";
    $result = mysqli_query($mysqli, $update_opmerking);
    if ($result) {
        echo $ex . " saved: " . $code;
    } else {
        echo "Error saving docent";
        echo $update_opmerking;
    }
} else {
    echo "No student found";
}
