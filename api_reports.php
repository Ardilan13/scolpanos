<?php

use function PHPSTORM_META\type;

session_start();
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$get = "SELECT DISTINCT schoolid FROM le_vakken";
$result = mysqli_query($mysqli, $get);

$data = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row["sku"] = $row["name"];
        $data[] = $row;
    }
}

$json_response = json_encode($data);

header('Content-Type: application/json');

echo $json_response;
