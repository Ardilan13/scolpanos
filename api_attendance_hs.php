<?php
ini_set('memory_limit', '512M');
session_start();
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

$get_verzuim_ps = "SELECT s.studentnumber,sc.api_id,v.schooljaar,v.p1,v.p2,v.p3,v.p4,v.p5,v.p6,v.p7,v.p8,v.p9,v.p10,v.datum
                    FROM le_verzuim_hs v
                    INNER JOIN students s ON v.studentid = s.id
                    INNER JOIN schools sc ON s.schoolid = sc.id
                    WHERE sc.api_id != '' AND sc.schooltype = 2 AND v.schooljaar IS NOT NULL
                    AND (v.p1 IN ('A','L','S','X','M','T','U') OR 
                    v.p2 IN ('A','L','S','X','M','T','U') OR 
                    v.p3 IN ('A','L','S','X','M','T','U') OR 
                    v.p4 IN ('A','L','S','X','M','T','U') OR 
                    v.p5 IN ('A','L','S','X','M','T','U') OR 
                    v.p6 IN ('A','L','S','X','M','T','U') OR 
                    v.p7 IN ('A','L','S','X','M','T','U') OR 
                    v.p8 IN ('A','L','S','X','M','T','U') OR 
                    v.p9 IN ('A','L','S','X','M','T','U') OR 
                    v.p10 IN ('A','L','S','X','M','T','U'))";

$data = array();
if ($result_verzuim = mysqli_query($mysqli, $get_verzuim_ps)) {
    while ($row_verzuim = mysqli_fetch_assoc($result_verzuim)) {
        $d = array();
        $verzuim = array();
        $verzuim = ["p1" => $row_verzuim['p1'], "p2" => $row_verzuim['p2'], "p3" => $row_verzuim['p3'], "p4" => $row_verzuim['p4'], "p5" => $row_verzuim['p5'], "p6" => $row_verzuim['p6'], "p7" => $row_verzuim['p7'], "p8" => $row_verzuim['p8'], "p9" => $row_verzuim['p9'], "p10" => $row_verzuim['p10']];
        $verzuim = array_filter($verzuim, function ($value) {
            return ($value == "A" || $value == "L" || $value == "S" || $value == "X" || $value == "M" || $value == "T" || $value == "U");
        });

        foreach ($verzuim as $key => $value) {
            $d['period'] = $row_verzuim['schooljaar'];
            $d['schoolId'] = $row_verzuim['api_id'];
            $d['userId'] = $row_verzuim['studentnumber'];
            $d['date'] = $row_verzuim['datum'];
            switch ($value) {
                case "L":
                    $key = "laat";
                    break;
                case "A":
                    $key = "absent";
                    break;
                case "X":
                    $key = "afspraak";
                    break;
                case "S":
                    $key = "spijbelen";
                    break;
                case "M":
                    $key = "toestemming";
                    break;
                case "U":
                    $key = "uitgestuurd";
                    break;
                case "T":
                    $key = "time-out";
                    break;
                default:
                    $key = "";
                    break;
            }
            $d['type'] = $key;
            $data[] = $d;
        }
    }
    mysqli_free_result($result_verzuim);
}

mysqli_close($mysqli);

$json_response = json_encode($data);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'Error al codificar JSON: ' . json_last_error_msg();
} else {
    header('Content-Type: application/json');
    echo $json_response;
}
