<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ini_set('max_execution_time', 120); // Establece el límite de tiempo de ejecución a 60 segundos

require_once("../config/app.config.php");
require_once "../classes/3rdparty/vendor/autoload.php";
require_once "../classes/DBCreds.php";
require_once("../classes/spn_setting.php");
require_once("../classes/spn_utils.php");
require_once("../classes/spn_leerling.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$spreadsheet = $reader->load("../templates/diploma.xlsx");
$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

$s = new spn_setting();
$u = new spn_utils();
$DBCreds = new DBCreds();
$l = new spn_leerling();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$schooljaar = $_SESSION['SchoolJaar'];
$schoolid = $_SESSION['SchoolID'];
$schoolname = $_SESSION['schoolname'];
$her = 0;
$mem = 0;
$i = 2;

$get_personalia = "SELECT s.id,p.opmerking,s.lastname,s.firstname,s.sex,s.dob,s.birthplace,s.profiel FROM personalia p INNER JOIN students s ON s.id = p.studentid WHERE p.schoolid = $schoolid AND p.schooljaar = '$schooljaar' ORDER BY";
$sql_order = " lastname , firstname";
if ($s->_setting_mj) {
    $get_personalia .= " sex " . $s->_setting_sort . ", " . $sql_order;
} else {
    $get_personalia .=  $sql_order;
}

$result = mysqli_query($mysqli, $get_personalia);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {

        $timestamp = strtotime($row["dob"]);
        $dia = date("j", $timestamp);
        $mes = date("n", $timestamp);
        switch ($mes) {
            case 1:
                $mes = "januari";
                break;
            case 2:
                $mes = "februari";
                break;
            case 3:
                $mes = "maart";
                break;
            case 4:
                $mes = "april";
                break;
            case 5:
                $mes = "mei";
                break;
            case 6:
                $mes = "juni";
                break;
            case 7:
                $mes = "juli";
                break;
            case 8:
                $mes = "augustus";
                break;
            case 9:
                $mes = "september";
                break;
            case 10:
                $mes = "oktober";
                break;
            case 11:
                $mes = "november";
                break;
            case 12:
                $mes = "december";
                break;
        }
        $ano = date("Y", $timestamp);
        $fecha = $dia . " " . $mes . " " . $ano;
        $hojaActiva->setCellValue('A' . $i, $row["lastname"] . ", " . $row["firstname"]);
        $hojaActiva->setCellValue('B' . $i, $fecha);
        $birthplace = $row["birthplace"];
        $formattedBirthplace = mb_convert_case($birthplace, MB_CASE_TITLE, "UTF-8");
        $hojaActiva->setCellValue('C' . $i, $formattedBirthplace);
        $hojaActiva->setCellValue('D' . $i, date("Y"));
        switch (substr($row["profiel"], 0, 2)) {
            case "MM":
                $profiel = "Mens En Maatschappij";
                break;
            case "NW":
                $profiel = "Natuurwetenschappen";
                break;
            case "HU":
                $profiel = "Humaniora";
                break;
            default:
                $profiel = "";
                break;
        }
        $hojaActiva->setCellValue('E' . $i, $profiel);
        $get_paket = "SELECT g1,g2,g3,g4,p1,p2,p3,k1,k2 FROM paket WHERE paket = '" . $row['profiel'] . "'";
        $result_paket = mysqli_query($mysqli, $get_paket);
        while ($row_paket = mysqli_fetch_assoc($result_paket)) {
            $p1 = $row_paket["g1"];
            $p2 = $row_paket["g2"];
            $p3 = $row_paket["g3"];
            $p4 = $row_paket["g4"];
            $p5 = $row_paket["p1"];
            $p6 = $row_paket["p2"];
            $p7 = $row_paket["p3"];
            $p8 = $row_paket["k1"];
            $p9 = $row_paket["k2"];
        }
        $cijfers = array();

        $get_cijfers = "SELECT
            e.type,
            CASE WHEN e0.e1 IS NOT NULL AND e0.e1 <> '' THEN e.e1 ELSE NULL END AS e1,
            CASE WHEN e0.e2 IS NOT NULL AND e0.e2 <> '' THEN e.e2 ELSE NULL END AS e2,
            CASE WHEN e0.e3 IS NOT NULL AND e0.e3 <> '' THEN e.e3 ELSE NULL END AS e3,
            CASE WHEN e0.e4 IS NOT NULL AND e0.e4 <> '' THEN e.e4 ELSE NULL END AS e4,
            CASE WHEN e0.e5 IS NOT NULL AND e0.e5 <> '' THEN e.e5 ELSE NULL END AS e5,
            CASE WHEN e0.e6 IS NOT NULL AND e0.e6 <> '' THEN e.e6 ELSE NULL END AS e6,
            CASE WHEN e0.e7 IS NOT NULL AND e0.e7 <> '' THEN e.e7 ELSE NULL END AS e7,
            CASE WHEN e0.e8 IS NOT NULL AND e0.e8 <> '' THEN e.e8 ELSE NULL END AS e8,
            CASE WHEN e0.e9 IS NOT NULL AND e0.e9 <> '' THEN e.e9 ELSE NULL END AS e9,
            CASE WHEN e0.e10 IS NOT NULL AND e0.e10 <> '' THEN e.e10 ELSE NULL END AS e10,
            CASE WHEN e0.e11 IS NOT NULL AND e0.e11 <> '' THEN e.e11 ELSE NULL END AS e11,
            CASE WHEN e0.e12 IS NOT NULL AND e0.e12 <> '' THEN e.e12 ELSE NULL END AS e12,
            e.tv1,
            p.ckv,
            p.lo,
            p.her
            FROM eba_ex e
            INNER JOIN personalia p ON e.id_personalia = p.id
            INNER JOIN students s ON p.studentid = s.id
            LEFT JOIN eba_ex e0 ON e0.id_personalia = e.id_personalia AND e0.schooljaar = e.schooljaar AND e0.type = 0
            WHERE s.id = '" . $row['id'] . "'
            AND e.schooljaar = '$schooljaar'
            AND (e.type = '0')
            ORDER BY s.lastname, s.firstname,e.type; ";
        $result1 = mysqli_query($mysqli, $get_cijfers);
        if (mysqli_num_rows($result1) > 0) {
            $eba = array();
            while ($row1 = mysqli_fetch_assoc($result1)) {
                $cijfers[$row1["type"]] = $row1;
            }
            for ($k = 1; $k <= 12; $k++) {
                $pos = "e" . $k;
                if (isset($cijfers[0][$pos]) && $cijfers[0][$pos] != null) {
                    $eba[] = $pos;
                }
            }
        }

        for ($j = 1; $j <= 9; $j++) {
            $vak = "p" . $j;
            $index = false;
            switch ($$vak) {
                case "NE":
                    $pos = "e" . 1;
                    $vak = "Nederlandse taal en literatuur";
                    break;
                case "EN":
                    $pos = "e" . 2;
                    $vak = "Engelse taal en literatuur";
                    break;
                case "SP":
                    $pos = "e" . 3;
                    $vak = "Spaanse taal en literatuur";
                    break;
                case "PA":
                    $pos = "e" . 4;
                    $vak = "Papiamentse taal en cultuur";
                    break;
                case "WI":
                    $pos = "e" . 5;
                    $vak = "Wiskunde";
                    break;
                case "NA":
                    $pos = "e" . 6;
                    $vak = "Natuur- en scheikunde 1";
                    break;
                case "SK":
                    $pos = "e" . 7;
                    $vak = "Natuur- en scheikunde 2";
                    break;
                case "BI":
                    $pos = "e" . 8;
                    $vak = "Biologie";
                    break;
                case "EC":
                    $pos = "e" . 9;
                    $vak = "Economie";
                    break;
                case "AK":
                    $pos = "e" . 10;
                    $vak = "Aardrijkskunde";
                    break;
                case "GS":
                    $pos = "e" . 11;
                    $vak = "Geschiedenis en staatsinrichting";
                    break;
                case "RE":
                    $pos = "e" . 12;
                    $vak = "Religie en levensbeschouwing";
                    break;
                case "CKV":
                    $pos = "ckv";
                    $vak = "Culturele en kunstzinnige vorming";
                    break;
                case "LO":
                    $pos = "lo";
                    $vak = "Lichamelijke opvoeding";
                    break;
                default:
                    $pos = "";
                    $vak = "";
                    break;
            }
            $index = array_search($pos, $eba);
            if ($index !== false) {
                unset($eba[$index]);
            }
            if ($pos != "") {
                switch ($j) {
                    case 1:
                        $col = "F";
                        break;
                    case 2:
                        $col = "G";
                        break;
                    case 3:
                        $col = "H";
                        break;
                    case 4:
                        $col = "I";
                        break;
                    case 5:
                        $col = "K";
                        break;
                    case 6:
                        $col = "L";
                        break;
                    case 7:
                        $col = "M";
                        break;
                    case 8:
                        $col = "N";
                        break;
                    case 9:
                        $col = "O";
                        break;
                    default:
                        $col = "";
                        break;
                }
                $hojaActiva->setCellValue($col . $i, $vak);
            }
        }
        $i++;
    }
}


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="certificate_' . $schooljaar . '.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
