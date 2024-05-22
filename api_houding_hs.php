<?php
ini_set('memory_limit', '512M');
session_start();
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

$json = "./assets/js/ps_houding_config.json";
$jsonData = file_get_contents($json);
$dataArray = json_decode($jsonData, true);

$get_houding_ps = "SELECT s.studentnumber,s.firstname,s.lastname,sc.api_id,h.schooljaar,h.rapnummer,h.klas,h.vakid,v.volledigenaamvak,
                    h.h1,h.h2,h.h3,h.h4,h.h5,h.h6,h.h7,h.h8,h.h9,h.h10,h.h11,h.h12,h.h13,h.h14,h.h15,h.h16,h.h17,h.h18,h.h19,h.h20,h.h21,h.h22,h.h23,h.h24,h.h25 
                    FROM le_houding_hs h 
                    INNER JOIN students s ON h.studentid = s.id
                    INNER JOIN schools sc ON s.schoolid = sc.id
                    INNER JOIN le_vakken v ON h.vakid = v.id AND v.schoolid = sc.id
                    WHERE sc.api_id != '' AND sc.schooltype = 2 AND v.ID > 0 AND v.ID IS NOT NULL
                    AND (h.h1 > 0 OR h.h2 > 0 OR h.h3 > 0 OR h.h4 > 0 OR h.h5 > 0 OR h.h6 > 0 OR h.h7 > 0 OR h.h8 > 0 OR h.h9 > 0 OR h.h10 > 0 OR h.h11 > 0 OR h.h12 > 0 OR h.h13 > 0 OR h.h14 > 0 OR h.h15 > 0 OR h.h16 > 0 OR h.h17 > 0 OR h.h18 > 0 OR h.h19 > 0 OR h.h20 > 0 OR h.h21 > 0 OR h.h22 > 0 OR h.h23 > 0 OR h.h24 > 0 OR h.h25 > 0)";

$data = array();
if ($result_houding = mysqli_query($mysqli, $get_houding_ps)) {
    while ($row_houding = mysqli_fetch_assoc($result_houding)) {
        $d = array();
        $houding = array();
        $criteria = array();
        for ($i = 1; $i <= 25; $i++) {
            $key = "h" . $i;
            if (isset($row_houding[$key]) && $row_houding[$key] > 0 && $row_houding[$key] != null) {
                if (isset($dataArray[0][$key])) {
                    $houding[$dataArray[0][$key]] = $row_houding[$key];
                } else {
                    $houding[$key] = $row_houding[$key];
                }
            }
        }
        foreach ($houding as $key => $value) {
            $criteria[] = ["sku" => $key, "grade" => $value];
        }
        $d['criteria'] = $criteria;

        $rapnummer = htmlspecialchars($row_houding['rapnummer'], ENT_QUOTES, 'UTF-8');
        $schooljaar = htmlspecialchars($row_houding['schooljaar'], ENT_QUOTES, 'UTF-8');
        $firstname = htmlspecialchars($row_houding['firstname'], ENT_QUOTES, 'UTF-8');
        $lastname = htmlspecialchars($row_houding['lastname'], ENT_QUOTES, 'UTF-8');

        $d['report'] = ["id" => $rapnummer, "period" => $schooljaar];
        $d['schoolId'] = $row_houding['api_id'];
        $d['student'] = ["name" => $firstname, "lastname" => $lastname];
        $d['class'] = $row_houding['klas'];
        $d['userId'] = $row_houding['studentnumber'];
        $d['subject'] = $row_houding['volledigenaamvak'];

        $data[] = $d;
    }
    mysqli_free_result($result_houding); // Libera el resultado de la memoria
}

mysqli_close($mysqli); // Cierra la conexión a la base de datos

$json_response = json_encode($data);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'Error al codificar JSON: ' . json_last_error_msg();
} else {
    header('Content-Type: application/json');
    echo $json_response;
}
