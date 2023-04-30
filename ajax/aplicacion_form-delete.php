<?php
require_once("../classes/DBCreds.php");
$DBCreds = new DBCreds();
date_default_timezone_set("America/Aruba");
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

$id = $_POST["id"];
$query = "DELETE FROM aplicacion_forms WHERE ID = $id";
$resultado = mysqli_query($mysqli, $query);
if ($resultado) {
    $user = $_SERVER['REMOTE_ADDR'];
    $date = date('Y-m-d H:i:s');
    $audit = "INSERT INTO audit (UserGUID,module,audit_function,audit_datetime) VALUES ('$user','aplicacion_forms delete',$id,'$date')";
    $resultado1 = mysqli_query($mysqli, $audit);
    if ($resultado1) {
        print 1;
    } else {
        print 0;
    }
} else {
    print 0;
}
/* 
$search = "SELECT ID FROM aplicacion_forms WHERE ID = $id";
$resultado1 = mysqli_query($mysqli, $search);
if (mysqli_num_rows($resultado1) != 0) {
    echo 0;
} else {
    echo 1;
} */
