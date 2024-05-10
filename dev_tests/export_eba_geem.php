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

$get_personalia = "SELECT p.code,p.ckv,p.lo,p.her,s.lastname,s.firstname FROM personalia p INNER JOIN students s ON s.id = p.studentid WHERE p.schoolid = $schoolid AND p.schooljaar = '$schooljaar' AND (p.ckv IS NOT NULL OR p.lo IS NOT NULL) ORDER BY";
$sql_order = " lastname , firstname";
if ($s->_setting_mj) {
  $get_personalia .= " sex " . $s->_setting_sort . ", " . $sql_order;
} else {
  $get_personalia .=  $sql_order;
}
$result = mysqli_query($mysqli, $get_personalia);
if ($result->num_rows > 0) {
  $hojaActiva->setCellValue('B8', $schoolname);
  $hojaActiva->setCellValue('C7', $schooljaar);
  while ($row = mysqli_fetch_assoc($result)) {
    $i = $i == 52 ? 62 : $i;
    $hojaActiva->setCellValue('B' . $i, $row["lastname"]);
    $hojaActiva->setCellValue('C' . $i, $row["firstname"]);
    $hojaActiva->setCellValue('D' . $i, $row["her"] == 1 ? "Voldoende" : ($row["ckv"] == 1 ? "Voldoende" : "Onvoldoende"));
    $hojaActiva->setCellValue('F' . $i, $row["lo"] == 1 ? "Voldoende" : ($row["lo"] == 0 ? "Onvoldoende" : ($row["lo"] == 2 ? "Goed" : "")));
    $i++;
  }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="eba_geem_' . $schooljaar . '.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
