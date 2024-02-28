<?php
ini_set('max_execution_time', 300);
ini_set('memory_limit', '256M');
ob_start();

require_once("../classes/spn_rapport.php");
require_once("../config/app.config.php");
require_once "../classes/3rdparty/vendor/autoload.php";
require_once "../classes/DBCreds.php";
require_once("../classes/spn_setting.php");

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$r = new spn_rapport();


if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

require_once("../classes/verzamelstaten_export.php");
$ve = new verzamelstaten_export();
$level_klas = substr($_GET["rapport_klassen_lijst"], 0, 1);

if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
	$schooljaar = $_SESSION['SchoolJaar'];
	$schoolid = $_SESSION['SchoolID'];
	$klas_in = $_GET["rapport_klassen_lijst"];
	$rap_in = $_GET["rapport"];
	$user = $_SESSION["UserGUID"];
	$s = new spn_setting();
	$s->getsetting_info($schoolid, false);
	$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	switch ($level_klas) {
		case "2":
			$spreadsheet = $reader->load("../templates/verza_ps_2.xlsx");
			$spreadsheet->setActiveSheetIndex(4);
			$hojaActiva = $spreadsheet->getActiveSheet();
			break;
		case "3":
			$spreadsheet = $reader->load("../templates/verza_ps_3.xlsx");
			$spreadsheet->setActiveSheetIndex(4);
			$hojaActiva = $spreadsheet->getActiveSheet();
			break;
		case "4":
		case "5":
			$spreadsheet = $reader->load("../templates/verza_ps_4-5.xlsx");
			$spreadsheet->setActiveSheetIndex(4);
			$hojaActiva = $spreadsheet->getActiveSheet();
			break;
		case "6":
			$spreadsheet = $reader->load("../templates/verza_ps_6.xlsx");
			$spreadsheet->setActiveSheetIndex(4);
			$hojaActiva = $spreadsheet->getActiveSheet();
			break;
		default:
			$spreadsheet = $reader->load("../templates/verza_ps_2022.xlsx");
			$spreadsheet->setActiveSheetIndex(3);
			$hojaActiva = $spreadsheet->getActiveSheet();
			break;
	}
	$i = 1;
	switch ($_SESSION["SchoolID"]) {
		case 7:
			$scol = 1;
			break;
		case 11:
			$scol = 2;
			break;
		case 4:
			$scol = 3;
			break;
		case 9:
			$scol = 4;
			break;
		case 6:
			$scol = 5;
			break;
		case 10:
			$scol = 6;
			break;
		default:
			$scol = 0;
			break;
	}
	$hojaActiva->setCellValue('B8', $scol);

	while ($i <= $rap_in) {
		$_laststudent = 0;
		$_current_student_start_row = 4;
		$_while_counter = 0;
		$sql_query = "SELECT DISTINCT
		s.id as studentid,
		c.id as cijferid,
		v.id as vakid,
		v.vak_naam,
		st.year_period,
		s.class,
		s.dob,
		s.firstname,
		s.lastname,
		s.sex,
		c.gemiddelde,
		CONCAT(app.firstname,' ',app.lastname) as docent_name
		FROM students s
		LEFT JOIN le_cijfers_ps c ON s.id = c.studentid AND s.class = c.klas AND s.schoolid = c.school_id AND c.rapnummer = $i AND c.schooljaar = '$schooljaar'
		LEFT JOIN le_vakken_ps v ON c.vak = v.id
		INNER JOIN setting st ON st.schoolid = s.schoolid
		INNER JOIN app_useraccounts app ON st.schoolid = app.SchoolID and app.UserGUID = '$user'
		WHERE
		s.schoolid = $schoolid
		AND st.year_period = '$schooljaar'
		AND s.class = '$klas_in'
		AND s.status = 1
		ORDER BY ";
		$sql_order = " s.lastname , s.firstname";
		if ($s->_setting_mj) {
			$sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
		} else {
			$sql_query .=  $sql_order;
		}
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
		$DBCreds = new DBCreds();
		$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
		$mysqli->set_charset('utf8');
		$resultado = mysqli_query($mysqli, $sql_query);
		while ($row = mysqli_fetch_assoc($resultado)) {
			if ($_while_counter == 0) {
				$_laststudent = $row["studentid"];
				$hojaActiva->setCellValue('O1', $_GET["rapport_klassen_lijst"]);
				$hojaActiva->setCellValue('AF1', $_SESSION['SchoolJaar']);
				$hojaActiva->setCellValue('S1', $row["docent_name"]);
				$hojaActiva->setCellValue('D1', "Rapport " . $i);
			}
			$_currentstudent = $row["studentid"];
			if ($_currentstudent != $_laststudent) {
				$_current_student_start_row++;
				$hojaActiva->setCellValue('B' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
			}
			if ($_while_counter == 0) {
				$hojaActiva->setCellValue('B' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
			}
			switch ($row["vakid"]) {
				case 1:
					$returnvalue = "I";
					break;

				case 2:
					$returnvalue = "D";
					break;

				case 3:
					$returnvalue = "E";
					break;

				case 5:
					$returnvalue = "G";
					break;

				case 6:
					$returnvalue = "L";
					break;

				case 7:
					$returnvalue = "O";
					break;

				case 8:
					$returnvalue = "Q";
					break;

				case 9:
					$returnvalue = "T";
					break;

				case 10:
					$returnvalue = "Z";
					break;

				case 11:
					$returnvalue = "AA";
					break;

				case 12:
					$returnvalue = "P";
					break;

				case 27:
					$returnvalue = "W";
					break;

				case 28:
					$returnvalue = "S";
					break;
			}
			$colgemiddelde = (string)$returnvalue . (string)$_current_student_start_row;
			if ($row["gemiddelde"] > 0.0) {
				$hojaActiva->setCellValue($colgemiddelde, $row["gemiddelde"]);
			}
			$_laststudent = $_currentstudent;
			$_while_counter++;
		}

		$sql_query_houding = "SELECT DISTINCT
		s.id as studentid,
		h.h1,h.h2,h.h3,h.h4,h.h5,h.h6,h.h7,h.h8,h.h9,h.h10,
		h.h11,h.h12,h.h13,h.h14
		FROM students s
		LEFT JOIN le_houding h on h.studentid = s.id and s.class = h.klas AND h.rapnummer = $i AND h.schooljaar = '$schooljaar'
		INNER JOIN setting st ON st.schoolid = s.schoolid
		WHERE
		s.schoolid = $schoolid
		AND st.year_period = '$schooljaar'
		AND s.class = '$klas_in'
		ORDER BY";
		$sql_order = " s.lastname , s.firstname";
		// if ($s->_setting_mj) {
		// 	$sql_query_houding .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
		// } else {
		$sql_query_houding .=  $sql_order;
		// }
		$_while_counter = 0;
		$_current_student_start_row = 4;
		$_laststudent = 0;
		$cont_houdin = 1;

		$resultado = mysqli_query($mysqli, $sql_query_houding);
		while ($row = mysqli_fetch_assoc($resultado)) {
			if ($_while_counter == 0) {
				$_laststudent = $row["studentid"];
			}

			$_currentstudent = $row["studentid"];

			if ($_currentstudent != $_laststudent) {
				$_current_student_start_row++;
			}
			$h1 = $row["h1"];
			$h2 = $row["h2"];
			$h3 = $row["h3"];
			$h4 = $row["h4"];
			$h5 = $row["h5"];
			$h6 = $row["h6"];
			$h7 = $row["h7"];
			$h8 = $row["h8"];
			$h9 = $row["h9"];
			$h10 = $row["h10"];
			$h11 = $row["h11"];
			$h12 = $row["h12"];
			$h13 = $row["h13"];
			$h14 = $row["h14"];
			while ($cont_houdin < 15) {
				switch ($cont_houdin) {
					case 1:
						$_h1 = "";
						$colhouding = "AJ" . (string)$_current_student_start_row;
						if ($h1 == 1 || !isset($h1)) {
							$_h1 = "A";
						}
						if ($h1 == 2) {
							$_h1 = "B";
						}
						if ($h1 == 3) {
							$_h1 = "C";
						}
						if ($h1 == 4) {
							$_h1 = "D";
						}
						if ($h1 == 5) {
							$_h1 = "E";
						}
						if ($h1 == 6) {
							$_h1 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h1);
						break;

					case 2:
						$_h2 = "";
						$colhouding = "AK" . (string)$_current_student_start_row;
						if ($h2 == 1 || !isset($h2)) {
							$_h2 = "A";
						}
						if ($h2 == 2) {
							$_h2 = "B";
						}
						if ($h2 == 3) {
							$_h2 = "C";
						}
						if ($h2 == 4) {
							$_h2 = "D";
						}
						if ($h2 == 5) {
							$_h2 = "E";
						}
						if ($h2 == 6) {
							$_h2 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h2);
						break;

					case 3:
						$_h3 = "";
						$colhouding = "AL" . (string)$_current_student_start_row;
						if ($h3 == 1 || !isset($h3)) {
							$_h3 = "A";
						}
						if ($h3 == 2) {
							$_h3 = "B";
						}
						if ($h3 == 3) {
							$_h3 = "C";
						}
						if ($h3 == 4) {
							$_h3 = "D";
						}
						if ($h3 == 5) {
							$_h3 = "E";
						}
						if ($h3 == 6) {
							$_h3 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h3);
						break;

					case 4:
						$_h4 = "";
						$colhouding = "AM" . (string)$_current_student_start_row;
						if ($h4 == 1 || !isset($h4)) {
							$_h4 = "A";
						}
						if ($h4 == 2) {
							$_h4 = "B";
						}
						if ($h4 == 3) {
							$_h4 = "C";
						}
						if ($h4 == 4) {
							$_h4 = "D";
						}
						if ($h4 == 5) {
							$_h4 = "E";
						}
						if ($h4 == 6) {
							$_h4 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h4);
						break;

					case 5:
						$_h5 = "";
						$colhouding = "AN" . (string)$_current_student_start_row;
						if ($h5 == 1 || !isset($h5)) {
							$_h5 = "A";
						}
						if ($h5 == 2) {
							$_h5 = "B";
						}
						if ($h5 == 3) {
							$_h5 = "C";
						}
						if ($h5 == 4) {
							$_h5 = "D";
						}
						if ($h5 == 5) {
							$_h5 = "E";
						}
						if ($h5 == 6) {
							$_h5 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h5);
						break;

					case 6:
						$_h6 = "";
						$colhouding = "AO" . (string)$_current_student_start_row;
						if ($h6 == 1 || !isset($h6)) {
							$_h6 = "A";
						}
						if ($h6 == 2) {
							$_h6 = "B";
						}
						if ($h6 == 3) {
							$_h6 = "C";
						}
						if ($h6 == 4) {
							$_h6 = "D";
						}
						if ($h6 == 5) {
							$_h6 = "E";
						}
						if ($h6 == 6) {
							$_h6 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h6);
						break;

					case 7:
						$_h7 = "";
						$colhouding = "AP" . (string)$_current_student_start_row;
						if ($h7 == 1 || !isset($h7)) {
							$_h7 = "A";
						}
						if ($h7 == 2) {
							$_h7 = "B";
						}
						if ($h7 == 3) {
							$_h7 = "C";
						}
						if ($h7 == 4) {
							$_h7 = "D";
						}
						if ($h7 == 5) {
							$_h7 = "E";
						}
						if ($h7 == 6) {
							$_h7 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h7);
						break;

					case 8:
						$_h8 = "";
						$colhouding = "AQ" . (string)$_current_student_start_row;
						if ($h8 == 1 || !isset($h8)) {
							$_h8 = "A";
						}
						if ($h8 == 2) {
							$_h8 = "B";
						}
						if ($h8 == 3) {
							$_h8 = "C";
						}
						if ($h8 == 4) {
							$_h8 = "D";
						}
						if ($h8 == 5) {
							$_h8 = "E";
						}
						if ($h8 == 6) {
							$_h8 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h8);
						break;

					case 9:
						$_h9 = "";
						$colhouding = "AR" . (string)$_current_student_start_row;
						if ($h9 == 1 || !isset($h9)) {
							$_h9 = "A";
						}
						if ($h9 == 2) {
							$_h9 = "B";
						}
						if ($h9 == 3) {
							$_h9 = "C";
						}
						if ($h9 == 4) {
							$_h9 = "D";
						}
						if ($h9 == 5) {
							$_h9 = "E";
						}
						if ($h9 == 6) {
							$_h9 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h9);
						break;

					case 10:
						$_h10 = "";
						$colhouding = "AS" . (string)$_current_student_start_row;
						if ($h10 == 1 || !isset($h10)) {
							$_h10 = "A";
						}
						if ($h10 == 2) {
							$_h10 = "B";
						}
						if ($h10 == 3) {
							$_h10 = "C";
						}
						if ($h10 == 4) {
							$_h10 = "D";
						}
						if ($h10 == 5) {
							$_h10 = "E";
						}
						if ($h10 == 6) {
							$_h10 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h10);
						break;

					case 11:
						$_h11 = "";
						$colhouding = "AT" . (string)$_current_student_start_row;
						if ($h11 == 1 || !isset($h11)) {
							$_h11 = "A";
						}
						if ($h11 == 2) {
							$_h11 = "B";
						}
						if ($h11 == 3) {
							$_h11 = "C";
						}
						if ($h11 == 4) {
							$_h11 = "D";
						}
						if ($h11 == 5) {
							$_h11 = "E";
						}
						if ($h11 == 6) {
							$_h11 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h11);
						break;

					case 12:
						$_h12 = "";
						$colhouding = "J" . (string)$_current_student_start_row;
						if ($h12 == 1 || !isset($h12)) {
							$_h12 = "A";
						}
						if ($h12 == 2) {
							$_h12 = "B";
						}
						if ($h12 == 3) {
							$_h12 = "C";
						}
						if ($h12 == 4) {
							$_h12 = "D";
						}
						if ($h12 == 5) {
							$_h12 = "E";
						}
						if ($h12 == 6) {
							$_h12 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h12);
						break;

					case 13:
						$_h13 = "";
						$colhouding = "M" . (string)$_current_student_start_row;
						if ($h13 == 1 || !isset($h13)) {
							$_h13 = "A";
						}
						if ($h13 == 2) {
							$_h13 = "B";
						}
						if ($h13 == 3) {
							$_h13 = "C";
						}
						if ($h13 == 4) {
							$_h13 = "D";
						}
						if ($h13 == 5) {
							$_h13 = "E";
						}
						if ($h13 == 6) {
							$_h13 = "F";
						}

						$hojaActiva->setCellValue($colhouding, $_h13);
						break;

						// case 14:
						// 	$_h14 = "";
						// 	$colhouding = "U" . (string)$_current_student_start_row;
						// 	if ($h14 == 1 || !isset($h14)) {
						// 		$_h14 = "A";
						// 	}
						// 	if ($h14 == 2) {
						// 		$_h14 = "B";
						// 	}
						// 	if ($h14 == 3) {
						// 		$_h14 = "C";
						// 	}
						// 	if ($h14 == 4) {
						// 		$_h14 = "D";
						// 	}
						// 	if ($h14 == 5) {
						// 		$_h14 = "E";
						// 	}
						// 	if ($h14 == 6) {
						// 		$_h14 = "F";
						// 	}
						// 	if ($h14 == 7) {
						// 		$_h14 = "G";
						// 	}
						// 	if ($h14 == 8) {
						// 		$_h14 = "H";
						// 	}

						// 	$hojaActiva->setCellValue($colhouding, $_h14);
						// 	break;
				}

				$cont_houdin++;
			}

			$cont_houdin = 1;
			$_laststudent = $_currentstudent;

			$_while_counter++;
		}
		$sql_query_student = "SELECT id,dob from students s where s.class = '$klas_in' and s.schoolid = $schoolid ORDER BY";

		$sql_order = " s.lastname , s.firstname";
		// if ($s->_setting_mj) {
		// 	$sql_query_student .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
		// } else {
		$sql_query_student .=  $sql_order;
		// }

		$_current_student_start_row = 4;
		$cont_laat = 0;
		$cont_verzuim = 0;
		$_laststudent = 0;
		$_while_counter = 0;
		$schooljaar_array = explode("-", $schooljaar);
		$schooljaar_pasado = $schooljaar_array[0] - 1 . "-" . $schooljaar_array[0];

		$resultado1 = mysqli_query($mysqli, $sql_query_student);
		require_once("../classes/spn_utils.php");
		$u = new spn_utils();
		while ($row = mysqli_fetch_assoc($resultado1)) {
			$id = $row['id'];
			$sql_query_verzuim = "SELECT SUM(v.telaat) as telaat, SUM(v.absentie) as absentie
			FROM le_verzuim v WHERE v.studentid = $id AND v.schooljaar = '$schooljaar' AND DATE(v.datum) BETWEEN '$fecha1' AND '$fecha2' AND (v.telaat != 0 OR v.absentie != 0) LIMIT 1";
			$resultado3 = mysqli_query($mysqli, $sql_query_verzuim);
			if ($row1 = mysqli_fetch_assoc($resultado3)) {
				$cont_laat = $row1["telaat"];
				$cont_verzuim = $row1["absentie"];
			}

			//opmerking
			$opmerking = "";
			$filter_opmerking = "op.opmerking" . $i;
			$sql_query_opmerking = "SELECT $filter_opmerking as opmerking FROM opmerking op WHERE op.studentid = $id AND op.schooljaar = '$schooljaar' and op.rapport = $i LIMIT 1";
			$resultado2 = mysqli_query($mysqli, $sql_query_opmerking);
			if ($row2 = mysqli_fetch_assoc($resultado2)) {
				$opmerking = $row2["opmerking"];
			}

			$hojaActiva->setCellValue("AG" . (string)$_current_student_start_row, $cont_laat);
			$hojaActiva->setCellValue("AH" . (string)$_current_student_start_row, $cont_verzuim);
			if ($level_klas ==  6) {
				$ned_pro = 0;
				$rek_pro = 0;
				$ned_cont = 0;
				$rek_cont = 0;
				$wer_pro = 0;
				$wer_cont = 0;
				$hojaActiva->setCellValue("BD" . (string)$_current_student_start_row, $opmerking);
				$sql_query_k5 = "SELECT vak,gemiddelde FROM le_cijfers_ps WHERE studentid = $id AND schooljaar = '$schooljaar_pasado' AND school_id = $schoolid AND vak IN (1,6,7) AND gemiddelde is not NULL";
				$resultado_k5 = mysqli_query($mysqli, $sql_query_k5);
				if ($i == 1) {
					$spreadsheet->setActiveSheetIndex(3);
					$hojaActiva = $spreadsheet->getActiveSheet();
					$hojaActiva->setCellValue("AZ" . (string)$_current_student_start_row, $u->convertfrommysqldate($row["dob"]));
					$spreadsheet->setActiveSheetIndex(0);
					$hojaActiva = $spreadsheet->getActiveSheet();
					if ($resultado_k5->num_rows > 0) {
						while ($row_k5 = mysqli_fetch_assoc($resultado_k5)) {
							if ($row_k5["vak"] == 1) {
								$rek_pro += $row_k5["gemiddelde"];
								$rek_cont++;
							}
							if ($row_k5["vak"] == 6) {
								$ned_pro += $row_k5["gemiddelde"];
								$ned_cont++;
							}
							if ($row_k5["vak"] == 7) {
								$wer_pro += $row_k5["gemiddelde"];
								$wer_cont++;
							}
						}
						if ($ned_cont > 0)
							$hojaActiva->setCellValue("AU" . (string)$_current_student_start_row, round($ned_pro / $ned_cont, 1));
						if ($rek_cont > 0)
							$hojaActiva->setCellValue("AV" . (string)$_current_student_start_row, round($rek_pro / $rek_cont, 1));
						if ($wer_cont > 0)
							$hojaActiva->setCellValue("AW" . (string)$_current_student_start_row, round($wer_pro / $wer_cont, 1));
					}
				}
			} else {
				$hojaActiva->setCellValue("BD" . (string)$_current_student_start_row, $opmerking);
				if ($i == 1) {
					$spreadsheet->setActiveSheetIndex(3);
					$hojaActiva = $spreadsheet->getActiveSheet();
					switch ($level_klas) {
						case 2:
							$op1 = "AU";
							$op2 = "AR";
							$advies = "AS";
							$ciclo = "AT";
							break;
						case 3:
						case 4:
							$op1 = "AV";
							$op2 = "AS";
							$advies = "AT";
							$ciclo = "AU";
							break;
					}
					// $get_bespreking = "SELECT opmerking1,opmerking2,advies,ciclo FROM opmerking WHERE studentid = $id AND schooljaar = '$schooljaar' AND rapport = 4 LIMIT 1";
					// $result_bespreking = mysqli_query($mysqli, $get_bespreking);
					// while ($row_bespreking = mysqli_fetch_assoc($result_bespreking)) {
					// 	$hojaActiva->setCellValue($op1 . (string)$_current_student_start_row, $row_bespreking["opmerking1"]);
					// 	$hojaActiva->setCellValue($op2 . (string)$_current_student_start_row, $row_bespreking["opmerking2"] == "true" ? "X" : "");
					// 	$hojaActiva->setCellValue($advies . (string)$_current_student_start_row, $row_bespreking["advies"] == "true" ? "X" : "");
					// 	$hojaActiva->setCellValue($ciclo . (string)$_current_student_start_row, $row_bespreking["ciclo"] == "true" ? "X" : "");
					// }

					$spreadsheet->setActiveSheetIndex(0);
					$hojaActiva = $spreadsheet->getActiveSheet();
				}
			}
			$cont_laat = 0;
			$cont_verzuim = 0;
			$cont_huis = 0;
			$opmerking = "";
			$_laststudent = 0;



			$_current_student_start_row++;
		}
		$i++;
	}
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="verzamelstaten_' . $_GET["rapport_klassen_lijst"] . '.xlsx"');
	header('Cache-Control: max-age=0');

	$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
} else if ($_SESSION["SchoolID"] == 18) {
	require_once("../classes/spn_utils.php");
	$u = new spn_utils();
	$schooljaar = $_SESSION['SchoolJaar'];
	$schoolid = $_SESSION['SchoolID'];
	$klas_in = $_GET["rapport_klassen_lijst"];
	$rap_in = $_GET["rapport"];
	$user = $_SESSION["UserGUID"];
	$s = new spn_setting();
	$s->getsetting_info($schoolid, false);
	$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	switch ($level_klas) {
		case "1":
			$spreadsheet = $reader->load("../templates/verza_scol18_1.xlsx");
			$spreadsheet->setActiveSheetIndex(8);
			$hojaActiva = $spreadsheet->getActiveSheet();

			break;
		default:
			$spreadsheet = $reader->load("../templates/verza_scol18_2.xlsx");
			$spreadsheet->setActiveSheetIndex(3);
			$hojaActiva = $spreadsheet->getActiveSheet();
			$laat = "AI";
			$verzuim = "AJ";
			$p_opmerking = "AK";
			break;
	}
	$i = 1;

	switch ($_SESSION["SchoolID"]) {
		case 18:
			$hojaActiva->setCellValue('B4', 2);
			break;
		default:
			$hojaActiva->setCellValue('B4', 1);
			break;
	}

	while ($i <= $rap_in) {
		$_laststudent = 0;
		$_current_student_start_row = 6;
		$_while_counter = 0;
		$sql_query = "SELECT DISTINCT
		s.id as studentid,
		v.volledigenaamvak,
		v.volgorde,
		s.firstname,
		s.lastname,
		c.gemiddelde,
		CONCAT(app.firstname,' ',app.lastname) as docent_name
		FROM students s
		LEFT JOIN le_cijfers c ON s.id = c.studentid AND s.class = c.klas AND c.rapnummer = $i AND c.schooljaar = '$schooljaar'
		LEFT JOIN le_vakken v ON c.vak = v.id
		INNER JOIN setting st ON st.schoolid = s.schoolid
		INNER JOIN app_useraccounts app ON st.schoolid = app.SchoolID and app.UserGUID = '$user'
		WHERE
		s.schoolid = $schoolid
		AND st.year_period = '$schooljaar'
		AND s.class = '$klas_in'
		AND s.status = 1
		ORDER BY";
		$sql_order = " s.lastname , s.firstname";
		if ($s->_setting_mj) {
			$sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
		} else {
			$sql_query .=  $sql_order;
		}
		switch ($i) {
			case 1:
				$spreadsheet->setActiveSheetIndex(0);
				$fecha1 = $s->_setting_begin_rap_1;
				$fecha2 = $s->_setting_end_rap_1;
				break;

			case 2:
				if ($klas_in == 1) {
					$spreadsheet->setActiveSheetIndex(2);
				} else {
					$spreadsheet->setActiveSheetIndex(1);
				}
				$fecha1 = $s->_setting_begin_rap_2;
				$fecha2 = $s->_setting_end_rap_2;
				break;

			case 3:
				if ($klas_in == 1) {
					$spreadsheet->setActiveSheetIndex(4);
				} else {
					$spreadsheet->setActiveSheetIndex(2);
				}
				$fecha1 = $s->_setting_begin_rap_3;
				$fecha2 = $s->_setting_end_rap_3;
				break;
		}
		$hojaActiva = $spreadsheet->getActiveSheet();
		$DBCreds = new DBCreds();
		$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
		$mysqli->set_charset('utf8mb4');
		$resultado = mysqli_query($mysqli, $sql_query);
		while ($row = mysqli_fetch_assoc($resultado)) {
			if ($_while_counter == 0) {
				$_laststudent = $row["studentid"];
				$hojaActiva->setCellValue('B3', $klas_in);
				$hojaActiva->setCellValue('K2', $schooljaar);
				$hojaActiva->setCellValue('B5', $row["docent_name"]);
				$hojaActiva->setCellValue('B1', "Trimester " . $i);
			}
			$_currentstudent = $row["studentid"];
			if ($_currentstudent != $_laststudent) {
				$_current_student_start_row++;
				$hojaActiva->setCellValue('B' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
			}
			if ($_while_counter == 0) {
				$hojaActiva->setCellValue('B' . (string)$_current_student_start_row, $row["lastname"] . ", " . $row["firstname"]);
			}

			switch ($level_klas) {
				case "1":
					switch ($row["volgorde"]) {
						case 1:
							$returnvalue = "C";
							break;
						case 2:
							$returnvalue = "D";
							break;
						case 3:
							$returnvalue = "E";
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
							$returnvalue = "I";
							break;
						case 8:
							$returnvalue = "J";
							break;
						case 9:
							$returnvalue = "K";
							break;
						case 10:
							$returnvalue = "L";
							break;
						case 11:
							$returnvalue = "M";
							break;
						case 12:
							$returnvalue = "N";
							break;
						case 13:
							$returnvalue = "O";
							break;
						case 14:
							$returnvalue = "P";
							break;
						case 15:
							$returnvalue = "Q";
							break;
						case 16:
							$returnvalue = "R";
							break;
						case 17:
							$returnvalue = "S";
							break;
						case 18:
							$returnvalue = "T";
							break;
						case 19:
							$returnvalue = "U";
							break;
						case 20:
							$returnvalue = "V";
							break;
						case 21:
							$returnvalue = "W";
							break;
						case 22:
							$returnvalue = "X";
							break;
						case 23:
							$returnvalue = "Y";
							break;
						case 24:
							$returnvalue = "Z";
							break;
						case 25:
							$returnvalue = "AA";
							break;
						case 26:
							$returnvalue = "AB";
							break;
						case 27:
							$returnvalue = "AC";
							break;
						case 28:
							$returnvalue = "AD";
							break;
						case 29:
							$returnvalue = "AE";
							break;
						case 30:
							$returnvalue = "AF";
							break;
						case 31:
							$returnvalue = "AG";
							break;
						case 32:
							$returnvalue = "AH";
							break;
					}
					break;
				default:
					switch ($row["volgorde"]) {
						case 1:
							$returnvalue = "C";
							break;
						case 2:
							$returnvalue = $row['volledigenaamvak'] == "lesa comprension" ? "J" : "D";
							break;
						case 3:
							$returnvalue = "E";
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
							$returnvalue = "I";
							break;
						case 8:
							$returnvalue = "K";
							break;
						case 9:
							$returnvalue = "L";
							break;
						case 10:
							$returnvalue = "M";
							break;
						case 11:
							$returnvalue = "N";
							break;
						case 12:
							$returnvalue = "O";
							break;
						case 13:
							$returnvalue = "P";
							break;
						case 14:
							$returnvalue = "Q";
							break;
						case 15:
							$returnvalue = "R";
							break;
						case 16:
							$returnvalue = "V";
							break;
						case 18:
							$returnvalue = "W";
							break;
						case 20:
							$returnvalue = "X";
							break;
						case 21:
							$returnvalue = "Y";
							break;
						case 24:
							$returnvalue = "T";
							break;
						case 25:
							$returnvalue = "U";
							break;
						case 26:
							$returnvalue = "S";
							break;
						default:
							$returnvalue = "XX";
							break;
					}
					break;
			}

			$colgemiddelde = (string)$returnvalue . (string)$_current_student_start_row;
			if ($row["gemiddelde"] > 0.0) {
				$hojaActiva->setCellValue($colgemiddelde, $row["gemiddelde"]);
			}
			if ($_while_counter == 0 || $_currentstudent != $_laststudent) {
				//houding
				switch ($level_klas) {
					case "1":
						$p_h1 = "AI";
						$p_h2 = "AJ";
						$p_h3 = "AK";
						$p_h4 = "AL";
						$p_h5 = "AM";
						$p_h6 = "AN";
						$p_h7 = "AO";
						$p_h8 = "AP";
						$p_h10 = "AQ";
						break;
					default:
						$p_h1 = "Z";
						$p_h2 = "AA";
						$p_h3 = "AB";
						$p_h4 = "AC";
						$p_h5 = "AD";
						$p_h6 = "AE";
						$p_h7 = "AF";
						$p_h8 = "AG";
						$p_h10 = "AH";
						break;
				}
				$sql_query_houding = "SELECT h.h1,h.h2,h.h3,h.h4,h.h5,h.h6,h.h7,h.h8,h.h9,h.h10 FROM le_houding h WHERE h.studentid = $_currentstudent AND h.klas = '$klas_in' AND h.rapnummer = $i AND h.schooljaar = '$schooljaar' LIMIT 1";
				$resultado_houding = mysqli_query($mysqli, $sql_query_houding);
				$row_houding = mysqli_fetch_assoc($resultado_houding);
				$h1 = $row_houding["h1"];
				$h2 = $row_houding["h2"];
				$h3 = $row_houding["h3"];
				$h4 = $row_houding["h4"];
				$h5 = $row_houding["h5"];
				$h6 = $row_houding["h6"];
				$h7 = $row_houding["h7"];
				$h8 = $row_houding["h8"];
				$h9 = $row_houding["h9"];
				$h10 = $row_houding["h10"];

				for ($x = 1; $x <= 10; $x++) {
					$pos = "h" . $x;
					if ($$pos == 1 || !isset($$pos)) {
						$$pos = "A";
					}
					if ($$pos == 2) {
						$$pos = "B";
					}
					if ($$pos == 3) {
						$$pos = "C";
					}
					if ($$pos == 4) {
						$$pos = "D";
					}
					if ($$pos == 5) {
						$$pos = "E";
					}
					if ($$pos == 6) {
						$$pos = "F";
					}
					switch ($pos) {
						case "h1":
							$colhouding = $p_h1 . (string)$_current_student_start_row;
							break;
						case "h2":
							$colhouding = $p_h2 . (string)$_current_student_start_row;
							break;
						case "h3":
							$colhouding = $p_h3 . (string)$_current_student_start_row;
							break;
						case "h4":
							$colhouding = $p_h4 . (string)$_current_student_start_row;
							break;
						case "h5":
							$colhouding = $p_h5 . (string)$_current_student_start_row;
							break;
						case "h6":
							$colhouding = $p_h6 . (string)$_current_student_start_row;
							break;
						case "h7":
							$colhouding = $p_h7 . (string)$_current_student_start_row;
							break;
						case "h8":
							$colhouding = $p_h8 . (string)$_current_student_start_row;
							break;
						case "h10":
							$colhouding = $p_h10 . (string)$_current_student_start_row;
							break;
						default:
							$colhouding = "XX" . (string)$_current_student_start_row;
							break;
					}
					$hojaActiva->setCellValue($colhouding, $$pos);
				}

				//verzuim
				$cont_laat = 0;
				$cont_verzuim = 0;
				$sql_query_verzuim = "SELECT SUM(v.telaat) as telaat, SUM(v.absentie) as absentie
				FROM le_verzuim v WHERE v.studentid = $_currentstudent AND v.schooljaar = '$schooljaar' AND DATE(v.datum) BETWEEN '$fecha1' AND '$fecha2' AND (v.telaat != 0 OR v.absentie != 0) LIMIT 1";
				$resultado1 = mysqli_query($mysqli, $sql_query_verzuim);
				if ($row1 = mysqli_fetch_assoc($resultado1)) {
					$cont_laat = $row1["telaat"];
					$cont_verzuim = $row1["absentie"];
				}

				//opmerking
				$opmerking = "";
				$filter_opmerking = "op.opmerking" . $i;
				$sql_query_opmerking = "SELECT $filter_opmerking as opmerking FROM opmerking op WHERE op.studentid = $_currentstudent AND op.schooljaar = '$schooljaar' LIMIT 1";
				$resultado2 = mysqli_query($mysqli, $sql_query_opmerking);
				if ($row2 = mysqli_fetch_assoc($resultado2)) {
					$opmerking = $row2["opmerking"];
				}
				$hojaActiva->setCellValue($laat . (string)$_current_student_start_row, $cont_laat);
				$hojaActiva->setCellValue($verzuim . (string)$_current_student_start_row, $cont_verzuim);
				$hojaActiva->setCellValue($p_opmerking . (string)$_current_student_start_row, $opmerking);
			}

			$_laststudent = $_currentstudent;
			$_while_counter++;
		}
		$i++;
	}
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="verzamelstaten_' . $_GET["rapport_klassen_lijst"] . '.xlsx"');
	header('Cache-Control: max-age=0');

	$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
} else if ($_SESSION["SchoolID"] != 8) {
	$result = $ve->create_verzamelstaten($_SESSION["SchoolID"], $_SESSION['SchoolJaar'], $_GET["rapport_klassen_lijst"], $_GET["rapport"], $_SESSION["UserGUID"], false);
} else if ($_SESSION["SchoolID"] == 8) {
	$returnvalue = 0;
	$user_permission = "";
	$sql_query = "";
	$htmlcontrol = "";
	$schoolid = $_SESSION['SchoolID'];
	$s = new spn_setting();
	$s->getsetting_info($schoolid, false);

	$_while_counter = 0;

	/* cijferloop variables */
	$_currentstudent = null;
	$_laststudent = null;

	/* Excel current position variable */
	$_current_excel_studentpos = null;

	/* Excel start positions */
	$_current_student_start_row = 4;
	$_current_student_start_col = "B";

	$cont_houdin = 1;
	$cont_verzuim = 1;
	$cont_verzuim_laat = "AT";
	$cont_verzuim_verzm = "AU";
	$cont_verzuim_huiswerk = "AM";

	/* Default Excel positions */
	$_default_excel_leerkracht = "R1";
	$_default_excel_klas = "J1";
	$_default_excel_schooljaar = "AF1";
	$_default_excel_docent_name = "R1";


	$_debug_write_counter = 0;
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
		INNER JOIN app_useraccounts app ON st.schoolid = app.SchoolID and app.UserGUID = ?
		WHERE
		s.schoolid = ?
		AND v.SchoolID = ?
		AND st.year_period = ?
		AND c.schooljaar = ?
		AND s.class = ?
		AND c.klas = ?
		AND v.klas = ?
		AND c.rapnummer = ?
		AND x_index is not null
		AND c.gemiddelde > 0.0
		ORDER BY";
	$sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
	if ($s->_setting_mj) {
		$sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
	} else {
		$sql_query .=  $sql_order;
	}

	$schooljaar = $_SESSION['SchoolJaar'];
	$schoolid = $_SESSION['SchoolID'];
	$klas_in = $_GET["rapport_klassen_lijst"];
	$rap_in = $_GET["rapport"];
	$sql_query_houding = "SELECT DISTINCT
		s.id as studentid,
		h.h1,h.h2,h.h3,h.h4,h.h5,h.h6,h.h7,h.h8,h.h9,h.h10,
		h.h11,h.h12,h.h13,h.h14,h.h15, h.h16, h.h17, h.h18

		FROM students s
		LEFT JOIN le_houding h on h.studentid = s.id
		LEFT JOIN le_cijfers c on c.studentid = s.id
		INNER JOIN setting st ON st.schoolid = s.schoolid
		WHERE
		s.schoolid = ?
		AND st.year_period = ?
		AND h.schooljaar = ?
		AND s.class = ?
		AND h.klas = ?
		AND h.rapnummer = ?
		AND c.gemiddelde > 0
		ORDER BY";
	$sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
	if ($s->_setting_mj) {
		$sql_query_houding .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
	} else {
		$sql_query_houding .=  $sql_order;
	}

	$sql_query_verzuim = "SELECT
		s.id as studentid,
		Gettelaat('$schooljaar','$klas_in',s.id , $schoolid, $rap_in) as telaat,
		Getabsentie('$schooljaar','$klas_in',s.id , $schoolid, $rap_in) as absentie,
		Getuitsturen('$schooljaar','$klas_in',s.id , $schoolid, $rap_in) as uitsturen,
		0,
		0,
		0,
		''
		FROM students s
		WHERE
		s.schoolid = $schoolid
		AND s.class = '$klas_in'
		ORDER BY s.lastname, s.firstname";
	switch ($level_klas) {
		case 1:
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			if ($level_klas == 1) {
				$spreadsheet = $reader->load("../templates/8_conrado_klas_1.xlsx");
			} else {
				$spreadsheet = $reader->load("../templates/8_conrado_klas_1.xlsx");
			}
			$s = new spn_setting();
			$s->getsetting_info($schoolid, false);
			$i = 1;
			while ($i <= $_GET["rapport"]) {
				$_while_counter = 0;

				/* cijferloop variables */
				$_currentstudent = null;
				$_laststudent = null;

				/* Excel current position variable */
				$_current_excel_studentpos = null;

				/* Excel start positions */
				$_current_student_start_row = 4;
				$_current_student_start_col = "B";

				$cont_houdin = 1;
				$cont_verzuim = 1;
				$cont_verzuim_laat = "AT";
				$cont_verzuim_verzm = "AU";
				$cont_verzuim_huiswerk = "AM";

				/* Default Excel positions */
				$_default_excel_leerkracht = "R1";
				$_default_excel_klas = "J1";
				$_default_excel_schooljaar = "AF1";
				$_default_excel_docent_name = "R1";


				$_debug_write_counter = 0;
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
				$DBCreds = new DBCreds();
				$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
				$mysqli->set_charset('utf8');
				if ($select = $mysqli->prepare($sql_query)) {
					if ($select->bind_param("siisssssi", $_SESSION["UserGUID"], $_SESSION["SchoolID"], $_SESSION["SchoolID"], $_SESSION['SchoolJaar'], $_SESSION['SchoolJaar'], $_GET["rapport_klassen_lijst"], $_GET["rapport_klassen_lijst"], $_GET["rapport_klassen_lijst"], $i)) {

						if ($select->execute()) {
							$select->store_result();
							if ($select->num_rows > 0) {
								$result = 1;
								$select->bind_result(
									$studentid_out,
									$cijferid_out,
									$schooljaar_out,
									$class_out,
									$firstname_out,
									$lastname_out,
									$sex_out,
									$gemiddelde_out,
									$volgorde_out,
									$x_index_out,
									$volledigenaamvak_out,
									$docent_name
								);
								while ($select->fetch()) {									/* initialize last student id if whilecounter == 0 */
									if ($_while_counter == 0) {
										$_laststudent = $studentid_out;

										/* Write Docent, Klas, Schooljaar fields */
										$hojaActiva->setCellValue($_default_excel_leerkracht, "SPN Excel Export");
										$hojaActiva->setCellValue($_default_excel_klas, $_GET["rapport_klassen_lijst"]);
										$hojaActiva->setCellValue($_default_excel_schooljaar, $_SESSION['SchoolJaar']);
										$hojaActiva->setCellValue($_default_excel_docent_name, $docent_name);
									}

									/* set the current student variable */
									$_currentstudent = $studentid_out;

									if ($_currentstudent != $_laststudent) {
										$_current_student_start_row++;
										/* Write Student Id */
										#$hojaActiva->setCellValue("BU" . (string)$_current_student_start_row, $studentid_out);
										$hojaActiva->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $lastname_out . ", " . $firstname_out);

										// /* Write Rekenen */
										// $rekenen_result = $this->get_rekenen_by_student($klas_in, $studentid_out, $schoolid, $rap_in,false);
										// $hojaActiva->setCellValue("J" . (string)$_current_student_start_row, $rekenen_result);

										/* Write Opmerking */
										/* $opmerking_result = $this->read_opmerking_by_rapport($schooljaar, $studentid_out, $_SESSION['SchoolID'], $klas_in, $rap_in);
										if ($opmerking_result == "0") {
											$opmerking_result = "";
										}
										$hojaActiva->setCellValue("AV" . (string)$_current_student_start_row, $opmerking_result); */
									}

									if ($_while_counter == 0) {
										/* Write Student Id */
										#$hojaActiva->setCellValue("BU" . (string)$_current_student_start_row, $studentid_out);
										/* Write Student Name */
										$hojaActiva->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $lastname_out . ", " . $firstname_out);


										/* Write Opmerking */
										/* 									$opmerking_result = $this->read_opmerking_by_rapport($schooljaar, $studentid_out, $_SESSION['SchoolID'], $klas_in, $rap_in);
										if ($opmerking_result == "0") {
											$opmerking_result = "";
										}
										$hojaActiva->setCellValue("AV" . (string)$_current_student_start_row, $opmerking_result); */
									}

									switch ($x_index_out) {
										case 1:
											$returnvalue = "A";
											break;

										case 2:
											$returnvalue = "B";
											break;

										case 3:
											$returnvalue = "C";
											break;

										case 4:
											$returnvalue = "D";
											break;

										case 5:
											$returnvalue = "E";
											break;

										case 6:
											$returnvalue = "F";
											break;

										case 7:
											$returnvalue = "G";
											break;

										case 8:
											$returnvalue = "H";
											break;

										case 9:
											$returnvalue = "I";
											break;

										case 10:
											$returnvalue = "J";
											break;

										case 11:
											$returnvalue = "K";
											break;

										case 12:
											$returnvalue = "L";
											break;

										case 13:
											$returnvalue = "M";
											break;

										case 14:
											$returnvalue = "N";
											break;

										case 15:
											$returnvalue = "O";
											break;

										case 16:
											$returnvalue = "P";
											break;

										case 17:
											$returnvalue = "Q";
											break;

										case 18:
											$returnvalue = "R";
											break;

										case 19:
											$returnvalue = "S";
											break;

										case 20:
											$returnvalue = "T";
											break;

										case 21:
											$returnvalue = "U";
											break;

										case 22:
											$returnvalue = "V";
											break;

										case 23:
											$returnvalue = "W";
											break;

										case 24:
											$returnvalue = "X";
											break;

										case 25:
											$returnvalue = "Y";
											break;

										case 26:
											$returnvalue = "Z";
											break;

										case 27:
											$returnvalue = "AA";
											break;

										case 28:
											$returnvalue = "AB";
											break;

										case 29:
											$returnvalue = "AC";
											break;

										case 30:
											$returnvalue = "AD";
											break;

										case 31:
											$returnvalue = "AE";
											break;

										case 32:
											$returnvalue = "AF";
											break;

										case 33:
											$returnvalue = "AG";
											break;

										case 34:
											$returnvalue = "AH";
											break;

										case 35:
											$returnvalue = "AI";
											break;

										case 36:
											$returnvalue = "AJ";
											break;

										case 37:
											$returnvalue = "AK";
											break;

										case 38:
											$returnvalue = "AL";
											break;

										case 39:
											$returnvalue = "AM";
											break;

										case 40:
											$returnvalue = "AN";
											break;

										case 41:
											$returnvalue = "AO";
											break;

										case 42:
											$returnvalue = "AP";
											break;

										case 43:
											$returnvalue = "AQ";
											break;

										case 44:
											$returnvalue = "AR";
											break;

										case 45:
											$returnvalue = "AS";
											break;

										case 46:
											$returnvalue = "AT";
											break;

										case 47:
											$returnvalue = "AU";
											break;
										case 48:
											$returnvalue = "AV";
											break;
										case 49:
											$returnvalue = "AW";
											break;

										case 50:
											$returnvalue = "AX";
											break;

										case 51:
											$returnvalue = "AY";
											break;

										case 52:
											$returnvalue = "AZ";
											break;

										case 53:
											$returnvalue = "BA";
											break;

										case 54:
											$returnvalue = "BB";
											break;

										case 55:
											$returnvalue = "BC";
											break;

										case 56:
											$returnvalue = "BD";
											break;

										case 57:
											$returnvalue = "BE";
											break;

										case 58:
											$returnvalue = "BF";
											break;

										case 59:
											$returnvalue = "BG";
											break;

										case 60:
											$returnvalue = "BH";
											break;

										case 61:
											$returnvalue = "BI";
											break;

										case 62:
											$returnvalue = "BJ";
											break;

										case 63:
											$returnvalue = "BK";
											break;

										case 64:
											$returnvalue = "BL";
											break;
										case 65:
											$returnvalue = "BM";
											break;

										case 110:
											$returnvalue = "DF";
											break;

										case 111:
											$returnvalue = "CW";
											break;

										case 112:
											$returnvalue = "CX";
											break;
									}

									$colgemiddelde = (string)$returnvalue . (string)$_current_student_start_row;
									if ($gemiddelde_out > 0.1) {
										$hojaActiva->setCellValue($colgemiddelde, $gemiddelde_out);
									}
									$_laststudent = $_currentstudent;
									$_while_counter++;
								}
							}
						}
					}
				}

				if ($select = $mysqli->prepare($sql_query_houding)) {
					if ($select->bind_param("issssi", $_SESSION["SchoolID"], $_SESSION['SchoolJaar'], $_SESSION['SchoolJaar'], $_GET["rapport_klassen_lijst"], $_GET["rapport_klassen_lijst"], $i)) {
						if ($select->execute()) {
							$select->store_result();
							if ($select->num_rows > 0) {
								$result = 1;
								$select->bind_result(
									$studentid_out,
									$h1,
									$h2,
									$h3,
									$h4,
									$h5,
									$h6,
									$h7,
									$h8,
									$h9,
									$h10,
									$h11,
									$h12,
									$h13,
									$h14,
									$h15,
									$h16,
									$h17,
									$h18
								);
								$_current_student_start_row = 3;
								while ($select->fetch()) {
									/* initialize last student id if whilecounter == 0 */
									if ($_while_counter == 0) {
										$_laststudent = $studentid_out;
									}

									/* set the current student variable */
									$_currentstudent = $studentid_out;
									if ($_currentstudent != $_laststudent) {
										$_current_student_start_row++;
									}

									/*Insert Houding student*/
									/*	while(($studentid_out != ($hojaActiva->getCell("BU" . (string)$_current_student_start_row)->getValue())) && $_cijfers_count)
											{
												$_current_student_start_row++;
												if ($hojaActiva->getCell("BU" . (string)$_current_student_start_row)->getValue() == "")
												{
													$_cijfers_count = false;
												}
											}
											*/
									while ($cont_houdin < 19) {
										// print "auqi la el igual de as letras";
										// $colhouding = (string)$this->column_houdin($cont_houdin) . (string)$_current_student_start_row;
										switch ($cont_houdin) {
											case 1:
												$_h1 = "";
												$colhouding = "T" . (string)$_current_student_start_row;
												if ($h1 == 1 || !isset($h1)) {
													$_h1 = "B";
												}
												if ($h1 == 2) {
													$_h1 = "B";
												}
												if ($h1 == 3) {
													$_h1 = "C";
												}
												if ($h1 == 4) {
													$_h1 = "D";
												}
												if ($h1 == 5) {
													$_h1 = "E";
												}
												if ($h1 == 6) {
													$_h1 = "F";
												}
												if ($h1 == 10) {
													$_h1 = "B";
												}
												if ($h1 == 11) {
													$_h1 = "S";
												}
												if ($h1 == 12) {
													$_h1 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h1);
												break;

											case 2:
												$_h2 = "";
												$colhouding = "U" . (string)$_current_student_start_row;
												if ($h2 == 1 || !isset($h2)) {
													$_h2 = "B";
												}
												if ($h2 == 2) {
													$_h2 = "B";
												}
												if ($h2 == 3) {
													$_h2 = "C";
												}
												if ($h2 == 4) {
													$_h2 = "D";
												}
												if ($h2 == 5) {
													$_h2 = "E";
												}
												if ($h2 == 6) {
													$_h2 = "F";
												}
												if ($h2 == 10) {
													$_h2 = "B";
												}
												if ($h2 == 11) {
													$_h2 = "S";
												}
												if ($h2 == 12) {
													$_h2 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h2);
												break;

											case 3:
												$_h3 = "";
												$colhouding = "Y" . (string)$_current_student_start_row;
												if ($h3 == 1 || !isset($h3)) {
													$_h3 = "B";
												}
												if ($h3 == 2) {
													$_h3 = "B";
												}
												if ($h3 == 3) {
													$_h3 = "C";
												}
												if ($h3 == 4) {
													$_h3 = "D";
												}
												if ($h3 == 5) {
													$_h3 = "E";
												}
												if ($h3 == 6) {
													$_h3 = "F";
												}
												if ($h3 == 10) {
													$_h3 = "B";
												}
												if ($h3 == 11) {
													$_h3 = "S";
												}
												if ($h3 == 12) {
													$_h3 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h3);
												break;

											case 4:
												$_h4 = "";
												$colhouding = "AA" . (string)$_current_student_start_row;
												if ($h4 == 1 || !isset($h4)) {
													$_h4 = "B";
												}
												if ($h4 == 2) {
													$_h4 = "B";
												}
												if ($h4 == 3) {
													$_h4 = "C";
												}
												if ($h4 == 4) {
													$_h4 = "D";
												}
												if ($h4 == 5) {
													$_h4 = "E";
												}
												if ($h4 == 6) {
													$_h4 = "F";
												}
												if ($h4 == 10) {
													$_h4 = "B";
												}
												if ($h4 == 11) {
													$_h4 = "S";
												}
												if ($h4 == 12) {
													$_h4 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h4);
												break;

											case 5:
												$_h5 = "";
												$colhouding = "AB" . (string)$_current_student_start_row;
												if ($h5 == 1 || !isset($h5)) {
													$_h5 = "B";
												}
												if ($h5 == 2) {
													$_h5 = "B";
												}
												if ($h5 == 3) {
													$_h5 = "C";
												}
												if ($h5 == 4) {
													$_h5 = "D";
												}
												if ($h5 == 5) {
													$_h5 = "E";
												}
												if ($h5 == 6) {
													$_h5 = "F";
												}
												if ($h5 == 10) {
													$_h5 = "B";
												}
												if ($h5 == 11) {
													$_h5 = "S";
												}
												if ($h5 == 12) {
													$_h5 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h5);
												break;

											case 6:
												$_h6 = "";
												$colhouding = "AC" . (string)$_current_student_start_row;
												if ($h6 == 1 || !isset($h6)) {
													$_h6 = "B";
												}
												if ($h6 == 2) {
													$_h6 = "B";
												}
												if ($h6 == 3) {
													$_h6 = "C";
												}
												if ($h6 == 4) {
													$_h6 = "D";
												}
												if ($h6 == 5) {
													$_h6 = "E";
												}
												if ($h6 == 6) {
													$_h6 = "F";
												}
												if ($h6 == 10) {
													$_h6 = "B";
												}
												if ($h6 == 11) {
													$_h6 = "S";
												}
												if ($h6 == 12) {
													$_h6 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h6);
												break;

											case 7:
												$_h7 = "";
												$colhouding = "AD" . (string)$_current_student_start_row;
												if ($h7 == 1 || !isset($h7)) {
													$_h7 = "B";
												}
												if ($h7 == 2) {
													$_h7 = "B";
												}
												if ($h7 == 3) {
													$_h7 = "C";
												}
												if ($h7 == 4) {
													$_h7 = "D";
												}
												if ($h7 == 5) {
													$_h7 = "E";
												}
												if ($h7 == 6) {
													$_h7 = "F";
												}
												if ($h7 == 10) {
													$_h7 = "B";
												}
												if ($h7 == 11) {
													$_h7 = "S";
												}
												if ($h7 == 12) {
													$_h7 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h7);
												break;

											case 8:
												$_h8 = "";
												$colhouding = "AE" . (string)$_current_student_start_row;
												if ($h8 == 1 || !isset($h8)) {
													$_h8 = "B";
												}
												if ($h8 == 2) {
													$_h8 = "B";
												}
												if ($h8 == 3) {
													$_h8 = "C";
												}
												if ($h8 == 4) {
													$_h8 = "D";
												}
												if ($h8 == 5) {
													$_h8 = "E";
												}
												if ($h8 == 6) {
													$_h8 = "F";
												}
												if ($h8 == 10) {
													$_h8 = "B";
												}
												if ($h8 == 11) {
													$_h8 = "S";
												}
												if ($h8 == 12) {
													$_h8 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h8);
												break;

											case 9:
												$_h9 = "";
												$colhouding = "AF" . (string)$_current_student_start_row;
												if ($h9 == 1 || !isset($h9)) {
													$_h9 = "B";
												}
												if ($h9 == 2) {
													$_h9 = "B";
												}
												if ($h9 == 3) {
													$_h9 = "C";
												}
												if ($h9 == 4) {
													$_h9 = "D";
												}
												if ($h9 == 5) {
													$_h9 = "E";
												}
												if ($h9 == 6) {
													$_h9 = "F";
												}
												if ($h9 == 10) {
													$_h9 = "B";
												}
												if ($h9 == 11) {
													$_h9 = "S";
												}
												if ($h9 == 12) {
													$_h9 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h9);
												break;

											case 10:
												$_h10 = "";
												$colhouding = "AH" . (string)$_current_student_start_row;
												if ($h10 == 1 || !isset($h10)) {
													$_h10 = "B";
												}
												if ($h10 == 2) {
													$_h10 = "B";
												}
												if ($h10 == 3) {
													$_h10 = "C";
												}
												if ($h10 == 4) {
													$_h10 = "D";
												}
												if ($h10 == 5) {
													$_h10 = "E";
												}
												if ($h10 == 6) {
													$_h10 = "F";
												}
												if ($h10 == 10) {
													$_h10 = "B";
												}
												if ($h10 == 11) {
													$_h10 = "S";
												}
												if ($h10 == 12) {
													$_h10 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h10);
												break;

											case 11:
												$_h11 = "";
												$colhouding = "AI" . (string)$_current_student_start_row;
												if ($h11 == 1 || !isset($h11)) {
													$_h11 = "B";
												}
												if ($h11 == 2) {
													$_h11 = "B";
												}
												if ($h11 == 3) {
													$_h11 = "C";
												}
												if ($h11 == 4) {
													$_h11 = "D";
												}
												if ($h11 == 5) {
													$_h11 = "E";
												}
												if ($h11 == 6) {
													$_h11 = "F";
												}
												if ($h11 == 10) {
													$_h11 = "B";
												}
												if ($h11 == 11) {
													$_h11 = "S";
												}
												if ($h11 == 12) {
													$_h11 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h11);
												break;

											case 12:
												$_h12 = "";
												$colhouding = "AJ" . (string)$_current_student_start_row;
												if ($h12 == 1 || !isset($h12)) {
													$_h12 = "B";
												}
												if ($h12 == 2) {
													$_h12 = "B";
												}
												if ($h12 == 3) {
													$_h12 = "C";
												}
												if ($h12 == 4) {
													$_h12 = "D";
												}
												if ($h12 == 5) {
													$_h12 = "E";
												}
												if ($h12 == 6) {
													$_h12 = "F";
												}
												if ($h12 == 10) {
													$_h12 = "B";
												}
												if ($h12 == 11) {
													$_h12 = "S";
												}
												if ($h12 == 12) {
													$_h12 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h12);
												break;

											case 13:
												$_h13 = "";
												$colhouding = "AK" . (string)$_current_student_start_row;
												if ($h13 == 1 || !isset($h13)) {
													$_h13 = "B";
												}
												if ($h13 == 2) {
													$_h13 = "B";
												}
												if ($h13 == 3) {
													$_h13 = "C";
												}
												if ($h13 == 4) {
													$_h13 = "D";
												}
												if ($h13 == 5) {
													$_h13 = "E";
												}
												if ($h13 == 6) {
													$_h13 = "F";
												}
												if ($h13 == 10) {
													$_h13 = "B";
												}
												if ($h13 == 11) {
													$_h13 = "S";
												}
												if ($h13 == 12) {
													$_h13 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h13);
												break;

											case 14:
												$_h14 = "";
												$colhouding = "AL" . (string)$_current_student_start_row;
												if ($h14 == 1 || !isset($h14)) {
													$_h14 = "B";
												}
												if ($h14 == 2) {
													$_h14 = "B";
												}
												if ($h14 == 3) {
													$_h14 = "C";
												}
												if ($h14 == 4) {
													$_h14 = "D";
												}
												if ($h14 == 5) {
													$_h14 = "E";
												}
												if ($h14 == 6) {
													$_h14 = "F";
												}
												if ($h14 == 10) {
													$_h14 = "B";
												}
												if ($h14 == 11) {
													$_h14 = "S";
												}
												if ($h14 == 12) {
													$_h14 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h14);
												break;
											case 15:
												$_h15 = "";
												$colhouding = "M" . (string)$_current_student_start_row;
												if ($h15 == 1 || !isset($h15)) {
													$_h15 = "B";
												}
												if ($h15 == 2) {
													$_h15 = "B";
												}
												if ($h15 == 3) {
													$_h15 = "C";
												}
												if ($h15 == 4) {
													$_h15 = "D";
												}
												if ($h15 == 5) {
													$_h15 = "E";
												}
												if ($h15 == 6) {
													$_h15 = "F";
												}
												if ($h15 == 10) {
													$_h15 = "B";
												}
												if ($h15 == 11) {
													$_h15 = "S";
												}
												if ($h15 == 12) {
													$_h15 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h15);
												break;

											case 16:
												$_h16 = "";
												$colhouding = "AG" . (string)$_current_student_start_row;
												if ($h16 == 1 || !isset($h16)) {
													$_h16 = "B";
												}
												if ($h16 == 2) {
													$_h16 = "B";
												}
												if ($h16 == 3) {
													$_h16 = "C";
												}
												if ($h16 == 4) {
													$_h16 = "D";
												}
												if ($h16 == 5) {
													$_h16 = "E";
												}
												if ($h16 == 6) {
													$_h16 = "F";
												}
												if ($h16 == 10) {
													$_h16 = "B";
												}
												if ($h16 == 11) {
													$_h16 = "S";
												}
												if ($h16 == 12) {
													$_h16 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h16);
												break;

											case 17:
												$_h17 = "";
												$colhouding = "AW" . (string)$_current_student_start_row;
												if ($h17 == 1 || !isset($h17)) {
													$_h17 = "B";
												}
												if ($h17 == 2) {
													$_h17 = "B";
												}
												if ($h17 == 3) {
													$_h17 = "C";
												}
												if ($h17 == 4) {
													$_h17 = "D";
												}
												if ($h17 == 5) {
													$_h17 = "E";
												}
												if ($h17 == 6) {
													$_h17 = "F";
												}
												if ($h17 == 10) {
													$_h17 = "B";
												}
												if ($h17 == 11) {
													$_h17 = "S";
												}
												if ($h17 == 12) {
													$_h17 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h17);
												break;

											case 18:
												$_h18 = "";
												$colhouding = "AX" . (string)$_current_student_start_row;
												if ($h18 == 1 || !isset($h18)) {
													$_h18 = "B";
												}
												if ($h18 == 2) {
													$_h18 = "B";
												}
												if ($h18 == 3) {
													$_h18 = "C";
												}
												if ($h18 == 4) {
													$_h18 = "D";
												}
												if ($h18 == 5) {
													$_h18 = "E";
												}
												if ($h18 == 6) {
													$_h18 = "F";
												}
												if ($h18 == 10) {
													$_h18 = "B";
												}
												if ($h18 == 11) {
													$_h18 = "S";
												}
												if ($h18 == 12) {
													$_h18 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h18);
												break;


											default:
												echo "Error Houding";
												break;
										}

										$cont_houdin++;
									}

									$cont_houdin = 1;
									$_laststudent = $_currentstudent;

									$_while_counter++;
								}
							}
						}
					}
				}
				$_current_student_start_row = 4;
				$cont_laat = 0;
				$cont_verzuim = 0;
				$cont_huis = 0;
				$opmerking = "";
				$_laststudent = 0;
				$_while_counter = 0;
				$klas_in = $_GET["rapport_klassen_lijst"];
				$schooljaar = $_SESSION['SchoolJaar'];

				$sql_query_student = "SELECT s.id from students s INNER JOIN le_cijfers c ON s.id = c.studentid and s.class = c.klas where s.class = '$klas_in' and schoolid = $schoolid and c.gemiddelde > 0 GROUP BY s.id ORDER BY";

				$sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
				if ($s->_setting_mj) {
					$sql_query_student .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
				} else {
					$sql_query_student .=  $sql_order;
				}
				$resultado1 = mysqli_query($mysqli, $sql_query_student);
				require_once("../classes/spn_utils.php");
				$u = new spn_utils();
				while ($row = mysqli_fetch_assoc($resultado1)) {
					$id = $row['id'];
					$sql_query_verzuim = "SELECT 
					s.id AS studentid,
					v.telaat,
					v.absentie,
					v.huiswerk,
					s.lastname,
					s.firstname,
					o.opmerking1,
					o.opmerking2,
					o.opmerking3,
					v.created,
					v.datum
				FROM students s 
				LEFT JOIN le_verzuim v ON s.id = v.studentid AND v.schooljaar = '$schooljaar' AND v.studentid = s.id
				LEFT JOIN opmerking o ON o.studentid = s.id AND o.schooljaar = '$schooljaar'
				WHERE s.class = '$klas_in' 
					AND s.schoolid = $schoolid
					AND s.id = $id
				ORDER BY v.created";
					$resultado = mysqli_query($mysqli, $sql_query_verzuim);
					while ($row1 = mysqli_fetch_assoc($resultado)) {
						$datum = $u->convertfrommysqldate_new($row1["datum"]);
						if ($datum >= $fecha1 && $datum <= $fecha2) {
							if ($row1['telaat'] > 0) {
								$cont_laat++;
							}
							if ($row1['absentie'] > 0) {
								$cont_verzuim++;
							}
							if ($row1['huiswerk'] > 0) {
								$cont_huis++;
							}
						}
						if ($i == 1) {
							$opmerking = $row1["opmerking1"];
						} else if ($i == 2) {
							$opmerking = $row1["opmerking2"];
						} else if ($i == 3) {
							$opmerking = $row1["opmerking3"];
						}
					}
					$hojaActiva->setCellValue("AT" . (string)$_current_student_start_row, $cont_laat);
					$hojaActiva->setCellValue("AU" . (string)$_current_student_start_row, $cont_verzuim);
					$hojaActiva->setCellValue("AM" . (string)$_current_student_start_row, $cont_huis);
					$hojaActiva->setCellValue("AV" . (string)$_current_student_start_row, $opmerking);
					$cont_laat = 0;
					$cont_verzuim = 0;
					$cont_huis = 0;
					$opmerking = "";
					$_laststudent = 0;
					$_current_student_start_row++;
				}
				$i++;
			}
			break;
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
			$schoolid = $_SESSION['SchoolID'];
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			switch ($level_klas) {
				case 2:
					$spreadsheet = $reader->load("../templates/8_conrado_klas_2.xlsx");
					break;
				case 3:
					$spreadsheet = $reader->load("../templates/8_conrado_klas_3.xlsx");
					break;
				case 4:
				case 5:
					$spreadsheet = $reader->load("../templates/8_conrado_klas_4-5.xlsx");
					break;
				case 6:
					$spreadsheet = $reader->load("../templates/8_conrado_klas_6.xlsx");
					break;
			}
			$s = new spn_setting();
			$s->getsetting_info($schoolid, false);
			require_once("../classes/spn_utils.php");
			$u = new spn_utils();
			$i = 1;
			while ($i <= $_GET["rapport"]) {
				$_while_counter = 0;

				/* cijferloop variables */
				$_currentstudent = null;
				$_laststudent = null;

				/* Excel current position variable */
				$_current_excel_studentpos = null;

				/* Excel start positions */
				$_current_student_start_row = 4;
				$_current_student_start_col = "B";

				$cont_houdin = 1;
				$cont_verzuim = 1;
				$cont_verzuim_laat = "AY";
				$cont_verzuim_verzm = "AZ";
				$cont_verzuim_huiswerk = "AM";

				/* Default Excel positions */
				$_default_excel_klas = "I1";
				$_default_excel_schooljaar = "AC1";
				$_default_excel_docent_name = "O1";


				$_debug_write_counter = 0;

				switch ($i) {
					case 1:
						$spreadsheet->setActiveSheetIndex(0);
						$fecha1 = $s->_setting_begin_rap_1;
						$fecha1 = $u->convertfrommysqldate_new($fecha1);

						$fecha2 = $s->_setting_end_rap_1;
						$fecha2 = $u->convertfrommysqldate_new($fecha2);
						break;

					case 2:
						$spreadsheet->setActiveSheetIndex(1);
						$fecha1 = $s->_setting_begin_rap_2;
						$fecha1 = $u->convertfrommysqldate_new($fecha1);

						$fecha2 = $s->_setting_end_rap_2;
						$fecha2 = $u->convertfrommysqldate_new($fecha2);
						break;

					case 3:
						$spreadsheet->setActiveSheetIndex(2);
						$fecha1 = $s->_setting_begin_rap_3;
						$fecha1 = $u->convertfrommysqldate_new($fecha1);

						$fecha2 = $s->_setting_end_rap_3;
						$fecha2 = $u->convertfrommysqldate_new($fecha2);
						break;
				}
				$hojaActiva = $spreadsheet->getActiveSheet();
				$DBCreds = new DBCreds();
				$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
				$mysqli->set_charset('utf8');
				if ($select = $mysqli->prepare($sql_query)) {
					if ($select->bind_param("siisssssi", $_SESSION["UserGUID"], $_SESSION["SchoolID"], $_SESSION["SchoolID"], $_SESSION['SchoolJaar'], $_SESSION['SchoolJaar'], $_GET["rapport_klassen_lijst"], $_GET["rapport_klassen_lijst"], $_GET["rapport_klassen_lijst"], $i)) {

						if ($select->execute()) {
							$select->store_result();
							if ($select->num_rows > 0) {
								$result = 1;
								$select->bind_result(
									$studentid_out,
									$cijferid_out,
									$schooljaar_out,
									$class_out,
									$firstname_out,
									$lastname_out,
									$sex_out,
									$gemiddelde_out,
									$volgorde_out,
									$x_index_out,
									$volledigenaamvak_out,
									$docent_name
								);
								$_current_student_start_row = 4;
								$_current_student_start_col = "B";
								$hul_prom = 0;
								$hul_cont = 0;
								while ($select->fetch()) {									/* initialize last student id if whilecounter == 0 */
									if ($_while_counter == 0) {
										$_laststudent = $studentid_out;
										/* Write Docent, Klas, Schooljaar fields */
										$hojaActiva->setCellValue($_default_excel_klas, $_GET["rapport_klassen_lijst"]);
										$hojaActiva->setCellValue($_default_excel_schooljaar, $_SESSION['SchoolJaar']);
										$hojaActiva->setCellValue($_default_excel_docent_name, $docent_name);
									}

									/* set the current student variable */
									$_currentstudent = $studentid_out;

									if ($_currentstudent != $_laststudent) {
										$_current_student_start_row++;
										/* Write Student Id */
										#$hojaActiva->setCellValue("BU" . (string)$_current_student_start_row, $studentid_out);
										if ($hul_cont > 0) {
											$hojaActiva->setCellValue("I" . (string)($_current_student_start_row - 1), round($hul_prom / $hul_cont, 1));
											$hul_prom = 0;
											$hul_cont = 0;
										}
										$hojaActiva->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $lastname_out . ", " . $firstname_out);

										// /* Write Rekenen */
										// $rekenen_result = $this->get_rekenen_by_student($klas_in, $studentid_out, $schoolid, $rap_in,false);
										// $hojaActiva->setCellValue("J" . (string)$_current_student_start_row, $rekenen_result);

										/* Write Opmerking */
										/* $opmerking_result = $this->read_opmerking_by_rapport($schooljaar, $studentid_out, $_SESSION['SchoolID'], $klas_in, $rap_in);
									if ($opmerking_result == "0") {
										$opmerking_result = "";
									}
									$hojaActiva->setCellValue("AV" . (string)$_current_student_start_row, $opmerking_result); */
									}

									if ($_while_counter == 0) {
										/* Write Student Id */
										#$hojaActiva->setCellValue("BU" . (string)$_current_student_start_row, $studentid_out);
										/* Write Student Name */
										$hojaActiva->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $lastname_out . ", " . $firstname_out);


										/* Write Opmerking */
										/* 									$opmerking_result = $this->read_opmerking_by_rapport($schooljaar, $studentid_out, $_SESSION['SchoolID'], $klas_in, $rap_in);
									if ($opmerking_result == "0") {
										$opmerking_result = "";
									}
									$hojaActiva->setCellValue("AV" . (string)$_current_student_start_row, $opmerking_result); */
									}

									switch ($volledigenaamvak_out) {
										case "Reflexion":
											$returnvalue = "C";
											break;

										case "Vocabulario":
											$returnvalue = "D";
											break;

										case "Dictado":
											$returnvalue = "E";
											break;

										case "PAP Scucha y papia":
											$returnvalue = "F";
											break;

										case "Lesamento Tecnico":
											$returnvalue = "G";
											break;

										case "Lesamento Comprensivo":
											$returnvalue = "H";
											break;

										case "Stellen":
										case "Woordenschat":
										case "Luistervaardigheid":
										case "Leesbegrip":
										case "Dictee":
										case "Taalbeschouwing":
											$returnvalue = "I";
											$hul_prom += $gemiddelde_out;
											$hul_cont++;
											break;

										case "Nocion di number":
											$returnvalue = "K";
											break;

										case "Operacion basico y avansa":
											$returnvalue = "L";
											break;

										case "Midi y geometria":
											$returnvalue = "M";
											break;

										case "Tabel":
											$returnvalue = "N";
											break;

										case "Wereldorientatie":
											$returnvalue = "O";
											break;

										case "Skirbi":
											$returnvalue = "P";
											break;

										case "Engels":
											$returnvalue = "Q";
											break;

										case "Spaans":
											$returnvalue = "R";
											break;

										case "Trafico":
											$returnvalue = "S";
											break;

										case "Charla":
											$returnvalue = "T";
											break;

										case "Movecion":
											$returnvalue = "U";
											break;

										case "Arte":
											$returnvalue = "W";
											break;

										default:
											$returnvalue = "XX";
											break;
									}

									$colgemiddelde = (string)$returnvalue . (string)$_current_student_start_row;
									if ($gemiddelde_out > 0.1 && $returnvalue != "I" && $returnvalue != "XX") {
										$hojaActiva->setCellValue($colgemiddelde, $gemiddelde_out);
									}
									$_laststudent = $_currentstudent;
									$_while_counter++;
								}
							}
						}
					}
				}

				$sql_query_houding = "SELECT DISTINCT
			s.id as studentid,
			h.h1,h.h2,h.h3,h.h4,h.h5,h.h6,h.h7,h.h8,h.h9,h.h10,
			h.h11,h.h12,h.h13,h.h14, h.h15, h.h16, h.h17, h.h18

			FROM students s
			LEFT JOIN le_houding h on h.studentid = s.id
			INNER JOIN setting st ON st.schoolid = s.schoolid
			WHERE
			s.schoolid = ?
			AND h.schooljaar = ?
			AND s.class = ?
			AND h.klas = ?
			AND h.rapnummer = ?
			ORDER BY s.sex ASC,s.lastname ASC, s.firstname";

				if ($select = $mysqli->prepare($sql_query_houding)) {
					if ($select->bind_param("isssi", $_SESSION["SchoolID"], $_SESSION['SchoolJaar'], $_GET["rapport_klassen_lijst"], $_GET["rapport_klassen_lijst"], $i)) {
						if ($select->execute()) {
							$select->store_result();
							if ($select->num_rows > 0) {
								$result = 1;
								$select->bind_result(
									$studentid_out,
									$h1,
									$h2,
									$h3,
									$h4,
									$h5,
									$h6,
									$h7,
									$h8,
									$h9,
									$h10,
									$h11,
									$h12,
									$h13,
									$h14,
									$h15,
									$h16,
									$h17,
									$h18
								);
								$_current_student_start_row = 3;
								$cont_houdin = 1;
								while ($select->fetch()) {
									/* initialize last student id if whilecounter == 0 */
									if ($_while_counter == 0) {
										$_laststudent = $studentid_out;
									}

									/* set the current student variable */
									$_currentstudent = $studentid_out;
									if ($_currentstudent != $_laststudent) {
										$_current_student_start_row++;
									}

									/*Insert Houding student*/
									/* while (($studentid_out != ($hojaActiva->getCell("BU" . (string)$_current_student_start_row)->getValue())) && $_cijfers_count) {
									$_current_student_start_row++;
									if ($hojaActiva->getCell("BU" . (string)$_current_student_start_row)->getValue() == "") {
										$_cijfers_count = false;
									}
								} */

									while ($cont_houdin < 19) {
										// print "auqi la el igual de as letras";
										// $colhouding = (string)$this->column_houdin($cont_houdin) . (string)$_current_student_start_row;
										switch ($cont_houdin) {
											case 1:
												$_h1 = "";
												$colhouding = "Q" . (string)$_current_student_start_row;
												if ($h1 == 1 || !isset($h1)) {
													$_h1 = "B";
												}
												if ($h1 == 2) {
													$_h1 = "B";
												}
												if ($h1 == 3) {
													$_h1 = "C";
												}
												if ($h1 == 4) {
													$_h1 = "D";
												}
												if ($h1 == 5) {
													$_h1 = "E";
												}
												if ($h1 == 6) {
													$_h1 = "F";
												}
												if ($h1 == 10) {
													$_h1 = "B";
												}
												if ($h1 == 11) {
													$_h1 = "S";
												}
												if ($h1 == 12) {
													$_h1 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h1);
												break;

											case 2:
												$_h2 = "";
												$colhouding = "R" . (string)$_current_student_start_row;
												if ($h2 == 1 || !isset($h2)) {
													$_h2 = "B";
												}
												if ($h2 == 2) {
													$_h2 = "B";
												}
												if ($h2 == 3) {
													$_h2 = "C";
												}
												if ($h2 == 4) {
													$_h2 = "D";
												}
												if ($h2 == 5) {
													$_h2 = "E";
												}
												if ($h2 == 6) {
													$_h2 = "F";
												}
												if ($h2 == 10) {
													$_h2 = "B";
												}
												if ($h2 == 11) {
													$_h2 = "S";
												}
												if ($h2 == 12) {
													$_h2 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h2);
												break;

											case 3:
												$_h3 = "";
												$colhouding = "v" . (string)$_current_student_start_row;
												if ($h3 == 1 || !isset($h3)) {
													$_h3 = "B";
												}
												if ($h3 == 2) {
													$_h3 = "B";
												}
												if ($h3 == 3) {
													$_h3 = "C";
												}
												if ($h3 == 4) {
													$_h3 = "D";
												}
												if ($h3 == 5) {
													$_h3 = "E";
												}
												if ($h3 == 6) {
													$_h3 = "F";
												}
												if ($h3 == 10) {
													$_h3 = "B";
												}
												if ($h3 == 11) {
													$_h3 = "S";
												}
												if ($h3 == 12) {
													$_h3 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h3);
												break;

											case 4:
												$_h4 = "";
												$colhouding = "X" . (string)$_current_student_start_row;
												if ($h4 == 1 || !isset($h4)) {
													$_h4 = "B";
												}
												if ($h4 == 2) {
													$_h4 = "B";
												}
												if ($h4 == 3) {
													$_h4 = "C";
												}
												if ($h4 == 4) {
													$_h4 = "D";
												}
												if ($h4 == 5) {
													$_h4 = "E";
												}
												if ($h4 == 6) {
													$_h4 = "F";
												}
												if ($h4 == 10) {
													$_h4 = "B";
												}
												if ($h4 == 11) {
													$_h4 = "S";
												}
												if ($h4 == 12) {
													$_h4 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h4);
												break;

											case 5:
												$_h5 = "";
												$colhouding = "Y" . (string)$_current_student_start_row;
												if ($h5 == 1 || !isset($h5)) {
													$_h5 = "B";
												}
												if ($h5 == 2) {
													$_h5 = "B";
												}
												if ($h5 == 3) {
													$_h5 = "C";
												}
												if ($h5 == 4) {
													$_h5 = "D";
												}
												if ($h5 == 5) {
													$_h5 = "E";
												}
												if ($h5 == 6) {
													$_h5 = "F";
												}
												if ($h5 == 10) {
													$_h5 = "B";
												}
												if ($h5 == 11) {
													$_h5 = "S";
												}
												if ($h5 == 12) {
													$_h5 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h5);
												break;

											case 6:
												$_h6 = "";
												$colhouding = "Z" . (string)$_current_student_start_row;
												if ($h6 == 1 || !isset($h6)) {
													$_h6 = "B";
												}
												if ($h6 == 2) {
													$_h6 = "B";
												}
												if ($h6 == 3) {
													$_h6 = "C";
												}
												if ($h6 == 4) {
													$_h6 = "D";
												}
												if ($h6 == 5) {
													$_h6 = "E";
												}
												if ($h6 == 6) {
													$_h6 = "F";
												}
												if ($h6 == 10) {
													$_h6 = "B";
												}
												if ($h6 == 11) {
													$_h6 = "S";
												}
												if ($h6 == 12) {
													$_h6 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h6);
												break;

											case 7:
												$_h7 = "";
												$colhouding = "AA" . (string)$_current_student_start_row;
												if ($h7 == 1 || !isset($h7)) {
													$_h7 = "B";
												}
												if ($h7 == 2) {
													$_h7 = "B";
												}
												if ($h7 == 3) {
													$_h7 = "C";
												}
												if ($h7 == 4) {
													$_h7 = "D";
												}
												if ($h7 == 5) {
													$_h7 = "E";
												}
												if ($h7 == 6) {
													$_h7 = "F";
												}
												if ($h7 == 10) {
													$_h7 = "B";
												}
												if ($h7 == 11) {
													$_h7 = "S";
												}
												if ($h7 == 12) {
													$_h7 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h7);
												break;

											case 8:
												$_h8 = "";
												$colhouding = "AB" . (string)$_current_student_start_row;
												if ($h8 == 1 || !isset($h8)) {
													$_h8 = "B";
												}
												if ($h8 == 2) {
													$_h8 = "B";
												}
												if ($h8 == 3) {
													$_h8 = "C";
												}
												if ($h8 == 4) {
													$_h8 = "D";
												}
												if ($h8 == 5) {
													$_h8 = "E";
												}
												if ($h8 == 6) {
													$_h8 = "F";
												}
												if ($h8 == 10) {
													$_h8 = "B";
												}
												if ($h8 == 11) {
													$_h8 = "S";
												}
												if ($h8 == 12) {
													$_h8 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h8);
												break;

											case 9:
												$_h9 = "";
												$colhouding = "AC" . (string)$_current_student_start_row;
												if ($h9 == 1 || !isset($h9)) {
													$_h9 = "B";
												}
												if ($h9 == 2) {
													$_h9 = "B";
												}
												if ($h9 == 3) {
													$_h9 = "C";
												}
												if ($h9 == 4) {
													$_h9 = "D";
												}
												if ($h9 == 5) {
													$_h9 = "E";
												}
												if ($h9 == 6) {
													$_h9 = "F";
												}
												if ($h9 == 10) {
													$_h9 = "B";
												}
												if ($h9 == 11) {
													$_h9 = "S";
												}
												if ($h9 == 12) {
													$_h9 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h9);
												break;

											case 10:
												$_h10 = "";
												$colhouding = "AE" . (string)$_current_student_start_row;
												if ($h10 == 1 || !isset($h10)) {
													$_h10 = "B";
												}
												if ($h10 == 2) {
													$_h10 = "B";
												}
												if ($h10 == 3) {
													$_h10 = "C";
												}
												if ($h10 == 4) {
													$_h10 = "D";
												}
												if ($h10 == 5) {
													$_h10 = "E";
												}
												if ($h10 == 6) {
													$_h10 = "F";
												}
												if ($h10 == 10) {
													$_h10 = "B";
												}
												if ($h10 == 11) {
													$_h10 = "S";
												}
												if ($h10 == 12) {
													$_h10 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h10);
												break;

											case 11:
												$_h11 = "";
												$colhouding = "AF" . (string)$_current_student_start_row;
												if ($h11 == 1 || !isset($h11)) {
													$_h11 = "B";
												}
												if ($h11 == 2) {
													$_h11 = "B";
												}
												if ($h11 == 3) {
													$_h11 = "C";
												}
												if ($h11 == 4) {
													$_h11 = "D";
												}
												if ($h11 == 5) {
													$_h11 = "E";
												}
												if ($h11 == 6) {
													$_h11 = "F";
												}
												if ($h11 == 10) {
													$_h11 = "B";
												}
												if ($h11 == 11) {
													$_h11 = "S";
												}
												if ($h11 == 12) {
													$_h11 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h11);
												break;

											case 12:
												$_h12 = "";
												$colhouding = "AG" . (string)$_current_student_start_row;
												if ($h12 == 1 || !isset($h12)) {
													$_h12 = "B";
												}
												if ($h12 == 2) {
													$_h12 = "B";
												}
												if ($h12 == 3) {
													$_h12 = "C";
												}
												if ($h12 == 4) {
													$_h12 = "D";
												}
												if ($h12 == 5) {
													$_h12 = "E";
												}
												if ($h12 == 6) {
													$_h12 = "F";
												}
												if ($h12 == 10) {
													$_h12 = "B";
												}
												if ($h12 == 11) {
													$_h12 = "S";
												}
												if ($h12 == 12) {
													$_h12 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h12);
												break;

											case 13:
												$_h13 = "";
												$colhouding = "AH" . (string)$_current_student_start_row;
												if ($h13 == 1 || !isset($h13)) {
													$_h13 = "B";
												}
												if ($h13 == 2) {
													$_h13 = "B";
												}
												if ($h13 == 3) {
													$_h13 = "C";
												}
												if ($h13 == 4) {
													$_h13 = "D";
												}
												if ($h13 == 5) {
													$_h13 = "E";
												}
												if ($h13 == 6) {
													$_h13 = "F";
												}
												if ($h13 == 10) {
													$_h13 = "B";
												}
												if ($h13 == 11) {
													$_h13 = "S";
												}
												if ($h13 == 12) {
													$_h13 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h13);
												break;

											case 14:
												$_h14 = "";
												$colhouding = "AI" . (string)$_current_student_start_row;
												if ($h14 == 1 || !isset($h14)) {
													$_h14 = "B";
												}
												if ($h14 == 2) {
													$_h14 = "B";
												}
												if ($h14 == 3) {
													$_h14 = "C";
												}
												if ($h14 == 4) {
													$_h14 = "D";
												}
												if ($h14 == 5) {
													$_h14 = "E";
												}
												if ($h14 == 6) {
													$_h14 = "F";
												}
												if ($h14 == 10) {
													$_h14 = "B";
												}
												if ($h14 == 11) {
													$_h14 = "S";
												}
												if ($h14 == 12) {
													$_h14 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h14);
												break;

												// case 15:
												// 	$_h15 = "";
												// 	$colhouding = "M" . (string)$_current_student_start_row;
												// 	if ($h15 == 1 || !isset($h15)) {
												// 		$_h15 = "B";
												// 	}
												// 	if ($h15 == 2) {
												// 		$_h15 = "B";
												// 	}
												// 	if ($h15 == 3) {
												// 		$_h15 = "C";
												// 	}
												// 	if ($h15 == 4) {
												// 		$_h15 = "D";
												// 	}
												// 	if ($h15 == 5) {
												// 		$_h15 = "E";
												// 	}
												// 	if ($h15 == 6) {
												// 		$_h15 = "F";
												// 	}
												// 	if ($h15 == 10) {
												// 		$_h15 = "B";
												// 	}
												// 	if ($h15 == 11) {
												// 		$_h15 = "S";
												// 	}
												// 	if ($h15 == 12) {
												// 		$_h15 = "I";
												// 	};
												// 	$hojaActiva->setCellValue($colhouding, $_h15);
												// 	break;

											case 16:
												$_h16 = "";
												$colhouding = "AD" . (string)$_current_student_start_row;
												if ($h16 == 1 || !isset($h16)) {
													$_h16 = "B";
												}
												if ($h16 == 2) {
													$_h16 = "B";
												}
												if ($h16 == 3) {
													$_h16 = "C";
												}
												if ($h16 == 4) {
													$_h16 = "D";
												}
												if ($h16 == 5) {
													$_h16 = "E";
												}
												if ($h16 == 6) {
													$_h16 = "F";
												}
												if ($h16 == 10) {
													$_h16 = "B";
												}
												if ($h16 == 11) {
													$_h16 = "S";
												}
												if ($h16 == 12) {
													$_h16 = "I";
												};
												$hojaActiva->setCellValue($colhouding, $_h16);
												break;

												// case 17:
												// 	$_h17 = "";
												// 	$colhouding = "BE" . (string)$_current_student_start_row;
												// 	if ($h17 == 1 || !isset($h17)) {
												// 		$_h17 = "B";
												// 	}
												// 	if ($h17 == 2) {
												// 		$_h17 = "B";
												// 	}
												// 	if ($h17 == 3) {
												// 		$_h17 = "C";
												// 	}
												// 	if ($h17 == 4) {
												// 		$_h17 = "D";
												// 	}
												// 	if ($h17 == 5) {
												// 		$_h17 = "E";
												// 	}
												// 	if ($h17 == 6) {
												// 		$_h17 = "F";
												// 	}
												// 	if ($h17 == 10) {
												// 		$_h17 = "B";
												// 	}
												// 	if ($h17 == 11) {
												// 		$_h17 = "S";
												// 	}
												// 	if ($h17 == 12) {
												// 		$_h17 = "I";
												// 	};
												// 	$hojaActiva->setCellValue($colhouding, $_h17);
												// 	break;

												// case 18:
												// 	$_h18 = "";
												// 	$colhouding = "BF" . (string)$_current_student_start_row;
												// 	if ($h18 == 1 || !isset($h18)) {
												// 		$_h18 = "B";
												// 	}
												// 	if ($h18 == 2) {
												// 		$_h18 = "B";
												// 	}
												// 	if ($h18 == 3) {
												// 		$_h18 = "C";
												// 	}
												// 	if ($h18 == 4) {
												// 		$_h18 = "D";
												// 	}
												// 	if ($h18 == 5) {
												// 		$_h18 = "E";
												// 	}
												// 	if ($h18 == 6) {
												// 		$_h18 = "F";
												// 	}
												// 	if ($h18 == 10) {
												// 		$_h18 = "B";
												// 	}
												// 	if ($h18 == 11) {
												// 		$_h18 = "S";
												// 	}
												// 	if ($h18 == 12) {
												// 		$_h18 = "I";
												// 	};
												// 	$hojaActiva->setCellValue($colhouding, $_h18);
												// 	break;



											default:

												break;
										}

										$cont_houdin++;
									}

									$cont_houdin = 1;
									$_laststudent = $_currentstudent;

									$_while_counter++;
								}
							}
						}
					}
				}
				$_current_student_start_row = 4;
				$cont_laat = 0;
				$cont_verzuim = 0;
				$cont_huis = 0;
				$opmerking = null;
				$_laststudent = 0;
				$_while_counter = 0;
				$klas_in = $_GET["rapport_klassen_lijst"];
				$schooljaar = $_SESSION['SchoolJaar'];
				$rap_in = $_GET["rapport"];
				$schooljaar_array = explode("-", $schooljaar);
				$schooljaar_pasado = $schooljaar_array[0] - 1 . "-" . $schooljaar_array[0];
				require_once("../classes/spn_utils.php");
				$u = new spn_utils();

				$sql_query_student = "SELECT id,dob from students s where s.class = '$klas_in' and schoolid = $schoolid ORDER BY";

				$sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
				if ($s->_setting_mj) {
					$sql_query_student .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
				} else {
					$sql_query_student .=  $sql_order;
				}
				$resultado1 = mysqli_query($mysqli, $sql_query_student);
				while ($row = mysqli_fetch_assoc($resultado1)) {
					$id = $row['id'];
					$sql_query_verzuim = "SELECT 
					s.id AS studentid,
					v.telaat,
					v.absentie,
					v.huiswerk,
					s.lastname,
					s.firstname,
					o.opmerking1,
					o.opmerking2,
					o.opmerking3,
					v.created,
					v.datum
				FROM students s 
				LEFT JOIN le_verzuim v ON s.id = v.studentid AND v.schooljaar = '$schooljaar' AND v.studentid = s.id
				LEFT JOIN opmerking o ON o.studentid = s.id AND o.schooljaar = '$schooljaar' AND o.rapport = $i
				WHERE s.class = '$klas_in' 
					AND s.schoolid = $schoolid
					AND s.id = $id
				ORDER BY v.created";
					$resultado = mysqli_query($mysqli, $sql_query_verzuim);
					while ($row1 = mysqli_fetch_assoc($resultado)) {
						$id = $row1["studentid"];
						$datum = $u->convertfrommysqldate_new($row1["datum"]);
						if ($datum >= $fecha1 && $datum <= $fecha2) {
							if ($row1['telaat'] > 0) {
								$cont_laat++;
							}
							if ($row1['absentie'] > 0) {
								$cont_verzuim++;
							}
							if ($row1['huiswerk'] > 0) {
								$cont_huis++;
							}
						}
						$opmerking = $row1["opmerking1"];
					}
					if ($level_klas ==  6) {
						$hul_pro = 0;
						$mat_pro = 0;
						$pap_pro = 0;
						$hul_cont = 0;
						$mat_cont = 0;
						$pap_cont = 0;
						$wer_pro = 0;
						$wer_cont = 0;
						$hojaActiva->setCellValue("BC" . (string)$_current_student_start_row, $opmerking);
						$hojaActiva->setCellValue("BA" . (string)$_current_student_start_row, $cont_laat);
						$hojaActiva->setCellValue("BB" . (string)$_current_student_start_row, $cont_verzuim);
						$sql_query_k5 = "SELECT
						c.gemiddelde,
						v.volledigenaamvak
						FROM le_cijfers c
						INNER JOIN le_vakken v ON c.vak = v.id
						WHERE
						c.studentid = $id
						AND v.SchoolID = $schoolid
						AND c.schooljaar = '$schooljaar_pasado'
						AND c.gemiddelde IS NOT NULL  
						AND v.volledigenaamvak IN ('Stellen','Woordenschat','Luistervaardigheid','Leesbegrip','Dictee','Taalbeschouwing','Reflexion','Vocabulario','Dictado','PAP Scucha y papia','Tabel','Midi y geometria','Operacion basico y avansa','Nocion di number','Wereldorientatie')";
						$resultado_k5 = mysqli_query($mysqli, $sql_query_k5);
						if ($i == 1) {
							$spreadsheet->setActiveSheetIndex(3);
							$hojaActiva = $spreadsheet->getActiveSheet();
							$hojaActiva->setCellValue("AR" . (string)$_current_student_start_row, $u->convertfrommysqldate($row["dob"]));
							$spreadsheet->setActiveSheetIndex(0);
							$hojaActiva = $spreadsheet->getActiveSheet();
							while ($row_k5 = mysqli_fetch_assoc($resultado_k5)) {
								switch ($row_k5["volledigenaamvak"]) {
									case "Reflexion":
									case "Vocabulario":
									case "Dictado":
									case "PAP Scucha y papia":
										$pap_pro += $row_k5["gemiddelde"];
										$pap_cont++;
										break;
									case "Stellen":
									case "Woordenschat":
									case "Luistervaardigheid":
									case "Leesbegrip":
									case "Dictee":
									case "Taalbeschouwing":
										$hul_pro += $row_k5["gemiddelde"];
										$hul_cont++;
										break;
									case "Nocion di number":
									case "Operacion basico y avansa":
									case "Midi y geometria":
									case "Tabel":
										$mat_pro += $row_k5["gemiddelde"];
										$mat_cont++;
										break;
									case "Wereldorientatie":
										$wer_pro += $row_k5["gemiddelde"];
										$wer_cont++;
										break;
									default:
										$returnvalue = "XX";
										break;
								}
							}
							if ($hul_cont > 0)
								$hojaActiva->setCellValue("AQ" . (string)$_current_student_start_row, round($hul_pro / $hul_cont, 1));
							if ($mat_cont > 0)
								$hojaActiva->setCellValue("AR" . (string)$_current_student_start_row, round($mat_pro / $mat_cont, 1));
							if ($pap_cont > 0)
								$hojaActiva->setCellValue("AS" . (string)$_current_student_start_row, round($pap_pro / $pap_cont, 1));
							if ($wer_cont > 0)
								$hojaActiva->setCellValue("AT" . (string)$_current_student_start_row, round($wer_pro / $wer_cont, 1));
						}
					} else {
						$hojaActiva->setCellValue("AU" . (string)$_current_student_start_row, $opmerking);
						$hojaActiva->setCellValue("AS" . (string)$_current_student_start_row, $cont_laat);
						$hojaActiva->setCellValue("AT" . (string)$_current_student_start_row, $cont_verzuim);
					}
					$cont_laat = 0;
					$cont_verzuim = 0;
					$cont_huis = 0;
					$opmerking = null;
					$_laststudent = 0;
					$_current_student_start_row++;
				}

				$i++;
			}

			break;
	}



	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="verzamelstaten_' . $_GET["rapport_klassen_lijst"] . '.xlsx"');
	header('Cache-Control: max-age=0');

	$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

	/* if (isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["rapport_klassen_lijst"])) {
		if ($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT") {
			$result = $r->createrapport($_SESSION["SchoolID"], $_SESSION['SchoolJaar'], $_GET["rapport_klassen_lijst"], $_GET["rapport"], false);
		} else if ($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING") {
			$result = $r->createrapport($_SESSION["SchoolID"], $_SESSION['SchoolJaar'], $_GET["rapport_klassen_lijst"], $_GET["rapport"], false);
		}
	} */
}
ob_flush();
