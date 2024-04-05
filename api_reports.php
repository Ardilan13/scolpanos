<?php

use function PHPSTORM_META\type;

ini_set('memory_limit', '256M');

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
            $vak = "v.vak_naam";
            $vaken = "le_vakken_ps v ON e.vak = v.id";
        } else {
            $vak = "v.volledigenaamvak";
            $vaken = "le_vakken v ON e.vak = v.ID";
        }

        $get_extra = "SELECT e.schooljaar,e.klas,e.rapnummer," . $vak . ",e.oc1,e.oc2,e.oc3,e.oc4,e.oc5,e.oc6,e.oc7,e.oc8,e.oc9,e.oc10,e.oc11,e.oc12,e.oc13,e.oc14,e.oc15,e.oc16,e.oc17,e.oc18,e.oc19,e.oc20
            FROM le_cijfersextra e INNER JOIN " . $vaken . " WHERE e.schoolid = " . $row["id"] . " ORDER BY e.rapnummer";
        $result_extra = mysqli_query($mysqli, $get_extra);
        while ($row_extra = mysqli_fetch_assoc($result_extra)) {
            for ($i = 1; $i <= 20; $i++) {
                $oc = "oc" . $i;
                if ($row_extra[$oc] > 0) {
                    $d = array();
                    $d['schoolId'] = $row['api_id'];
                    $d['period'] = $row_extra['schooljaar'];
                    $d['klas'] = $row_extra['klas'];
                    $d['vak'] = $row_extra[substr($vak, 2)];
                    $d['id'] = $row_extra['rapnummer'];
                    $d['description'] = $row_extra[$oc];
                    $data[] = $d;
                }
            }
        }

        // if ($row["type"] == 1) {
        //     $get_periods = "SELECT DISTINCT cp.schooljaar FROM le_cijfers_ps cp WHERE cp.school_id = " . $row["id"] . " AND cp.gemiddelde > 0.0 ORDER BY cp.schooljaar";
        //     $result_periods = mysqli_query($mysqli, $get_periods);
        //     while ($row_periods = mysqli_fetch_assoc($result_periods)) {
        //         $d = array();
        //         $d['schoolId'] = $row['api_id'];
        //         $d['range'] = $row_periods['schooljaar'];
        //         $d['start'] = substr($row_periods['schooljaar'], 0, 4);
        //         $d['end'] = substr($row_periods['schooljaar'], 5, 4);
        //         $data[] = $d;
        //     }
        // } else {
        //     $get_periods = "SELECT e.schooljaar,e.klas,e.rapnummer,e.vak,e.oc1,e.oc2,e.oc3,e.oc4,e.oc5,e.oc6,e.oc7,e.oc8,e.oc9,e.oc10,e.oc11,e.oc12,e.oc13,e.oc14,e.oc15,e.oc16,e.oc17,e.oc18,e.oc19,e.oc20
        //     FROM le_cijfersextra e
        //     WHERE e.schoolid = " . $row["id"] . " ORDER BY e.rapnummer";
        //     $result_periods = mysqli_query($mysqli, $get_periods);
        //     while ($row_periods = mysqli_fetch_assoc($result_periods)) {
        //         $d = array();
        //         $d['schoolId'] = $row['api_id'];
        //         $d['range'] = $row_periods['schooljaar'];
        //         $d['start'] = substr($row_periods['schooljaar'], 0, 4);
        //         $d['end'] = substr($row_periods['schooljaar'], 5, 4);
        //         $data[] = $d;
        //     }
        // }
    }
}

$json_response = json_encode($data);

header('Content-Type: application/json');

echo $json_response;
