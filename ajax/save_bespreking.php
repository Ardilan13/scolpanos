<?php
require_once "../classes/DBCreds.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$schoolid = $_SESSION["SchoolID"];
$datum = date('Y-m-d');
$schooljaar = $_POST["schooljaar"];
$klas = $_POST["klas"];
$rapport = $_POST["rap"];
$studentid = $_POST["studentid"];
$opmerking = $_POST["opmerking"];
$definitiet = $_POST["definitiet"];
$radio1 = $_POST["radio1"] != null ? $_POST["radio1"] : 0;
$radio2 = $_POST["radio2"] != null ? $_POST["radio2"] : 0;
$radio3 = $_POST["radio3"] != null ? $_POST["radio3"] : 0;

$sql = "SELECT * FROM opmerking WHERE SchoolID = '$schoolid' AND klas = '$klas' AND studentid = '$studentid' AND rapport = $rapport AND schooljaar ='$schooljaar';";
$result = mysqli_query($mysqli, $sql);
if ($result->num_rows > 0) {
    $sql = "UPDATE opmerking SET opmerking1 = ?, opmerking2 = ?,opmerking3 = ?,advies = ?, ciclo = ? WHERE SchoolID = ? AND klas = ? AND studentid = ? AND rapport = ? AND schooljaar = ?;";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, "sssssisiis", $opmerking, $radio1, $definitiet, $radio2, $radio3, $schoolid, $klas, $studentid, $rapport, $schooljaar);
    if (mysqli_stmt_execute($stmt)) {
        echo "Opmerkingen Updated";
    } else {
        echo "Error update";
    }
} else {
    $sql = "INSERT INTO opmerking (SchoolID, klas, studentid, rapport, schooljaar, opmerking1, opmerking2, opmerking3, advies, ciclo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, "isiissssis", $schoolid, $klas, $studentid, $rapport, $schooljaar, $opmerking, $radio1, $definitiet, $radio2, $radio3);
    if (mysqli_stmt_execute($stmt)) {
        echo "Opmerkingen Saved";
    } else {
        echo "Error insert";
    }
}
