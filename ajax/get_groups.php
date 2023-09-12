<?php
require_once "../classes/DBCreds.php";
session_start();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$schoolid = $_SESSION["SchoolID"];
$user = $_SESSION["UserGUID"];
$json = array();

if ($_SESSION["UserRights"] == "DOCENT") {
    $get_students = "SELECT g.vak,h.vak as gro,g.name FROM user_hs h INNER JOIN groups g ON h.vak = g.id WHERE h.user_GUID = '$user' AND h.schoolid = '$schoolid' AND h.klas = '4' ORDER BY g.id";
    $result = mysqli_query($mysqli, $get_students);
    if ($result->num_rows > 0) {
        while ($row1 = mysqli_fetch_assoc($result)) {
            $json[] = array("id" => $row1['vak'], "vak" => $row1['name'], "group" => $row1['gro']);
        }
    } else {
        $json[] = array("id" => "0", "vak" => "SN", "group" => "0");
    }
} else {
    $get_students = "SELECT g.vak,g.name,g.id as gro FROM groups g WHERE g.schoolid = $schoolid ORDER BY g.id";
    $result = mysqli_query($mysqli, $get_students);
    if ($result->num_rows > 0) {
        while ($row1 = mysqli_fetch_assoc($result)) {
            $json[] = array("id" => $row1['vak'], "vak" => $row1['name'], "group" => $row1['gro']);
        }
    } else {
        $json[] = array("id" => "0", "vak" => "SN", "group" => "0");
    }
}


print(json_encode($json));
