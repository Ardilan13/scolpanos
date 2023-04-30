<?php
require_once ("spn_setting.php");

class spn_rapport_school_8_k_5
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

	public $activesheet_index = null;

	//rekenen variables
	public $sp_get_rekenen_by_student = 'sp_get_rekenen_by_student';
	public $count_cijfers = null;
	public $count_cijfers_i = null;
	public $sum_cijfers=null;
	public $sp_read_opmerking_by_rapport = "sp_read_opmerking_by_rapport";

	function _openexceltemplate($klas_in)
	{

		require_once("../classes/3rdparty/PHPExcel/PHPExcel.php");
		require_once("../classes/3rdparty/PHPExcel/PHPExcel/IOFactory.php");
		require_once("../classes/3rdparty/PHPExcel/PHPExcel/Writer/Excel2007.php");

		$this->rapport_reader = PHPExcel_IOFactory::createReader('Excel2007');

		$level_klas= substr($klas_in,0,1);

		$this->rapport_modified = $this->rapport_reader->load("../templates/8_conrado_klas_5.xlsm");
	}
	function _exportexcelfile($exporttobrowser = true)
	{

		if($exporttobrowser)
		{
			/* add headers for output to browser */
			header('Content-type: application/vnd.ms-excel.sheet.macroEnabled.12');
			header('Content-Disposition: attachment; filename="conrado_coronel.xlsm"');
			/* add headers for output to browser */
		}

		$this->rapport_writer = PHPExcel_IOFactory::createWriter($this->rapport_modified,'Excel2007');
		$this->rapport_writer->save('php://output');

	}
	function createrapport($schoolid,$schooljaar,$klas_in,$rap_in,$generate_all_rapporten)
	{
		if(is_numeric($rap_in))
		{
			/* open the excel template */
			if($generate_all_rapporten == true)
			{
				$this->_openexceltemplate($klas_in);
				for($i = 1 ; $i <4; $i++)
				{

					$this->_writerapportdata_cijfers($schoolid,$schooljaar,$klas_in,$i);
					$this->_writerapportdata_houding($schoolid, $schooljaar, $klas_in, $i);
					$this->_writeeapportdata_verzuim($schoolid, $schooljaar, $klas_in, $i);

				}
				/* export when done */
				$this->_exportexcelfile();
			}
			else
			{
				if($rap_in == 1)
				{
					$this->_openexceltemplate($klas_in);

					/* rap 1 */
					for($i = 1 ; $i <2; $i++)
					{
						$this->_writerapportdata_cijfers($schoolid, $schooljaar, $klas_in, $i);
						$this->_writerapportdata_houding($schoolid, $schooljaar, $klas_in, $i);
						$this->_writeeapportdata_verzuim($schoolid, $schooljaar, $klas_in, $i);
					}
				$this->_exportexcelfile(); //quitar export
				}
				else if ($rap_in == 2)
				{
					$this->_openexceltemplate($klas_in);
					for($i = 1 ; $i <3; $i++) {
						/* rap 2 */
						$this->_writerapportdata_cijfers($schoolid, $schooljaar, $klas_in, $i);
						$this->_writerapportdata_houding($schoolid, $schooljaar, $klas_in, $i);
						$this->_writeeapportdata_verzuim($schoolid, $schooljaar, $klas_in, $i);
					}
					$this->_exportexcelfile();
				}
				else if ($rap_in == 3)
				{
					$this->_openexceltemplate($klas_in);
					for($i = 1 ; $i <4; $i++) {
						/* rap 3 */
						$this->_writerapportdata_cijfers($schoolid, $schooljaar, $klas_in, $i);
						$this->_writerapportdata_houding($schoolid, $schooljaar, $klas_in, $i);
						$this->_writeeapportdata_verzuim($schoolid, $schooljaar, $klas_in, $i);

					}
					$this->_exportexcelfile();
				}
			}
		}
	}
	function _writerapportdata_cijfers($schoolid,$schooljaar,$klas_in,$rap_in)
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

		$cont_houdin = 47;
		$cont_verzuim = 80;

		/* Default Excel positions */
		$_default_excel_leerkracht = "R1";
		$_default_excel_klas = "J1";
		$_default_excel_schooljaar = "AF1";
		$_default_excel_docent_name = "R1";


		$_debug_write_counter = 0;

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
		c.id as cijferid,
		st.year_period,
		s.class,
		s.firstname,
		s.lastname,
		s.sex,
		c.gemiddelde,
		v.volgorde,
		v.x_index,
		CONCAT(app.firstname,' ',app.lastname) as docent_name
		FROM students s
		LEFT JOIN le_cijfers c ON s.id = c.studentid
		LEFT JOIN le_vakken v ON c.vak = v.id
		INNER JOIN setting st ON st.schoolid = s.schoolid
		INNER JOIN app_useraccounts app ON st.schoolid = app.SchoolID and app.UserGUID = ?
		WHERE
		s.schoolid = ?
		AND st.year_period = ?
		AND c.schooljaar = ?
		AND s.class = ?
		AND c.klas = ?
		AND v.klas = ?
		AND c.rapnummer = ?
		AND x_index is not null
		ORDER BY ";

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
			$mysqli->set_charset('utf8');
			if($select=$mysqli->prepare($sql_query))
			{
				if($select->bind_param("sisssssi",$_SESSION["UserGUID"],$schoolid, $schooljaar,$schooljaar, $klas_in, $klas_in, $klas_in, $rap_in))
				{

					if($select->execute())
					{
						$select->store_result();
						$this->error = false;
						if($select->num_rows > 0)
						{
							$result=1;
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
								$x_index_out,$docent_name);
								/* Audit by Caribe Developers */
								require_once ("spn_audit.php");
								$spn_audit = new spn_audit();
								$UserGUID = $_SESSION['UserGUID'];
								$spn_audit->create_audit($UserGUID, 'rapport','write rapport cijfer',true);


								while($select->fetch())
								{									/* initialize last student id if whilecounter == 0 */
									if($_while_counter == 0)
									{
										$_laststudent = $studentid_out;

										/* Write Docent, Klas, Schooljaar fields */
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_leerkracht,"SPN Excel Export");
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_klas,$klas_in);
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_schooljaar,$schooljaar);
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_docent_name,$docent_name);

									}

									/* set the current student variable */
									$_currentstudent = $studentid_out;

									if($_currentstudent != $_laststudent)
									{
										$_current_student_start_row++;
										/* Write Student Id */
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("BU" . (string)$_current_student_start_row, $studentid_out);
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $lastname_out . ", " . $firstname_out);

										// /* Write Rekenen */
										$rekenen_result = $this->get_rekenen_by_student($klas_in, $studentid_out, $schoolid, $rap_in,false);
										// $this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("J" . (string)$_current_student_start_row, $rekenen_result);

										/* Write Opmerking */
										$opmerking_result = $this->read_opmerking_by_rapport($schooljaar,$studentid_out,$_SESSION['SchoolID'],$klas_in, $rap_in);
										if ($opmerking_result=="0"){$opmerking_result="";}
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("AV" . (string)$_current_student_start_row, $opmerking_result);

									}

									if($_while_counter == 0)
									{
										/* Write Student Id */
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("BU" . (string)$_current_student_start_row, $studentid_out);
										/* Write Student Name */
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $lastname_out . ", " . $firstname_out);


										/* Write Opmerking */
										$opmerking_result = $this->read_opmerking_by_rapport($schooljaar,$studentid_out,$_SESSION['SchoolID'],$klas_in, $rap_in);
										if ($opmerking_result=="0"){$opmerking_result="";}
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("AV" . (string)$_current_student_start_row, $opmerking_result);
									}

									$colgemiddelde = (string)$this->_returncolumnfromindex($x_index_out) . (string)$_current_student_start_row;
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colgemiddelde, $gemiddelde_out);
									$_laststudent = $_currentstudent;
									$_while_counter++;

								}

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
		function _writerapportdata_houding($schoolid,$schooljaar,$klas_in,$rap_in)
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


			$cont_houdin = 1;


			$_debug_write_counter = 0;

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
			h.h1,h.h2,h.h3,h.h4,h.h5,h.h6,h.h7,h.h8,h.h9,h.h10,
			h.h11,h.h12,h.h13,h.h14, h.h15, h.h16, h.h17, h.h18

			FROM students s
			LEFT JOIN le_houding h on h.studentid = s.id
			INNER JOIN setting st ON st.schoolid = s.schoolid
			WHERE
			s.schoolid = ?
			AND st.year_period = ?
			AND s.class = ?
			AND h.klas = ?
			AND h.rapnummer = ?
			ORDER BY ";
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
					if($select->bind_param("isssi",$schoolid, $schooljaar, $klas_in, $klas_in, $rap_in))
					{
						if($select->execute())
						{
							$select->store_result();
							$this->error = false;
							if($select->num_rows > 0)
							{
								$result=1;
								$select->bind_result(
									$studentid_out,
									$h1,$h2,$h3,$h4,$h5,$h6,$h7,$h8,$h9,$h10,$h11,$h12,$h13,$h14,$h15,$h16,$h17,$h18);
									/* Audit by Caribe Developers */
									require_once ("spn_audit.php");
									$spn_audit = new spn_audit();
									$UserGUID = $_SESSION['UserGUID'];
									$spn_audit->create_audit($UserGUID, 'rapport','write rapport cijfer',true);
									//$this->_openexceltemplate($klas_in);

									while($select->fetch() && $_cijfers_count)
									{
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

										/*Insert Houding student*/
										while(($studentid_out != ($this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->getCell("BU" . (string)$_current_student_start_row)->getValue())) && $_cijfers_count)
										{
											$_current_student_start_row++;
											if ($this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->getCell("BU" . (string)$_current_student_start_row)->getValue() == "")
											{
												$_cijfers_count = false;
											}
										}

										while ($cont_houdin < 19)
											{
											// print "auqi la el igual de as letras";
											// $colhouding = (string)$this->column_houdin($cont_houdin) . (string)$_current_student_start_row;
											switch ($cont_houdin){
												case 1:
												$_h1="";
												$colhouding = "BD". (string)$_current_student_start_row;
												if ($h1==1 || !isset($h1)){$_h1="B";}if ($h1==2){$_h1="B";}if ($h1==3){$_h1="C";}if ($h1==4){$_h1="D";}if ($h1==5){$_h1="E";}if ($h1==6){$_h1="F";}if ($h1==10){$_h1="B";}if ($h1==11){$_h1="S";}if ($h1==12){$_h1="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h1);
												break;

												case 2:
												$_h2="";
												$colhouding = "BD". (string)$_current_student_start_row;
												if ($h2==1 || !isset($h2)){$_h2="B";}if ($h2==2){$_h2="B";}if ($h2==3){$_h2="C";}if ($h2==4){$_h2="D";}if ($h2==5){$_h2="E";}if ($h2==6){$_h2="F";}if ($h2==10){$_h2="B";}if ($h2==11){$_h2="S";}if ($h2==12){$_h2="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h2);
												break;

												case 3:
												$_h3="";
												$colhouding = "Y". (string)$_current_student_start_row;
												if ($h3==1 || !isset($h3)){$_h3="B";}if ($h3==2){$_h3="B";}if ($h3==3){$_h3="C";}if ($h3==4){$_h3="D";}if ($h3==5){$_h3="E";}if ($h3==6){$_h3="F";}if ($h3==10){$_h3="B";}if ($h3==11){$_h3="S";}if ($h3==12){$_h3="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h3);
												break;

												case 4:
												$_h4="";
												$colhouding = "AA". (string)$_current_student_start_row;
												if ($h4==1 || !isset($h4)){$_h4="B";}if ($h4==2){$_h4="B";}if ($h4==3){$_h4="C";}if ($h4==4){$_h4="D";}if ($h4==5){$_h4="E";}if ($h4==6){$_h4="F";}if ($h4==10){$_h4="B";}if ($h4==11){$_h4="S";}if ($h4==12){$_h4="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h4);
												break;

												case 5:
												$_h5="";
												$colhouding = "AB". (string)$_current_student_start_row;
												if ($h5==1 || !isset($h5)){$_h5="B";}if ($h5==2){$_h5="B";}if ($h5==3){$_h5="C";}if ($h5==4){$_h5="D";}if ($h5==5){$_h5="E";}if ($h5==6){$_h5="F";}if ($h5==10){$_h5="B";}if ($h5==11){$_h5="S";}if ($h5==12){$_h5="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h5);
												break;

												case 6:
												$_h6="";
												$colhouding = "AC". (string)$_current_student_start_row;
												if ($h6==1 || !isset($h6)){$_h6="B";}if ($h6==2){$_h6="B";}if ($h6==3){$_h6="C";}if ($h6==4){$_h6="D";}if ($h6==5){$_h6="E";}if ($h6==6){$_h6="F";}if ($h6==10){$_h6="B";}if ($h6==11){$_h6="S";}if ($h6==12){$_h6="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h6);
												break;

												case 7:
												$_h7="";
												$colhouding = "AD". (string)$_current_student_start_row;
												if ($h7==1 || !isset($h7)){$_h7="B";}if ($h7==2){$_h7="B";}if ($h7==3){$_h7="C";}if ($h7==4){$_h7="D";}if ($h7==5){$_h7="E";}if ($h7==6){$_h7="F";}if ($h7==10){$_h7="B";}if ($h7==11){$_h7="S";}if ($h7==12){$_h7="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h7);
												break;

												case 8:
												$_h8="";
												$colhouding = "AE". (string)$_current_student_start_row;
												if ($h8==1 || !isset($h8)){$_h8="B";}if ($h8==2){$_h8="B";}if ($h8==3){$_h8="C";}if ($h8==4){$_h8="D";}if ($h8==5){$_h8="E";}if ($h8==6){$_h8="F";}if ($h8==10){$_h8="B";}if ($h8==11){$_h8="S";}if ($h8==12){$_h8="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h8);
												break;

												case 9:
												$_h9="";
												$colhouding = "AF". (string)$_current_student_start_row;
												if ($h9==1 || !isset($h9)){$_h9="B";}if ($h9==2){$_h9="B";}if ($h9==3){$_h9="C";}if ($h9==4){$_h9="D";}if ($h9==5){$_h9="E";}if ($h9==6){$_h9="F";}if ($h9==10){$_h9="B";}if ($h9==11){$_h9="S";}if ($h9==12){$_h9="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h9);
												break;

												case 10:
												$_h10="";
												$colhouding = "AH". (string)$_current_student_start_row;
												if ($h10==1 || !isset($h10)){$_h10="B";}if ($h10==2){$_h10="B";}if ($h10==3){$_h10="C";}if ($h10==4){$_h10="D";}if ($h10==5){$_h10="E";}if ($h10==6){$_h10="F";}if ($h10==10){$_h10="B";}if ($h10==11){$_h10="S";}if ($h10==12){$_h10="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h10);
												break;

												case 11:
												$_h11="";
												$colhouding = "AI". (string)$_current_student_start_row;
												if ($h11==1 || !isset($h11)){$_h11="B";}if ($h11==2){$_h11="B";}if ($h11==3){$_h11="C";}if ($h11==4){$_h11="D";}if ($h11==5){$_h11="E";}if ($h11==6){$_h11="F";}if ($h11==10){$_h11="B";}if ($h11==11){$_h11="S";}if ($h11==12){$_h11="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h11);
												break;

												case 12:
												$_h12="";
												$colhouding = "AJ". (string)$_current_student_start_row;
												if ($h12==1 || !isset($h12)){$_h12="B";}if ($h12==2){$_h12="B";}if ($h12==3){$_h12="C";}if ($h12==4){$_h12="D";}if ($h12==5){$_h12="E";}if ($h12==6){$_h12="F";}if ($h12==10){$_h12="B";}if ($h12==11){$_h12="S";}if ($h12==12){$_h12="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h12);
												break;

												case 13:
												$_h13="";
												$colhouding = "AK". (string)$_current_student_start_row;
												if ($h13==1 || !isset($h13)){$_h13="B";}if ($h13==2){$_h13="B";}if ($h13==3){$_h13="C";}if ($h13==4){$_h13="D";}if ($h13==5){$_h13="E";}if ($h13==6){$_h13="F";}if ($h13==10){$_h13="B";}if ($h13==11){$_h13="S";}if ($h13==12){$_h13="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h13);
												break;

												case 14:
												$_h14="";
												$colhouding = "AL". (string)$_current_student_start_row;
												if ($h14==1 || !isset($h14)){$_h14="B";}if ($h14==2){$_h14="B";}if ($h14==3){$_h14="C";}if ($h14==4){$_h14="D";}if ($h14==5){$_h14="E";}if ($h14==6){$_h14="F";}if ($h14==10){$_h14="B";}if ($h14==11){$_h14="S";}if ($h14==12){$_h14="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h14);
												break;

												case 15:
												$_h15="";
												$colhouding = "M". (string)$_current_student_start_row;
												if ($h15==1 || !isset($h15)){$_h15="B";}if ($h15==2){$_h15="B";}if ($h15==3){$_h15="C";}if ($h15==4){$_h15="D";}if ($h15==5){$_h15="E";}if ($h15==6){$_h15="F";}if ($h15==10){$_h15="B";}if ($h15==11){$_h15="S";}if ($h15==12){$_h15="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h15);
												break;

												case 16:
												$_h16="";
												$colhouding = "AG". (string)$_current_student_start_row;
												if ($h16==1 || !isset($h16)){$_h16="B";}if ($h16==2){$_h16="B";}if ($h16==3){$_h16="C";}if ($h16==4){$_h16="D";}if ($h16==5){$_h16="E";}if ($h16==6){$_h16="F";}if ($h16==10){$_h16="B";}if ($h16==11){$_h16="S";}if ($h16==12){$_h16="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h16);
												break;

												case 17:
												$_h17="";
												$colhouding = "BE". (string)$_current_student_start_row;
												if ($h17==1 || !isset($h17)){$_h17="B";}if ($h17==2){$_h17="B";}if ($h17==3){$_h17="C";}if ($h17==4){$_h17="D";}if ($h17==5){$_h17="E";}if ($h17==6){$_h17="F";}if ($h17==10){$_h17="B";}if ($h17==11){$_h17="S";}if ($h17==12){$_h17="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h17);
												break;

												case 18:
												$_h18="";
												$colhouding = "BF". (string)$_current_student_start_row;
												if ($h18==1 || !isset($h18)){$_h18="B";}if ($h18==2){$_h18="B";}if ($h18==3){$_h18="C";}if ($h18==4){$_h18="D";}if ($h18==5){$_h18="E";}if ($h18==6){$_h18="F";}if ($h18==10){$_h18="B";}if ($h18==11){$_h18="S";}if ($h18==12){$_h18="I";};
												$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colhouding, $_h18);
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
									//exit();
									if($this->debug)
									{
										/* Write the times written to the excel sheet */
										// $this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("B50",$_debug_write_counter);
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
				$cont_verzuim_laat = "AT";
				$cont_verzuim_verzm = "AU";
				$cont_verzuim_huiswerk = "AM";


				/* Default Excel positions */
				$_default_excel_leerkracht = "D1";
				$_default_excel_klas = "D2";
				$_default_excel_schooljaar = "D3";


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
											$select->bind_result(
												$studentid_out,
												$telaat,
												$absentie,
												$toetsinhalen,
												$uitsturen,
												$LP,
												$huiswerk,
												$opmerking);
												while($select->fetch() && $_cijfers_count)
												{
													//print "$studentid_out,$cijferid_out,$schooljaar_out,$class_out,$firstname_out,$lastname_out,$sex_out,$gemiddelde_out,$volgorde_out,$x_index_out";

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
													while($studentid_out != ($this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->getCell("BU" . (string)$_current_student_start_row)->getValue()) && $_cijfers_count)
													{

														$_current_student_start_row++;
														if ($this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->getCell("BU" . (string)$_current_student_start_row)->getValue() == "")
														{
															$_cijfers_count = false;
														}
													}
													while ($cont_verzuim < 4)
													{
														$_colverzuim_laat ="";
														$_colverzuim_verzm="";
														$_colverzuim_huiswerk ="";

														$_colverzuim_laat = (string)$cont_verzuim_laat . (string)$_current_student_start_row;
														$_colverzuim_verzm = (string)$cont_verzuim_verzm . (string)$_current_student_start_row;
														$_colverzuim_huiswerk = (string)$cont_verzuim_huiswerk . (string)$_current_student_start_row;

														switch ($cont_verzuim)
														{
															case 1:
															$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_colverzuim_laat, $telaat);
															// print "caso 1 ".$_colverzuim_laat." - ";
															break;
															case 2;
															$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_colverzuim_verzm, $absentie);
															// print "caso 2 ".$_colverzuim_omperking." - ";
															break;
															case 3;
															$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_colverzuim_huiswerk, $huiswerk);
															break;
															case 4;
															break;

															default:
															echo "Error Verzuim";
															break;
														}

														$cont_verzuim++;
													}


													$cont_verzuim = 1;
													$cont_verzuim_laat = "AT";
													$cont_verzuim_verzm = "AU";
													$cont_verzuim_huiswerk = "AM";

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
						function _returncolumnfromindex($index)
						{

							if($this->debug)
							{
								error_log("index type: " . gettype($index));
								error_log("index value: " . $index);
							}

							$returnvalue = "";

							switch($index)
							{
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

								default:
								echo "Error en cijfers";
								break;
							}

							if($this->debug)
							{

								error_log("return value: " . $returnvalue);
							}

							return $returnvalue;
						}
						function column_houdin($index)
						{

							if($this->debug)
							{
								error_log("index type: " . gettype($index));
								error_log("index value: " . $index);
							}

							$returnvalue = "";

							switch($index)
							{
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
								break;


								default:
								$returnvalue = -1;
								break;
							}

							if($this->debug)
							{

								error_log("return value: " . $returnvalue);
							}

							return $returnvalue;
						}
						function column_verzuim($index)
						{

							if($this->debug)
							{
								error_log("index type: " . gettype($index));
								error_log("index value: " . $index);
							}

							$returnvalue = "";

							switch($index)
							{
								case 39:
								$returnvalue = "AM"; /* dictee */
								break;

								case 40:
								$returnvalue = "AN"; /* taaloefening */
								break;

								case 41:
								$returnvalue = "AO"; /* tekst */
								break;

								case 42:
								$returnvalue = "AP"; /* lezen */
								break;

								case 43:
								$returnvalue = "AQ"; /* SKIP */
								break;

								case 44:
								$returnvalue = "AR"; /* */
								break;
								case 45:
								$returnvalue = "AS"; /* */
								break;

								default:
								$returnvalue = -1;
								break;
							}

							if($this->debug)
							{

								error_log("return value: " . $returnvalue);
							}

							return $returnvalue;
						}
						function get_rekenen_by_student($klas, $studentid, $schoolID, $rapnumber, $dummy)
						{
							$returnvalue = "";
							$sql_query = "";
							$htmlcontrol="";

							$result = 0;
							if ($dummy)
							$result = 1;
							else
							{
								mysqli_report(MYSQLI_REPORT_STRICT);
								require_once("spn_utils.php");
								$utils = new spn_utils();
								try
								{
									require_once("DBCreds.php");
									$DBCreds = new DBCreds();
									$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort,$dummy);
									$sp_get_rekenen_by_student = "sp_get_rekenen_by_student";
									$count_cijfers = null;
									$count_cijfers_i = null;
									$sum_cijfers=null;
									$sum_cijfers_i=null;

									if($stmt=$mysqli->prepare("CALL " . $this->$sp_get_rekenen_by_student . "(?,?,?,?)"))
									{
										if ($stmt->bind_param("siii",$klas, $studentid, $schoolID, $rapnumber))
										{
											if($stmt->execute())
											{

												$this->error = false;
												$result=1;
												$stmt->bind_result($rapnummer, $vak, $c1,$c2,$c3,$c4,$c5,$c6,$c7,$c8,$c9,$c10,$c11,$c12,$c13,$c14,$c15,$c16,$c17,$c18,$c19,$c20);
												$stmt->store_result();

												if($stmt->num_rows > 0)

												{
													while($stmt->fetch())
													{
														$sum_cijfers_i = $c1+$c2+$c3+$c4+$c5+$c6+$c7+$c8+$c9+$c10+$c11+$c12+$c13+$c14+$c15+$c16+$c17+$c18+$c19+$c20;
														// calculo de rekenen
														for($i = 1 ; $i < 21; $i++){
															if (isset(${"c" . $i}))

															{
																//echo ${"c" . $i};
																$count_cijfers_i++;
															}

														}
														//$count_cijfers = $count_cijfers + 1;
														$sum_cijfers = $sum_cijfers+$sum_cijfers_i;

													}

													$rekenen = $sum_cijfers/$count_cijfers_i;

												}
												else
												{
													$rekenen = "";
												}
											}

											else
											{
												$result = 0;
												$this->mysqlierror = $mysqli->error;
												$this->mysqlierrornumber = $mysqli->errno;
											}
										}
										else
										{
											$result = 0;
											$this->mysqlierror = $mysqli->error;
											$this->mysqlierrornumber = $mysqli->errno;
										}
									}
									else
									{
										$result = 0;
										$this->mysqlierror = $mysqli->error;
										$this->mysqlierrornumber = $mysqli->errno;
									}
								}

								catch(Exception $e)
								{
									$result = -2;
									$this->exceptionvalue = $e->getMessage();
									$result = $e->getMessage();
								}
								return $rekenen;
							}
						}
						function get_avi_by_student($studentid_out,$schooljaar, $rapnumber, $dummy)
						{
							$returnvalue = "";
							$sql_query = "";
							$htmlcontrol="";
							$avi_student = "";

							$result = 0;
							if ($dummy)
							$result = 1;
							else
							{
								mysqli_report(MYSQLI_REPORT_STRICT);
								require_once("spn_utils.php");
								$utils = new spn_utils();
								try
								{
									require_once("DBCreds.php");
									$DBCreds = new DBCreds();
									$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort,$dummy);
									$sp_get_avi_by_student = "sp_get_avi_by_student";


									if($stmt=$mysqli->prepare("CALL " . $sp_get_avi_by_student . "(?,?,?)"))
									{
										if ($stmt->bind_param("isi",$studentid_out,$schooljaar, $rapnumber))
										{
											if($stmt->execute())
											{

												$this->error = false;
												$result=1;
												$stmt->bind_result($avi_level);
												$stmt->store_result();

												if($stmt->num_rows > 0)

												{
													while($stmt->fetch())
													{
														$avi_student = $avi_level;

													}

												}
												else
												{
													$avi_student = "";
												}
											}

											else
											{
												$result = 0;
												$this->mysqlierror = $mysqli->error;
												$this->mysqlierrornumber = $mysqli->errno;
											}
										}
										else
										{
											$result = 0;
											$this->mysqlierror = $mysqli->error;
											$this->mysqlierrornumber = $mysqli->errno;
										}
									}
									else
									{
										$result = 0;
										$this->mysqlierror = $mysqli->error;
										$this->mysqlierrornumber = $mysqli->errno;
									}
								}

								catch(Exception $e)
								{
									$result = -2;
									$this->exceptionvalue = $e->getMessage();
									$result = $e->getMessage();
								}
								return $avi_student;
							}
						}
						function read_opmerking_by_rapport($schooljaar,$studentid_out,$schoolid,$klas_in, $rap_in)
						{
							require_once("spn_utils.php");
							$sql_query = "";
							$htmlcontrol="";
							$result = 0;
							$dummy = 0;
							require_once("DBCreds.php");
							$DBCreds = new DBCreds();
							date_default_timezone_set("America/Aruba");
							$_DateTime = date("Y-m-d H:i:s");
							$status = 1;
							$json_result ="";

							mysqli_report(MYSQLI_REPORT_STRICT);
							try
							{
								require_once("DBCreds.php");
								$DBCreds = new DBCreds();

								$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

								if($select = $mysqli->prepare("CALL ".$this->sp_read_opmerking_by_rapport." (?,?,?,?,?)"))
								{
									if($select->bind_param("siisi",$schooljaar,$studentid_out,$schoolid,$klas_in,$rap_in))
									{
										if($select->execute())
										{
											$this->error = false;
											$result = 1;

											$select->bind_result($opmerking_comment);

											$select->store_result();
											if($select->num_rows > 0)
											{
												$opmerking_result="";
												while($select->fetch())
												{
													$_opmerking_result = $opmerking_comment;
												}
											}

											else
											{
												$result = 0;
												$this->mysqlierror = $mysqli->error;
												$this->mysqlierrornumber = $mysqli->errno;

												$_opmerking_result = $result;
											}
										}
										else
										{
											$result = 0;
											$this->mysqlierror = $mysqli->error;
											$this->mysqlierrornumber = $mysqli->errno;

											$_opmerking_result = $result;
										}
									}
									else
									{
										$result = 0;
										$this->mysqlierror = $mysqli->error;
										$this->mysqlierrornumber = $mysqli->errno;

										$_opmerking_result = $result;
									}
								}
								else
								{
									$result = 0;
									$this->mysqlierror = $mysqli->error;
									$this->mysqlierrornumber = $mysqli->errno;

									$_opmerking_result = $result;
								}


							}
							catch(Exception $e)
							{
								$result = -2;
								$this->exceptionvalue = $e->getMessage();
								$result = $e->getMessage();

								$_opmerking_result = $result;
							}

							return $_opmerking_result;
						}
					}

					?>
