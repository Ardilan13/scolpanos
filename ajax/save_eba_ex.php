<?php
session_start();
include_once '../classes/DBCreds.php';
$id = $_POST["id"];
$ex = $_POST["ex"];
$value = $_POST["value"];

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

if ($ex != "profiel" && $ex != "profiel_n") {
    $update_opmerking = "UPDATE eba_ex SET $ex = '$value' WHERE id = $id;";
    $result = mysqli_query($mysqli, $update_opmerking);
    if ($result) {
        echo $ex . " saved: " . $value;
    } else {
        echo "Error saving ex";
    }
} else {
    $update_profiel = "UPDATE students SET $ex = '$value' WHERE id = $id;";
    $result = mysqli_query($mysqli, $update_profiel);
    if ($result) {
        echo $id . " saved: " . $value;
    } else {
        echo "Error saving ex";
    }
}
