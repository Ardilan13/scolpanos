<?php
require_once "../classes/DBCreds.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$type = $_POST["type"];
$id = $_POST["vakid"];
if ($type == "delete") {
    $sql = "DELETE FROM groups WHERE id = $id;";
    $result = mysqli_query($mysqli, $sql);
    if ($result) {
        echo 3;
    } else {
        echo 0 . $sql;
    }
} else {
    $schoolid = $_SESSION["SchoolID"];
    $schooljaar = $_SESSION["SchoolJaar"];
    $name = $_POST["group_name"];
    $vak = $_POST["group_vak"];

    $sql = "SELECT * FROM groups WHERE schoolid = '$schoolid' AND vak = '$vak' AND schooljaar ='$schooljaar' AND id = $id;";
    $result = mysqli_query($mysqli, $sql);
    if ($result->num_rows > 0) {
        $sqlu = "UPDATE groups SET name = '$name', vak = '$vak' WHERE id = $id;";
        $result = mysqli_query($mysqli, $sqlu);
        if ($result) {
            echo 2;
        } else {
            echo 0 . $sql;
        }
    } else {
        $sqli = "INSERT INTO groups (schoolid,schooljaar,name,vak) VALUES ($schoolid,'$schooljaar', '$name',$vak);";
        $result = mysqli_query($mysqli, $sqli);
        if ($result) {
            echo 1;
        } else {
            echo 0 . $sql;
        }
    }
}
