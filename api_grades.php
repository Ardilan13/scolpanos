<?php

use function PHPSTORM_META\type;

ini_set('memory_limit', '256M');

session_start();
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
// $get = "SELECT id,api_id,schoolname as name,schooltype as type FROM schools WHERE id > 3 ORDER BY id";
// $result = mysqli_query($mysqli, $get);

// $data = array();
// if ($result->num_rows > 0) {
//     while ($row = mysqli_fetch_assoc($result)) {
// if ($row["type"] == 1) {
$get_grades = "SELECT sc.api_id,s.studentnumber,c.schooljaar,c.rapnummer,c.klas,(SELECT v.vak_naam FROM le_vakken_ps v WHERE v.id = c.vak LIMIT 1) as vak,c.c1,c.c2,c.c3,c.c4,c.c5,c.c6,c.c7,c.c8,c.c9,c.c10,c.c11,c.c12,c.c13,c.c14,c.c15,c.c16,c.c17,c.c18,c.c19,c.c20 
                FROM le_cijfers_ps c INNER JOIN students s ON c.studentid = s.id INNER JOIN schools sc ON s.schoolid = sc.id WHERE sc.api_id != '' AND c.school_id = 4 AND c.schooljaar = '2023-2024' AND c.rapnummer = 1 AND (c1 > 0 OR c2 > 0 OR c3 > 0 OR c4 > 0 OR c5 > 0 OR c6 > 0 OR c7 > 0 OR c8 > 0 OR c9 > 0 OR c10 > 0 OR c11 > 0 OR c12 > 0 OR c13 > 0 OR c14 > 0 OR c15 > 0 OR c16 > 0 OR c17 > 0 OR c18 > 0 OR c19 > 0 OR c20 > 0) ORDER BY c.schooljaar";
$vak = "v.vak_naam";
$vaken = "le_vakken_ps v ON e.vak = v.id";
// } else {
//     $get_grades = "SELECT sc.api_id,s.studentnumber,c.schooljaar,c.rapnummer,c.klas,c.c1,c.c2,c.c3,c.c4,c.c5,c.c6,c.c7,c.c8,c.c9,c.c10,c.c11,c.c12,c.c13,c.c14,c.c15,c.c16,c.c17,c.c18,c.c19,c.c20 
//                 FROM le_cijfers c INNER JOIN students s ON c.studentid = s.id INNER JOIN le_vakken v ON c.vak = v.ID WHERE v.SchoolID = " . $row["id"] . " ORDER BY c.schooljaar";
//     $vak = "v.volledigenaamvak";
//     $vaken = "le_vakken v ON e.vak = v.ID";
// }
$result_grade = mysqli_query($mysqli, $get_grades);
while ($row_grade = mysqli_fetch_assoc($result_grade)) {
    // for ($i = 1; $i <= 20; $i++) {
    $w = "c1";
    if ($row_grade[$w] > 0) {
        $d = array();
        $d['schoolId'] = $row_grade['api_id'];
        $d['period'] = $row_grade['schooljaar'];
        $d['vak'] = $row_grade["vak"];
        $d['rapport'] = $row_grade['rapnummer'];
        $d['klas'] = $row_grade['klas'];
        $d['rank'] = $row_grade[$w];
        $d['sku'] = "c" . $i;
        $d['student'] = $row_grade['studentnumber'];
        // $extra = "e.oc" . $i;
        // $warde = "w.c" . $i;
        // $get_report = "SELECT e.rapnummer," . $vak . "," . $extra . "," . $warde . " 
        // FROM le_cijfersextra e INNER JOIN " . $vaken . " LEFT JOIN le_cijferswaarde w ON e.schooljaar = w.schooljaar AND e.klas = w.klas AND e.rapnummer = w.rapnummer AND e.vak = w.vak 
        // WHERE e.schoolid = " . $row["id"] . " AND e.schooljaar = '" . $row_grade['schooljaar'] . "' AND e.klas = '" . $row_grade["klas"] . "' AND e.rapnummer = " . $row_grade['rapnummer'] . " LIMIT 1";
        // $result_report = mysqli_query($mysqli, $get_report);
        // while ($row_report = mysqli_fetch_assoc($result_report)) {
        //     $r = array();
        //     $r['schoolId'] = $row['api_id'];
        //     $r['period'] = $row_grade['schooljaar'];
        //     $r['vak'] = $row_report[substr($vak, 2)];
        //     $r['id'] = $row_report['rapnummer'];
        //     $r['description'] = $row_report[$extra];
        //     $r['percentile'] = $row_report[$warde];
        //     $d['report'] = $r;
        // }
        $data[] = $d;
    }
    // }
    //     }
    // }
}

$json_response = json_encode($data);

header('Content-Type: application/json');

echo $json_response;
