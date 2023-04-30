<?php
require_once ("spn_setting.php");

class spn_overzicht
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



	function __construct()
	{



	}

	function _openexceltemplate()
	{
		require_once("../classes/3rdparty/PHPExcel/PHPExcel.php");
		require_once("../classes/3rdparty/PHPExcel/PHPExcel/IOFactory.php");
		require_once("../classes/3rdparty/PHPExcel/PHPExcel/Writer/Excel2007.php");
		$this->rapport_reader = PHPExcel_IOFactory::createReader('Excel2007');
		$this->rapport_modified = $this->rapport_reader->load("../templates/overzicht_SPN.xlsx");
	}
	function _openexceltemplate_basic_student_data()
	{
		require_once("../classes/3rdparty/PHPExcel/PHPExcel.php");
		require_once("../classes/3rdparty/PHPExcel/PHPExcel/IOFactory.php");
		require_once("../classes/3rdparty/PHPExcel/PHPExcel/Writer/Excel2007.php");
		$this->rapport_reader = PHPExcel_IOFactory::createReader('Excel2007');
		$this->rapport_modified = $this->rapport_reader->load("../templates/basic_info_students.xlsx");
	}
	function _exportexcelfile($klas_in,$exporttobrowser = true)
	{

		if($exporttobrowser)
		{
			/* add headers for output to browser */
			header('Content-type: application/vnd.ms-excel.sheet.macroEnabled.12');
			header('Content-Disposition: attachment; filename="LEERLING_EXPORT_'.$klas_in.'.xlsx"');
		}

		/* add headers for output to browser */


		$this->rapport_writer = PHPExcel_IOFactory::createWriter($this->rapport_modified,'Excel2007');
		$this->rapport_writer->save('php://output');

	}

	function createrapport($schoolid,$schooljaar,$klas_in)
	{

		/* open the excel template */

		$this->_openexceltemplate();
		$this->_writer_overzicht_data($schoolid,$schooljaar,$klas_in);
		/* export when done */
		$this->_exportexcelfile($klas_in);

	}

	function _writer_overzicht_data($schoolid,$schooljaar,$klas_in)
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
		$_current_student_start_row = 8;
		$_current_student_start_col = "B";


		/* Default Excel positions */
		$_default_excel_klas = "E4";
		$_default_excel_schooljaar = "H1";

		$_default_excel_schoolname = "D4";
		$_default_excel_letter_class = "K1";
		$_idiom="";


		$_debug_write_counter = 0;
		$this->activesheet_index = 0;

		//$_default_activesheet_index = 0;

		mysqli_report(MYSQLI_REPORT_STRICT);
		$s = new spn_setting();
		$s->getsetting_info($schoolid, false);
		require_once("spn_utils.php");
		$utils = new spn_utils();

		$sql_query = "SELECT id,idnumber,lastname, firstname, sex,
		studentnumber,
		address,
		ne,en,sp,pa,voertaalanders,
		birthplace,
		enrollmentdate,
		dob,
		@id_family:= id_family as idfamily,
		(select position_company from contact where id_family = @id_family and type='Mother' limit 1) as mother,
		(select position_company from contact where id_family = @id_family and type='Father' limit 1) as father,
		(select mobile_phone from contact where id_family = @id_family and type='Mother' limit 1) as mobiel_moeder,
		(select home_phone from contact where id_family = @id_family and type='Mother' limit 1) as Huis_Telefoon_moeder,
		(select work_phone from contact where id_family = @id_family and type='Mother' limit 1) as Work_tel_moeder,
		(select mobile_phone from contact where id_family = @id_family and type='Father' limit 1) as mobiel_vader,
		(select home_phone from contact where id_family = @id_family and type='Father' limit 1) as Huis_Telefoon_vader,
		(select work_phone from contact where id_family = @id_family and type='Father' limit 1) as Work_tel_vader
		from students where schoolid = ? and class = ? ORDER BY ";

		$sql_order = "lastname, firstname ";
		if ($s->_setting_mj)
		{
			$sql_query .= " sex ". $s->_setting_sort . ", " . $sql_order;
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
			//print $sql_query;
			if($select=$mysqli->prepare($sql_query))
			{
				if($select->bind_param("is",$schoolid, $klas_in))
				{
					if($select->execute())
					{
						$select->store_result();
						$this->error = false;
						if($select->num_rows > 0)
						{
							$result=1;
							$select->bind_result($studentid_out, $idnumber,$firstname, $lastname,
							$sex,
							$studentnumber,
							$address,
							$ne,$en,$sp,$pa,$voertaalanders,
							$nationaliteit,
							$created,
							$dob,
							$idfamily,
							$mother,
							$father,
							$mobiel_mother,
							$huis_mother,
							$work_mother,
							$mobiel_father,
							$huis_father,
							$work_father);
							/* Audit by Caribe Developers */
							//$this->_openexceltemplate();

							while($select->fetch())
							{
								$voertaalanders=($voertaalanders == "" ? "" : $voertaalanders );
								$created = substr($utils->convertfrommysqldate($created),-4);
								/* initialize last student id if whilecounter == 0 */
								if($_while_counter == 0)
								{
									$_laststudent = $studentid_out;

									/* Write Docent, Klas, Schooljaar fields */
									$_current_student_start_row = 8;
									$_current_student_start_col = "B";


									/* Default Excel positions */
									$_default_excel_klas = "k1";
									$_default_excel_schooljaar = "G1";

									$_default_excel_schoolname = "D4";
									$_default_excel_letter_class = "I1";




									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_schoolname,$_SESSION["schoolname"]);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_schooljaar,$_SESSION["SchoolJaar"]);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_klas,$klas_in);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $firstname);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("D". (string)$_current_student_start_row, $lastname);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("E" . (string)$_current_student_start_row, $sex);
									if ($_SESSION["SchoolID"]== 4){
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("F" . (string)$_current_student_start_row, $dob);
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("F6","Gebor datum");
									}
									else{
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("F" . (string)$_current_student_start_row, $idnumber);
									}
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("G" . (string)$_current_student_start_row, $address);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("H" . (string)$_current_student_start_row, $voertaalanders);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("I" . (string)$_current_student_start_row, $nationaliteit);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("J" . (string)$_current_student_start_row, $created);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("K" . (string)$_current_student_start_row, $father);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("L" . (string)$_current_student_start_row, $mother);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("N" . (string)$_current_student_start_row, $mobiel_mother);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("O" . (string)$_current_student_start_row, $huis_mother);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("P" . (string)$_current_student_start_row, $work_mother);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("Q" . (string)$_current_student_start_row, $mobiel_father);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("R" . (string)$_current_student_start_row, $huis_father);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("S" . (string)$_current_student_start_row, $work_father);

								}

								/* set the current student variable */
								$_currentstudent = $studentid_out;


								if($_currentstudent != $_laststudent)
								{
									$_current_student_start_row++;

									/* Write Student Id */
									//$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("AT" . (string)$_current_student_start_row, $studentid_out);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $firstname);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("D". (string)$_current_student_start_row, $lastname);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("E" . (string)$_current_student_start_row, $sex);
									if ($_SESSION["SchoolID"]== 4){
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("F" . (string)$_current_student_start_row, $dob);
									}
									else{
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("F" . (string)$_current_student_start_row, $idnumber);
									}

									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("G" . (string)$_current_student_start_row, $address);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("H" . (string)$_current_student_start_row, $voertaalanders);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("I" . (string)$_current_student_start_row, $nationaliteit);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("J" . (string)$_current_student_start_row, $created);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("K" . (string)$_current_student_start_row, $father);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("L" . (string)$_current_student_start_row, $mother);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("N" . (string)$_current_student_start_row, $mobiel_mother);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("O" . (string)$_current_student_start_row, $huis_mother);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("P" . (string)$_current_student_start_row, $work_mother);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("Q" . (string)$_current_student_start_row, $mobiel_father);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("R" . (string)$_current_student_start_row, $huis_father);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("S" . (string)$_current_student_start_row, $work_father);


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

	function createrapport_basic_student_data($schoolid,$schooljaar,$klas_in)
	{
		/* open the excel template */
		$this->_openexceltemplate_basic_student_data();
		$this->_writer_basic_student_data($schoolid,$schooljaar,$klas_in);
		/* export when done */
		$this->_exportexcelfile($klas_in);

	}

	function _writer_basic_student_data($schoolid,$schooljaar,$klas_in)
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
		$_current_student_start_row = 6;
		$_current_student_start_col = "B";


		/* Default Excel positions */
		$_default_excel_klas = "B4";
		$_default_excel_schooljaar = "B3";

		$_default_excel_schoolname = "A1";
		$_default_excel_letter_class = "B4";
		$_idiom="";


		$_debug_write_counter = 0;
		$this->activesheet_index = 0;

		//$_default_activesheet_index = 0;

		mysqli_report(MYSQLI_REPORT_STRICT);
		$s = new spn_setting();
		$s->getsetting_info($schoolid, false);
		require_once("spn_utils.php");
		$utils = new spn_utils();

		if ($klas_in == "All"){
			$sql_query = "select id, studentnumber, firstname, lastname , class, sex, dob from students where schoolid = ? and class <> ? ORDER BY class, ";
		}
		else{
			$sql_query = "select id, studentnumber, firstname, lastname , class, sex, dob from students where schoolid = ? and class = ? ORDER BY ";

		}


		$sql_order = "lastname, firstname ";
		if ($s->_setting_mj)
		{
			$sql_query .= " sex ". $s->_setting_sort . ", " . $sql_order;
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
			//print $sql_query;
			if($select=$mysqli->prepare($sql_query))
			{
				if($select->bind_param("is",$schoolid, $klas_in))
				{
					if($select->execute())
					{
						$select->store_result();
						$this->error = false;
						if($select->num_rows > 0)
						{
							$result=1;
							$select->bind_result($studentid_out, $studentnumber, $firstname, $lastname , $class, $sex, $dob);
							/* Audit by Caribe Developers */
							//$this->_openexceltemplate();
							$i=1;
							while($select->fetch())
							{
								$i++;

								// print('este es i: '.$i.'<br>');
								// print('este es $studentnumber: '.$studentnumber.'<br>');
								// print('este es $firstname: '.$firstname.'<br>');
								// print('este es $lastname: '.$lastname.'<br>');
								// print('este es $class: '.$class.'<br>');
								// print('este es $sex: '.$sex.'<br>');
								// print('este es $dob: '.$dob.'<br>');

								/* initialize last student id if whilecounter == 0 */
								if($_while_counter == 0)
								{
									$_laststudent = $studentid_out;

									/* Write Docent, Klas, Schooljaar fields */
									$_current_student_start_row = 6;
									$_current_student_start_col = "B";


									/* Default Excel positions */
									$_default_excel_klas = "B4";
									$_default_excel_schooljaar = "B3";
									$_default_excel_schoolname = "A1";
									$_default_excel_letter_class = "B4";

									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_schoolname,$_SESSION["schoolname"]);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_schooljaar,$_SESSION["SchoolJaar"]);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_klas,$klas_in);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $firstname);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("A". (string)$_current_student_start_row, $studentnumber);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("D". (string)$_current_student_start_row, $lastname);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("E" . (string)$_current_student_start_row, $sex);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("G" . (string)$_current_student_start_row, $dob);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("F" . (string)$_current_student_start_row, $class);

								}

								/* set the current student variable */
								$_currentstudent = $studentid_out;
								if($_currentstudent != $_laststudent)
								{
									$_current_student_start_row++;
									/* Write Student Id */
									//$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("AT" . (string)$_current_student_start_row, $studentid_out);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("A". (string)$_current_student_start_row, $studentnumber);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $firstname);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("D". (string)$_current_student_start_row, $lastname);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("E" . (string)$_current_student_start_row, $sex);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("G" . (string)$_current_student_start_row, $dob);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("F" . (string)$_current_student_start_row, $class);


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

}
?>
