<?php
require_once ("spn_setting.php");


class spn_rapport_school_14_HL2
{


	function createrapport($schoolid,$schooljaar,$grade_in,$klas_in,$rap_in, $dummy){

		require_once ("../classes/3rdparty/PHPExcel/PHPExcel/IOFactory.php");
		// Create new PHPExcel object

		$objPHPExcel = PHPExcel_IOFactory::load("../templates/14_bespreeklijst_HL.xlsx");



		$objPHPExcel->setActiveSheetIndex(0);

		$this->write_cijfer_data($schoolid,$schooljaar,$grade_in,$klas_in,$rap_in, $objPHPExcel);


		// FUNCION QUE DEBO BORRAR AL SUBIR

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		// $objWriter->save('SN_rapport_file_'.$klas_in.'.xlsx');
		// $objPHPExcel->disconnectWorksheets();
		// unset($objWriter, $objPHPExcel);
		// exit;

		// FUNCION de output file
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="SN_rapport_file_'.$klas_in.'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}


	function write_cijfer_data($schoolid,$schooljaar,$grade_in,$klas_in,$rap_in, $objPHPExcel)
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




		$default_grade = 'F3';
		$default_klas = 'L3';
		$default_mentor = "Q3";


		$_debug_write_counter = 0;
		$row="";
		$col="";
		$student_out ="";

		// $_default_activesheet_index = 0;

		$houding_result = 0;
		$column_houding = "";

		$verzuim_result = array();
		$tutor = "";

		$students_list = json_decode($this->get_students_list_json($grade_in,$klas_in));
		// $vak_list = json_decode($this->list_vaks_by_grade_json($klas_in,$grade_in));
		// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B8', 'Luis Bello');


		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($default_mentor,ucwords($this->get_tutor_name($klas_in)));
		// 		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($default_mentor,$this->get_tutor_name($klas_in));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA1', $rap_in);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($default_grade, $grade_in);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($default_klas, $klas_in);

		$nr=1;
		$nr_cijfers=1;
		$nr_vaks =1;
		$nr_initials =1;

		$_current_student_start_row_vaks = 6;
		$_current_student_start_row_initials = 7;
		$_current_student_start_row = 8;
		$_current_student_start_col = "B";
		$_current_student_start_rowcijfers = 8;

		$gem = "";

		$col = '';
		$z = '';

		$nr=1;

		$current_vak_name = '';
		$last_vak_name = '';
		if($students_list[0]->id != 0){
			foreach($students_list as $students_data)
			{
				$colum_vaks = '';
				$row =((string)$_current_student_start_col.(string)$_current_student_start_row);
				$student_out = $students_data->student;

				// PRINT STUDENT NAME
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.(string)$_current_student_start_row, $nr);
				$nr++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row, $student_out);

				$vak_name_list = json_decode($this->get_vaks_hl($students_data->id, $_SESSION['SchoolJaar']));
				$colum_vaks_name = 4;
				foreach($vak_name_list as $vak_name_data)
				{
					$current_vak_name = $vak_name_data->volledigenaamvak;

					if ($current_vak_name != $last_vak_name){
						if (strpos($vak_name_data->volledigenaamvak, '|') !== false) {
							$z = str_replace("|","&",$vak_name_data->volledigenaamvak);
						}
						else{
							$z =$vak_name_data->volledigenaamvak;
						}
						// PRINT THE VAKS CODES
						$col_vaks = (($this->_returncolumnfromindex($colum_vaks_name)).(string)$_current_student_start_row_vaks);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col_vaks, strtolower($z));

						// PRINT THE INITIALS
						$col = (($this->_returncolumnfromindex($colum_vaks_name)).(string)$_current_student_start_row_initials);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col, $vak_name_data->initials);


						for($i = 1 ; $i <=$rap_in; $i++){

							// Cijfers
							if ($i == 1){
								$col = (($this->_returncolumnfromindex($colum_vaks_name)).(string)$_current_student_start_row);

								$gem = $this->get_gemiddelde_by_student_and_vak( $students_data->id,$vak_name_data->vakid, $i) ;
								// $gem = $this->get_average_gemiddelde($i, $students_data->id,$vak_name_data->vakid) ;
								if ($gem <= 5.4  && $gem != 0 && $gem !='' && $gem != null){
									$objPHPExcel->getActiveSheet()->getStyle($col)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFADAD');
								}
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.(string)$_current_student_start_rowcijfers,$i);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col,  round($gem,1));


							}
							if ($i == 2){
								$col = (($this->_returncolumnfromindex($colum_vaks_name)).(string)($_current_student_start_row+1));
								$gem = $this->get_gemiddelde_by_student_and_vak( $students_data->id,$vak_name_data->vakid, $i) ;
								if ($gem <= 5.4  && $gem != 0 && $gem !='' && $gem != null){
									$objPHPExcel->getActiveSheet()->getStyle($col)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFADAD');
								}
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.(string)($_current_student_start_row+1),$i);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col,  round($gem,1));

							}
							if ($i == 3){
								$col = (($this->_returncolumnfromindex($colum_vaks_name)).(string)($_current_student_start_row+2));
								$gem = $this->get_gemiddelde_by_student_and_vak( $students_data->id,$vak_name_data->vakid, $i) ;
								if ($gem <= 5.4 && $gem != 0 && $gem !='' && $gem != null ){
									$objPHPExcel->getActiveSheet()->getStyle($col)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFADAD');
								}
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.(string)($_current_student_start_row+2),$i);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col,  round($gem,1));

							}

							if ($i == $rap_in){

								if ($i == 1){
									$col = ($this->_returncolumnfromindex($colum_vaks_name).(string)($_current_student_start_rowcijfers+1));
								}
								if ($i == 2){
									$col = ($this->_returncolumnfromindex($colum_vaks_name).(string)($_current_student_start_rowcijfers+2));
								}
								if ($i == 3){
									$col = ($this->_returncolumnfromindex($colum_vaks_name).(string)($_current_student_start_rowcijfers+3));
								}
								$objPHPExcel->getActiveSheet()->getStyle($col)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('B7DDE8');
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col, round($this->get_average_gemiddelde($rap_in,$students_data->id, $vak_name_data->vakid)));
							}


							// $gem = $this->get_average_gemiddelde($i, $students_data->id,$vak_name_data->groupid) ;
							//
							// // print('<br>Este es col: '.$col);
							// // print('<br>Este es gem: '.$gem);
							//
							// if ($gem <= 5.4){
							// 	// $objPHPExcel->getActiveSheet()->getStyle($col)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFADAD');
							// }
							// $objPHPExcel->setActiveSheetIndex(0)->setCellValue($row, $gem);

						}


						$z = '';
						$col_vaks = '';
						$colum_vaks_name++;

						$last_vak_name = $vak_name_data->volledigenaamvak;
					}

					// if ($rap_in==1){
					// 	$_current_student_start_row_vaks = $_current_student_start_row_vaks+4;
					// 	$_current_student_start_row_initials = $_current_student_start_row_initials+4;
					// 	$_current_student_start_row =$_current_student_start_row+4 ;
					// 	$_current_student_start_rowcijfers = $_current_student_start_rowcijfers+4;
					// }
					// if ($rap_in==2){
					// 	$_current_student_start_row_vaks = $_current_student_start_row_vaks+6;
					// 	$_current_student_start_row_initials = $_current_student_start_row_initials+6;
					// 	$_current_student_start_row =$_current_student_start_row+6;
					// 	$_current_student_start_rowcijfers = $_current_student_start_rowcijfers+6;
					// }
					// if ($rap_in==3){
					// 	$_current_student_start_row_vaks = $_current_student_start_row_vaks+8;
					// 	$_current_student_start_row_initials = $_current_student_start_row_initials+5;
					// 	$_current_student_start_row =$_current_student_start_row+8 ;
					// 	$_current_student_start_rowcijfers = $_current_student_start_rowcijfers+8;
					// }


				}

				if ($rap_in==1){
					$_current_student_start_row_vaks = $_current_student_start_row_vaks+4;
					$_current_student_start_row_initials = $_current_student_start_row_initials+4;
					$_current_student_start_row =$_current_student_start_row+4 ;
					$_current_student_start_rowcijfers = $_current_student_start_rowcijfers+4;
				}
				if ($rap_in==2){
					$_current_student_start_row_vaks = $_current_student_start_row_vaks+6;
					$_current_student_start_row_initials = $_current_student_start_row_initials+6;
					$_current_student_start_row =$_current_student_start_row+6;
					$_current_student_start_rowcijfers = $_current_student_start_rowcijfers+6;
				}
				if ($rap_in==3){
					$_current_student_start_row_vaks = $_current_student_start_row_vaks+8;
					$_current_student_start_row_initials = $_current_student_start_row_initials+8;
					$_current_student_start_row =$_current_student_start_row+8 ;
					$_current_student_start_rowcijfers = $_current_student_start_rowcijfers+8;
				}












			}
			// $nr++;
		}

	}
	function get_average_gemiddelde($rap,$studentid, $vakid)
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

		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();

			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if($select =$mysqli->prepare("CALL sp_get_total_gemiddelde_by_rap (?,?,?)"))
			{
				if($select->bind_param("iii", $rap,$studentid, $vakid))
				{
					if($select->execute())
					{
						$this->error = false;
						$result = 1;

						$select->bind_result($gemiddelde);

						$select->store_result();

						if($select->num_rows > 0 )
						{
							while($select->fetch())
							{
								$htmlcontrol = $gemiddelde;
							}
						}
						else
						{
							$htmlcontrol = "";
						}
					}
					else
					{
						$result = 0;
						$this->mysqlierror = $mysqli->error;
						$this->mysqlierrornumber = $mysqli->errno;
						$result = $mysqli->error;
					}
				}
				else
				{
					/* error executing query */
					$this->error = true;
					$this->errormessage = $mysqli->error;
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
				/* error preparing query */
				$this->error = true;
				$this->errormessage = $mysqli->error;
				$result=0;

				print "error del sql: ". $this->errormessage;

				if($this->debug)
				{
					print "error preparing query";
				}
			}
			// Cierre del prepare
			$returnvalue = $htmlcontrol;
		}
		catch(Exception $e)
		{
			$this->error = true;
			$this->errormessage = $e->getMessage();
			$result=0;

			if($this->debug)
			{
				print "exception: " . $e->getMessage();
			}
		}
		return $returnvalue;
	}

	function get_gemiddelde_by_student_and_vak($studentid, $vakid, $rap)
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

		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();

			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			$sql_query = "select c.gemiddelde from le_cijfers c inner join groups g on g.id = c.vak where c.studentid= $studentid and g.vakid =$vakid and c.rapnummer =$rap ;";

			if($select = $mysqli->prepare($sql_query))
			{
				if($select->execute())
				{
					$this->error = false;
					$result = 1;

					$select->bind_result($gemiddelde);

					$select->store_result();

					if($select->num_rows > 0 )
					{
						while($select->fetch())
						{
							$htmlcontrol = $gemiddelde;
						}
					}

					else
					{
						$htmlcontrol = "";
					}


				}
				else
				{
					/* error executing query */
					$this->error = true;
					$this->errormessage = $mysqli->error;
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
				/* error preparing query */
				$this->error = true;
				$this->errormessage = $mysqli->error;
				$result=0;

				print "error del sql: ". $this->errormessage;

				if($this->debug)
				{
					print "error preparing query";
				}
			}
			// Cierre del prepare
			$returnvalue = $htmlcontrol;
		}
		catch(Exception $e)
		{
			$this->error = true;
			$this->errormessage = $e->getMessage();
			$result=0;

			if($this->debug)
			{
				print "exception: " . $e->getMessage();
			}
		}
		return $returnvalue;
	}

	function get_students_list_json($grade, $klas)
	{
		$json = array();
		$result=false;  /* function will return this variable, true if auth successful, false if auth unsuccessful */

		mysqli_report(MYSQLI_REPORT_STRICT);

		try
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			$mysqli->set_charset('utf8');

			if($klas == "All")
			{
				$sql_query_text = "select s.id, s.firstname, s.lastname from students s inner join class c on c.name_class = s.class where c.level_class = ? and s.schoolid = ? order by s.lastname;";

			}
			else
			{
				$sql_query_text = "select id, firstname, lastname from students where class = ? and schoolid = ? order by lastname;";

			}
			if($select=$mysqli->prepare($sql_query_text))
			{
				if($klas == "All")
				{
					if($select->bind_param("si",$grade, $_SESSION['SchoolID']))
					{
						if($select->execute())
						{
							$select->store_result();
							$select->bind_result($id,$firstname,$lastname);
							$count=$select->num_rows;

							if($count >= 1)
							{
								while($row = $select->fetch())
								{
									$json[] = array("id"=>$id,"student"=>$lastname.', '.$firstname );
								}

							}
							else
							{
								/* No students found */
								$json[] = array("id"=>"0","student"=>"NONE");
								// $this->errormsg_internal=  "no students exist";
								// if($this->debug)
								// {
								//   print "no students exist";
								// }
							}

							$result = 1;
							$select->close();
							$mysqli->close();
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

					if($select->bind_param("si",$klas, $_SESSION['SchoolID']))
					{
						if($select->execute())
						{
							$select->store_result();
							$select->bind_result($id,$firstname,$lastname);
							$count=$select->num_rows;

							if($count >= 1)
							{
								while($row = $select->fetch())
								{
									// $json[] = array("id"=>$id,"student"=>$firstname . chr(32) . $lastname);
									$json[] = array("id"=>$id,"student"=>$lastname.', '.$firstname );
									// print($id);
								}
							}
							else
							{
								/* No klassen found */
								$json[] = array("id"=>"0","student"=>"NONE");
								// $this->errormsg_internal=  "no students exist";
								// if($this->debug)
								// {
								//   print "no klassen exist";
								// }
							}

							$result = 1;
							$select->close();
							$mysqli->close();
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

			}
			else
			{
				$result = 0;
				$this->mysqlierror = $mysqli->error;
				$this->mysqlierrornumber = $mysqli->errno;
			}
			$result = json_encode($json);

			return $result;


		}
		catch (Exception $e)
		{
			$this->error = true;
			$this->errormsg_internal= $e-getMessage();
			/* handle error
			maybe db offline or something else */
			return $result;
		}


	}
	function get_cijfers_hl($studentid,$rapnummer,$schoolJaar)
	{
		$returnvalue = "";
		$sql_query = "";
		$htmlcontrol="";
		$V = "";
		$vakname ="";

		$result = 0;

		//   print('enre aqui');
		mysqli_report(MYSQLI_REPORT_STRICT);
		require_once("spn_utils.php");
		$utils = new spn_utils();
		try
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if($stmt=$mysqli->prepare("CALL sp_get_cijfers_hl (?,?,?)"))
			{
				if ($stmt->bind_param("iis",$studentid,$rapnummer,$schoolJaar))
				{
					if($stmt->execute())
					{
						$this->error = false;
						$result=1;
						$stmt->bind_result($id,$volledigenaamvak,$initials, $x_index, $gemiddelde );
						$stmt->store_result();

						if($stmt->num_rows > 0)
						{
							while($row = $stmt->fetch())
							{
								$json[] = array("id"=>$id,"volledigenaamvak"=>$volledigenaamvak,"x_index"=>$x_index, "initials"=>$initials,"gemiddelde"=>$gemiddelde);
							}
						}
						else
						{
							$json[] = array("id"=>"0","volledigenaamvak"=>"NONE");
						}
					}
				}
			}
		}
		catch(Exception $e)
		{
			$result = -2;
			$this->exceptionvalue = $e->getMessage();
			$result = $e->getMessage();
		}
		$result = json_encode($json);
		return $result;

	}
	function _returncolumnfromindex($index)
	{

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


			default:
			$returnvalue = -1;
			break;
		}
		return $returnvalue;
	}
	function get_tutor_name($klas)
	{
		$returnvalue = "";
		$sql_query = "";
		$htmlcontrol="";
		$V = "";
		$vakname ="";

		$result = "";

		//   print('enre aqui');
		mysqli_report(MYSQLI_REPORT_STRICT);
		require_once("spn_utils.php");
		$utils = new spn_utils();
		try
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			$sql_query = "SELECT a.firstname, a.lastname from app_useraccounts a inner join user_hs u on u.user_GUID = a.UserGUID where klas = '$klas' and tutor = 'Yes';";
			if($stmt=$mysqli->prepare($sql_query))
			{

				if($stmt->execute())
				{
					$this->error = false;
					$result=1;
					$stmt->bind_result($firstname, $lastname);
					$stmt->store_result();

					if($stmt->num_rows > 0)
					{
						while($row = $stmt->fetch())
						{
							$htmlcontrol =$firstname.' '.$lastname;
						}
					}
					else
					{
						$htmlcontrol='';
					}
				}

			}
		}
		catch(Exception $e)
		{
			$result = -2;
			$this->exceptionvalue = $e->getMessage();
			$result = $e->getMessage();
		}
		$result = $htmlcontrol;
		return $result;

	}
	function get_vaks_hl($studentid,$schoolJaar)
	{
		$returnvalue = "";
		$sql_query = "";
		$htmlcontrol="";
		$V = "";
		$vakname ="";

		$result = 0;

		//   print('enre aqui');
		mysqli_report(MYSQLI_REPORT_STRICT);
		require_once("spn_utils.php");
		$utils = new spn_utils();
		try
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if($stmt=$mysqli->prepare("CALL sp_get_vaks_hl (?,?)"))
			{
				if ($stmt->bind_param("is",$studentid,$schoolJaar))
				{
					if($stmt->execute())
					{
						$this->error = false;
						$result=1;
						$stmt->bind_result($vakid, $groupid, $volledigenaamvak,$initials, $x_index );
						$stmt->store_result();

						if($stmt->num_rows > 0)
						{
							while($row = $stmt->fetch())
							{
								$json[] = array("vakid"=>$vakid,"groupid"=>$groupid, "volledigenaamvak"=>$volledigenaamvak,"x_index"=>$x_index, "initials"=>$initials);
							}
						}
						else
						{
							$json[] = array("id"=>"0","volledigenaamvak"=>"NONE");
						}
					}
				}
			}
		}
		catch(Exception $e)
		{
			$result = -2;
			$this->exceptionvalue = $e->getMessage();
			$result = $e->getMessage();
		}
		$result = json_encode($json);
		return $result;

	}

}

?>
