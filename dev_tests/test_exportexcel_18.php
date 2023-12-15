<?php
require_once("../classes/spn_rapport.php");
require_once("../config/app.config.php");
require_once "../classes/3rdparty/vendor/autoload.php";
require_once "../classes/DBCreds.php";
require_once("../classes/spn_setting.php");

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$_laststudent = 0;
$_current_student_start_row = 7;
$_while_counter = 0;
$schooljaar = $_SESSION['SchoolJaar'];
$schoolid = $_SESSION['SchoolID'];
$klas_in = $_GET["rapport_klassen_lijst"];
$level_klas = substr($klas_in, 0, 1);
$rap_in = $_GET["rapport"];
$user = $_SESSION["UserGUID"];

$s = new spn_setting();
$s->getsetting_info($schoolid, false);

$sql_query = "SELECT DISTINCT
	s.id as studentid,
	c.id as cijferid,
	st.year_period,
	s.class,
	s.firstname,
	s.lastname,
	s.sex,
	c.gemiddelde,
	v.volgorde,
	v.x_index,
	v.volledigenaamvak,
	CONCAT(app.firstname,' ',app.lastname) as docent_name
	FROM students s
	LEFT JOIN le_cijfers c ON s.id = c.studentid
	LEFT JOIN le_vakken v ON c.vak = v.id
	INNER JOIN setting st ON st.schoolid = s.schoolid
	INNER JOIN app_useraccounts app ON st.schoolid = app.SchoolID and app.UserGUID = '$user'
	WHERE
	s.schoolid = $schoolid
	AND v.SchoolID = $schoolid
	AND st.year_period = '$schooljaar'
	and c.gemiddelde >= 0
	AND c.schooljaar = '$schooljaar'
	AND s.class = '$klas_in'
	AND c.klas = '$klas_in'
	AND v.klas = '$klas_in'
	AND c.rapnummer = $rap_in
	AND x_index is not null
    ORDER BY";
$sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
if ($s->_setting_mj) {
    $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
} else {
    $sql_query .=  $sql_order;
}
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
switch ($level_klas) {
    case "1":
        $spreadsheet = $reader->load("../templates/verza_scol18_1.xlsx");
        break;
    default:
        $spreadsheet = $reader->load("../templates/verza_scol18_2.xlsx");
        break;
}
$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

$resultado = mysqli_query($mysqli, $sql_query);
while ($row = mysqli_fetch_assoc($resultado)) {
    if ($_while_counter == 0) {
        $_laststudent = $row["studentid"];
        $hojaActiva->setCellValue("B2", "RAPPORT " . $rap_in);
        $hojaActiva->setCellValue("B3", "Fecha:" . $_SESSION['SchoolJaar']);
        $hojaActiva->setCellValue("B4", "Maestro:" . $row["docent_name"]);
    }
    $_currentstudent = $row["studentid"];

    if ($_currentstudent != $_laststudent) {
        $_current_student_start_row++;
        $hojaActiva->setCellValue('B' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
    }

    if ($_while_counter == 0) {
        $hojaActiva->setCellValue('B' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
    }

    switch ($row["x_index"]) {
        case 2:
            $returnvalue = "C";
            break;

        case 3:
            if ($row["volledigenaamvak"] == "lesa comprension") {
                $returnvalue = "M";
            } else {
                $returnvalue = "D";
            }
            break;

        case 4:
            $returnvalue = "F";
            break;

        case 5:
            $returnvalue = "G";
            break;

        case 6:
            $returnvalue = "H";
            break;

        case 7:
            $returnvalue = "K";
            break;

        case 8:
            $returnvalue = "L";
            break;

        case 9:
            $returnvalue = "P";
            break;

        case 10:
            $returnvalue = "Q";
            break;

        case 11:
            $returnvalue = "R";
            break;

        case 12:
            $returnvalue = "S";
            break;

        case 13:
            $returnvalue = "T";
            break;

        case 14:
            $returnvalue = "U";
            break;

        case 15:
            $returnvalue = "V";
            break;

        case 16:
            $returnvalue = "W";
            break;

        case 17:
            $returnvalue = "AE";
            break;

        case 19:
            $returnvalue = "AF";
            break;

        case 21:
            $returnvalue = "AI";
            break;

        case 22:
            $returnvalue = "AJ";
            break;

        case 25:
            $returnvalue = "AB";
            break;

        case 26:
            $returnvalue = "AC";
            break;

        case 27:
            $returnvalue = "AA";
            break;
    }
    $colgemiddelde = (string)$returnvalue . (string)$_current_student_start_row;
    $hojaActiva->setCellValue($colgemiddelde, $row["gemiddelde"]);
    $_laststudent = $_currentstudent;
    $_while_counter++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="verzamelstaten_' . $_GET["rapport_klassen_lijst"] . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
