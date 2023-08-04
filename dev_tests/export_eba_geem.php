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
$spreadsheet = $reader->load("../templates/eba_gem.xlsx");
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

$get_personalia = "SELECT studentid,code FROM personalia WHERE schoolid = $schoolid AND schooljaar = '$schooljaar' ORDER BY id";
$result = mysqli_query($mysqli, $get_personalia);
if ($result->num_rows > 0) {
    $i = $i == 52 ? 62 : $i;
    $hojaActiva->setCellValue('A8', "School: " . $schoolname);
    $hojaActiva->setCellValue('C7', "Schooljaar: " . $schooljaar);
    while ($row = mysqli_fetch_assoc($result)) {
        $get_cijfers = "SELECT 
                           CASE 
                             WHEN AVG(CASE WHEN v.volledigenaamvak = 'CKV' THEN c.gemiddelde END) < 6 THEN 'Onvoldoende'
                             WHEN AVG(CASE WHEN v.volledigenaamvak = 'CKV' THEN c.gemiddelde END) >= 6 THEN 'Vold'
                           END AS ckv,
                           CASE 
                             WHEN AVG(CASE WHEN v.volledigenaamvak = 'lo' THEN c.gemiddelde END) < 6 THEN 'Onvoldoende'
                             WHEN AVG(CASE WHEN v.volledigenaamvak = 'lo' THEN c.gemiddelde END) >= 6 THEN 'Vold'
                           END AS lo,
                           s.lastname,
                           s.firstname
                         FROM students s
                         LEFT JOIN le_cijfers c ON s.id = c.studentid AND c.schooljaar = '2021-2022' AND c.gemiddelde > 0
                         LEFT JOIN le_vakken v ON c.vak = v.ID AND v.schoolid = $schoolid AND v.volledigenaamvak IN ('CKV', 'lo')
                         WHERE s.id = $row[studentid]
                         GROUP BY s.lastname, s.firstname;";
        $result1 = mysqli_query($mysqli, $get_cijfers);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            $hojaActiva->setCellValue('B' . $i, $row1["lastname"]);
            $hojaActiva->setCellValue('C' . $i, $row1["firstname"]);
            $hojaActiva->setCellValue('D' . $i, $row1["ckv"]);
            $hojaActiva->setCellValue('F' . $i, $row1["lo"]);
        }
        $i++;
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="eba_geem_' . $schooljaar . '.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
