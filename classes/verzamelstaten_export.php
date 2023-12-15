<?php

ob_start();

require_once("spn_setting.php");
require_once "3rdparty/vendor/autoload.php";
require_once "DBCreds.php";
require_once("spn_setting.php");
require_once("spn_utils.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class verzamelstaten_export
{

    function create_verzamelstaten($schoolid, $schooljaar, $klas_in, $rap_in, $user, $generate_all_rapporten)
    {
        $returnvalue = 0;
        $user_permission = "";
        $sql_query = "";
        $htmlcontrol = "";
        $s = new spn_setting();
        $s->getsetting_info($schoolid, false);

        $_while_counter = 0;

        $_currentstudent = null;
        $_laststudent = null;

        $_current_student_start_row = 4;
        /* 
        $sql_query_houding = "SELECT coalesce(h1,4),coalesce(h2,4),coalesce(h3,4),coalesce(h4,4),coalesce(h5,4),coalesce(h6,4) FROM le_houding_hs where klas = '$klas_in' and vakid = '' and studentid = $studentid_out and schooljaar = '$schooljaar' and rapnummer = $rap_in";

        $sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
        if ($s->_setting_mj) {
            $sql_query_houding .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
        } else {
            $sql_query_houding .=  $sql_order;
        } */

        $x = 1;
        $level_klas = substr($klas_in, 0, 1);

        switch ($level_klas) {
            case 1:
            case 2:
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                $spreadsheet = $reader->load("../templates/verzamelstaten_test.xlsx");

                $spreadsheet->setActiveSheetIndex(0);
                $hojaActiva = $spreadsheet->getActiveSheet();

                $DBCreds = new DBCreds();
                $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
                $mysqli->set_charset('utf8');
                while ($x <= $rap_in) {
                    $_while_counter = 0;

                    $_currentstudent = null;
                    $_laststudent = null;

                    $_current_student_start_row = 4;
                    $sql_query = "SELECT DISTINCT
		                s.id as studentid,
		                c.id as cijferid,
		                v.id as vakid,
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
		                AND c.rapnummer = $x
		                AND x_index is not null
		                ORDER BY";
                    $sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
                    if ($s->_setting_mj) {
                        $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
                    } else {
                        $sql_query .=  $sql_order;
                    }

                    $resultado = mysqli_query($mysqli, $sql_query);
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        if ($_while_counter == 0) {
                            $_laststudent = $row["studentid"];
                            $hojaActiva->setCellValue('D1', $klas_in);
                            $hojaActiva->setCellValue('D2', $schooljaar);
                            $hojaActiva->setCellValue('D3', $row["docent_name"]);
                        }
                        $_currentstudent = $row["studentid"];
                        $vakid_out = $row["vakid"];
                        if ($_currentstudent != $_laststudent) {
                            $_current_student_start_row++;
                            $hojaActiva->setCellValue('D' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
                        }
                        if ($_while_counter == 0) {
                            $hojaActiva->setCellValue('D' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
                        }

                        switch ($row["volledigenaamvak"]) {
                            case 'kgl':
                                switch ($x) {
                                    case 1:
                                        $houding = "F";
                                        $returnvalue = "E";
                                        break;

                                    case 2:
                                        $houding = "H";
                                        $returnvalue = "G";
                                        break;

                                    case 3:
                                        $houding = "J";
                                        $returnvalue = "I";
                                        break;
                                }
                                break;

                            case 'pv':
                                switch ($x) {
                                    case 1:
                                        $houding = "M";
                                        $returnvalue = "L";
                                        break;

                                    case 2:
                                        $houding = "O";
                                        $returnvalue = "N";
                                        break;

                                    case 3:
                                        $houding = "Q";
                                        $returnvalue = "P";
                                        break;
                                }
                                break;

                            case 'lo':
                                switch ($x) {
                                    case 1:
                                        $houding = "T";
                                        $returnvalue = "S";
                                        break;

                                    case 2:
                                        $houding = "V";
                                        $returnvalue = "U";
                                        break;

                                    case 3:
                                        $houding = "X";
                                        $returnvalue = "W";
                                        break;
                                }
                                break;

                            case 'asw':
                                switch ($x) {
                                    case 1:
                                        $houding = "AA";
                                        $returnvalue = "Z";
                                        break;

                                    case 2:
                                        $houding = "AC";
                                        $returnvalue = "AB";
                                        break;

                                    case 3:
                                        $houding = "AE";
                                        $returnvalue = "AD";
                                        break;
                                }
                                break;

                            case 'pa':
                                switch ($x) {
                                    case 1:
                                        $houding = "AH";
                                        $returnvalue = "AG";
                                        break;

                                    case 2:
                                        $houding = "AJ";
                                        $returnvalue = "AI";
                                        break;

                                    case 3:
                                        $houding = "AL";
                                        $returnvalue = "AK";
                                        break;
                                }
                                break;

                            case 'ne':
                                switch ($x) {
                                    case 1:
                                        $houding = "AO";
                                        $returnvalue = "AN";
                                        break;

                                    case 2:
                                        $houding = "AQ";
                                        $returnvalue = "AP";
                                        break;

                                    case 3:
                                        $houding = "AS";
                                        $returnvalue = "AR";
                                        break;
                                }
                                break;

                            case 'en':
                                switch ($x) {
                                    case 1:
                                        $houding = "AV";
                                        $returnvalue = "AU";
                                        break;

                                    case 2:
                                        $houding = "AX";
                                        $returnvalue = "AW";
                                        break;

                                    case 3:
                                        $houding = "AZ";
                                        $returnvalue = "AY";
                                        break;
                                }
                                break;

                            case 'sp':
                                switch ($x) {
                                    case 1:
                                        $houding = "BC";
                                        $returnvalue = "BB";
                                        break;

                                    case 2:
                                        $houding = "BE";
                                        $returnvalue = "BD";
                                        break;

                                    case 3:
                                        $houding = "BG";
                                        $returnvalue = "BF";
                                        break;
                                }
                                break;

                            case 'mu':
                                switch ($x) {
                                    case 1:
                                        $houding = "BJ";
                                        $returnvalue = "BI";
                                        break;

                                    case 2:
                                        $houding = "BL";
                                        $returnvalue = "BK";
                                        break;

                                    case 3:
                                        $houding = "BN";
                                        $returnvalue = "BM";
                                        break;
                                }
                                break;

                            case 'bv':
                                switch ($x) {
                                    case 1:
                                        $houding = "BQ";
                                        $returnvalue = "BP";
                                        break;

                                    case 2:
                                        $houding = "BS";
                                        $returnvalue = "BR";
                                        break;

                                    case 3:
                                        $houding = "BU";
                                        $returnvalue = "BT";
                                        break;
                                }
                                break;

                            case 'n&t':
                                switch ($x) {
                                    case 1:
                                        $houding = "BX";
                                        $returnvalue = "BW";
                                        break;

                                    case 2:
                                        $houding = "BZ";
                                        $returnvalue = "BY";
                                        break;

                                    case 3:
                                        $houding = "CB";
                                        $returnvalue = "CA";
                                        break;
                                }
                                break;

                            case 'wi':
                                switch ($x) {
                                    case 1:
                                        $houding = "CE";
                                        $returnvalue = "CD";
                                        break;

                                    case 2:
                                        $houding = "CG";
                                        $returnvalue = "CF";
                                        break;

                                    case 3:
                                        $houding = "CI";
                                        $returnvalue = "CH";
                                        break;
                                }
                                break;

                            case 'ik':
                                switch ($x) {
                                    case 1:
                                        $houding = "CL";
                                        $returnvalue = "CK";
                                        break;

                                    case 2:
                                        $houding = "CN";
                                        $returnvalue = "CM";
                                        break;

                                    case 3:
                                        $houding = "CP";
                                        $returnvalue = "CO";
                                        break;
                                }
                                break;
                            default:
                                $returnvalue = "XX";
                                $houding = "XZ";
                                break;
                        }
                        $sql_query_houding = "SELECT vakid,h1,h2,h3,h4,h5,h6 
                                FROM le_houding_hs where klas = '$klas_in' and vakid = $vakid_out and studentid = $_currentstudent and schooljaar = '$schooljaar' and rapnummer = $x";
                        $resultado1 = mysqli_query($mysqli, $sql_query_houding);
                        while ($row1 = mysqli_fetch_assoc($resultado1)) {
                            $h1 = $row1['h1'];
                            $h2 = $row1['h2'];
                            $h3 = $row1['h3'];
                            $h4 = $row1['h4'];
                            $h5 = $row1['h5'];
                            $h6 = $row1['h6'];
                            if (!$h1 || $h1 != '' || $h1 != null) {
                                if ($h1 == 0)
                                    $h1 = 4;

                                $_h1 = (float)$h1;
                            } else
                                $_h1 = (float)4;

                            if (!$h2 || $h2 != '' || $h1 != null) {
                                if ($h2 == 0)
                                    $h2 = 4;

                                $_h2 = (float)$h2;
                            } else
                                $_h2 = (float)4;

                            if (!$h3 || $h3 != '' || $h1 != null) {
                                if ($h3 == 0)
                                    $h3 = 4;

                                $_h3 = (float)$h3;
                            } else
                                $_h3 = (float)4;

                            if (!$h4 || $h4 != '' || $h1 != null) {
                                if ($h4 == 0)
                                    $h4 = 4;

                                $_h4 = (float)$h4;
                            } else
                                $_h4 = (float)4;

                            if (!$h5 || $h5 != '' || $h1 != null) {
                                if ($h5 == 0)
                                    $h5 = 4;

                                $_h5 = (float)$h5;
                            } else
                                $_h5 = (float)4;

                            if (!$h6 || $h6 != '' || $h1 != null) {
                                if ($h6 == 0)
                                    $h6 = 4;

                                $_h6 = (float)$h6;
                            } else
                                $_h6 = (float)4;
                            $sum_average = ($_h1 + $_h2 + $_h3 + $_h4 + $_h5 + $_h6) / 6;
                        }
                        $colgemiddelde = (string)$returnvalue . (string)$_current_student_start_row;
                        $colhouding = (string)$houding . (string)$_current_student_start_row;
                        $hojaActiva->setCellValue($colgemiddelde, $row["gemiddelde"]);
                        $hojaActiva->setCellValue($colhouding, $sum_average);
                        $_laststudent = $_currentstudent;
                        $_while_counter++;
                    }
                    $sql_query_student = "SELECT id from students s where s.class = '$klas_in' and schoolid = $schoolid ORDER BY";

                    $sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
                    if ($s->_setting_mj) {
                        $sql_query_student .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
                    } else {
                        $sql_query_student .=  $sql_order;
                    }

                    switch ($x) {
                        case 1:
                            $fecha1 = $s->_setting_begin_rap_1;
                            $fecha2 = $s->_setting_end_rap_1;
                            break;

                        case 2:
                            $fecha1 = $s->_setting_begin_rap_2;
                            $fecha2 = $s->_setting_end_rap_2;
                            break;

                        case 3:
                            $fecha1 = $s->_setting_begin_rap_3;
                            $fecha2 = $s->_setting_end_rap_3;
                            break;
                    }

                    $u = new spn_utils();
                    $_current_student_start_row = 4;
                    $cont_laat = 0;
                    $cont_verzuim = 0;
                    $cont_huis = 0;
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
                                if ($row1['p1'] == 'L' || $row1['p2'] == 'L' || $row1['p3'] == 'L' || $row1['p4'] == 'L' || $row1["p5"] == 'L' || $row1["p6"] == 'L' || $row1["p7"] == 'L' || $row1["p8"] == 'L' || $row1["p9"] == 'L' || $row1["p10"] == 'L') {
                                    $cont_laat++;
                                } else if ($row1['p1'] == 'A' || $row1['p2'] == 'A' || $row1['p3'] == 'A' || $row1['p4'] == 'A' || $row1["p5"] == 'A' || $row1["p6"] == 'A' || $row1["p7"] == 'A' || $row1["p8"] == 'A' || $row1["p9"] == 'A' || $row1["p10"] == 'A') {
                                    $cont_verzuim++;
                                }
                            }
                        }
                        switch ($x) {
                            case 1:
                                $laat = "CV";
                                $verzuim = "CR";
                                break;

                            case 2:
                                $laat = "CW";
                                $verzuim = "CS";
                                break;

                            case 3:
                                $laat = "CX";
                                $verzuim = "CT";
                                break;
                        }
                        $hojaActiva->setCellValue($laat . (string)$_current_student_start_row, $cont_laat);
                        $hojaActiva->setCellValue($verzuim . (string)$_current_student_start_row, $cont_verzuim);
                        $cont_laat = 0;
                        $cont_verzuim = 0;
                        $cont_huis = 0;
                        $opmerking = null;
                        $_laststudent = 0;
                        $_current_student_start_row++;
                    }

                    $x++;
                }

                break;

            case 3:
            case 4:
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                $spreadsheet = $reader->load("../templates/13_verza_3-4.xlsx");

                $spreadsheet->setActiveSheetIndex(0);
                $hojaActiva = $spreadsheet->getActiveSheet();


                $DBCreds = new DBCreds();
                $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
                $mysqli->set_charset('utf8');
                while ($x <= $rap_in) {
                    $_while_counter = 0;

                    $_currentstudent = null;
                    $_laststudent = null;

                    $_current_student_start_row = 5;
                    $sql_query = "SELECT DISTINCT
		                s.id as studentid,
		                c.id as cijferid,
		                v.id as vakid,
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
		                AND c.rapnummer = $x
		                AND x_index is not null
		                ORDER BY";
                    $sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
                    if ($s->_setting_mj) {
                        $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
                    } else {
                        $sql_query .=  $sql_order;
                    }

                    $resultado = mysqli_query($mysqli, $sql_query);
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        $ckv = 0;
                        if ($_while_counter == 0) {
                            $_laststudent = $row["studentid"];
                            $hojaActiva->setCellValue('C2', $klas_in);
                            $hojaActiva->setCellValue('C3', $schooljaar);
                            $hojaActiva->setCellValue('C4', $row["docent_name"]);
                        }
                        $_currentstudent = $row["studentid"];
                        $vakid_out = $row["vakid"];
                        if ($_currentstudent != $_laststudent) {
                            $_current_student_start_row++;
                            $hojaActiva->setCellValue('C' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
                        }
                        if ($_while_counter == 0) {
                            $hojaActiva->setCellValue('C' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
                        }

                        switch ($row["volledigenaamvak"]) {
                            case 'kgl':
                                switch ($x) {
                                    case 1:
                                        $houding = "CY";
                                        $returnvalue = "CX";
                                        break;

                                    case 2:
                                        $houding = "DA";
                                        $returnvalue = "CZ";
                                        break;

                                    case 3:
                                        $houding = "DC";
                                        $returnvalue = "DB";
                                        break;
                                }
                                break;

                            case 'CKV':
                                switch ($x) {
                                    case 1:
                                        $houding = "CD";
                                        $returnvalue = "CC";
                                        break;

                                    case 2:
                                        $houding = "CF";
                                        $returnvalue = "CE";
                                        break;

                                    case 3:
                                        $houding = "CH";
                                        $returnvalue = "CG";
                                        break;
                                }
                                break;

                            case 'lo':
                                switch ($x) {
                                    case 1:
                                        $houding = "CR";
                                        $returnvalue = "CQ";
                                        break;

                                    case 2:
                                        $houding = "CT";
                                        $returnvalue = "CS";
                                        break;

                                    case 3:
                                        $houding = "CV";
                                        $returnvalue = "CU";
                                        break;
                                }
                                break;

                            case 'pa':
                                switch ($x) {
                                    case 1:
                                        $houding = "CK";
                                        $returnvalue = "CJ";
                                        break;

                                    case 2:
                                        $houding = "CM";
                                        $returnvalue = "CL";
                                        break;

                                    case 3:
                                        $houding = "CO";
                                        $returnvalue = "CN";
                                        break;
                                }
                                break;

                            case 'ne':
                                switch ($x) {
                                    case 1:
                                        $houding = "E";
                                        $returnvalue = "D";
                                        break;

                                    case 2:
                                        $houding = "G";
                                        $returnvalue = "F";
                                        break;

                                    case 3:
                                        $houding = "I";
                                        $returnvalue = "H";
                                        break;
                                }
                                break;

                            case 'en':
                                switch ($x) {
                                    case 1:
                                        $houding = "L";
                                        $returnvalue = "K";
                                        break;

                                    case 2:
                                        $houding = "N";
                                        $returnvalue = "M";
                                        break;

                                    case 3:
                                        $houding = "P";
                                        $returnvalue = "O";
                                        break;
                                }
                                break;

                            case 'sp':
                                switch ($x) {
                                    case 1:
                                        $houding = "S";
                                        $returnvalue = "R";
                                        break;

                                    case 2:
                                        $houding = "U";
                                        $returnvalue = "T";
                                        break;

                                    case 3:
                                        $houding = "W";
                                        $returnvalue = "V";
                                        break;
                                }
                                break;
                            case 'gs':
                                switch ($x) {
                                    case 1:
                                        $houding = "Z";
                                        $returnvalue = "Y";
                                        break;

                                    case 2:
                                        $houding = "AB";
                                        $returnvalue = "AA";
                                        break;

                                    case 3:
                                        $houding = "AD";
                                        $returnvalue = "AC";
                                        break;
                                }
                                break;

                            case 'wi':
                                switch ($x) {
                                    case 1:
                                        $houding = "AN";
                                        $returnvalue = "AM";
                                        break;

                                    case 2:
                                        $houding = "AP";
                                        $returnvalue = "AO";
                                        break;

                                    case 3:
                                        $houding = "AR";
                                        $returnvalue = "AQ";
                                        break;
                                }
                                break;

                            case 'ak':
                                switch ($x) {
                                    case 1:
                                        $houding = "AG";
                                        $returnvalue = "AF";
                                        break;

                                    case 2:
                                        $houding = "AI";
                                        $returnvalue = "AH";
                                        break;

                                    case 3:
                                        $houding = "AK";
                                        $returnvalue = "AJ";
                                        break;
                                }
                                break;

                            case 'na':
                                switch ($x) {
                                    case 1:
                                        $houding = "AU";
                                        $returnvalue = "AT";
                                        break;

                                    case 2:
                                        $houding = "AW";
                                        $returnvalue = "AV";
                                        break;

                                    case 3:
                                        $houding = "AY";
                                        $returnvalue = "AX";
                                        break;
                                }
                                break;

                            case 'sk':
                                switch ($x) {
                                    case 1:
                                        $houding = "BB";
                                        $returnvalue = "BA";
                                        break;

                                    case 2:
                                        $houding = "BD";
                                        $returnvalue = "BC";
                                        break;

                                    case 3:
                                        $houding = "BF";
                                        $returnvalue = "BE";
                                        break;
                                }
                                break;

                            case 'bi':
                                switch ($x) {
                                    case 1:
                                        $houding = "BI";
                                        $returnvalue = "BH";
                                        break;

                                    case 2:
                                        $houding = "BK";
                                        $returnvalue = "BJ";
                                        break;

                                    case 3:
                                        $houding = "BM";
                                        $returnvalue = "BL";
                                        break;
                                }
                                break;

                            case 'eco':
                                switch ($x) {
                                    case 1:
                                        $houding = "BP";
                                        $returnvalue = "BO";
                                        break;

                                    case 2:
                                        $houding = "BR";
                                        $returnvalue = "BQ";
                                        break;

                                    case 3:
                                        $houding = "BT";
                                        $returnvalue = "BS";
                                        break;
                                }
                                break;

                            case 'ec':
                                switch ($x) {
                                    case 1:
                                        $houding = "BP";
                                        $returnvalue = "BO";
                                        break;

                                    case 2:
                                        $houding = "BR";
                                        $returnvalue = "BQ";
                                        break;

                                    case 3:
                                        $houding = "BT";
                                        $returnvalue = "BS";
                                        break;
                                }
                                break;

                            case 'ik':
                                switch ($x) {
                                    case 1:
                                        $houding = "BW";
                                        $returnvalue = "BV";
                                        break;

                                    case 2:
                                        $houding = "BY";
                                        $returnvalue = "BX";
                                        break;

                                    case 3:
                                        $houding = "CA";
                                        $returnvalue = "BZ";
                                        break;
                                }
                                break;
                            case 'mu':
                            case 'bv':
                                switch ($x) {
                                    case 1:
                                        $houding = "CD";
                                        $returnvalue = "CC";
                                        break;

                                    case 2:
                                        $houding = "CF";
                                        $returnvalue = "CE";
                                        break;

                                    case 3:
                                        $houding = "CH";
                                        $returnvalue = "CG";
                                        break;
                                }
                                break;
                            default:
                                $returnvalue = "XX";
                                $houding = "XZ";
                                break;
                        }
                        $sql_query_houding = "SELECT vakid,h1,h2,h3,h4,h5,h6 
                                FROM le_houding_hs where klas = '$klas_in' and vakid = $vakid_out and studentid = $_currentstudent and schooljaar = '$schooljaar' and rapnummer = $x";
                        $resultado1 = mysqli_query($mysqli, $sql_query_houding);
                        while ($row1 = mysqli_fetch_assoc($resultado1)) {
                            $h1 = $row1['h1'];
                            $h2 = $row1['h2'];
                            $h3 = $row1['h3'];
                            $h4 = $row1['h4'];
                            $h5 = $row1['h5'];
                            $h6 = $row1['h6'];
                            if (!$h1 || $h1 != '' || $h1 != null) {
                                if ($h1 == 0)
                                    $h1 = 4;

                                $_h1 = (float)$h1;
                            } else
                                $_h1 = (float)4;

                            if (!$h2 || $h2 != '' || $h1 != null) {
                                if ($h2 == 0)
                                    $h2 = 4;

                                $_h2 = (float)$h2;
                            } else
                                $_h2 = (float)4;

                            if (!$h3 || $h3 != '' || $h1 != null) {
                                if ($h3 == 0)
                                    $h3 = 4;

                                $_h3 = (float)$h3;
                            } else
                                $_h3 = (float)4;

                            if (!$h4 || $h4 != '' || $h1 != null) {
                                if ($h4 == 0)
                                    $h4 = 4;

                                $_h4 = (float)$h4;
                            } else
                                $_h4 = (float)4;

                            if (!$h5 || $h5 != '' || $h1 != null) {
                                if ($h5 == 0)
                                    $h5 = 4;

                                $_h5 = (float)$h5;
                            } else
                                $_h5 = (float)4;

                            if (!$h6 || $h6 != '' || $h1 != null) {
                                if ($h6 == 0)
                                    $h6 = 4;

                                $_h6 = (float)$h6;
                            } else
                                $_h6 = (float)4;
                            $sum_average = ($_h1 + $_h2 + $_h3 + $_h4 + $_h5 + $_h6) / 6;
                        }
                        $colgemiddelde = (string)$returnvalue . (string)$_current_student_start_row;
                        $colhouding = (string)$houding . (string)$_current_student_start_row;
                        $hojaActiva->setCellValue($colgemiddelde, $row["gemiddelde"]);
                        $hojaActiva->setCellValue($colhouding, $sum_average);
                        if ($schoolid == 13 && $_currentstudent != $_laststudent) {
                            $cont = 0;
                            $sql_query_ckv = "SELECT v.volledigenaamvak as naam,c.gemiddelde
                                FROM le_cijfers c INNER JOIN le_vakken v ON c.vak = v.ID where c.klas = '$klas_in' and c.studentid = $_currentstudent and c.schooljaar = '$schooljaar' and c.rapnummer = $x and (v.volledigenaamvak = 'mu' or v.volledigenaamvak = 'bv')";
                            $resultadockv = mysqli_query($mysqli, $sql_query_ckv);
                            while ($row_ckv = mysqli_fetch_assoc($resultadockv)) {
                                if ($row_ckv['naam'] == 'mu' || $row_ckv['naam'] == 'bv' && $row_ckv['gemiddelde'] > 0) {
                                    $cont++;
                                    $ckv = $ckv + $row_ckv['gemiddelde'];
                                }
                            }
                            if ($cont == 2) {
                                $ckv = $ckv / 2;
                            }
                            switch ($x) {
                                case 1:
                                    $houding = "CD";
                                    $returnvalue = "CC";
                                    break;

                                case 2:
                                    $houding = "CF";
                                    $returnvalue = "CE";
                                    break;

                                case 3:
                                    $houding = "CH";
                                    $returnvalue = "CG";
                                    break;
                            }
                            $colgemiddelde = (string)$returnvalue . (string)$_current_student_start_row;
                            $hojaActiva->setCellValue($colgemiddelde, $ckv);
                        }
                        $_laststudent = $_currentstudent;
                        $_while_counter++;
                    }
                    $sql_query_student = "SELECT id from students s where s.class = '$klas_in' and schoolid = $schoolid ORDER BY";

                    $sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
                    if ($s->_setting_mj) {
                        $sql_query_student .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
                    } else {
                        $sql_query_student .=  $sql_order;
                    }

                    switch ($x) {
                        case 1:
                            $fecha1 = $s->_setting_begin_rap_1;
                            $fecha2 = $s->_setting_end_rap_1;
                            break;

                        case 2:
                            $fecha1 = $s->_setting_begin_rap_2;
                            $fecha2 = $s->_setting_end_rap_2;
                            break;

                        case 3:
                            $fecha1 = $s->_setting_begin_rap_3;
                            $fecha2 = $s->_setting_end_rap_3;
                            break;
                    }

                    $u = new spn_utils();
                    $_current_student_start_row = 5;
                    $cont_laat = 0;
                    $cont_verzuim = 0;
                    $cont_huis = 0;
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
                                if ($row1['p1'] == 'L' || $row1['p2'] == 'L' || $row1['p3'] == 'L' || $row1['p4'] == 'L' || $row1["p5"] == 'L' || $row1["p6"] == 'L' || $row1["p7"] == 'L' || $row1["p8"] == 'L' || $row1["p9"] == 'L' || $row1["p10"] == 'L') {
                                    $cont_laat++;
                                } else if ($row1['p1'] == 'A' || $row1['p2'] == 'A' || $row1['p3'] == 'A' || $row1['p4'] == 'A' || $row1["p5"] == 'A' || $row1["p6"] == 'A' || $row1["p7"] == 'A' || $row1["p8"] == 'A' || $row1["p9"] == 'A' || $row1["p10"] == 'A') {
                                    $cont_verzuim++;
                                }
                            }
                        }
                        switch ($x) {
                            case 1:
                                $laat = "DI";
                                $verzuim = "DE";
                                break;

                            case 2:
                                $laat = "DJ";
                                $verzuim = "DF";
                                break;

                            case 3:
                                $laat = "DJ";
                                $verzuim = "DG";
                                break;
                        }
                        $hojaActiva->setCellValue($laat . (string)$_current_student_start_row, $cont_laat);
                        $hojaActiva->setCellValue($verzuim . (string)$_current_student_start_row, $cont_verzuim);
                        $cont_laat = 0;
                        $cont_verzuim = 0;
                        $cont_huis = 0;
                        $opmerking = null;
                        $_laststudent = 0;
                        $_current_student_start_row++;
                    }

                    $x++;
                }

                break;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="verzamelstaten' . $klas_in . '.xlsx"');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}


ob_flush();
