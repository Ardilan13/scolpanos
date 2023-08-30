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
$spreadsheet = $reader->load("../templates/eba_personalia.xlsx");
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
$i = 5;

$s->getsetting_info($schoolid, false);

$get_personalia = "SELECT s.id,p.opmerking,s.lastname,s.firstname,s.sex,s.dob,s.birthplace FROM personalia p INNER JOIN students s ON s.id = p.studentid WHERE p.schoolid = $schoolid AND p.schooljaar = '$schooljaar' ORDER BY";
$sql_order = " lastname , firstname";
if ($s->_setting_mj) {
    $get_personalia .= " sex " . $s->_setting_sort . ", " . $sql_order;
} else {
    $get_personalia .=  $sql_order;
}
$result = mysqli_query($mysqli, $get_personalia);
if ($result->num_rows > 0) {
    $hojaActiva->setCellValue('A1', "School: " . $schoolname);
    $hojaActiva->setCellValue('D1', "Schooljaar: " . $schooljaar);
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row["dob"] != null && $row["dob"] != "0000-00-00") {
            $dob = new DateTime($row["dob"]);
        } else {
            $dob = null;
        }

        $hojaActiva->setCellValue('B' . $i, $row["lastname"]);
        $hojaActiva->setCellValue('C' . $i, $row["firstname"]);
        $hojaActiva->setCellValue('D' . $i, $row["sex"]);
        $hojaActiva->setCellValue('E' . $i, $dob != null ? $dob->format("d M Y") : "");
        $hojaActiva->setCellValue('F' . $i, $row["birthplace"]);
        $hojaActiva->setCellValue('G' . $i, $row["opmerking"]);

        $i++;
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="eba_personalia_' . $schooljaar . '.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
