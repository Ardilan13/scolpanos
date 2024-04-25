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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$spreadsheet = $reader->load("../templates/eba_ex5.xlsx");
$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

$s = new spn_setting();
$u = new spn_utils();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$schooljaar = $_SESSION['SchoolJaar'];
$schoolid = $_SESSION['SchoolID'];
$schoolname = $_SESSION['schoolname'];
$last_personalia = "";
$her = 0;
$mem = 0;
$i = 4;

$s->getsetting_info($schoolid, false);

$get_personalia = "SELECT
e.id_personalia,
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
e.tv2,
e.opmerking,
s.id,
s.lastname, 
s.firstname, 
s.dob,
s.sex,
s.birthplace,
s.profiel 
FROM eba_ex e
INNER JOIN personalia p ON e.id_personalia = p.id
INNER JOIN students s ON p.studentid = s.id
LEFT JOIN eba_ex e0 ON e0.id_personalia = e.id_personalia AND e0.schooljaar = e.schooljaar AND e0.type = 0
WHERE e.schoolid = '$schoolid'
AND e.schooljaar = '$schooljaar'
AND s.SchoolID = '$schoolid'
AND (e.type = '2' OR e.type = '3' OR e.type = '4' OR e.type = '5')
ORDER BY s.lastname, s.firstname,e.type;";
$result = mysqli_query($mysqli, $get_personalia);
if ($result->num_rows > 0) {
    $hojaActiva->setCellValue('BX2', $get_personalia);
    $hojaActiva->setCellValue('D1', $schoolname);
    $hojaActiva->setCellValue('AF1', "Schooljaar: " . $schooljaar);
    while ($row = mysqli_fetch_assoc($result)) {
        $personalia = $row["id_personalia"];
        if ($last_personalia != $personalia && $last_personalia != "") {
            $i = $i + 3;
            $her = 0;
            $mem = 0;
        }
        $hojaActiva->setCellValue('A' . $i, $row["id_personalia"]);
        $hojaActiva->setCellValue('B' . $i, $row["lastname"]);
        $hojaActiva->setCellValue('B' . ($i + 1), $row["dob"]);
        $hojaActiva->setCellValue('B' . ($i + 2), $row["birthplace"]);
        $hojaActiva->setCellValue('C' . $i, $row["firstname"]);
        $hojaActiva->setCellValue('D' . $i, $row["sex"]);
        $hojaActiva->setCellValue('E' . $i, $row["profiel"]);
        $hojaActiva->setCellValue('H' . $i, substr($row["profiel"], 0, 2));

        if ($row["type"] != 5) {
            $k = ($row["type"] == 2) ? $i : ($i + 1);

            $get_colors = "SELECT e.* FROM eba_ex e WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar' AND e.type = 0 AND id_personalia = $personalia LIMIT 1;";
            $result2 = mysqli_query($mysqli, $get_colors);
            if ($result2->num_rows > 0) {
                while ($row1 = mysqli_fetch_assoc($result2)) {
                    $array = array();

                    $hojaActiva->setCellValue('BA' . ($i + 1), $row1["e1"]);
                    $hojaActiva->setCellValue('BB' . ($i + 1), $row1["e2"]);
                    $hojaActiva->setCellValue('BC' . ($i + 1), $row1["e3"]);
                    $hojaActiva->setCellValue('BD' . ($i + 1), $row1["e4"]);
                    $hojaActiva->setCellValue('BE' . ($i + 1), $row1["e5"]);
                    $hojaActiva->setCellValue('BF' . ($i + 1), $row1["e6"]);
                    $hojaActiva->setCellValue('BG' . ($i + 1), $row1["e7"]);
                    $hojaActiva->setCellValue('BH' . ($i + 1), $row1["e8"]);
                    $hojaActiva->setCellValue('BI' . ($i + 1), $row1["e9"]);
                    $hojaActiva->setCellValue('BJ' . ($i + 1), $row1["e10"]);
                    $hojaActiva->setCellValue('BK' . ($i + 1), $row1["e11"]);
                    $hojaActiva->setCellValue('BL' . ($i + 1), $row1["e12"]);

                    for ($j = 1; $j <= 12; $j++) {
                        switch ($j) {
                            case 1:
                                $vaken = "ne";
                                $pos = "J";
                                break;
                            case 2:
                                $vaken = "en";
                                $pos = "K";
                                break;
                            case 3:
                                $vaken = "sp";
                                $pos = "L";
                                break;
                            case 4:
                                $vaken = "pa";
                                $pos = "M";
                                break;
                            case 5:
                                $vaken = "wi";
                                $pos = "N";
                                break;
                            case 6:
                                $vaken = "sk1";
                                $pos = "O";
                                break;
                            case 7:
                                $vaken = "sk2";
                                $pos = "P";
                                break;
                            case 8:
                                $vaken = "bi";
                                $pos = "Q";
                                break;
                            case 9:
                                $vaken = "ec";
                                $pos = "R";
                                break;
                            case 10:
                                $vaken = "ak";
                                $pos = "S";
                                break;
                            case 11:
                                $vaken = "gs";
                                $pos = "T";
                                break;
                            case 12:
                                $vaken = "re";
                                $pos = "U";
                                break;
                        }
                        if ($row1["e" . $j] == "H") {
                            $array[] = [$j => $vaken];
                        }

                        switch ($row1["e" . $j]) {
                            case "X":
                                $color = "FFFFFF";
                                $def = "FFFFFF";
                                $m = $i + 1;
                                break;
                            case "V":
                                $color = "1E90FF";
                                $def = "FFFFFF";
                                $m = $i + 2;
                                break;
                            case "H":
                                $color = "CCFFFF";
                                $def = "FFFFFF";
                                $m = $i + 1;
                                break;
                            case "NS":
                                $color = "FF1493";
                                $def = "FFFFFF";
                                $m = $i + 1;
                                break;
                            default:
                                $color = "D3D3D3";
                                $def = "D3D3D3";
                                $m = $i + 2;
                                break;
                        }
                        $hojaActiva->getStyle($pos . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($def);
                        $hojaActiva->getStyle($pos . ($i + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($def);
                        $hojaActiva->getStyle($pos . ($i + 2))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFFFCC");
                        $hojaActiva->getStyle($pos . $m)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
                    }
                }
            }
            $her = 0;
            foreach ($array as $value) {
                foreach ($value as $key => $vak) {
                    if ($row["type"] == 2 || $row["type"] == 4) {
                        if (isset($row["e" . $key]) && $row["e" . $key] !== NULL && $row["e" . $key] > 0.0) {
                            $columna = '';
                            $col_vak = '';
                            switch ($her) {
                                case 0:
                                    $columna = 'AA';
                                    $col_vak = 'Z';
                                    break;
                                case 1:
                                    $columna = 'AC';
                                    $col_vak = 'AB';
                                    break;
                                case 2:
                                    $columna = 'AE';
                                    $col_vak = 'AD';
                                    break;
                                default:
                                    $columna = 'BX';
                                    break;
                            }
                            $hojaActiva->setCellValue($columna . $k, $row["e" . $key]);
                            $hojaActiva->setCellValue($col_vak . ($i + 2), $vak);
                            $her++;
                        }
                    }
                }
            }

            if ($row["type"] != 4) {
                $hojaActiva->setCellValue('J' . $k, $row["e1"]);
                $hojaActiva->setCellValue('K' . $k, $row["e2"]);
                $hojaActiva->setCellValue('L' . $k, $row["e3"]);
                $hojaActiva->setCellValue('M' . $k, $row["e4"]);
                $hojaActiva->setCellValue('N' . $k, $row["e5"]);
                $hojaActiva->setCellValue('O' . $k, $row["e6"]);
                $hojaActiva->setCellValue('P' . $k, $row["e7"]);
                $hojaActiva->setCellValue('Q' . $k, $row["e8"]);
                $hojaActiva->setCellValue('R' . $k, $row["e9"]);
                $hojaActiva->setCellValue('S' . $k, $row["e10"]);
                $hojaActiva->setCellValue('T' . $k, $row["e11"]);
                $hojaActiva->setCellValue('U' . $k, $row["e12"]);

                $get_vold = "SELECT code,ckv,lo,her FROM personalia WHERE id = $personalia LIMIT 1";
                $result3 = mysqli_query($mysqli, $get_vold);
                if ($result3->num_rows > 0) {
                    while ($row2 = mysqli_fetch_assoc($result3)) {
                        $hojaActiva->setCellValue('A' . $i, $row2["code"]);
                        $hojaActiva->setCellValue('F' . $i, $row2["lo"] == 1 ? "V" : "O");
                        $hojaActiva->setCellValue('G' . $i, $row2["her"] == 1 ? "V" : ($row2["ckv"] == 1 ? "V" : "O"));
                    }
                }
            }
        } else {
            $hojaActiva->setCellValue('X' . ($i + 2), $row["tv1"]);
            $hojaActiva->setCellValue('AG' . ($i + 2), $row["tv2"]);
            $hojaActiva->setCellValue('AH' . ($i + 2), $row["opmerking"]);
        }
        $last_personalia = $personalia;
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ex5_' . $schooljaar . '.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
