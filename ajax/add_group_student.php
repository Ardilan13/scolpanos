<?php
require_once "../classes/DBCreds.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$paket = null;
$check = $_POST["check"];
$id = $_POST["id"];
$group = $_POST["group"];
$schooljaar = $_SESSION["SchoolJaar"];
$vak = $_POST["vak"];
$name = $_POST["name"];
$vrijstelling = ["e1" => 'NE', "e2" => 'EN', "e3" => 'SP', "e4" => 'PA', "e5" => 'WI', "e6" => 'NA', "e7" => 'SK', "e8" => 'BI', "e9" => 'EC', "e10" => 'AK', "e11" => 'GS', "e12" => 'RE'];
$eba = array_search($name, $vrijstelling);

if ($check == "true") {
    $sqli = "INSERT INTO group_student (group_id,student_id,schooljaar) VALUES ($group, '$id','$schooljaar');";
    $result = mysqli_query($mysqli, $sqli);
    if ($result && $eba != false && $eba != "") {
        $eba_update = "UPDATE eba_ex 
        INNER JOIN personalia ON eba_ex.id_personalia = personalia.id
        SET $eba = 'X'
        WHERE 
            eba_ex.schooljaar = '$schooljaar' 
            AND eba_ex.type = 0 
            AND personalia.studentid = $id
            AND eba_ex.schoolid = " . $_SESSION["SchoolID"] . ";";
        $result = mysqli_query($mysqli, $eba_update);
    }
} else {
    $sql = "DELETE FROM group_student WHERE student_id = $id AND group_id = $group AND schooljaar = '$schooljaar';";
    $result = mysqli_query($mysqli, $sql);
    if ($result && $eba != false && $eba != "") {
        $eba_update = "UPDATE eba_ex 
        INNER JOIN personalia ON eba_ex.id_personalia = personalia.id
        SET $eba = NULL
        WHERE 
            eba_ex.schooljaar = '$schooljaar' 
            AND eba_ex.type = 0 
            AND personalia.studentid = $id
            AND eba_ex.schoolid = " . $_SESSION["SchoolID"] . ";";
        $result = mysqli_query($mysqli, $eba_update);
    }
}

//ACTUALIZACION PAKET
$get = "SELECT SUBSTRING(gr.name, 1, 2) AS name FROM group_student g INNER JOIN groups gr ON gr.id = g.group_id WHERE g.student_id = $id AND g.schooljaar = '$schooljaar' AND gr.schoolid = " . $_SESSION["SchoolID"] . ";";
$result = mysqli_query($mysqli, $get);
while ($row = mysqli_fetch_assoc($result)) {
    $student_group[] = strtoupper($row["name"]);
}
sort($student_group);

$get_paket = "SELECT * FROM paket";
$result = mysqli_query($mysqli, $get_paket);
while ($row = mysqli_fetch_assoc($result)) {
    $vaks_paket = [$row['g1'], $row['g2'], $row['g3'], $row['g4'], $row['p1'], $row['p2'], $row['p3'], $row['k1'], $row['k2'], $row['k3']];
    $vaks_paket = array_filter($vaks_paket, function ($elemento) {
        return strpos($elemento, 'CKV') !== 0 && !empty($elemento);
    });
    sort($vaks_paket);
    $paket = ($vaks_paket == $student_group && $paket == null) ? $row['paket'] : $paket;
    if ($paket !== null) {
        $profiel = "UPDATE students SET profiel = '$paket' WHERE id = $id;";
        $result = mysqli_query($mysqli, $profiel);
        echo $paket;
        break;
    }
}
