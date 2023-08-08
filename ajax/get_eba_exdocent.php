<?php
session_start();
include_once '../classes/DBCreds.php';
$id = $_POST["id"];
$ex = "e." . $_POST["ex"];

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$select = "SELECT $ex FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id WHERE p.studentid = $id AND e.type = 1 AND $ex IS NOT NULL;";
$result = mysqli_query($mysqli, $select);
if ($result->num_rows > 0) {
    $personalia = mysqli_fetch_assoc($result)[$_POST["ex"]];
    echo json_encode(array("personalia" => $personalia, "ex" => $_POST["ex"], "id" => $id));
}
