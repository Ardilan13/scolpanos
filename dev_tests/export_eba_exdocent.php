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

$schooljaar = $_SESSION['SchoolJaar'];
$schoolid = $_SESSION['SchoolID'];
$schoolname = $_SESSION['schoolname'];
$i = 13;

$s->getsetting_info($schoolid, false);

$get_personalia = "SELECT e.*,s.lastname,s.firstname,s.profiel FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id INNER JOIN students s ON p.studentid = s.id WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar' AND s.SchoolID = $schoolid AND e.type = 1 ORDER BY s.lastname, s.firstname;";
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
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="eba_exdocent_' . $schooljaar . '.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
