<?php

ob_start();

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

require_once "../classes/3rdparty/vendor/autoload.php";
require_once "../classes/DBCreds.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$klas_in = $_GET["ttr_klas_list"];
$period = $_GET["period"];
$schoolid = $_SESSION["SchoolID"];
$schooljaar = $_SESSION["SchoolJaar"];
$period = $_GET["period"];
$ttr_datum = $_GET["ttr_datum"];
$ttr_dl = $_GET["ttr_dl"];

$_current_student_start_row = 7;
$_current_student_start_col = "B";
$_default_excel_level_klas = "E4";
$_default_excel_schoolname = "B4";
$_default_excel_letter_class = "G4";
$_default_excel_date = "L4";
$_default_excel_dl = "P4";

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$spreadsheet = $reader->load("../templates/TTR_blank.xlsx");
$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

$hojaActiva->setTitle('TTR');
$hojaActiva->setCellValue($_default_excel_level_klas, substr($klas_in, 0, 1));
$hojaActiva->setCellValue($_default_excel_letter_class, substr($klas_in, 1, 2));
$hojaActiva->setCellValue($_default_excel_date, $ttr_date);
$hojaActiva->setCellValue($_default_excel_dl, $ttr_dl);

$DBCreds = new DBCreds();
date_default_timezone_set("America/Aruba");
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
$sql_query = "SELECT
		s.id, s.id_family ,
		CONCAT(s.firstname,' ',s.lastname) as student_full_name,
		sc.schoolname
		FROM students s
		INNER JOIN schools sc ON
		sc.id = s.SchoolID
		INNER JOIN setting stt ON
		s.SchoolID = stt.schoolid
		WHERE s.class = ? AND s.schoolid = ? AND stt.year_period = ? ORDER BY";
$sql_order = " s.lastname , s.firstname";
if ($schoolid == 10) {
	$sql_query .= " s.sex ASC,s.lastname ASC, s.firstname";
} else {
	$sql_query .=  $sql_order;
}

if ($select = $mysqli->prepare($sql_query)) {
	if ($select->bind_param("sis", $klas_in, $schoolid, $schooljaar)) {
		if ($select->execute()) {
			$select->store_result();
			if ($select->num_rows > 0) {
				$result = 1;
				$select->bind_result($studentid_out, $id_family, $student_full_name, $schoolname);
				while ($select->fetch()) {
					$hojaActiva->setCellValue($_default_excel_schoolname, $schoolname);
					$hojaActiva->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $student_full_name);
					$_current_student_start_row++;
				}
			}
		}
	}
}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ttr_' . $klas_in . '_' . $period . '.xlsx.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');


ob_flush();
