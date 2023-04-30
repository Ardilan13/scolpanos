<?php
require_once ("spn_setting.php");

class spn_woord_rapport
{

	public $tablename_students = "students";
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

	public $activesheet_index = null;

	//rekenen variables
	public $sp_get_rekenen_by_student = 'sp_get_rekenen_by_student';
	public $count_cijfers = null;
	public $count_cijfers_i = null;
	public $sum_cijfers=null;


	function __construct()
	{



	}

	function _openexceltemplate()
	{

		require_once("../classes/3rdparty/PHPExcel/PHPExcel.php");
		require_once("../classes/3rdparty/PHPExcel/PHPExcel/IOFactory.php");
		require_once("../classes/3rdparty/PHPExcel/PHPExcel/Writer/Excel2007.php");

		$this->rapport_reader = PHPExcel_IOFactory::createReader('Excel2007');
		if ($_SESSION["SchoolID"]==8)
		{
			$this->rapport_modified = $this->rapport_reader->load("../templates/8_woord_rapport.xlsx");
		}
		else if ($_SESSION["SchoolID"]==6)
		{
			$this->rapport_modified = $this->rapport_reader->load("../templates/6_woord_rapport.xlsx");
		}
		else if ($_SESSION["SchoolID"]==10)
		{
			$this->rapport_modified = $this->rapport_reader->load("../templates/10_woord_rapport.xlsx");
		}
		else if ($_SESSION["SchoolID"]==9)
		{
			$this->rapport_modified = $this->rapport_reader->load("../templates/woordrapport.xlsx");
		}
		else
		{
			$this->rapport_modified = $this->rapport_reader->load("../templates/woord_rapport.xlsx");
		}

	}
	function _exportexcelfile($rap_in,$klas_in,$exporttobrowser = true)
	{

		if($exporttobrowser)
		{
			/* add headers for output to browser */
			header('Content-type: application/vnd.ms-excel.sheet.macroEnabled.12');
			if ($_SESSION["SchoolID"]==8)
			{
				header('Content-Disposition: attachment; filename="woord_rapport_ST_'.$klas_in.'_'.$rap_in.'.xlsx"');
			}
			else {
				header('Content-Disposition: attachment; filename="Woord_Rapport_'.$klas_in.'_'.$rap_in.'.xlsx"');# code...
			}
			/* add headers for output to browser */
		}

		$this->rapport_writer = PHPExcel_IOFactory::createWriter($this->rapport_modified,'Excel2007');
		$this->rapport_writer->setIncludeCharts(TRUE);
		$this->rapport_writer->save('php://output');

	}

	function createrapport($schoolid,$schooljaar,$klas_in,$rap_in,$generate_all_rapporten)
	{

		/* open the excel template */
		if($generate_all_rapporten == true)
		{
			$this->_openexceltemplate();
			$this->_writer_student_name($schoolid,$schooljaar,$klas_in,$rap_in);
			$this-> _writeeapportdata_verzuim($schoolid,$schooljaar,$klas_in,$rap_in);

			/* export when done */
			$this->_exportexcelfile($rap_in,$klas_in);
		}

	}

