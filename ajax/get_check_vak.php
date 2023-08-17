<?php
require_once "../classes/DBCreds.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$schoolid = $_SESSION["SchoolID"];
$schooljaar = $_SESSION["SchoolJaar"];
$name = $_GET['group_name'];

$sql = "SELECT id FROM groups WHERE schoolid = '$schoolid' AND name = '$name' AND schooljaar ='$schooljaar';";
$result = mysqli_query($mysqli, $sql);
if ($result->num_rows > 0) {
    echo 1;
} else {
    echo 0;
}
