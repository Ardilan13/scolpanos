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
            ,w.c1,w.c2,w.c3,w.c4,w.c5,w.c6,w.c7,w.c8,w.c9,w.c10,w.c11,w.c12,w.c13,w.c14,w.c15,w.c16,w.c17,w.c18,w.c19,w.c20
            FROM le_cijfersextra e INNER JOIN " . $vaken . " LEFT JOIN le_cijferswaarde w ON e.schooljaar = w.schooljaar AND e.klas = w.klas AND e.rapnummer = w.rapnummer AND e.vak = w.vak WHERE e.schoolid = " . $row["id"] . " ORDER BY e.rapnummer";
        $result_extra = mysqli_query($mysqli, $get_extra);
        while ($row_extra = mysqli_fetch_assoc($result_extra)) {
            for ($i = 1; $i <= 20; $i++) {
                $oc = "oc" . $i;
                $w = "c" . $i;
                if ($row_extra[$oc] != NULL || $row_extra[$w] > 0) {
                    $d = array();
                    $d['schoolId'] = $row['api_id'];
                    $d['period'] = $row_extra['schooljaar'];
                    $d['klas'] = $row_extra['klas'];
                    $d['vak'] = $row_extra[substr($vak, 2)];
                    $d['id'] = $row_extra['rapnummer'];
                    $d['description'] = $row_extra[$oc];
                    $d['percentile'] = $row_extra[$w];
                    $d['position'] = $i;
                    $data[] = $d;
                }
            }
        }
    }
}

$json_response = json_encode($data);

header('Content-Type: application/json');

echo $json_response;
