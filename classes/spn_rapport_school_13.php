<?php
require_once("spn_setting.php");


class spn_rapport_school_13
{

	public $tablename_cijfers = "le_cijfers";
	public $exceptionvalue = "";
	public $mysqlierror = "";
	public $mysqlierrornumber = "";
	public $debug = false;

	public $DBCreds = null;
	public $mysqli = null;


	/* Excel variable */
	public $rapport_reader = null;
	public $rapport_modified = null;
	public $rapport_writer = null;

	public $activesheet_index = 0;

	//rekenen variables
	public $sp_get_rekenen_by_student = 'sp_get_rekenen_by_student';
	public $count_cijfers = null;
	public $count_cijfers_i = null;
	public $sum_cijfers = null;
	public $sp_read_opmerking_by_rapport = "sp_read_opmerking_by_rapport";


	function __construct()
	{
	}

	function _openexceltemplate()
	{

		require_once("../classes/3rdparty/PHPExcel/PHPExcel.php");
		require_once("../classes/3rdparty/PHPExcel/PHPExcel/IOFactory.php");
		require_once("../classes/3rdparty/PHPExcel/PHPExcel/Writer/Excel2007.php");

		$this->rapport_reader = PHPExcel_IOFactory::createReader('Excel2007');
		$this->rapport_modified = $this->rapport_reader->load("../templates/13_abrahamdeveer_rapport_file.xlsm");
		//$this->rapport_modified = $this->rapport_reader->load("../templates/10_pieterboer.xlsm");

	}
	function _exportexcelfile($exporttobrowser = true)
	{

		if ($exporttobrowser) {
			/* add headers for output to browser */
			header('Content-type: application/vnd.ms-excel.sheet.macroEnabled.12');
			header('Content-Disposition: attachment; filename="abrahamdeveer_rapport_file.xlsm"');
			/* add headers for output to browser */
		}

		$this->rapport_writer = PHPExcel_IOFactory::createWriter($this->rapport_modified, 'Excel2007');
		$this->rapport_writer->save('php://output');
	}

	function createrapport($schoolid, $schooljaar, $klas_in, $rap_in, $generate_all_rapporten)
	{

		if (is_numeric($rap_in)) {
			/* open the excel template */
			if ($generate_all_rapporten == true) {
				$this->_openexceltemplate();
				for ($i = 1; $i < 4; $i++) {

					$this->_writerapportdata_cijfers($schoolid, $schooljaar, $klas_in, $i);
					// $this->_writerapportdata_houding($schoolid, $schooljaar, $klas_in, $i);
					// $this->_writeeapportdata_verzuim($schoolid, $schooljaar, $klas_in, $i);

				}

				/* export when done */
				$this->_exportexcelfile();
			} else {
				if ($rap_in == 1) {
					$this->_openexceltemplate();

					/* rap 1 */
					for ($i = 1; $i < 2; $i++) {

						$this->_writerapportdata_cijfers($schoolid, $schooljaar, $klas_in, $i);
						// $this->_writerapportdata_houding($schoolid, $schooljaar, $klas_in, $i);
						// $this->_writeeapportdata_verzuim($schoolid, $schooljaar, $klas_in, $i);


					}
					$this->_exportexcelfile(); //quitar export
				} else if ($rap_in == 2) {
					$this->_openexceltemplate();
					for ($i = 1; $i < 3; $i++) {
						/* rap 2 */
						$this->_writerapportdata_cijfers($schoolid, $schooljaar, $klas_in, $i);
						// $this->_writerapportdata_houding($schoolid, $schooljaar, $klas_in, $i);
						// $this->_writeeapportdata_verzuim($schoolid, $schooljaar, $klas_in, $i);


					}
					$this->_exportexcelfile();
				} else if ($rap_in == 3) {
					$this->_openexceltemplate();
					for ($i = 1; $i < 4; $i++) {
						/* rap 3 */
						$this->_writerapportdata_cijfers($schoolid, $schooljaar, $klas_in, $i);
						// $this->_writerapportdata_houding($schoolid, $schooljaar, $klas_in, $i);
						// $this->_writeeapportdata_verzuim($schoolid, $schooljaar, $klas_in, $i);

					}
					$this->_exportexcelfile();
				}
			}
		}
	}

