<?php
session_start();
require_once "../classes/DBCreds.php";

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
$week = $_POST['week'];
$dialuna = $_POST["dialuna"];
$diamars = $_POST["diamars"];
$diaranson = $_POST["diaranson"];
$diahuebs = $_POST["diahuebs"];
$diabierna = $_POST["diabierna"];
$klas = $_POST["klas"];
$schooljaar = $_SESSION["SchoolJaar"];
$user = $_SESSION["UserGUID"];
$evaluacion = $_POST["evaluacion"];
date_default_timezone_set("America/Aruba");
$updated = date('d-m-Y H:i:s');
$query_test = "SELECT id FROM planning WHERE user = '$user' AND schooljaar = '$schooljaar' AND week = '$week'";
$resultado = mysqli_query($mysqli, $query_test);
if (mysqli_num_rows($resultado) != 0) {
    $exist = 1;
    $query = "UPDATE planning SET dialuna='$dialuna',diamars='$diamars',diaranson='$diaranson',diahuebs='$diahuebs',diabierna='$diabierna',klas='$klas',evaluacion='$evaluacion',updated='$updated' WHERE user = '$user' AND schooljaar = '$schooljaar' AND week = '$week'";
} else {
    $query = "INSERT INTO planning (user, updated, schooljaar, klas, week, dialuna, diamars, diaranson, diahuebs, diabierna, evaluacion) VALUES ('$user','$updated','$schooljaar','$klas', '$week', '$dialuna', '$diamars', '$diaranson', '$diahuebs', '$diabierna', '$evaluacion')";
}
$resultado = mysqli_query($mysqli, $query);
if ($resultado) {
    if ($exist == 1) {
        print 2;
    } else {
        print 1;
    }
} else {
    print 0;
}
