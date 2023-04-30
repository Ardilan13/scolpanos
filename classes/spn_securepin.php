<?php



/** Error reporting */

error_reporting(E_ALL);

ini_set('display_errors', TRUE);

ini_set('display_startup_errors', TRUE);

class spn_securepin

{







	function createrapport($schoolid,$klas_in){



		require_once ("../classes/3rdparty/PHPExcel/PHPExcel/IOFactory.php");

		// Create new PHPExcel object

		$objPHPExcel = PHPExcel_IOFactory::load("../templates/secure_pin_template.xlsx");





		// $objPHPExcel->setActiveSheetIndex(0)->getActiveSheet()->setTitle('Simple');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet

		$objPHPExcel->setActiveSheetIndex(0);

		// Redirect output to a clientâ€™s web browser (Excel2007)





		$this->write_cijfer_data($schoolid, $klas_in, $objPHPExcel);





		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		header('Content-Disposition: attachment;filename="SecurePin_EXPORT_'.$klas_in.'.xlsx"');

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





	function write_cijfer_data($schoolid,$klas_in, $objPHPExcel)

	{

		$returnvalue = 0;

		$sql_query = "";

		$htmlcontrol="";



		$_debug_write_counter = 0;

		$this->activesheet_index = 0;

		$_current_student_start_row = 2;



		mysqli_report(MYSQLI_REPORT_STRICT);

		require_once("spn_setting.php");

		$s = new spn_setting();

		$s->getsetting_info($schoolid, false);

		require_once("spn_utils.php");

		$utils = new spn_utils();



		if ($klas_in == 'All'){

			$sql_query = "select id, class, studentnumber, firstname, lastname, securepin from students where schoolid = $schoolid order by ";

		}

		else{

			$sql_query = "select id, class, studentnumber, firstname, lastname, securepin from students where schoolid = $schoolid and class = '$klas_in' order by ";

		}

		$sql_order = "lastname, firstname ";

		if ($s->_setting_mj){

			$sql_query .= " sex ". $s->_setting_sort . ", " . $sql_order;

		}

		else{

			$sql_query .=  $sql_order;

		}

		// echo $sql_query;



		try

		{

			require_once("DBCreds.php");

			$DBCreds = new DBCreds();

			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			$mysqli->set_charset('utf8');

			//print $sql_query;

			if($select=$mysqli->prepare($sql_query)){



				if($select->execute()){





					$select->store_result();

					$this->error = false;

					if($select->num_rows > 0){

						$select->bind_result($id, $class, $studentnumber, $firstname, $lastname, $securepin);

						while($select->fetch()){

							// print($_current_student_start_row.'|');



							$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . (string)$_current_student_start_row, $class);

							$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . (string)$_current_student_start_row, $studentnumber);

							$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . (string)$_current_student_start_row, $firstname);

							$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . (string)$_current_student_start_row, $lastname);

							$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . (string)$_current_student_start_row, $securepin);
							
							

							$_current_student_start_row++;

						}

					}

				}

			}

		}

		catch (Exception $e){

			$error = $e->getMessage();

			echo $error;

		}



	}



}

