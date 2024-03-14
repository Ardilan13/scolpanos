<?php

use function PHPSTORM_META\type;

session_start();
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$get = "SELECT c.name_class as 'group',s.api_id as schoolId FROM class c INNER JOIN schools s ON c.SchoolID = s.id WHERE c.SchoolID > 3 ORDER BY s.api_id,c.name_class";
$result = mysqli_query($mysqli, $get);

$data = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

$json_response = json_encode($data);

header('Content-Type: application/json');

echo $json_response;
