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
$spreadsheet = $reader->load("../templates/eba_ex1.xlsx");
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
$i = 13;

$s->getsetting_info($schoolid, false);

$get_personalia = "SELECT e.*,p.code,s.lastname,s.firstname,s.profiel FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id INNER JOIN students s ON p.studentid = s.id WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar' AND s.SchoolID = $schoolid AND e.type = 0 ORDER BY s.lastname, s.firstname;";
$result = mysqli_query($mysqli, $get_personalia);
if ($result->num_rows > 0) {
    $hojaActiva->setCellValue('B8', $schoolname);
    $hojaActiva->setCellValue('C7', $schooljaar);
    while ($row = mysqli_fetch_assoc($result)) {
        $hojaActiva->setCellValue('A' . $i, $row["code"]);
        $hojaActiva->setCellValue('B' . $i, $row["lastname"]);
        $hojaActiva->setCellValue('C' . $i, $row["firstname"]);
        $hojaActiva->setCellValue('E' . $i, $row["e1"] == "D" ? "X" : $row["e1"]);
        $hojaActiva->setCellValue('F' . $i, $row["e2"] == "D" ? "X" : $row["e2"]);
        $hojaActiva->setCellValue('G' . $i, $row["e3"] == "D" ? "X" : $row["e3"]);
        $hojaActiva->setCellValue('H' . $i, $row["e4"] == "D" ? "X" : $row["e4"]);
        $hojaActiva->setCellValue('I' . $i, $row["e5"] == "D" ? "X" : $row["e5"]);
        $hojaActiva->setCellValue('J' . $i, $row["e6"] == "D" ? "X" : $row["e6"]);
        $hojaActiva->setCellValue('K' . $i, $row["e7"] == "D" ? "X" : $row["e7"]);
        $hojaActiva->setCellValue('L' . $i, $row["e8"] == "D" ? "X" : $row["e8"]);
        $hojaActiva->setCellValue('M' . $i, $row["e9"] == "D" ? "X" : $row["e9"]);
        $hojaActiva->setCellValue('N' . $i, $row["e10"] == "D" ? "X" : $row["e10"]);
        $hojaActiva->setCellValue('O' . $i, $row["e11"] == "D" ? "X" : $row["e11"]);
        $hojaActiva->setCellValue('P' . $i, $row["e12"] == "D" ? "X" : $row["e12"]);
        $hojaActiva->setCellValue('Q' . $i, $row["profiel"]);

        $i++;
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="eba_ex1_' . $schooljaar . '.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
