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

$schooljaar = $_SESSION['SchoolJaar'];
$currentYear = intval(substr($schooljaar, 0, 4)); // Obtenemos el año actual (2022)
$previousYear = $currentYear - 1; // Calculamos el año anterior (2021)
$previousSchooljaar = $previousYear . "-" . ($previousYear + 1);
$schoolid = $_SESSION['SchoolID'];
$klas_in = $_GET["rapport_klassen_lijst"];
$rap_in = $_GET["rapport"];
$user = $_SESSION["UserGUID"];
$s = new spn_setting();
$u = new spn_utils();
$s->getsetting_info($schoolid, false);
$v = null;

if ($schoolid == 12) {
    $img = 3;
} else if ($schoolid == 13) {
    $img = 2;
} else if ($schoolid == 18) {
    $img = 1;
} else {
    $img = 0;
}

$level_klas = substr($klas_in, 0, 1);

switch ($level_klas) {
    case 1:
    case 2:
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load("../templates/verza_v2-1-2.xlsx");
        break;

    case 3:
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load("../templates/verza_v2-3-4.xlsx");
        break;
    case 4:
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load("../templates/verza_4.xlsx");
        break;
}

$i = 1;

if ($level_klas == 4) {
    $spreadsheet->setActiveSheetIndex(5);
} else {
    $spreadsheet->setActiveSheetIndex(6);
}
$hojaActiva = $spreadsheet->getActiveSheet();
$hojaActiva->setCellValue('B4', $img);