	function _writerapportdata_cijfers($schoolid, $schooljaar, $klas_in, $rap_in)
	{
		$returnvalue = 0;
		$user_permission = "";
		$sql_query = "";
		$htmlcontrol = "";

		$_while_counter = 0;

		/* cijferloop variables */
		$_currentstudent = null;
		$_laststudent = null;

		/* Excel current position variable */
		$_current_excel_studentpos = null;

		/* Excel start positions */
		$_current_student_start_row = 5;
		$_current_student_start_col = "D";

		$cont_houdin = 50;
		$cont_verzuim = 80;

		/* Default Excel positions */
		$_default_excel_teacher = "D5";
		$_default_excel_klas = "D2";
		$_default_excel_schooljaar = "D3";
		$_default_excel_docent_name = "D4";


		$_debug_write_counter = 0;

		// $_default_activesheet_index = 0;

		$houding_result = 0;
		$column_houding = "";

		$verzuim_result = array();
		$tutor = "";



		mysqli_report(MYSQLI_REPORT_STRICT);
		$s = new spn_setting();
		$s->getsetting_info($schoolid, false);


		$sessionUserGUID = $_SESSION['UserGUID'];

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
		v.ID,
		CONCAT(app.firstname,' ',app.lastname) as docent_name
		FROM students s
		LEFT JOIN le_cijfers c ON s.id = c.studentid
		LEFT JOIN le_vakken v ON c.vak = v.id
		INNER JOIN setting st ON st.schoolid = s.schoolid
		INNER JOIN app_useraccounts app ON st.schoolid = app.SchoolID and app.UserGUID = '$sessionUserGUID'
		WHERE
		s.schoolid = $schoolid
		AND st.year_period = '$schooljaar'
		AND c.schooljaar = '$schooljaar'
		AND s.class = '$klas_in'
		AND c.klas = '$klas_in'
		AND v.klas = '$klas_in'
		AND c.rapnummer = $rap_in
		AND v.volgorde <> 99
		AND x_index is not null
		AND x_index <>99
		AND x_index != 1
		AND x_index != 0
		AND y_index <>99
		ORDER BY ";

		$sql_order = " s.lastname, s.firstname ";
		if ($s->_setting_mj) {
			$sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
		} else {
			$sql_query .=  $sql_order;
		}

		try {
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			$mysqli->set_charset('utf8');
			// print($sql_query);
			if ($select = $mysqli->prepare($sql_query)) {
				if ($select->execute()) {
					$select->store_result();
					$this->error = false;
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
							$vakid_out,
							$docent_name
						);
						/* Audit by Caribe Developers */
						require_once("spn_audit.php");
						$spn_audit = new spn_audit();
						$UserGUID = $_SESSION['UserGUID'];
						$spn_audit->create_audit($UserGUID, 'rapport', 'write rapport cijfer', true);

						while ($select->fetch()) {
							if ($_while_counter == 0) {
								$_laststudent = $studentid_out;

								/* Write Docent, Klas, Schooljaar fields */
								$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_teacher, "SPN Excel Export");
								$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_klas, strtoupper($klas_in));
								$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_schooljaar, $schooljaar);

								$tutor = $this->_writetutorName($klas_in);

								if ($tutor == '') {
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_docent_name, $docent_name);
								} else {
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_docent_name, $tutor);
								}
							}
							/* set the current student variable */


							$_currentstudent = $studentid_out;

							if ($_currentstudent != $_laststudent) {
								$_current_student_start_row++;
								/* Write Student Id */
								$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("DL" . (string)$_current_student_start_row, $studentid_out);
								$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("D" . (string)$_current_student_start_row, $lastname_out . ", " . $firstname_out);
							}

							if ($_while_counter == 0) {
								/* Write Student Id */
								$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("DL" . (string)$_current_student_start_row, $studentid_out);
								/* Write Student Name */
								$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("D" . (string)$_current_student_start_row, $lastname_out . ", " . $firstname_out);
							}

							// ****HOUDING***
							$houding_result = $this->_writerapportdata_houding($klas_in, $vakid_out, $studentid_out, $rap_in);
							if ($houding_result == 0) {
								$houding_result = "";
							}

							if ($rap_in == 1) {
								$column_houding = PHPExcel_Cell::stringFromColumnIndex(($x_index_out));
								$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($column_houding . (string)$_current_student_start_row, $houding_result);
								$colgemiddelde = PHPExcel_Cell::stringFromColumnIndex(($x_index_out - 1)) . (string)$_current_student_start_row;
							}
							if ($rap_in == 2) {
								$colgemiddelde = PHPExcel_Cell::stringFromColumnIndex(($x_index_out + 1)) . (string)$_current_student_start_row;
								$column_houding = PHPExcel_Cell::stringFromColumnIndex(($x_index_out + 2));
								$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($column_houding . (string)$_current_student_start_row, $houding_result);
								if ($vakid_out == 6329) {
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("BL" . (string)$_current_student_start_row, $gemiddelde_out);
								}
							}
							if ($rap_in == 3) {
								$colgemiddelde = PHPExcel_Cell::stringFromColumnIndex(($x_index_out + 3)) . (string)$_current_student_start_row;
								$column_houding = PHPExcel_Cell::stringFromColumnIndex(($x_index_out + 4));
								$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($column_houding . (string)$_current_student_start_row, $houding_result);
								if ($vakid_out == 6334) {
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("AR" . (string)$_current_student_start_row, $gemiddelde_out);
								}
							}


							// ******VERZUIM*****
							/* $verzuim_result = $this->_writeeapportdata_verzuim_absent($_SESSION['SchoolID'], $_SESSION['SchoolJaar'], $klas_in, $rap_in, $studentid_out);
							if ($rap_in == 1) {
								$col_verzuim_telaat = "CV";
							}
							if ($rap_in == 2) {
								$col_verzuim_telaat = "CW";
							}
							if ($rap_in == 3) {
								$col_verzuim_telaat = "CX";
							}
							$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($col_verzuim_telaat . (string)$_current_student_start_row, $verzuim_result[0]);

							if ($rap_in == 1) {
								$col_verzuim_absent = "CR";
							}
							if ($rap_in == 2) {
								$col_verzuim_absent = "CS";
							}
							if ($rap_in == 3) {
								$col_verzuim_absent = "CT";
							}
							$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($col_verzuim_absent . (string)$_current_student_start_row, $verzuim_result[1]);

							if ($rap_in == 1) {
								$col_verzuim_uitsturen = "FD";
							}
							if ($rap_in == 2) {
								$col_verzuim_uitsturen = "FE";
							}
							if ($rap_in == 3) {
								$col_verzuim_uitsturen = "FF";
							}
							$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($col_verzuim_uitsturen . (string)$_current_student_start_row, $verzuim_result[2]); */



							$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colgemiddelde, $gemiddelde_out);

							$_laststudent = $_currentstudent;

							$_while_counter++;
						}
						//exit();
						if ($this->debug) {
							/* Write the times written to the excel sheet */
							$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("B50", $_debug_write_counter);
						}
					} else {
						if ($this->debug) {
							print "no rows returned";
						}
					}
				} else {
					/* error executing query */
					$this->error = true;
					$this->mysqlierror = $mysqli->error;
					$result = 0;

					if ($this->debug) {
						print "error executing query" . "<br />";
						print "error" . $mysqli->error;
					}
				}
			} else {
				/* error preparing query */
				$this->error = true;
				$this->mysqlierror = $mysqli->error;
				$result = 0;

				if ($this->debug) {
					print "error preparing query";
				}
			}
		} catch (PHPExcel_Exception $excel) {

			if ($this->debug) {
				error_log("excel exception: " . $excel->getMessage());
			}
		} catch (Exception $e) {
			$this->error = true;
			$this->mysqlierror = $e->getMessage();
			$result = 0;

			if ($this->debug) {
				error_log("exception: " . $e->getMessage());
			}
		}


		return $returnvalue;
	}
	function _writerapportdata_houding($klas_in, $vakid_out, $studentid_out, $rap_in)
	{

		$returnvalue = 0;
		$user_permission = "";
		$sql_query = "";
		$htmlcontrol = "";
		$activesheet_index = 0;

		//$_default_activesheet_index = 0;
		mysqli_report(MYSQLI_REPORT_STRICT);

		$sql_query = "";
		$_h1 = 0;
		$_h2 = 0;
		$_h3 = 0;
		$_h4 = 0;
		$_h5 = 0;
		$_h6 = 0;
		$sum_average = 0;

		$schooljaar = $_SESSION['SchoolJaar'];

		try {
			// print('sql_query de Houding: '.$sql_query);
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			$sql_query = "SELECT coalesce(h1,4),coalesce(h2,4),coalesce(h3,4),coalesce(h4,4),coalesce(h5,4),coalesce(h6,4) FROM le_houding_hs where klas = '$klas_in' and vakid = $vakid_out and studentid = $studentid_out and schooljaar = '$schooljaar' and rapnummer = $rap_in";
			// print('Este es el query de houding: '.$sql_query);

			if ($select = $mysqli->prepare($sql_query)) {

				if ($select->execute()) {
					$select->store_result();
					if ($select->num_rows > 0) {
						$select->bind_result($h1, $h2, $h3, $h4, $h5, $h6);
						while ($select->fetch()) {
							if (!$h1 || $h1 != '' || $h1 != null) {
								$_h1 = (float)$h1;
							}

							if (!$h2 || $h2 != '' || $h1 != null) {
								$_h2 = (float)$h2;
							}

							if (!$h3 || $h3 != '' || $h1 != null) {
								$_h3 = (float)$h3;
							}

							if (!$h4 || $h4 != '' || $h1 != null) {
								$_h4 = (float)$h4;
							}

							if (!$h5 || $h5 != '' || $h1 != null) {
								$_h5 = (float)$h5;
							}

							if (!$h6 || $h6 != '' || $h1 != null) {
								$_h6 = (float)$h6;
							}

							$sum_average = ($_h1 + $_h2 + $_h3 + $_h4 + $_h5 + $_h6) / 6;
							// print('| este es el houding: '.$sum_average);
							$result = $sum_average;
						}
					} else {
						$result = 0;
					}
				} else {
					/* error executing query */
					$this->error = true;
					$this->mysqlierror = $mysqli->error;
					$result = 0;

					if ($this->debug) {
						print "error executing query" . "<br />";
						print "error" . $mysqli->error;
					}
				}
			} else {
				/* error preparing query */
				$this->error = true;
				$this->mysqlierror = $mysqli->error;
				$result = 0;

				if ($this->debug) {
					print "error preparing query";
				}
			}
			// print('| Este es Result: '.$result);
			return $result;
		} catch (PHPExcel_Exception $excel) {
			return -2;
			if ($this->debug) {
				error_log("excel exception: " . $excel->getMessage());
			}
		}
	}
	function _writetutorName($klas_in)
	{

		$returnvalue = 0;
		$sql_query = "";
		$htmlcontrol = "";

		//$_default_activesheet_index = 0;
		mysqli_report(MYSQLI_REPORT_STRICT);

		$sql_query = "";


		try {
			// print('sql_query de Houding: '.$sql_query);
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			$sql_query = "select CONCAT(A.firstname,' ',a.lastname) as tutorname from app_useraccounts a inner join user_hs u on UserGUID = user_GUID where u.klas = '$klas_in' and tutor = 'yes';";
			// print('Este es el query de houding: '.$sql_query);

			if ($select = $mysqli->prepare($sql_query)) {

				if ($select->execute()) {
					$select->store_result();
					if ($select->num_rows > 0) {
						$select->bind_result($tutorName);
						while ($select->fetch()) {
							$result = $tutorName;
						}
					} else {
						$result = '';
					}
				} else {
					/* error executing query */
					$this->error = true;
					$this->mysqlierror = $mysqli->error;
					$result = 0;

					if ($this->debug) {
						print "error executing query" . "<br />";
						print "error" . $mysqli->error;
					}
				}
			} else {
				/* error preparing query */
				$this->error = true;
				$this->mysqlierror = $mysqli->error;
				$result = 0;

				if ($this->debug) {
					print "error preparing query";
				}
			}
			// print('| Este es Result: '.$result);
			return $result;
		} catch (PHPExcel_Exception $excel) {
			return -2;
			if ($this->debug) {
				error_log("excel exception: " . $excel->getMessage());
			}
		}
	}
	function _writeeapportdata_verzuim_absent($schoolid, $schooljaar, $klas_in, $rap_in, $studentid_out)
	{
		$returnvalue = 0;
		$user_permission = "";
		$sql_query = "";
		$htmlcontrol = "";
		$verzuim_array = array();
		mysqli_report(MYSQLI_REPORT_STRICT);
		$s = new spn_setting();
		$s->getsetting_info($schoolid, false);

		$sql_query = "SELECT DISTINCT SUM(lv.telaat), SUM(lv.absentie), SUM(lv.uitsturen) FROM students s LEFT JOIN le_verzuim lv on lv.studentid = s.id 	INNER JOIN setting st ON st.schoolid = s.schoolid
				WHERE 	s.schoolid = $schoolid AND st.year_period = '$schooljaar' AND s.class = '$klas_in' AND lv.klas = '$klas_in' AND lv.studentid = $studentid_out AND lv.datum >= (CASE $rap_in	WHEN 1 THEN st.br1 WHEN 2 THEN st.br2	WHEN 3 THEN st.br3 END)
				AND lv.datum <= (CASE $rap_in WHEN 1 THEN st.er1 WHEN 2 THEN st.er2 WHEN 3 THEN st.er3 END);";
		// print($sql_query);

		try {
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if ($select = $mysqli->prepare($sql_query)) {
				if ($select->execute()) {
					$select->store_result();
					$this->error = false;
					if ($select->num_rows > 0) {
						$result = 1;
						$select->bind_result($telaat, $absentie, $uitsturen);
						while ($select->fetch()) {
							// print($telaat);
							// print($absentie);

							array_push($verzuim_array, $telaat, $absentie, $uitsturen);
						}
					} else {
						array_push($verzuim_array, '', '', '');
					}
				} else {
					/* error executing query */
					$this->error = true;
					$this->mysqlierror = $mysqli->error;
					$result = 0;

					if ($this->debug) {
						print "error executing query" . "<br />";
						print "error" . $mysqli->error;
					}
				}
			} else {
				/* error preparing query */
				$this->error = true;
				$this->mysqlierror = $mysqli->error;
				$result = 0;

				if ($this->debug) {
					print "error preparing query";
				}
			}

			return $verzuim_array;
		} catch (Exception $e) {
			$this->error = true;
			$this->mysqlierror = $e->getMessage();
			$result = 0;

			if ($this->debug) {
				error_log("exception: " . $e->getMessage());
			}
			return $result;
		}
	}
	function get_rekenen_by_student($klas, $studentid, $schoolID, $rapnumber, $dummy)
	{
		$returnvalue = "";
		$sql_query = "";
		$htmlcontrol = "";

		$result = 0;
		if ($dummy)
			$result = 1;
		else {
			mysqli_report(MYSQLI_REPORT_STRICT);
			require_once("spn_utils.php");
			$utils = new spn_utils();
			try {
				require_once("DBCreds.php");
				$DBCreds = new DBCreds();
				$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);
				$sp_get_rekenen_by_student = "sp_get_rekenen_by_student";
				$count_cijfers = null;
				$count_cijfers_i = null;
				$sum_cijfers = null;
				$sum_cijfers_i = null;

				if ($stmt = $mysqli->prepare("CALL " . $this->$sp_get_rekenen_by_student . "(?,?,?,?)")) {
					if ($stmt->bind_param("siii", $klas, $studentid, $schoolID, $rapnumber)) {
						if ($stmt->execute()) {

							$this->error = false;
							$result = 1;
							$stmt->bind_result($rapnummer, $vak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20);
							$stmt->store_result();

							if ($stmt->num_rows > 0) {
								while ($stmt->fetch()) {
									$sum_cijfers_i = $c1 + $c2 + $c3 + $c4 + $c5 + $c6 + $c7 + $c8 + $c9 + $c10 + $c11 + $c12 + $c13 + $c14 + $c15 + $c16 + $c17 + $c18 + $c19 + $c20;
									// calculo de rekenen
									for ($i = 1; $i < 21; $i++) {
										if (isset(${"c" . $i})) {
											//echo ${"c" . $i};
											$count_cijfers_i++;
										}
									}
									//$count_cijfers = $count_cijfers + 1;
									$sum_cijfers = $sum_cijfers + $sum_cijfers_i;
								}

								$rekenen = $sum_cijfers / $count_cijfers_i;
							} else {
								$rekenen = "";
							}
						} else {
							$result = 0;
							$this->mysqlierror = $mysqli->error;
							$this->mysqlierrornumber = $mysqli->errno;
						}
					} else {
						$result = 0;
						$this->mysqlierror = $mysqli->error;
						$this->mysqlierrornumber = $mysqli->errno;
					}
				} else {
					$result = 0;
					$this->mysqlierror = $mysqli->error;
					$this->mysqlierrornumber = $mysqli->errno;
				}
			} catch (Exception $e) {
				$result = -2;
				$this->exceptionvalue = $e->getMessage();
				$result = $e->getMessage();
			}
			return $rekenen;
		}
	}
	function get_avi_by_student($studentid_out, $schooljaar, $rapnumber, $dummy)
	{
		$returnvalue = "";
		$sql_query = "";
		$htmlcontrol = "";
		$avi_student = "";

		$result = 0;
		if ($dummy)
			$result = 1;
		else {
			mysqli_report(MYSQLI_REPORT_STRICT);
			require_once("spn_utils.php");
			$utils = new spn_utils();
			try {
				require_once("DBCreds.php");
				$DBCreds = new DBCreds();
				$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);
				$sp_get_avi_by_student = "sp_get_avi_by_student";

				if ($stmt = $mysqli->prepare("CALL " . $sp_get_avi_by_student . "(?,?,?)")) {
					if ($stmt->bind_param("isi", $studentid_out, $schooljaar, $rapnumber)) {
						if ($stmt->execute()) {

							$this->error = false;
							$result = 1;
							$stmt->bind_result($avi_level);
							$stmt->store_result();

							if ($stmt->num_rows > 0) {
								while ($stmt->fetch()) {
									$avi_student = $avi_level;
								}
							} else {
								$avi_student = "";
							}
						} else {
							$result = 0;
							$this->mysqlierror = $mysqli->error;
							$this->mysqlierrornumber = $mysqli->errno;
						}
					} else {
						$result = 0;
						$this->mysqlierror = $mysqli->error;
						$this->mysqlierrornumber = $mysqli->errno;
					}
				} else {
					$result = 0;
					$this->mysqlierror = $mysqli->error;
					$this->mysqlierrornumber = $mysqli->errno;
				}
			} catch (Exception $e) {
				$result = -2;
				$this->exceptionvalue = $e->getMessage();
				$result = $e->getMessage();
			}
			return $avi_student;
		}
	}

	function read_opmerking_by_rapport($schooljaar, $studentid_out, $schoolid, $klas_in, $rap_in)
	{
		require_once("spn_utils.php");
		$sql_query = "";
		$htmlcontrol = "";
		$result = 0;
		$dummy = 0;
		require_once("DBCreds.php");
		$DBCreds = new DBCreds();
		date_default_timezone_set("America/Aruba");
		$_DateTime = date("Y-m-d H:i:s");
		$status = 1;
		$json_result = "";

		mysqli_report(MYSQLI_REPORT_STRICT);
		try {
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();

			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if ($select = $mysqli->prepare("CALL " . $this->sp_read_opmerking_by_rapport . " (?,?,?,?,?)")) {
				if ($select->bind_param("siisi", $schooljaar, $studentid_out, $schoolid, $klas_in, $rap_in)) {
					if ($select->execute()) {
						$this->error = false;
						$result = 1;

						$select->bind_result($opmerking_comment);

						$select->store_result();
						if ($select->num_rows > 0) {
							$opmerking_result = "";
							while ($select->fetch()) {

								$_opmerking_result = $opmerking_comment;
							}
						} else {
							$result = 0;
							$this->mysqlierror = $mysqli->error;
							$this->mysqlierrornumber = $mysqli->errno;

							$_opmerking_result = $result;
						}
					} else {
						$result = 0;
						$this->mysqlierror = $mysqli->error;
						$this->mysqlierrornumber = $mysqli->errno;

						$_opmerking_result = $result;
					}
				} else {
					$result = 0;
					$this->mysqlierror = $mysqli->error;
					$this->mysqlierrornumber = $mysqli->errno;

					$_opmerking_result = $result;
				}
			} else {
				$result = 0;
				$this->mysqlierror = $mysqli->error;
				$this->mysqlierrornumber = $mysqli->errno;

				$_opmerking_result = $result;
			}
		} catch (Exception $e) {
			$result = -2;
			$this->exceptionvalue = $e->getMessage();
			$result = $e->getMessage();

			$_opmerking_result = $result;
		}

		return $_opmerking_result;
	}
}
