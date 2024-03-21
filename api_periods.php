<?php

use function PHPSTORM_META\type;

session_start();
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$get = "SELECT id,api_id,schoolname as name,schooltype as type FROM schools WHERE id > 3 ORDER BY id";
$result = mysqli_query($mysqli, $get);

$data = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row["type"] == 1) {
            $get_periods = "SELECT DISTINCT cp.schooljaar FROM le_cijfers_ps cp WHERE cp.school_id = " . $row["id"] . " AND cp.gemiddelde > 0.0 ORDER BY cp.schooljaar";
            $result_periods = mysqli_query($mysqli, $get_periods);
            while ($row_periods = mysqli_fetch_assoc($result_periods)) {
                $d = array();
                $d['schoolId'] = $row['api_id'];
                $d['range'] = $row_periods['schooljaar'];
                $d['start'] = substr($row_periods['schooljaar'], 0, 4);
                $d['end'] = substr($row_periods['schooljaar'], 5, 4);
                $d['id'] = $row['id'];
                $data[] = $d;
            }
        }
        $get_periods = "SELECT DISTINCT c.schooljaar FROM le_cijfers c INNER JOIN le_vakken v ON c.vak = v.ID WHERE v.SchoolID = " . $row["id"] . " AND c.gemiddelde > 0.0 AND v.klas = '1A' ORDER BY c.schooljaar";
        $result_periods = mysqli_query($mysqli, $get_periods);
        while ($row_periods = mysqli_fetch_assoc($result_periods)) {
            $d = array();
            $d['schoolId'] = $row['api_id'];
            $d['range'] = $row_periods['schooljaar'];
            $d['start'] = substr($row_periods['schooljaar'], 0, 4);
            $d['end'] = substr($row_periods['schooljaar'], 5, 4);
            $d['id'] = $row['id'];
            $data[] = $d;
        }
    }
}

$json_response = json_encode($data);

header('Content-Type: application/json');

echo $json_response;
