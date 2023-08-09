<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
$spreadsheet = $reader->load("../templates/eba_exdocent.xlsx");
$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

$s = new spn_setting();
$u = new spn_utils();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$type = $_GET["type"];
$schooljaar = $_SESSION['SchoolJaar'];
$schoolid = $_SESSION['SchoolID'];
$schoolname = $_SESSION['schoolname'];
$i = 13;

$s->getsetting_info($schoolid, false);

$get_personalia = "SELECT 
e.id, 
e.id_personalia,
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
s.lastname, 
s.firstname, 
s.profiel 
FROM eba_ex e
INNER JOIN personalia p ON e.id_personalia = p.id
INNER JOIN students s ON p.studentid = s.id
LEFT JOIN eba_ex e0 ON e0.id_personalia = e.id_personalia AND e0.schooljaar = e.schooljaar AND e0.type = 0
WHERE e.schoolid = '$schoolid'
AND e.schooljaar = '$schooljaar'
AND s.SchoolID = '$schoolid'
AND e.type = '$type'
ORDER BY s.lastname, s.firstname;
";
$result = mysqli_query($mysqli, $get_personalia);
if ($result->num_rows > 0) {
    $hojaActiva->setCellValue('B8', $schoolname);
    $hojaActiva->setCellValue('C7', $schooljaar);
    while ($row = mysqli_fetch_assoc($result)) {
        $hojaActiva->setCellValue('B' . $i, $row["lastname"]);
        $hojaActiva->setCellValue('C' . $i, $row["firstname"]);
        $hojaActiva->setCellValue('E' . $i, $row["e1"]);
        $hojaActiva->setCellValue('F' . $i, $row["e2"]);
        $hojaActiva->setCellValue('G' . $i, $row["e3"]);
        $hojaActiva->setCellValue('H' . $i, $row["e4"]);
        $hojaActiva->setCellValue('I' . $i, $row["e5"]);
        $hojaActiva->setCellValue('J' . $i, $row["e6"]);
        $hojaActiva->setCellValue('K' . $i, $row["e7"]);
        $hojaActiva->setCellValue('L' . $i, $row["e8"]);
        $hojaActiva->setCellValue('M' . $i, $row["e9"]);
        $hojaActiva->setCellValue('N' . $i, $row["e10"]);
        $hojaActiva->setCellValue('O' . $i, $row["e11"]);
        $hojaActiva->setCellValue('P' . $i, $row["e12"]);
        $hojaActiva->setCellValue('Q' . $i, $row["profiel"]);

        $i++;
    }

    $get_docent = "SELECT CONCAT(a.Lastname,' ',a.Firstname) as docent,e.code,e.vak FROM eba_docentlist e INNER JOIN app_useraccounts a ON e.docent = a.UserGUID WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar'";
    $result1 = mysqli_query($mysqli, $get_docent);
    if ($result1->num_rows > 0) {
        $i = 12;
        while ($row1 = mysqli_fetch_assoc($result1)) {
            $hojaActiva->setCellValue('S' . $i, $row1["docent"]);
            $hojaActiva->setCellValue('U' . $i, $row1["vak"]);
            $hojaActiva->setCellValue('T' . $i, $row1["code"]);
            $i++;
        }
    }

    $get_colors = "SELECT e.* FROM eba_ex e WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar' AND e.type = 0 ORDER BY e.id_personalia;";
    $result2 = mysqli_query($mysqli, $get_colors);
    if ($result2->num_rows > 0) {
        $i = 13;
        while ($row = mysqli_fetch_assoc($result2)) {
            $hojaActiva->setCellValue('BB' . $i, $row["e1"]);
            $hojaActiva->setCellValue('BC' . $i, $row["e2"]);
            $hojaActiva->setCellValue('BD' . $i, $row["e3"]);
            $hojaActiva->setCellValue('BE' . $i, $row["e4"]);
            $hojaActiva->setCellValue('BF' . $i, $row["e5"]);
            $hojaActiva->setCellValue('BG' . $i, $row["e6"]);
            $hojaActiva->setCellValue('BH' . $i, $row["e7"]);
            $hojaActiva->setCellValue('BI' . $i, $row["e8"]);
            $hojaActiva->setCellValue('BJ' . $i, $row["e9"]);
            $hojaActiva->setCellValue('BK' . $i, $row["e10"]);
            $hojaActiva->setCellValue('BL' . $i, $row["e11"]);
            $hojaActiva->setCellValue('BM' . $i, $row["e12"]);

            $i++;
        }
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
if ($type == 1) {
    header('Content-Disposition: attachment;filename="eba_exdocent_' . $schooljaar . '.xlsx"');
} else {
    header('Content-Disposition: attachment;filename="eba_ex2_' . $schooljaar . '.xlsx"');
}

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
