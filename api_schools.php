<?php

use function PHPSTORM_META\type;

session_start();
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$get = "SELECT schoolname as name,schooltype as type FROM schools WHERE id > 3 ORDER BY id";
$result = mysqli_query($mysqli, $get);

$data = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row["type"] = ($row["type"] == 1) ? "PRIMARY_SCHOOL" : "HIGH_SCHOOL";
        $data[] = $row;
    }
}

$json_response = json_encode($data);

header('Content-Type: application/json');

echo $json_response;