while ($i <= $rap_in) {
    $v = array();
    $vri = null;
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $_while_counter = 0;
    $_currentstudent = null;
    $_laststudent = null;
    if ($level_klas == 4) {
        switch ($i) {
            case 1:
                $c = "IF(GREATEST(IFNULL(c.c1, 0), IFNULL(c.c2, 0)) = 0, NULL,GREATEST(IFNULL(c.c1, 0), IFNULL(c.c2, 0)))";
                break;
            case 2:
                $c = "IF(GREATEST(IFNULL(c.c5, 0), IFNULL(c.c6, 0)) = 0, NULL,GREATEST(IFNULL(c.c5, 0), IFNULL(c.c6, 0)))";
                break;
            case 3:
                $c = "IF(GREATEST(IFNULL(c.c9, 0), IFNULL(c.c10, 0)) = 0, NULL,GREATEST(IFNULL(c.c9, 0), IFNULL(c.c10, 0)))";
                break;
        }
        $_current_student_start_row = 7;
        $sql_query = "SELECT DISTINCT
                    s.id as studentid,
                    c.id as cijferid,
                    v.id as vakid,
                    st.year_period,
                    s.class,
                    s.firstname,
                    s.lastname,
                    s.sex,
                    s.dob,
                    s.profiel,
                    $c as gemiddelde,
                    IFNULL((c.c11), 0) as po,
                    ROUND( IF(c.c14 > 0.9,c.c14, NULL), 1) as cex,
                    ROUND( IF(c.c15 > 0.9,c.c15, NULL), 1) as her,
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
                    AND c.schooljaar = '$schooljaar'
                    AND s.class = '$klas_in'
                    AND c.klas = '$klas_in'
                    AND v.klas = '4A'
                    AND c.rapnummer = 4
                    AND x_index is not null
                    ORDER BY";
    } else {
        $_current_student_start_row = 6;
        $sql_query = "SELECT DISTINCT
                s.id as studentid,
                c.id as cijferid,
                v.id as vakid,
                st.year_period,
                s.class,
                s.firstname,
                s.lastname,
                s.sex,
                s.dob,
                s.profiel,
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
                AND c.rapnummer = $i
                AND x_index is not null
                ORDER BY";
    }
    $sql_order = " s.lastname , s.firstname";
    if ($s->_setting_mj) {
        $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
        $sql_query .=  $sql_order;
    }
    $sql_query .= ", v.volgorde";
    switch ($i) {
        case 1:
            $spreadsheet->setActiveSheetIndex(0);
            $fecha1 = $s->_setting_begin_rap_1;
            $fecha2 = $s->_setting_end_rap_1;
            break;

        case 2:
            $spreadsheet->setActiveSheetIndex(1);
            $fecha1 = $s->_setting_begin_rap_2;
            $fecha2 = $s->_setting_end_rap_2;
            break;

        case 3:
            $spreadsheet->setActiveSheetIndex(2);
            $fecha1 = $s->_setting_begin_rap_3;
            $fecha2 = $s->_setting_end_rap_3;
            break;
    }
    $hojaActiva = $spreadsheet->getActiveSheet();


    $resultado = mysqli_query($mysqli, $sql_query);
    $ckv = 0;
    $mu = 0;
    $bv = 0;
    $cont = 0;

    while ($row = mysqli_fetch_assoc($resultado)) {
        switch ($i) {
            case 1:
                $spreadsheet->setActiveSheetIndex(0);
                break;

            case 2:
                $spreadsheet->setActiveSheetIndex(1);
                break;

            case 3:
                $spreadsheet->setActiveSheetIndex(2);
                break;
        }
        $hojaActiva = $spreadsheet->getActiveSheet();
        $cont = $cont + 1;
        $ultima = mysqli_num_rows($resultado);
        $hojaActiva->setCellValue('BX1', $ultima . " " . $cont);
        if ($_while_counter == 0) {
            $_laststudent = $row["studentid"];
            if ($level_klas == 4) {
                $hojaActiva->setCellValue('B3', $klas_in);
                $hojaActiva->setCellValue('J2', $schooljaar);
            } else {
                $hojaActiva->setCellValue('B2', "Klas: " . $klas_in);
                $hojaActiva->setCellValue('L2', $schooljaar);
                $hojaActiva->setCellValue('B5', $row["docent_name"]);
            }
        }
        $_currentstudent = $row["studentid"];
        $vakid_out = $row["vakid"];
        if ($_currentstudent != $_laststudent) {

            if ($schoolid != 12 || $level_klas < 3) {
                if ($mu > 0 && $bv > 0)
                    $ckv = ($mu + $bv) / 2;
                else if ($mu > 0)
                    $ckv = $mu;
                else if ($bv > 0)
                    $ckv = $bv;

                switch ($level_klas) {
                    case 1:
                    case 2:
                        $hojaActiva->setCellValue('N' . (string)$_current_student_start_row, $ckv);
                        break;

                    case 3:
                    case 4:
                        $hojaActiva->setCellValue('P' . (string)$_current_student_start_row, $ckv);
                        break;
                }
                $ckv = 0;
                $mu = 0;
                $bv = 0;
            } else if ($row["volledigenaamvak"] == "CKV" && $schoolid == 12) {
                $hojaActiva->setCellValue('P' . (string)$_current_student_start_row, $row["gemiddelde"]);
            }

            $_current_student_start_row++;
            $hojaActiva->setCellValue('B' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
            if ($level_klas == 3 && $i == 1) {
                $hojaActiva->setCellValue('AB' . (string)$_current_student_start_row, $row["profiel"]);
            } else if ($level_klas == 4 && $i == 3) {
                $hojaActiva->setCellValue('R' . (string)$_current_student_start_row, $row["profiel"]);
            } else if ($level_klas == 4) {
                $hojaActiva->setCellValue('U' . (string)$_current_student_start_row, $row["profiel"]);
            }
        }
        if ($_while_counter == 0) {
            $hojaActiva->setCellValue('B' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
            if ($level_klas == 3 && $i == 1) {
                $hojaActiva->setCellValue('AB' . (string)$_current_student_start_row, $row["profiel"]);
            } else if ($level_klas == 4 && $i == 3) {
                $hojaActiva->setCellValue('R' . (string)$_current_student_start_row, $row["profiel"]);
            } else if ($level_klas == 4) {
                $hojaActiva->setCellValue('U' . (string)$_current_student_start_row, $row["profiel"]);
            }
        }

        if ($_currentstudent != $_laststudent) {
            $_current_student_start_row++;
            switch ($level_klas) {
                case 1:
                case 2:
                    if ($row["dob"] != "2000-00-00" && $row["dob"] != "0000-00-00") {
                        $spreadsheet->setActiveSheetIndex(5);
                        $hojaActiva = $spreadsheet->getActiveSheet();
                        $hojaActiva->setCellValue('BK' . (string)$_current_student_start_row, $u->convertfrommysqldate($row['dob']));
                    }
                    break;
                case 3:
                    if ($row["dob"] != "2000-00-00" && $row["dob"] != "0000-00-00") {
                        $spreadsheet->setActiveSheetIndex(3);
                        $hojaActiva = $spreadsheet->getActiveSheet();
                        $hojaActiva->setCellValue('BV' . (string)$_current_student_start_row, $u->convertfrommysqldate($row['dob']));

                        $spreadsheet->setActiveSheetIndex(4);
                        $hojaActiva = $spreadsheet->getActiveSheet();
                        $hojaActiva->setCellValue('BV' . (string)$_current_student_start_row, $u->convertfrommysqldate($row['dob']));

                        $spreadsheet->setActiveSheetIndex(5);
                        $hojaActiva = $spreadsheet->getActiveSheet();
                        $hojaActiva->setCellValue('BV' . (string)$_current_student_start_row, $u->convertfrommysqldate($row['dob']));
                    }
                    break;
            }
            switch ($i) {
                case 1:
                    $spreadsheet->setActiveSheetIndex(0);
                    break;

                case 2:
                    $spreadsheet->setActiveSheetIndex(1);
                    break;

                case 3:
                    $spreadsheet->setActiveSheetIndex(2);
                    break;
            }
            $hojaActiva = $spreadsheet->getActiveSheet();
            $_current_student_start_row--;
        }

        if ($_while_counter == 0) {
            $_current_student_start_row++;
            switch ($level_klas) {
                case 1:
                case 2:
                    $spreadsheet->setActiveSheetIndex(3);
                    $hojaActiva = $spreadsheet->getActiveSheet();
                    $hojaActiva->setCellValue('B2', "Klas: " . $klas_in);
                    $spreadsheet->setActiveSheetIndex(4);
                    $hojaActiva = $spreadsheet->getActiveSheet();
                    $hojaActiva->setCellValue('B2', "Klas: " . $klas_in);
                    $spreadsheet->setActiveSheetIndex(5);
                    $hojaActiva = $spreadsheet->getActiveSheet();
                    $hojaActiva->setCellValue('B2', "Klas: " . $klas_in);
                    if ($row["dob"] != "2000-00-00" && $row["dob"] != "0000-00-00") {
                        $hojaActiva->setCellValue('BK' . (string)$_current_student_start_row, $u->convertfrommysqldate($row['dob']));
                    }
                    break;
                case 3:
                    $spreadsheet->setActiveSheetIndex(3);
                    $hojaActiva = $spreadsheet->getActiveSheet();
                    $hojaActiva->setCellValue('B2', "Klas: " . $klas_in);
                    if ($row["dob"] != "2000-00-00" && $row["dob"] != "0000-00-00") {
                        $hojaActiva->setCellValue('BV' . (string)$_current_student_start_row, $u->convertfrommysqldate($row['dob']));
                    }

                    $spreadsheet->setActiveSheetIndex(4);
                    $hojaActiva = $spreadsheet->getActiveSheet();
                    $hojaActiva->setCellValue('B2', "Klas: " . $klas_in);
                    if ($row["dob"] != "2000-00-00" && $row["dob"] != "0000-00-00") {
                        $hojaActiva->setCellValue('BV' . (string)$_current_student_start_row, $u->convertfrommysqldate($row['dob']));
                    }

                    $spreadsheet->setActiveSheetIndex(5);
                    $hojaActiva = $spreadsheet->getActiveSheet();
                    $hojaActiva->setCellValue('B2', "Klas: " . $klas_in);
                    if ($row["dob"] != "2000-00-00" && $row["dob"] != "0000-00-00") {
                        $hojaActiva->setCellValue('BV' . (string)$_current_student_start_row, $u->convertfrommysqldate($row['dob']));
                    }
                    break;
            }
            switch ($i) {
                case 1:
                    $spreadsheet->setActiveSheetIndex(0);
                    break;

                case 2:
                    $spreadsheet->setActiveSheetIndex(1);
                    break;

                case 3:
                    $spreadsheet->setActiveSheetIndex(2);
                    break;
            }
            $hojaActiva = $spreadsheet->getActiveSheet();
            $_current_student_start_row--;
        }

        switch ($level_klas) {
            case 1:
            case 2:
                switch ($row["volledigenaamvak"]) {
                    case 'kgl':
                        $returnvalue = 'M';
                        break;

                    case 'pv':
                        $returnvalue = 'I';
                        break;

                    case 'lo':
                        $returnvalue = 'L';
                        break;

                    case 'asw':
                        $returnvalue = 'J';
                        break;

                    case 'pa':
                        $returnvalue = 'F';
                        break;

                    case 'ne':
                        $returnvalue = 'C';
                        break;

                    case 'en':
                        $returnvalue = 'D';
                        break;

                    case 'sp':
                        $returnvalue = 'E';
                        break;

                    case 'n&t':
                        $returnvalue = 'H';
                        break;

                    case 'wi':
                        $returnvalue = 'G';
                        break;

                    case 'ik':
                        $returnvalue = 'K';
                        break;

                    case 'rk':
                        $returnvalue = 'O';
                        break;
                    default:
                        $returnvalue = "XX";
                        $houding = "XZ";
                        break;
                }
                break;

            case 3:
                switch ($row["volledigenaamvak"]) {

                    case 'lo':
                        $returnvalue = 'O';
                        break;

                    case 'pa':
                        $returnvalue = 'F';
                        break;

                    case 'ne':
                        $returnvalue = 'C';
                        break;

                    case 'en':
                        $returnvalue = 'D';
                        break;

                    case 'sp':
                        $returnvalue = 'E';
                        break;

                    case 'wi':
                        $returnvalue = 'G';
                        break;

                    case 'ik':
                        $returnvalue = 'N';
                        break;

                    case 'rk':
                        $returnvalue = 'Q';
                        break;

                    case 'na':
                        $returnvalue = 'H';
                        break;

                    case 'sk':
                        $returnvalue = 'I';
                        break;

                    case 'bi':
                        $returnvalue = 'J';
                        break;

                    case 'ec':
                        $returnvalue = 'K';
                        break;

                    case 'ak':
                        $returnvalue = 'L';
                        break;

                    case 'gs':
                        $returnvalue = 'M';
                        break;

                    default:
                        $returnvalue = "XX";
                        break;
                }
                break;
            case 4:
                $vrijstelling = ["e1" => 'ne', "e2" => 'en', "e3" => 'sp', "e4" => 'pa', "e5" => 'wi', "e6" => 'na', "e7" => 'sk', "e8" => 'bi', "e9" => 'ec', "e10" => 'ak', "e11" => 'gs', "e12" => 're'];
                $vaks = "SELECT e.* FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id WHERE e.type = 0 AND  e.schooljaar = '$schooljaar' AND p.studentid = " . $row["studentid"] . " AND e.schoolid = $schoolid";
                $vaks_result = mysqli_query($mysqli, $vaks);
                while ($row1 = mysqli_fetch_assoc($vaks_result)) {
                    $eba_ex = [$row1['e1'], $row1['e2'], $row1['e3'], $row1['e4'], $row1['e5'], $row1['e6'], $row1['e7'], $row1['e8'], $row1['e9'], $row1['e10'], $row1['e11'], $row1['e12']];
                }
                for ($in = 1; $in < 13; $in++) {
                    if ($eba_ex[$in - 1] == 'V' && $vrijstelling["e$in"] == $row["volledigenaamvak"]) {
                        $v[] = $vrijstelling["e$in"];
                    }
                }
                if (in_array($row["volledigenaamvak"], $v)) {
                    $vri = true;
                } else {
                    $vri = null;
                }

                switch ($row["volledigenaamvak"]) {
                        // case 'lo':
                        //     $returnvalue = 'O';
                        //     break;

                    case 'pa':
                        $returnvalue = 'F';
                        $returnvalue_v = "F";
                        break;

                    case 'ne':
                        $returnvalue = 'C';
                        $returnvalue_v = "C";
                        break;

                    case 'en':
                        $returnvalue = 'D';
                        $returnvalue_v = "D";
                        break;

                    case 'sp':
                        $returnvalue = 'E';
                        $returnvalue_v = "E";
                        break;

                    case 'wi':
                        $returnvalue = 'G';
                        $returnvalue_v = "G";
                        break;

                    case 'ik':
                        $returnvalue = 'N';
                        break;

                    case 'rk':
                        $returnvalue = 'Q';
                        break;

                    case 'na':
                        $returnvalue = 'H';
                        $returnvalue_v = "H";
                        break;

                    case 'sk':
                        $returnvalue = $i == 3 ? 'J' : 'I';
                        $returnvalue_v = "I";
                        break;

                    case 'bi':
                        $returnvalue = $i == 3 ? 'K' : 'J';
                        $returnvalue_v = "J";
                        break;

                    case 'ec':
                        $returnvalue = $i == 3 ? 'M' : 'K';
                        $returnvalue_v = "K";
                        break;

                    case 'ak':
                        $returnvalue = $i == 3 ? 'N' : 'L';
                        $returnvalue_v = "L";
                        break;

                    case 'gs':
                        $returnvalue = $i == 3 ? 'P' : 'T';
                        $returnvalue_v = "M";
                        break;

                    default:
                        $returnvalue = "XX";
                        break;
                }
                break;
        }
        if ($cont == $ultima && ($schoolid != 12 || $level_klas < 3)) {
            if ($mu > 0 && $bv > 0)
                $ckv = ($mu + $bv) / 2;
            else if ($mu > 0)
                $ckv = $mu;
            else if ($bv > 0)
                $ckv = $bv;

            switch ($level_klas) {
                case 1:
                case 2:
                    $hojaActiva->setCellValue('N' . (string)$_current_student_start_row, $ckv);
                    break;

                case 3:
                case 4:
                    $hojaActiva->setCellValue('P' . (string)$_current_student_start_row, $ckv);
                    break;
            }
            $ckv = 0;
            $mu = 0;
            $bv = 0;
        } else if ($row["volledigenaamvak"] == "CKV" && $schoolid == 12) {
            $hojaActiva->setCellValue('P' . (string)$_current_student_start_row, $row["gemiddelde"]);
        }

        if ($row["gemiddelde"] > 0) {
            $colgemiddelde = (string)$returnvalue . (string)$_current_student_start_row;
            if ($vri == true && $level_klas == 4) {
                $hojaActiva->setCellValue($colgemiddelde, "V");
                $spreadsheet->setActiveSheetIndex(5);
                $hojaActiva = $spreadsheet->getActiveSheet();
                $colgemiddelde = (string)$returnvalue_v . (string)($_current_student_start_row + 4);
                $hojaActiva->setCellValue($colgemiddelde, $row["gemiddelde"]);
                switch ($i) {
                    case 1:
                        $spreadsheet->setActiveSheetIndex(0);
                        break;

                    case 2:
                        $spreadsheet->setActiveSheetIndex(1);
                        break;

                    case 3:
                        $spreadsheet->setActiveSheetIndex(2);
                        break;
                }
                $hojaActiva = $spreadsheet->getActiveSheet();
            } else {
                $hojaActiva->setCellValue($colgemiddelde, $row["gemiddelde"]);
            }
        }

        if ($i == 3 && $level_klas == 4 && $row["po"] > 0) {
            switch ($row["volledigenaamvak"]) {
                case "na":
                    $hojaActiva->setCellValue("I" . (string)$_current_student_start_row, $row["po"]);
                    break;
                case "bi":
                    $hojaActiva->setCellValue("L" . (string)$_current_student_start_row, $row["po"]);
                    break;
                case "gs":
                    $hojaActiva->setCellValue("Q" . (string)$_current_student_start_row, $row["po"]);
                    break;
                case "ak":
                    $hojaActiva->setCellValue("O" . (string)$_current_student_start_row, $row["po"]);
                    break;
            }
        }
        if ($level_klas == 4) {
            $spreadsheet->setActiveSheetIndex(4);
            $hojaActiva = $spreadsheet->getActiveSheet();
            switch ($row["volledigenaamvak"]) {
                case 'pa':
                    $cex = 'AB';
                    $her = 'AC';
                    break;

                case 'ne':
                    $cex = 'G';
                    $her = 'H';
                    break;

                case 'en':
                    $cex = 'N';
                    $her = 'O';
                    break;

                case 'sp':
                    $cex = 'U';
                    $her = 'V';
                    break;

                case 'wi':
                    $cex = 'AI';
                    $her = 'AJ';
                    break;

                case 'na':
                    $cex = 'AQ';
                    $her = 'AR';
                    break;

                case 'sk':
                    $cex = 'AX';
                    $her = 'AY';
                    break;

                case 'bi':
                    $cex = 'BF';
                    $her = 'BG';
                    break;

                case 'ec':
                    $cex = 'BM';
                    $her = 'BN';
                    break;

                case 'ak':
                    $cex = 'BU';
                    $her = 'BV';
                    break;

                case 'gs':
                    $cex = 'CC';
                    $her = 'CD';
                    break;


                default:
                    $cex = "XX";
                    $her = 'YY';
                    break;
            }
            if ($row["cex"] != NULL || $row["her"] != NULL) {
                $colcex = (string)$cex . (string)$_current_student_start_row;
                $colher = (string)$her . (string)$_current_student_start_row;
                $hojaActiva->setCellValue($colcex, $row["cex"]);
                $hojaActiva->setCellValue($colher, $row["her"]);
            }

            switch ($row["volledigenaamvak"]) {
                case 'lo':
                    $cex = 'CG';
                    break;
            }
            if ($row["gemiddelde"] != NULL && ($cex == 'CF' || $cex == 'CG')) {
                $colcex = (string)$cex . (string)$_current_student_start_row;
                $hojaActiva->setCellValue($colcex, $row["gemiddelde"] > 9 ? "Voldoende" : "Onvoldoende");
            }
            $get_ckv = "SELECT p.ckv FROM personalia p WHERE p.schoolid = $schoolid AND p.schooljaar = '$schooljaar' AND p.studentid = $_currentstudent;";
            $resultado_ckv = mysqli_query($mysqli, $get_ckv);
            $ckv_val = $resultado_ckv->fetch_assoc()["ckv"];
            if ($ckv_val != null) {
                $hojaActiva->setCellValue('CF' . (string)$_current_student_start_row, $ckv_val == 1 ? "Voldoende" : "Onvoldoende");
            }
        }
        $_laststudent = $_currentstudent;
        $_while_counter++;
    }

    $sql_query_student = "SELECT id from students s where s.class = '$klas_in' and schoolid = $schoolid ORDER BY";

    $sql_order = " s.lastname , s.firstname";
    if ($s->_setting_mj) {
        $sql_query_student .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
        $sql_query_student .=  $sql_order;
    }

    $_current_student_start_row = $level_klas == 4 ? 7 : 6;
    $cont_laat = 0;
    $cont_verzuim = 0;
    $_laststudent = 0;
    $_while_counter = 0;

    $resultado1 = mysqli_query($mysqli, $sql_query_student);
    while ($row = mysqli_fetch_assoc($resultado1)) {
        $id = $row['id'];
        $sql_query_verzuim = "SELECT s.id as studentid,v.p1,v.p2,v.p3,v.p4,v.p5,v.p6,v.p7,v.p8,v.p9,v.p10, v.datum
            from students s inner join le_verzuim_hs v
            where s.class = '$klas_in'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
        $resultado = mysqli_query($mysqli, $sql_query_verzuim);
        while ($row1 = mysqli_fetch_assoc($resultado)) {
            $datum = $u->convertfrommysqldate_new($row1["datum"]);
            if ($datum >= $fecha1 && $datum <= $fecha2) {
                if ($row1["p10"] == 'A') {
                    $cont_verzuim++;
                }
                for ($y = 1; $y <= 9; $y++) {
                    if ($row1["p" . $y] == 'L') {
                        $cont_laat++;
                    }
                }
            }
        }
        $query = "SELECT opmerking1, opmerking2, opmerking3 FROM opmerking WHERE klas = '$klas_in' AND SchoolID = $schoolid AND studentid = $id AND schooljaar = '$schooljaar' AND rapport = $i LIMIT 1";
        $resultado = mysqli_query($mysqli, $query);
        while ($row = mysqli_fetch_assoc($resultado)) {
            $opmerking1 = $row["opmerking1"];
            $opmerking2 = $row["opmerking2"];
            $opmerking3 = $row["opmerking3"];
        }
        switch ($i) {
            case 1:
                $opmerking = $opmerking1;
                break;

            case 2:
                $opmerking = $opmerking2;
                break;

            case 3:
                $opmerking = $opmerking3;
                break;
        }
        switch ($i) {
            case 1:
                $spreadsheet->setActiveSheetIndex(0);
                break;

            case 2:
                $spreadsheet->setActiveSheetIndex(1);
                break;

            case 3:
                $spreadsheet->setActiveSheetIndex(2);
                break;
        }
        $hojaActiva = $spreadsheet->getActiveSheet();
        switch ($level_klas) {
            case 1:
            case 2:
                $hojaActiva->setCellValue("U" . (string)$_current_student_start_row, $cont_laat);
                $hojaActiva->setCellValue("V" . (string)$_current_student_start_row, $cont_verzuim);
                $hojaActiva->setCellValue("X" . (string)$_current_student_start_row, $opmerking);
                break;

            case 3:
                $hojaActiva->setCellValue("W" . (string)$_current_student_start_row, $cont_laat);
                $hojaActiva->setCellValue("X" . (string)$_current_student_start_row, $cont_verzuim);
                $hojaActiva->setCellValue("AC" . (string)$_current_student_start_row, $opmerking);
                break;

            case 4:
                if ($i == 3) {
                    $hojaActiva->setCellValue("T" . (string)$_current_student_start_row,  $opmerking1);
                    $hojaActiva->setCellValue("S" . (string)$_current_student_start_row,  $opmerking3);
                    $query = "SELECT opmerking1, opmerking3 FROM opmerking WHERE klas = '$klas_in' AND SchoolID = $schoolid AND studentid = $id AND schooljaar = '$schooljaar' AND rapport = 4 LIMIT 1";
                    $resultado = mysqli_query($mysqli, $query);
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        $opmerking1 = $row["opmerking1"];
                        $opmerking3 = $row["opmerking3"];
                        $spreadsheet->setActiveSheetIndex(4);
                        $hojaActiva = $spreadsheet->getActiveSheet();
                        $hojaActiva->setCellValue("CQ" . (string)$_current_student_start_row,  $opmerking1);
                        $hojaActiva->setCellValue("CP" . (string)$_current_student_start_row,  $opmerking3);
                    }
                } else {
                    $hojaActiva->setCellValue("W" . (string)$_current_student_start_row,  $opmerking1);
                    $hojaActiva->setCellValue("V" . (string)$_current_student_start_row,  $opmerking3);
                }
                break;
        }
        $cont_laat = 0;
        $cont_verzuim = 0;
        $cont_huis = 0;
        $opmerking = '';
        $opmerking1 = '';
        $opmerking2 = '';
        $opmerking3 = '';
        $_laststudent = 0;
        $_current_student_start_row++;
    }
    $i++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="verzamelstaten_v2_' . $klas_in . '.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');


ob_flush();
