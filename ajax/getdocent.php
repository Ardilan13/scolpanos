<?php
session_start();
include_once '../classes/DBCreds.php';
$schoolid = $_SESSION["SchoolID"];
$json = array();

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$select_docents = "SELECT UserGUID,CONCAT(LastName,' ',FirstName) as name FROM `app_useraccounts` where SchoolID = $schoolid and UserRights = 'DOCENT' AND AccountEnabled = 1";
$result = mysqli_query($mysqli, $select_docents);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $json[] = array("user" => $row["UserGUID"], "name" => $row["name"]);
    }
    echo json_encode($json);
} else {
    echo "Error";
}
