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
$spreadsheet = $reader->load("../templates/eba_ex3.xlsx");
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
$i = 12;

$s->getsetting_info($schoolid, false);

$get_students = "SELECT p.id,
(SELECT lastname FROM students WHERE id = p.studentid) as lastname,
(SELECT firstname FROM students WHERE id = p.studentid) as name
FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id
WHERE e.schoolid = '$schoolid' 
AND e.schooljaar = '$schooljaar' 
AND e.type = 1
AND ('$code' IN (e.e1, e.e2, e.e3, e.e4, e.e5, e.e6, e.e7, e.e8, e.e9, e.e10, e.e11, e.e12)) 
ORDER BY e.id_personalia";
$result = mysqli_query($mysqli, $get_students);
if ($result->num_rows > 0) {
    $hojaActiva->setCellValue('D6', $name);
    $hojaActiva->setCellValue('D7', $vak);
    $hojaActiva->setCellValue('B5', $schoolname);
    $hojaActiva->setCellValue('C4', $schooljaar);
    while ($row = mysqli_fetch_assoc($result)) {
        $personalia = $row["id"];
        $hojaActiva->setCellValue('B' . $i, $row["lastname"]);
        $hojaActiva->setCellValue('C' . $i, $row["name"]);

        $i++;
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="eba_ex3_' . $schooljaar . '.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
