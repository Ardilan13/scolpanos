<?php
session_start();
include_once '../classes/DBCreds.php';
$id = $_POST["id"];
$ex = $_POST["ex"];
$code = $_POST["code"];
$schooljaar = $_SESSION["SchoolJaar"];

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$select = "SELECT e.id FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id WHERE p.studentid = $id AND e.type = 1 AND e.schooljaar = '$schooljaar';";
$result = mysqli_query($mysqli, $select);
if ($result->num_rows > 0) {
    $personalia = mysqli_fetch_assoc($result)['id'];
    $update_opmerking = "UPDATE eba_ex SET $ex = '$code' WHERE id = $personalia AND type = 1;";
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