	function _writer_student_name($schoolid,$schooljaar,$klas_in,$rap_in)
	{
		$returnvalue = 0;
		$user_permission="";
		$sql_query = "";
		$htmlcontrol="";

		$_while_counter = 0;

		/* cijferloop variables */
		$_currentstudent = null;
		$_laststudent = null;

		/* Excel current position variable */
		$_current_excel_studentpos = null;

		/* Excel start positions */
		$_current_student_start_row = 4;
		$_current_student_start_col = "B";

		if ($_SESSION["SchoolID"]==8)
		{
			/* Default Excel positions */
			$_default_excel_leerkracht = "N1";
			$_default_excel_level_klas = "H1";
			$_default_excel_schooljaar = "V1";

			$_default_excel_schoolname = "B1";
		}
		else {
			/* Default Excel positions */
			$_default_excel_leerkracht = "R1";
			$_default_excel_level_klas = "K1";
			$_default_excel_schooljaar = "X1";

			$_default_excel_schoolname = "B1";
		}

		$_debug_write_counter = 0;
		$this->activesheet_index = 0;

		//$_default_activesheet_index = 0;

		mysqli_report(MYSQLI_REPORT_STRICT);
		$s = new spn_setting();
		$s->getsetting_info($schoolid, false);

		$sql_query = "SELECT
		s.id, s.id_family ,
		CONCAT(s.firstname,' ',s.lastname) as student_full_name,
		sc.schoolname,
		CONCAT(app.firstname,' ',app.lastname) as docent_full_name
		FROM students s
		INNER JOIN schools sc ON
		sc.id = s.SchoolID
		INNER JOIN app_useraccounts app on
		s.SchoolID = app.SchoolID and
		app.userguid = ?
		INNER JOIN setting stt ON
		s.SchoolID = stt.schoolid
		WHERE s.class = ? AND s.schoolid = ? AND stt.year_period = ? ORDER BY ";

		$sql_order = " s.lastname ". $s->_setting_sort . ", s.firstname";
		if ($s->_setting_mj)
		{
			$sql_query .= " s.sex ". $s->_setting_sort . ", " . $sql_order;
		}
		else
		{
			$sql_query .=  $sql_order;
		}
		//echo $sql_query;

		try
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			$mysqli->set_charset('utf8');
			if($select=$mysqli->prepare($sql_query))
			{
				if($select->bind_param("ssis",$_SESSION['UserGUID'],$klas_in,$schoolid,$schooljaar))
				{
					if($select->execute())
					{
						$select->store_result();
						$this->error = false;
						if($select->num_rows > 0)
						{
							$result=1;
							$select->bind_result($studentid_out, $id_family, $student_full_name,$schoolname,$docent_full_name);

							//$this->_openexceltemplate();

							while($select->fetch())
							{

								/* initialize last student id if whilecounter == 0 */
								if($_while_counter == 0)
								{
									$_laststudent = $studentid_out;

									/* Write Docent, Klas, Schooljaar fields */
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_schoolname,$schoolname);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_level_klas,$klas_in);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_leerkracht,$docent_full_name);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_schooljaar,$schooljaar);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $student_full_name);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("AL" . (string)$_current_student_start_row, $studentid_out);

								}

								/* set the current student variable */
								$_currentstudent = $studentid_out;


								if($_currentstudent != $_laststudent)
								{
									$_current_student_start_row++;
									/* Write Student Id */

									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("AL" . (string)$_current_student_start_row, $studentid_out);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $student_full_name);


								}
								$_laststudent = $_currentstudent;
								$_while_counter++;
							}
							//exit();
							if($this->debug)
							{
								/* Write the times written to the excel sheet */
								$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("B50",$_debug_write_counter);
							}
						}
						else
						{
							if($this->debug)
							{
								print "no rows returned";
							}
						}

					}
					else
					{
						/* error executing query */
						$this->error = true;
						$this->mysqlierror = $mysqli->error;
						$result=0;

						if($this->debug)
						{
							print "error executing query" . "<br />";
							print "error" . $mysqli->error;
						}
					}
				}
				else
				{
					/* error binding parameters */
					$this->error = true;
					$this->mysqlierror = $mysqli->error;
					$result=0;

					if($this->debug)
					{
						print "error binding parameters";
					}
				}


			}
			else
			{
				/* error preparing query */
				$this->error = true;
				$this->mysqlierror = $mysqli->error;
				$result=0;

				if($this->debug)
				{
					print "error preparing query";
				}
			}

		}
		catch(PHPExcel_Exception $excel)
		{

			if($this->debug)
			{
				error_log("excel exception: " . $excel->getMessage());
			}
		}
		catch(Exception $e)
		{
			$this->error = true;
			$this->mysqlierror = $e->getMessage();
			$result=0;

			if($this->debug)
			{
				error_log("exception: " . $e->getMessage());
			}
		}

		return $returnvalue;

	}

	function _writeeapportdata_verzuim($schoolid,$schooljaar,$klas_in,$rap_in)
	{
		$returnvalue = 0;
		$user_permission="";
		$sql_query = "";
		$htmlcontrol="";

		$_while_counter = 0;
		$_cijfers_count = true;

		/* cijferloop variables */
		$_currentstudent = null;
		$_laststudent = null;

		/* Excel current position variable */
		$_current_excel_studentpos = null;

		/* Excel start positions */
		$_current_student_start_row = 4;
		$_current_student_start_col = "B";

		$cont_verzuim = 1;
		if ($_SESSION["SchoolID"]==8)
		{
			$cont_verzuim_laat = "AD";
			$cont_verzuim_verzm = "AE";
		} else if($_SESSION["SchoolID"]==9){
			$cont_verzuim_laat = "AI";
			$cont_verzuim_verzm = "AJ";
			$cont_verzuim_huiswerk = "AK";
		}
		else {
			$cont_verzuim_laat = "AG";
			$cont_verzuim_verzm = "AH";
			$cont_verzuim_huiswerk = "AI";
		}


		$_debug_write_counter = 0;

		//$_default_activesheet_index = 0;


		/* check rapport number */
		if($rap_in)
		{
			switch($rap_in)
			{
				case 1:
				$this->activesheet_index = 0;
				break;

				case 2:
				$this->activesheet_index = 1;
				break;

				case 3:
				$this->activesheet_index = 2;
				break;
			}
		}
		else
		{
			echo 'Error';
		}
		mysqli_report(MYSQLI_REPORT_STRICT);
		$s = new spn_setting();
		$s->getsetting_info($schoolid, false);

		$sql_query = "SELECT DISTINCT
		s.id as studentid,
		SUM(lv.telaat),
		SUM(lv.absentie),
		lv.toetsinhalen,
		lv.uitsturen,
		lv.LP,
		SUM(lv.huiswerk),
		lv.opmerking
		FROM students s
		LEFT JOIN le_verzuim lv on lv.studentid = s.id
		INNER JOIN setting st ON st.schoolid = s.schoolid
		WHERE
		s.schoolid = ?
		AND st.year_period = ?
		AND s.class = ?
		AND lv.klas = ?
		AND lv.datum >=
		(CASE ?
			WHEN 1 THEN st.br1
			WHEN 2 THEN st.br2
			WHEN 3 THEN st.br3
			END)
			AND lv.datum <=
			(CASE ?
				WHEN 1 THEN st.er1
				WHEN 2 THEN st.er2
				WHEN 3 THEN st.er3
				END)
				GROUP BY studentid ORDER BY ";

				$sql_order = " s.lastname ". $s->_setting_sort . ", s.firstname";
				if ($s->_setting_mj)
				{
					$sql_query .= " s.sex ". $s->_setting_sort . ", " . $sql_order;
				}
				else
				{
					$sql_query .=  $sql_order;
				}
				try
				{
					require_once("DBCreds.php");
					$DBCreds = new DBCreds();
					$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

					if($select=$mysqli->prepare($sql_query))
					{
						if($select->bind_param("isssss",$schoolid, $schooljaar, $klas_in, $klas_in, $rap_in, $rap_in))
						{
							if($select->execute())
							{
								$select->store_result();
								$this->error = false;
								if($select->num_rows > 0)
								{
									$result=1;
									$select->bind_result($studentid_out,$telaat,$absentie,$toetsinhalen,$uitsturen,$LP,$huiswerk,$opmerking);

									if ($_SESSION["SchoolID"]==8)
									{

										while($select->fetch() && $_cijfers_count)
										{
											// print "$studentid_out,$telaat,$absentie,$toetsinhalen,$uitsturen,$LP,$huiswerk,$opmerking";

											/* initialize last student id if whilecounter == 0 */
											if($_while_counter == 0)
											{
												$_laststudent = $studentid_out;
											}

											/* set the current student variable */
											$_currentstudent = $studentid_out;

											if($_currentstudent != $_laststudent)
											{
												$_current_student_start_row++;
											}

											while ($cont_verzuim < 3)
											{
												$_colverzuim_laat ="";
												$_colverzuim_verzm = "";

												$_colverzuim_laat = (string)$cont_verzuim_laat . (string)$_current_student_start_row;
												$_colverzuim_verzm = (string)$cont_verzuim_verzm . (string)$_current_student_start_row;

												switch ($cont_verzuim){
													case 1:
													$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_colverzuim_laat, $telaat);
													// print "caso 1 ".$_colverzuim_laat." - ";
													break;
													case 2;
													$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_colverzuim_verzm, $absentie);
													// print "caso 2 ".$_colverzuim_verzm." - ";
													break;


													default:
													echo "Error";
													break;
												}

												$cont_verzuim++;
											}

											$cont_verzuim = 1;
											$cont_verzuim_laat = "AD";
											$cont_verzuim_verzm = "AE";
											$_laststudent = $_currentstudent;

											$_while_counter++;

										}
									} /*Close if schoolid*/
									else
									{
										while($select->fetch() && $_cijfers_count)
										{
											// print "$studentid_out,$telaat,$absentie,$toetsinhalen,$uitsturen,$LP,$huiswerk,$opmerking";

											/* initialize last student id if whilecounter == 0 */
											if($_while_counter == 0)
											{
												$_laststudent = $studentid_out;
											}

											/* set the current student variable */
											$_currentstudent = $studentid_out;

											if($_currentstudent != $_laststudent)
											{
												$_current_student_start_row++;
											}
											/*Insert Verzuim student*/
											while($studentid_out != ($this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->getCell("AL" . (string)$_current_student_start_row)->getValue()) && $_cijfers_count)
											{

												$_current_student_start_row++;
												if ($this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->getCell("AL" . (string)$_current_student_start_row)->getValue() == "")
												{
													$_cijfers_count = false;
												}
											}

											while ($cont_verzuim < 4)
											{
												$_colverzuim_laat ="";
												$_colverzuim_huiswerk="";
												$_colverzuim_verzm = "";

												$_colverzuim_laat = (string)$cont_verzuim_laat . (string)$_current_student_start_row;
												$_colverzuim_verzm = (string)$cont_verzuim_verzm . (string)$_current_student_start_row;
												$_colverzuim_huiswerk = (string)$cont_verzuim_huiswerk . (string)$_current_student_start_row;
												switch ($cont_verzuim){
													case 1:
													$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_colverzuim_laat, $telaat);
													// print "caso 1 ".$_colverzuim_laat." - ";
													break;
													case 2;
													$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_colverzuim_verzm, $absentie);
													// print "caso 2 ".$_colverzuim_verzm." - ";
													break;
													case 3;
													$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_colverzuim_huiswerk, $huiswerk);
													// print "caso 3 ".$_colverzuim_huiswerk." - ";
													break;

													default:
													echo "Error";
													break;
												}

												$cont_verzuim++;
											}

											$cont_verzuim = 1;

											if($_SESSION["SchoolID"]==9){
												$cont_verzuim_laat = "AI";
												$cont_verzuim_verzm = "AJ";
												$cont_verzuim_huiswerk = "AK";
											}
											else {
												$cont_verzuim_laat = "AG";
												$cont_verzuim_verzm = "AH";
												$cont_verzuim_huiswerk = "AI";
											}


											$_laststudent = $_currentstudent;

											$_while_counter++;

										}
									}
									//exit();
									if($this->debug)
									{
										/* Write the times written to the excel sheet */
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("B50",$_debug_write_counter);
									}

								}
								else
								{
									if($this->debug)
									{
										print "no rows returned";
									}
								}

							}
							else
							{
								/* error executing query */
								$this->error = true;
								$this->mysqlierror = $mysqli->error;
								$result=0;

								if($this->debug)
								{
									print "error executing query" . "<br />";
									print "error" . $mysqli->error;
								}
							}
						}
						else
						{
							/* error binding parameters */
							$this->error = true;
							$this->mysqlierror = $mysqli->error;
							$result=0;

							if($this->debug)
							{
								print "error binding parameters";
							}
						}


					}
					else
					{
						/* error preparing query */
						$this->error = true;
						$this->mysqlierror = $mysqli->error;
						$result=0;

						if($this->debug)
						{
							print "error preparing query";
						}
					}

				}
				catch(PHPExcel_Exception $excel)
				{
					if($this->debug)
					{
						error_log("excel exception: " . $excel->getMessage());
					}
				}
				catch(Exception $e)
				{
					$this->error = true;
					$this->mysqlierror = $e->getMessage();
					$result=0;

					if($this->debug)
					{
						error_log("exception: " . $e->getMessage());
					}
				}


				return $returnvalue;

			}





		}
		?>
