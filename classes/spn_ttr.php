<?php
require_once ("spn_setting.php");

class spn_ttr
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
		if ($_SESSION["SchoolID"]==4)
		{
			$this->rapport_modified = $this->rapport_reader->load("../templates/Tempo_toets_LVS.xlsx");
		}
		else
		{
			$this->rapport_modified = $this->rapport_reader->load("../templates/TTR_blank.xlsx");
		}

	}
	function _exportexcelfile($period,$klas_in,$exporttobrowser = true)
	{

		if($exporttobrowser)
		{
			/* add headers for output to browser */
			header('Content-type: application/vnd.ms-excel.sheet.macroEnabled.12');
			if ($_SESSION["SchoolID"]==4)
			{
			header('Content-Disposition: attachment; filename="Tempo_toets_LVS_'.$klas_in.'_'.$period.'.xlsx"');
		}
		else {
			header('Content-Disposition: attachment; filename="ttr_'.$klas_in.'_'.$period.'.xlsx"');# code...
		}
			/* add headers for output to browser */
		}

		$this->rapport_writer = PHPExcel_IOFactory::createWriter($this->rapport_modified,'Excel2007');
		$this->rapport_writer->save('php://output');

	}

	function createrapport($schoolid,$schooljaar,$klas_in,$period,$ttr_date,$ttr_dl,$generate_all_rapporten)
	{

		/* open the excel template */
		if($generate_all_rapporten == true)
		{
			$this->_openexceltemplate();

			/* export when done */
			$this->_exportexcelfile($period,$klas_in);
		}

	}

	function _writer_ttr_data($schoolid,$schooljaar,$klas_in,$period,$ttr_date,$ttr_dl)
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
		$_current_student_start_row = 7;
		$_current_student_start_col = "B";


		/* Default Excel positions */
		$_default_excel_leerkracht = "D1";
		$_default_excel_level_klas = "E4";
		$_default_excel_schooljaar = "D3";

		$_default_excel_schoolname = "B4";
		$_default_excel_letter_class = "G4";
		$_default_excel_date = "L4";
		$_default_excel_dl = "P4";



		$_debug_write_counter = 0;
		$this->activesheet_index = 0;

		//$_default_activesheet_index = 0;

		mysqli_report(MYSQLI_REPORT_STRICT);
		$s = new spn_setting();
		$s->getsetting_info($schoolid, false);

		$sql_query = "SELECT
		s.id, s.id_family ,
		CONCAT(s.firstname,' ',s.lastname) as student_full_name,
		sc.schoolname
		FROM students s
		INNER JOIN schools sc ON
		sc.id = s.SchoolID
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
				if($select->bind_param("sis",$klas_in,$schoolid,$schooljaar))
				{
					if($select->execute())
					{
						$select->store_result();
						$this->error = false;
						if($select->num_rows > 0)
						{
							$result=1;
							$select->bind_result($studentid_out, $id_family, $student_full_name,$schoolname);
							/* Audit by Caribe Developers */
							require_once ("spn_audit.php");
							$spn_audit = new spn_audit();
							$UserGUID = $_SESSION['UserGUID'];
							$spn_audit->create_audit($UserGUID, 'ttr','write ttr cijfer',true);
							//$this->_openexceltemplate();

							while($select->fetch())
							{

								/* initialize last student id if whilecounter == 0 */
								if($_while_counter == 0)
								{
									$_laststudent = $studentid_out;

									/* Write Docent, Klas, Schooljaar fields */
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_schoolname,$schoolname);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_level_klas,substr($klas_in, 0,1));
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_letter_class,substr($klas_in, 1, 2));
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_date,$ttr_date);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_dl,$ttr_dl);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $student_full_name);



								}

								/* set the current student variable */
								$_currentstudent = $studentid_out;


								if($_currentstudent != $_laststudent)
								{
									$_current_student_start_row++;
									/* Write Student Id */
									//$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("AT" . (string)$_current_student_start_row, $studentid_out);



									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $student_full_name);

									/* Write Rekenen */
									//	$rekenen_result = $this->get_rekenen_by_student($klas_in, $studentid_out, $schoolid, $rap_in,false);
									//	$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("J" . (string)$_current_student_start_row, $rekenen_result);


								}

								// if($_while_counter == 0)
								// {
								// 	/* Write Student Id */
								// 	//$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("AT" . (string)$_current_student_start_row, $studentid_out);
								// 	/* Write Student Name */
								// 	$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $student_full_name);
								// 	/* Write Rekenen */
								// 	//$rekenen_result = $this->get_rekenen_by_student($klas_in, $studentid_out, $schoolid, $rap_in,false);
								// 	//	$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("J" . (string)$_current_student_start_row, $rekenen_result);
								//
								//
								// }
								//

								/* Get the correct position and write the cijfer */
								//	$colgemiddelde = (string)$this->_returncolumnfromindex($x_index_out) . (string)$_current_student_start_row;
								//	$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($colgemiddelde, $gemiddelde_out);

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
