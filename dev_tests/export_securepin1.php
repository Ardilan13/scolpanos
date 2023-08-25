<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
ini_set('max_execution_time', 300);
ob_start();
ini_set('memory_limit', '100M');


require_once("../config/app.config.php");
require_once "../classes/3rdparty/vendor/autoload.php";
require_once "../classes/DBCreds.php";
require_once("../classes/spn_setting.php");
require_once("../classes/spn_utils.php");

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$schoolid = $_GET["scol_id"];
$klas_in = $_GET["klas_list"];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CCCCCC');
$sheet->getStyle('A1:E1')->getFont()->setBold(true);

$sheet->setCellValue('A1', 'Klas');
$sheet->setCellValue('B1', 'Student Number');
$sheet->setCellValue('C1', 'Firstname');
$sheet->setCellValue('D1', 'Lastname');
$sheet->setCellValue('E1', 'SecurePin');

$row = 2; // Empezamos desde la segunda fila

if ($klas_in == 'All') {
	$sql_query = "select id, class, studentnumber, firstname, lastname, securepin from students where schoolid = $schoolid order by class;";
} else {
	$sql_query = "select id, class, studentnumber, firstname, lastname, securepin from students where schoolid = $schoolid and class = '$klas_in' order by class,lastname";
}

$resultado1 = mysqli_query($mysqli, $sql_query);
while ($data = mysqli_fetch_assoc($resultado1)) {
	$sheet->setCellValue('A' . $row, $data['class']);
	$sheet->setCellValue('B' . $row, $data['studentnumber']);
	$sheet->setCellValue('C' . $row, $data['firstname']);
	$sheet->setCellValue('D' . $row, $data['lastname']);
	$sheet->setCellValue('E' . $row, $data['securepin']);
	$row++;
}

foreach ($sheet->getRowDimensions() as $dimension) {
	$dimension->setRowHeight(-1);
}

foreach ($sheet->getColumnIterator() as $column) {
	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}

$styleArray = [
	'borders' => [
		'allBorders' => [
			'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			'color' => ['argb' => '000000'],
		],
	],
];

$sheet->getStyle('A1:E' . ($row - 1))->applyFromArray($styleArray);

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Secure_Pin_' . $klas_in . '.xlsx"');
$writer->save('php://output');

ob_flush();
