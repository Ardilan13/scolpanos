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
        if ($row["dob"] != "0000-00-00" && $row["dob"] != null) {

            $timestamp = strtotime($row["dob"]);
            $dia = date("j", $timestamp);
            $mes = date("n", $timestamp);
            $ano = date("Y", $timestamp);
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
            $fecha = $dia . " " . $mes . " " . $ano;
        } else {
            $fecha = "";
        }

        $hojaActiva->setCellValue('B' . $i, $row["lastname"]);
        $hojaActiva->setCellValue('C' . $i, $row["firstname"]);
        $hojaActiva->setCellValue('D' . $i, strtolower($row["sex"]));
        $hojaActiva->setCellValue('E' . $i, $fecha);
        $hojaActiva->setCellValue('F' . $i, ucwords(strtolower($row["birthplace"])));
        $hojaActiva->setCellValue('G' . $i, $row["opmerking"]);

        $i++;
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="eba_personalia_' . $schooljaar . '.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
