<?php
require_once "../classes/DBCreds.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$check = $_POST["check"];
$id = $_POST["id"];
$group = $_POST["group"];
$schooljaar = $_SESSION["SchoolJaar"];
if ($check == "true") {
    $sqli = "INSERT INTO group_student (group_id,student_id,schooljaar) VALUES ($group, '$id','$schooljaar');";
    $result = mysqli_query($mysqli, $sqli);
    if ($result) {
        echo 1;
    } else {
        echo 0 . $sqli;
    }
} else {
    $sql = "DELETE FROM group_student WHERE student_id = $id AND group_id = $group AND schooljaar = '$schooljaar';";
    $result = mysqli_query($mysqli, $sql);
    if ($result) {
        echo 3;
    } else {
        echo 0 . $sql;
    }
}
