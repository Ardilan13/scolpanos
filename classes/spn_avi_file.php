<?php
require_once ("spn_setting.php");

class spn_avi_file
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
		$this->rapport_modified = $this->rapport_reader->load("../templates/AVI_template.xlsx");

	}
	function _exportexcelfile($klas_in, $exporttobrowser = true)
	{

		if($exporttobrowser)
		{
			/* add headers for output to browser */
			header('Content-type: application/vnd.ms-excel.sheet.macroEnabled.12');
			header('Content-Disposition: attachment; filename="Avi_'.$klas_in.'.xlsx"');
			/* add headers for output to browser */
		}

		$this->rapport_writer = PHPExcel_IOFactory::createWriter($this->rapport_modified,'Excel2007');
		$this->rapport_writer->save('php://output');

	}

	function createrapport($schoolid,$schooljaar,$klas_in,$generate_all_rapporten)
	{

		/* open the excel template */
		if($generate_all_rapporten == true)
		{
			$this->_openexceltemplate();
			$this->writer_avi_data($schoolid,$schooljaar,$klas_in);


			/* export when done */
			$this->_exportexcelfile($klas_in);
		}

	}

	function writer_avi_data($schoolid,$schooljaar,$klas_in)
	{

		$returnvalue = 0;
		$user_permission="";
		$sql_query = "";
		$htmlcontrol="";
		$periode_flag ="";

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
		$_default_excel_schoolname = "A1";
		$_default_excel_schooljaar = "M4";
		$_default_excel_level_klas = "E4";
		$_default_excel_letter_class = "G4";

		$_debug_write_counter = 0;
		$this->activesheet_index = 0;

		//$_default_activesheet_index = 0;

		mysqli_report(MYSQLI_REPORT_STRICT);
		$s = new spn_setting();
		$s->getsetting_info($schoolid, false);

		$sql_query = "SELECT a.id_avi, a.period, a.level, a.promoted, a.mistakes, a.time_length,
		a.observation,s.firstname, s.lastname, s.id
		FROM avi a
		INNER JOIN students s
		ON s.id = a.id_student
		WHERE a.schooljaar = ?
		AND a.class = ?
		AND s.schoolid = ? ORDER BY ";

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

				if($select->bind_param("ssi",$schooljaar,$klas_in,$schoolid))
				{
					if($select->execute())
					{
						$select->store_result();
						$this->error = false;
						if($select->num_rows > 0)
						{
							// ECHO "llegue aca";
							$result=1;
							$select->bind_result($id_avi,$avi_period,$avi_level,$avi_promoted,$avi_mistakes, $avi_time_length, $avi_observation, $s_first_name,$s_last_name, $id_student);

							$this->_openexceltemplate();

							while($select->fetch())
							{
								/* initialize last student id if whilecounter == 0 */
								if($_while_counter == 0)
								{

									$_laststudent = $id_student;

									/* Write Docent, Klas, Schooljaar fields */
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $s_last_name . ", " . $s_first_name);

									// $this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_schoolname,$schoolname);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_schooljaar,$schooljaar);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_level_klas,substr($klas_in, 0,1));
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue($_default_excel_letter_class,substr($klas_in, 1, 2));



										if ($avi_period==1){
											$avi_level_colum ="D";
											$avi_promoted_colum  = "E";
											$avi_mistakes_colum  = "F";
											$avi_time_length_colum  = "G";
											$avi_observation_colum  = "H";

										}
										else if ($avi_period==2)
										{
											$avi_level_colum ="I";
											$avi_promoted_colum  = "J";
											$avi_mistakes_colum  = "K";
											$avi_time_length_colum  = "L";
											$avi_observation_colum  = "M";
										}
										else {
											$avi_level_colum ="N";
											$avi_promoted_colum  = "O";
											$avi_mistakes_colum  = "P";
											$avi_time_length_colum  = "Q";
											$avi_observation_colum  = "R";
										}

										$avi_promoted_value=($avi_promoted == 1 ? "YES":"NO" );


										if (strlen(preg_replace('/\s+/u','',$avi_mistakes)) == 0) {
											$avi_mistakes_value = "";
										}
										else {
											$avi_mistakes_value = $avi_mistakes;
										}
										if (strlen(preg_replace('/\s+/u','',$avi_observation)) == 0) {
											$avi_observation_value = "";
										}
										else {
											$avi_observation_value = $avi_observation;
										}

										/* Write Student Id */
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("T" . (string)$_current_student_start_row, $id_student);
									//	$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $s_last_name . ", " . $s_first_name);
										//
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_level_colum. (string)$_current_student_start_row, $avi_level);
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_promoted_colum . (string)$_current_student_start_row, $avi_promoted_value);
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_mistakes_colum . (string)$_current_student_start_row, $avi_mistakes_value);
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_time_length_colum . (string)$_current_student_start_row, $avi_time_length);
										$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_observation_colum . (string)$_current_student_start_row, $avi_observation_value);
										$_current_student_start_row++;
										$periode_flag = $avi_period;


								}
								/* set the current student variable */
								$_currentstudent = $id_student;

								if($_currentstudent != $_laststudent)
								{

									if ($avi_period==1){
										$avi_level_colum ="D";
										$avi_promoted_colum  = "E";
										$avi_mistakes_colum  = "F";
										$avi_time_length_colum  = "G";
										$avi_observation_colum  = "H";

									}
									else if ($avi_period==2)
									{
										$avi_level_colum ="I";
										$avi_promoted_colum  = "J";
										$avi_mistakes_colum  = "K";
										$avi_time_length_colum  = "L";
										$avi_observation_colum  = "M";
									}
									else {
										$avi_level_colum ="N";
										$avi_promoted_colum  = "O";
										$avi_mistakes_colum  = "P";
										$avi_time_length_colum  = "Q";
										$avi_observation_colum  = "R";
									}

									$avi_promoted_value=($avi_promoted == 1 ? "YES":"NO" );


									if (strlen(preg_replace('/\s+/u','',$avi_mistakes)) == 0) {
										$avi_mistakes_value = "";
									}
									else {
										$avi_mistakes_value = $avi_mistakes;
									}
									if (strlen(preg_replace('/\s+/u','',$avi_observation)) == 0) {
										$avi_observation_value = "";
									}
									else {
										$avi_observation_value = $avi_observation;
									}

									/* Write Student Id */
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("T" . (string)$_current_student_start_row, $id_student);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $s_last_name . ", " . $s_first_name);
									//
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_level_colum. (string)$_current_student_start_row, $avi_level);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_promoted_colum . (string)$_current_student_start_row, $avi_promoted_value);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_mistakes_colum . (string)$_current_student_start_row, $avi_mistakes_value);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_time_length_colum . (string)$_current_student_start_row, $avi_time_length);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_observation_colum . (string)$_current_student_start_row, $avi_observation_value);
									$_current_student_start_row++;
									$periode_flag = $avi_period;

								}
								else if ($_currentstudent == $_laststudent && $periode_flag != $avi_period)
								{

									$_current_student_start_row--;

									if ($avi_period==1){
										$avi_level_colum ="D";
										$avi_promoted_colum  = "E";
										$avi_mistakes_colum  = "F";
										$avi_time_length_colum  = "G";
										$avi_observation_colum  = "H";

									}
									else if ($avi_period==2)
									{
										$avi_level_colum ="I";
										$avi_promoted_colum  = "J";
										$avi_mistakes_colum  = "K";
										$avi_time_length_colum  = "L";
										$avi_observation_colum  = "M";
									}
									else {
										$avi_level_colum ="N";
										$avi_promoted_colum  = "O";
										$avi_mistakes_colum  = "P";
										$avi_time_length_colum  = "Q";
										$avi_observation_colum  = "R";
									}

									$avi_promoted_value=($avi_promoted == 1 ? "YES":"NO" );


									if (strlen(preg_replace('/\s+/u','',$avi_mistakes)) == 0) {
										$avi_mistakes_value = "";
									}
									else {
										$avi_mistakes_value = $avi_mistakes;
									}
									if (strlen(preg_replace('/\s+/u','',$avi_observation)) == 0) {
										$avi_observation_value = "";
									}
									else {
										$avi_observation_value = $avi_observation;
									}

									/* Write Student Id */
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue("T" . (string)$_current_student_start_row, $id_student);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$_current_student_start_col . (string)$_current_student_start_row, $s_last_name . ", " . $s_first_name);
									//
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_level_colum. (string)$_current_student_start_row, $avi_level);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_promoted_colum . (string)$_current_student_start_row, $avi_promoted_value);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_mistakes_colum . (string)$_current_student_start_row, $avi_mistakes_value);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_time_length_colum . (string)$_current_student_start_row, $avi_time_length);
									$this->rapport_modified->setActiveSheetIndex($this->activesheet_index)->setCellValue((string)$avi_observation_colum . (string)$_current_student_start_row, $avi_observation_value);
									$_current_student_start_row++;
									$periode_flag = $avi_period;

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
