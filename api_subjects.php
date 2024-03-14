<?php

use function PHPSTORM_META\type;

session_start();
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$get = "SELECT lv.vak_naam as name,s.api_id as schoolId FROM le_vakken_ps lv CROSS JOIN (SELECT DISTINCT api_id FROM schools WHERE id > 3) s
WHERE lv.type = 'c' ORDER BY s.api_id;";
$result = mysqli_query($mysqli, $get);

$data = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row["sku"] = $row["name"];
        $data[] = $row;
    }
}

$get = "SELECT 
v.volledigenaamvak AS sku,
v.complete_name AS name,
s.api_id AS schoolId
FROM 
le_vakken v 
INNER JOIN 
schools s ON v.SchoolID = s.id 
WHERE 
v.SchoolID > 3 AND s.schooltype = 2 AND v.volgorde <> 99
GROUP BY 
s.api_id, v.volledigenaamvak";
$result = mysqli_query($mysqli, $get);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row["name"] = (!isset($row["name"])) ? $row["sku"] : $row["name"];
        $data[] = $row;
    }
}

$json_response = json_encode($data);

header('Content-Type: application/json');

echo $json_response;
