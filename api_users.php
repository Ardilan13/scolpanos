<?php

use function PHPSTORM_META\type;

session_start();
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$get = "SELECT id FROM schools WHERE id > 3 ORDER BY id";
$result = mysqli_query($mysqli, $get);

$data = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $school_id = $row['id'];
        $get_students = "SELECT firstname,lastname,dob,class,studentnumber,securepin FROM students WHERE schoolid = $school_id ORDER BY id";
        $result_students = mysqli_query($mysqli, $get_students);
        while ($row_students = mysqli_fetch_assoc($result_students)) {
            $d = array();
            $d['schoolId'] = $school_id; // Corregido: Asignar el ID de la escuela aquí
            $d['student'] = ['name' => utf8_encode($row_students['firstname']), 'lastname' => utf8_encode($row_students["lastname"]), 'birthdate' => $row_students['dob'], 'class' => ['group' => $row_students['class']]];
            $d['pin'] = $row_students['studentnumber'];
            $d['password'] = $row_students['securepin'];
            $data[] = $d;
        }
    }
}

$json_response = json_encode($data);

header('Content-Type: application/json');

echo $json_response;
