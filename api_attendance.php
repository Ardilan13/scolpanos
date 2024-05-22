<?php
ini_set('memory_limit', '512M');
session_start();
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

$get_verzuim_ps = "SELECT s.studentnumber,sc.api_id,v.schooljaar,v.telaat,v.absentie,v.toetsinhalen,v.uitsturen,v.huiswerk,v.datum
                    FROM le_verzuim v
                    INNER JOIN students s ON v.studentid = s.id
                    INNER JOIN schools sc ON s.schoolid = sc.id
                    WHERE sc.api_id != '' AND sc.schooltype = 1 AND sc.id > 3 AND v.schooljaar IS NOT NULL AND v.datum != '--' AND v.datum != '0000-00-00'
                    AND (v.telaat > 0 OR v.absentie > 0 OR v.toetsinhalen > 0 OR v.uitsturen > 0 OR v.huiswerk)";

$data = array();
if ($result_verzuim = mysqli_query($mysqli, $get_verzuim_ps)) {
    while ($row_verzuim = mysqli_fetch_assoc($result_verzuim)) {
        $d = array();
        $verzuim = array();
        $verzuim = ["telaat" => $row_verzuim['telaat'], "absentie" => $row_verzuim['absentie'], "toetsinhalen" => $row_verzuim['toetsinhalen'], "uitsturen" => $row_verzuim['uitsturen'], "huiswerk" => $row_verzuim['huiswerk']];
        $verzuim = array_filter($verzuim, function ($value) {
            return $value > 0;
        });

        foreach ($verzuim as $key => $value) {
            if ($value > 0) {
                $d['period'] = $row_verzuim['schooljaar'];
                $d['schoolId'] = $row_verzuim['api_id'];
                $d['userId'] = $row_verzuim['studentnumber'];
                $d['date'] = $row_verzuim['datum'];
                $d['type'] = $key;
                $data[] = $d;
            }
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
