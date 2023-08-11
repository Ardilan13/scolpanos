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
$spreadsheet = $reader->load("../templates/eba_ex2a.xlsx");
$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

$s = new spn_setting();
$u = new spn_utils();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$code = $_GET["user"];
$name = $_GET["name"];
$vak = $_GET["vak"];
$schooljaar = $_SESSION['SchoolJaar'];
$schoolid = $_SESSION['SchoolID'];
$schoolname = $_SESSION['schoolname'];
$i = 13;

$s->getsetting_info($schoolid, false);

$get_students = "SELECT p.id,
(SELECT lastname FROM students WHERE id = p.studentid) as lastname,
(SELECT firstname FROM students WHERE id = p.studentid) as name,
CASE
  WHEN e.e1 = '$code' THEN 'e1'
  WHEN e.e2 = '$code' THEN 'e2'
  WHEN e.e3 = '$code' THEN 'e3'
  WHEN e.e4 = '$code' THEN 'e4'
  WHEN e.e5 = '$code' THEN 'e5'
  WHEN e.e6 = '$code' THEN 'e6'
  WHEN e.e7 = '$code' THEN 'e7'
  WHEN e.e8 = '$code' THEN 'e8'
  WHEN e.e9 = '$code' THEN 'e9'
  WHEN e.e10 = '$code' THEN 'e10'
  WHEN e.e11 = '$code' THEN 'e11'
  WHEN e.e12 = '$code' THEN 'e12'
END AS et
FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id
WHERE e.schoolid = '$schoolid' 
AND e.schooljaar = '$schooljaar' 
AND e.type = 1
AND ('$code' IN (e.e1, e.e2, e.e3, e.e4, e.e5, e.e6, e.e7, e.e8, e.e9, e.e10, e.e11, e.e12)) 
ORDER BY e.id_personalia";
$result = mysqli_query($mysqli, $get_students);
if ($result->num_rows > 0) {
    $hojaActiva->setCellValue('C5', $name);
    $hojaActiva->setCellValue('C6', $vak);
    $hojaActiva->setCellValue('B8', $schoolname);
    $hojaActiva->setCellValue('C7', $schooljaar);
    while ($row = mysqli_fetch_assoc($result)) {
        $personalia = $row["id"];
        $e = $row["et"];
        $cijfers = "SELECT $e as cijfer FROM eba_ex WHERE id_personalia = $personalia AND (type = 2 OR type = 3)";
        $result2 = mysqli_query($mysqli, $cijfers);
        $row2 = mysqli_fetch_assoc($result2);
        $hojaActiva->setCellValue('B' . $i, $row["lastname"]);
        $hojaActiva->setCellValue('C' . $i, $row["name"]);
        $hojaActiva->setCellValue('D' . $i, $row2["cijfer"]);
        $hojaActiva->setCellValue('H' . $i, $row2["cijfer"]);

        $i++;
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
