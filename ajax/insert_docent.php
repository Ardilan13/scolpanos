<?php
session_start();
include_once '../classes/DBCreds.php';
$docent = $_POST["docent"];
$code = $_POST["code"];
$vak = $_POST["vak"];
$schoolid = $_SESSION["SchoolID"];
$schooljaar = $_SESSION["SchoolJaar"];

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

if (isset($_POST['id']) && $_POST['id'] != '' && $_POST['id'] != null) {
    if ($code == 'delete') {
        $delete_docent = "DELETE FROM eba_docentlist WHERE id = " . $_POST['id'];
        $result = mysqli_query($mysqli, $delete_docent);
        if ($result) {
            echo $delete_docent;
        } else {
            echo "Error deleting docent";
            echo $delete_docent;
        }
    } else {
        $update_docent = "UPDATE eba_docentlist SET docent = '$docent', vak = '$vak', code = '$code' WHERE id = " . $_POST['id'];
        $result = mysqli_query($mysqli, $update_docent);
        if ($result) {
            echo $update_docent;
        } else {
            echo "Error updating docent";
            echo $update_docent;
        }
    }
} else {
    $insert_docent = "INSERT INTO eba_docentlist (docent,vak,code,schooljaar,schoolid) values ('$docent','$vak','$code','$schooljaar',$schoolid);";
    $result = mysqli_query($mysqli, $insert_docent);
    if ($result) {
        echo $insert_docent;
    } else {
        echo "Error saving docent";
        echo $insert_docent;
    }
}
