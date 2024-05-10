<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("../classes/DBCreds.php");
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

if (isset($_FILES['signature'])) {
    $user = $_SESSION["UserGUID"];
    $archivo_nombre = $_FILES['signature']['name'];
    $archivo_extension = pathinfo($archivo_nombre, PATHINFO_EXTENSION);
    $archivo_temporal = $_FILES['signature']['tmp_name'];
    $archivo_tipo = $_FILES['signature']['type'];
    $archivo_tamano = $_FILES['signature']['size'];

    $directorio_destino = './../signatures/';

    $archivos_usuario = glob($directorio_destino . $user . ".*");
    foreach ($archivos_usuario as $archivo) {
        unlink($archivo);
    }

    $nombre_archivo_final = $user . '.' . $archivo_extension;

    if (move_uploaded_file($archivo_temporal, $directorio_destino . $nombre_archivo_final)) {
        $sql_query = "UPDATE `app_useraccounts` SET signature = '$nombre_archivo_final' WHERE userGUID = '$user'";
        $result = mysqli_query($mysqli, $sql_query);
        if ($result) {
            echo "<script>
                        window.location.href = '../signature.php?e=1';
                  </script>";
        } else {
            echo "<script>
                        window.location.href = '../signature.php?e=0';
                  </script>";
        }
    } else {
        echo "<script>
                    window.location.href = '../signature.php?e=0'
              </script>";
    }
} else if (isset($_GET['delete'])) {
    $user = $_SESSION["UserGUID"];
    $directorio_destino = './../signatures/';
    $archivos_usuario = glob($directorio_destino . $user . ".*");
    foreach ($archivos_usuario as $archivo) {
        unlink($archivo);
    }
    $sql_query = "UPDATE `app_useraccounts` SET signature = NULL WHERE userGUID = '$user'";
    $result = mysqli_query($mysqli, $sql_query);
    echo "<script>
                    window.location.href = '../signature.php?e=2';
              </script>";
}
