<?php
session_start();
require_once "../classes/DBCreds.php";

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
$week = $_POST['week'];
$l1 = $_POST["l_1"];
$l2 = $_POST["l_2"];
$l3 = $_POST["l_3"];
$l4 = $_POST["l_4"];
$l5 = $_POST["l_5"];
$l6 = $_POST["l_6"];
$l7 = $_POST["l_7"];
$l8 = $_POST["l_8"];
$m1 = $_POST["m_1"];
$m2 = $_POST["m_2"];
$m3 = $_POST["m_3"];
$m4 = $_POST["m_4"];
$m5 = $_POST["m_5"];
$m6 = $_POST["m_6"];
$m7 = $_POST["m_7"];
$m8 = $_POST["m_8"];
$x1 = $_POST["x_1"];
$x2 = $_POST["x_2"];
$x3 = $_POST["x_3"];
$x4 = $_POST["x_4"];
$x5 = $_POST["x_5"];
$x6 = $_POST["x_6"];
$x7 = $_POST["x_7"];
$x8 = $_POST["x_8"];
$j1 = $_POST["j_1"];
$j2 = $_POST["j_2"];
$j3 = $_POST["j_3"];
$j4 = $_POST["j_4"];
$j5 = $_POST["j_5"];
$j6 = $_POST["j_6"];
$j7 = $_POST["j_7"];
$j8 = $_POST["j_8"];
$v1 = $_POST["v_1"];
$v2 = $_POST["v_2"];
$v3 = $_POST["v_3"];
$v4 = $_POST["v_4"];
$v5 = $_POST["v_5"];
$v6 = $_POST["v_6"];
$v7 = $_POST["v_7"];
$v8 = $_POST["v_8"];
$notes = $_POST["notes"];
$schooljaar = $_SESSION["SchoolJaar"];
$user = $_SESSION["UserGUID"];
date_default_timezone_set("America/Aruba");
$updated = date('d-m-Y H:i:s');
$query_test = "SELECT id FROM verwerk WHERE user = '$user' AND schooljaar = '$schooljaar' AND week = '$week'";
$resultado = mysqli_query($mysqli, $query_test);
if (mysqli_num_rows($resultado) != 0) {
    $exist = 1;
    $query = "UPDATE verwerk SET l1='$l1',l2='$l2',l3='$l3',l4='$l4',l5='$l5',l6='$l6',l7='$l7',l8='$l8',m1='$m1',m2='$m2',m3='$m3',m4='$m4',m5='$m5',m6='$m6',m7='$m7',m8='$m8',x1='$x1',x2='$x2',x3='$x3',x4='$x4',x5='$x5',x6='$x6',x7='$x7',x8='$x8',j1='$j1',j2='$j2',j3='$j3',j4='$j4',j5='$j5',j6='$j6',j7='$j7',j8='$j8',v1='$v1',v2='$v2',v3='$v3',v4='$v4',v5='$v5',v6='$v6',v7='$v7',v8='$v8',notes='$notes',updated='$updated' WHERE user = '$user' AND schooljaar = '$schooljaar' AND week = '$week'";
} else {
    $query = "INSERT INTO verwerk (user, updated, schooljaar, week, notes, l1, l2, l3, l4, l5, l6, l7, l8, m1, m2, m3, m4, m5, m6, m7, m8, x1, x2, x3, x4, x5, x6, x7, x8, j1, j2, j3, j4, j5, j6, j7, j8, v1, v2, v3, v4, v5, v6, v7, v8) VALUES ('$user','$updated','$schooljaar','$week','$notes', '$l1', '$l2', '$l3', '$l4', '$l5', '$l6', '$l7', '$l8', '$m1', '$m2', '$m3', '$m4', '$m5', '$m6', '$m7', '$m8', '$x1', '$x2', '$x3', '$x4', '$x5', '$x6', '$x7', '$x8', '$j1', '$j2', '$j3', '$j4', '$j5', '$j6', '$j7', '$j8', '$v1', '$v2', '$v3', '$v4', '$v5', '$v6', '$v7', '$v8')";
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
