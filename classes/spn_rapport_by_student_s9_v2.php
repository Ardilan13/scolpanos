<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
class spn_rapport_by_student_s9_v2
{



	function createrapport($schoolid, $schooljaar, $studentid, $generate_all_rapporten)
	{

		require_once("../classes/3rdparty/PHPExcel/PHPExcel/IOFactory.php");
		// Create new PHPExcel object
		$objPHPExcel = PHPExcel_IOFactory::load("../templates/Witte_kaart_2018_s9_v2.xlsx");


		// $objPHPExcel->setActiveSheetIndex(0)->getActiveSheet()->setTitle('Simple');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client’s web browser (Excel2007)


		$this->write_cijfer_data($objPHPExcel, $schoolid, $schooljaar, $studentid, $objPHPExcel);


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Witte_kaart.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}




	function write_cijfer_data($objPHPExcel, $schoolid, $schooljaar_rapport, $id_student)
	{

		$_default_scooljaar = "B4";
		$_default_klas = "B5";
		$_default_docent = "B6";

		$_default_period_1 = "B";
		$_default_period_2 = "F";
		$_default_period_3 = "J";
		$_colum_rapport = "";


		$c = 0;
		$counter_while = 0;
		$current_schooljaar = "";
		$last_schooljaar = "";

		require_once("DBCreds.php");

		if ($schooljaar_rapport == "All") {

			$rapport_query = "SELECT distinct concat(s.firstname, ' ', s.lastname), c.gemiddelde,
			v.volledigenaamvak, v.y_index, c.rapnummer, c.klas, c.schooljaar
			FROM
			le_cijfers c
			inner join
			le_vakken v on
			c.vak = v.id
			inner join
			students s on
			s.id = c.studentid
			WHERE c.studentid = ? order by c.schooljaar, c.rapnummer, v.y_index asc;";

			try {
				$DBCreds = new DBCreds();
				$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
				$mysqli->set_charset('utf8');
				if ($select = $mysqli->prepare($rapport_query)) {
					if ($select->bind_param("i", $id_student)) {
						if ($select->execute()) {
							$select->store_result();
							if ($select->num_rows > 0) {
								$result = 1;
								$select->bind_result($studentName, $gemiddelde, $volledigenaamvak, $y_index, $rapnummer, $klas, $schooljaar);

								$c = 0;
								$counter_while = 0;
								$current_schooljaar = "";
								$last_schooljaar = "";

								while ($select->fetch()) {
									if ($counter_while == 0) {

										$current_schooljaar = $schooljaar;

										$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_scooljaar, $schooljaar);
										$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_klas, $klas);
										$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_docent, "DOCENT");
										$this->write_verzuim_data($objPHPExcel, $id_student, $schooljaar, "B");
										$this->write_houding_data($objPHPExcel, $id_student, $schooljaar, "B");
										$this->write_avi_data($objPHPExcel, $id_student, $schooljaar, "B");

										if ($rapnummer == "1") {
											$colum_rapport = "B";
										} else if ($rapnummer == "2") {
											$colum_rapport = "C";
										} else {
											$colum_rapport = "D";
										}
										$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . (string)$y_index, $gemiddelde);
									}

									$last_schooljaar = $schooljaar;

									if ($current_schooljaar != $last_schooljaar) {
										$c++;
										if ($c == 1) {
											$_default_scooljaar = "F4";
											$_default_klas = "F5";
											$_default_docent = "F6";

											$this->write_verzuim_data($objPHPExcel, $id_student, $schooljaar, "F");
											$this->write_houding_data($objPHPExcel, $id_student, $schooljaar, "F");
											$this->write_avi_data($objPHPExcel, $id_student, $schooljaar, "F");
										}
										if ($c == 2) {

											$_default_scooljaar = "J4";
											$_default_klas = "J5";
											$_default_docent = "J6";

											$this->write_verzuim_data($objPHPExcel, $id_student, $schooljaar, "J");
											$this->write_houding_data($objPHPExcel, $id_student, $schooljaar, "J");
											$this->write_avi_data($objPHPExcel, $id_student, $schooljaar, "J");
										}
										if ($c == 3) {
											$_default_scooljaar = "N4";
											$_default_klas = "N5";
											$_default_docent = "N6";
											$this->write_verzuim_data($objPHPExcel, $id_student, $schooljaar, "N");
											$this->write_houding_data($objPHPExcel, $id_student, $schooljaar, "N");
											$this->write_avi_data($objPHPExcel, $id_student, $schooljaar, "N");
										}
										if ($c == 4) {
											$_default_scooljaar = "R4";
											$_default_klas = "R5";
											$_default_docent = "R6";
											$this->write_verzuim_data($objPHPExcel, $id_student, $schooljaar, "R");
											$this->write_houding_data($objPHPExcel, $id_student, $schooljaar, "R");
											$this->write_avi_data($objPHPExcel, $id_student, $schooljaar, "R");
										}
										if ($c == 5) {
											$_default_scooljaar = "V4";
											$_default_klas = "V5";
											$_default_docent = "V6";
											$this->write_verzuim_data($objPHPExcel, $id_student, $schooljaar, "V");
											$this->write_houding_data($objPHPExcel, $id_student, $schooljaar, "V");
											$this->write_avi_data($objPHPExcel, $id_student, $schooljaar, "V");
										}
										if ($c == 6) {
											$_default_scooljaar = "Z4";
											$_default_klas = "Z5";
											$_default_docent = "Z6";
											$this->write_verzuim_data($objPHPExcel, $id_student, $schooljaar, "Z");
											$this->write_houding_data($objPHPExcel, $id_student, $schooljaar, "Z");
											$this->write_avi_data($objPHPExcel, $id_student, $schooljaar, "Z");
										}
										if ($c == 7) {
											$_default_scooljaar = "AD4";
											$_default_klas = "AD5";
											$_default_docent = "AD6";
											$this->write_verzuim_data($objPHPExcel, $id_student, $schooljaar, "AD");
											$this->write_houding_data($objPHPExcel, $id_student, $schooljaar, "AD");
											$this->write_avi_data($objPHPExcel, $id_student, $schooljaar, "AD");
										}

										$current_schooljaar = $schooljaar;
									}

									// CHECK DE RAPPORT NUMBER AND SCHOOLJAAR NUMBER
									if ($c == 0) {
										if ($rapnummer == "1") {
											$colum_rapport = "B";
										} else if ($rapnummer == "2") {
											$colum_rapport = "C";
										} else {
											$colum_rapport = "D";
										}
									}

									if ($c == 1) {
										if ($rapnummer == "1") {
											$colum_rapport = "F";
										} else if ($rapnummer == "2") {
											$colum_rapport = "G";
										} else {
											$colum_rapport = "H";
										}
									}
									if ($c == 2) {
										if ($rapnummer == "1") {
											$colum_rapport = "J";
										} else if ($rapnummer == "2") {
											$colum_rapport = "K";
										} else {
											$colum_rapport = "L";
										}
									}
									if ($c == 3) {
										if ($rapnummer == "1") {
											$colum_rapport = "N";
										} else if ($rapnummer == "2") {
											$colum_rapport = "O";
										} else {
											$colum_rapport = "P";
										}
									}
									if ($c == 4) {
										if ($rapnummer == "1") {
											$colum_rapport = "R";
										} else if ($rapnummer == "2") {
											$colum_rapport = "S";
										} else {
											$colum_rapport = "T";
										}
									}
									if ($c == 5) {

										if ($rapnummer == "1") {
											$colum_rapport = "v";
										} else if ($rapnummer == "2") {
											$colum_rapport = "w";
										} else {
											$colum_rapport = "x";
										}
									}
									if ($c == 6) {

										if ($rapnummer == "1") {
											$colum_rapport = "Z";
										} else if ($rapnummer == "2") {
											$colum_rapport = "AA";
										} else {
											$colum_rapport = "AB";
										}
									}
									if ($c == 7) {

										if ($rapnummer == "1") {
											$colum_rapport = "AD";
										} else if ($rapnummer == "2") {
											$colum_rapport = "AE";
										} else {
											$colum_rapport = "AF";
										}
									}

									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_scooljaar, $schooljaar);
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_klas, $klas);
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_docent, "DOCENT");
									if ($y_index != null) {
										$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . (string)$y_index, $gemiddelde);
									}
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B3", $studentName);


									$counter_while++;
								}
							}
						}
					}
				}
			} catch (Exception $e) {
				$error = $e->getMessage();
				echo $error;
			}

			$rapport_query = "SELECT distinct concat(s.firstname, ' ', s.lastname), c.gemiddelde,
			v.vak_naam, c.rapnummer, c.klas, c.schooljaar
			FROM
			le_cijfers_ps c
			inner join
			le_vakken_ps v on
			c.vak = v.id
			inner join
			students s on
			s.id = c.studentid
			WHERE c.studentid = $id_student order by c.schooljaar, c.rapnummer asc";

			if ($select = $mysqli->prepare($rapport_query)) {
				if ($select->execute()) {
					$select->store_result();
					if ($select->num_rows > 0) {
						$result = 1;
						$select->bind_result($studentName, $gemiddelde, $volledigenaamvak, $rapnummer, $klas, $schooljaar);

						while ($select->fetch()) {
							if ($counter_while == 0) {

								$current_schooljaar = $schooljaar;

								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_scooljaar, $schooljaar);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_klas, $klas);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_docent, "DOCENT");

								if ($rapnummer == "1") {
									$colum_rapport = "B";
								} else if ($rapnummer == "2") {
									$colum_rapport = "C";
								} else {
									$colum_rapport = "D";
								}
							}

							$last_schooljaar = $schooljaar;

							if ($current_schooljaar != $last_schooljaar) {
								$c++;
								if ($c == 1) {
									$_default_scooljaar = "F4";
									$_default_klas = "F5";
									$_default_docent = "F6";
								}
								if ($c == 2) {

									$_default_scooljaar = "J4";
									$_default_klas = "J5";
									$_default_docent = "J6";
								}
								if ($c == 3) {
									$_default_scooljaar = "N4";
									$_default_klas = "N5";
									$_default_docent = "N6";
								}
								if ($c == 4) {
									$_default_scooljaar = "R4";
									$_default_klas = "R5";
									$_default_docent = "R6";
								}
								if ($c == 5) {
									$_default_scooljaar = "V4";
									$_default_klas = "V5";
									$_default_docent = "V6";
								}
								if ($c == 6) {
									$_default_scooljaar = "Z4";
									$_default_klas = "Z5";
									$_default_docent = "Z6";
								}
								if ($c == 7) {
									$_default_scooljaar = "AD4";
									$_default_klas = "AD5";
									$_default_docent = "AD6";
								}

								$current_schooljaar = $schooljaar;
							}

							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_scooljaar, $current_schooljaar);
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_docent, "DOCENT");


							if ($c == 0) {
								if ($rapnummer == "1") {
									$colum_rapport = "B";
								} else if ($rapnummer == "2") {
									$colum_rapport = "C";
								} else {
									$colum_rapport = "D";
								}
							}

							if ($c == 1) {
								if ($rapnummer == "1") {
									$colum_rapport = "F";
								} else if ($rapnummer == "2") {
									$colum_rapport = "G";
								} else {
									$colum_rapport = "H";
								}
							}
							if ($c == 2) {
								if ($rapnummer == "1") {
									$colum_rapport = "J";
								} else if ($rapnummer == "2") {
									$colum_rapport = "K";
								} else {
									$colum_rapport = "L";
								}
							}
							if ($c == 3) {
								if ($rapnummer == "1") {
									$colum_rapport = "N";
								} else if ($rapnummer == "2") {
									$colum_rapport = "O";
								} else {
									$colum_rapport = "P";
								}
							}
							if ($c == 4) {
								if ($rapnummer == "1") {
									$colum_rapport = "R";
								} else if ($rapnummer == "2") {
									$colum_rapport = "S";
								} else {
									$colum_rapport = "T";
								}
							}
							if ($c == 5) {

								if ($rapnummer == "1") {
									$colum_rapport = "v";
								} else if ($rapnummer == "2") {
									$colum_rapport = "w";
								} else {
									$colum_rapport = "x";
								}
							}
							if ($c == 6) {

								if ($rapnummer == "1") {
									$colum_rapport = "Z";
								} else if ($rapnummer == "2") {
									$colum_rapport = "AA";
								} else {
									$colum_rapport = "AB";
								}
							}
							if ($c == 7) {

								if ($rapnummer == "1") {
									$colum_rapport = "AD";
								} else if ($rapnummer == "2") {
									$colum_rapport = "AE";
								} else {
									$colum_rapport = "AF";
								}
							}

							switch ($volledigenaamvak) {
								case 'technisch lezen':
									$fila = 9;
									break;
								case 'begrijpend lezen':
									$fila = 10;
									break;
								case 'schrijven':
									$fila = 13;
									break;
								case 'rekenen':
									$fila = 14;
									break;
								case 'taal':
									$fila = 21;
									break;
								case 'wereldorientatie':
									$fila = 28;
									break;
								case 'expressie':
									$fila = 31;
									break;
								case 'boekbespreking/spreekbeurt':
									$fila = 36;
									break;
								case 'engels':
									$fila = 37;
									break;
								case 'spaans':
									$fila = 38;
									break;
								case 'verkeer':
									$fila = 40;
									break;
								case 'maatschappijleer':
									$fila = 41;
									break;
							}
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . (string)$fila, $gemiddelde);
							/* $this->write_verzuim_data($objPHPExcel, $id_student, $schooljaar_rapport, "B");
							$this->write_houding_data($objPHPExcel, $id_student, $schooljaar_rapport, "");
							$this->write_avi_data($objPHPExcel, $id_student, $schooljaar_rapport, ""); */
						}
					}
				}
			} else {

				if ($schooljaar_rapport == "2022-2023") {
					$rapport_query = "SELECT distinct concat(s.firstname, ' ',  s.lastname) as studentname, c.gemiddelde,
				v.vak_naam, c.rapnummer, c.klas, c.schooljaar
				FROM
				le_cijfers_ps c
				inner join
				le_vakken_ps v on
				c.vak = v.id
				inner join
				students s on
				s.id = c.studentid
				WHERE c.studentid = $id_student and schooljaar = '$schooljaar_rapport' order by c.schooljaar, c.rapnummer asc";
				} else {
					$rapport_query = "SELECT distinct concat(s.firstname, ' ',  s.lastname) as studentname, c.gemiddelde,
				v.volledigenaamvak, v.y_index, c.rapnummer, c.klas, c.schooljaar
				FROM
				le_cijfers c
				inner join
				le_vakken v on
				c.vak = v.id
				inner join
				students s on
				s.id = c.studentid
				WHERE c.studentid = $id_student and schooljaar = '$schooljaar_rapport' order by c.schooljaar, c.rapnummer, v.y_index asc;";
				}

				try {
					$DBCreds = new DBCreds();
					$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
					// $mysqli->set_charset('utf8');
					if ($select = $mysqli->prepare($rapport_query)) {
						if ($select->execute()) {
							$select->store_result();
							if ($select->num_rows > 0) {
								$result = 1;
								if ($schooljaar_rapport == "2022-2023") {
									$select->bind_result($studentName, $gemiddelde, $volledigenaamvak, $rapnummer, $klas, $schooljaar);
								} else {
									$select->bind_result($studentName, $gemiddelde, $volledigenaamvak, $y_index, $rapnummer, $klas, $schooljaar);
								}

								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_scooljaar, $schooljaar_rapport);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_docent, "DOCENT");


								$c = 0;

								while ($select->fetch()) {
									if ($c == 0) {

										$objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_klas, $klas);
										$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', $studentName);
									}

									if ($rapnummer == "1") {
										$colum_rapport = "B";
									} else if ($rapnummer == "2") {
										$colum_rapport = "C";
									} else {
										$colum_rapport = "D";
									}

									if (isset($y_index) && $y_index != null) {
										$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . (string)$y_index, $gemiddelde);
									} else {
										switch ($volledigenaamvak) {
											case 'technisch lezen':
												$fila = 9;
												break;
											case 'begrijpend lezen':
												$fila = 10;
												break;
											case 'schrijven':
												$fila = 13;
												break;
											case 'rekenen':
												$fila = 14;
												break;
											case 'taal':
												$fila = 21;
												break;
											case 'wereldorientatie':
												$fila = 28;
												break;
											case 'expressie':
												$fila = 31;
												break;
											case 'boekbespreking/spreekbeurt':
												$fila = 36;
												break;
											case 'engels':
												$fila = 37;
												break;
											case 'spaans':
												$fila = 38;
												break;
											case 'verkeer':
												$fila = 40;
												break;
											case 'maatschappijleer':
												$fila = 41;
												break;
										}
										$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . (string)$fila, $gemiddelde);
									}

									$c++;
								}

								$this->write_verzuim_data($objPHPExcel, $id_student, $schooljaar_rapport, "B");
								$this->write_houding_data($objPHPExcel, $id_student, $schooljaar_rapport, "");
								$this->write_avi_data($objPHPExcel, $id_student, $schooljaar_rapport, "");
							}
						}
					}
				} catch (Exception $e) {
					$error = $e->getMessage();
					echo $error;
				}
			}
		}
	}

	function write_verzuim_data($objPHPExcel, $id_student, $schooljaar, $colum_schooljaar)
	{

		$activesheet_index = 0;
		require_once("DBCreds.php");


		$verzuim_query = "SELECT SUM(telaat) AS telaat, SUM(absentie) AS absentie, SUM(LP) as lp, SUM(toetsinhalen) as toetsinhalen,
		SUM(uitsturen) AS uitsturen, SUM(huiswerk) AS huiswerk
		FROM le_verzuim where studentid = $id_student and schooljaar = '$schooljaar';";

		// print($verzuim_query);

		try {
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			$mysqli->set_charset('utf8');
			if ($select = $mysqli->prepare($verzuim_query)) {
				if ($select->execute()) {
					$select->store_result();
					if ($select->num_rows > 0) {
						$result = 1;
						$select->bind_result($telaat, $absentie, $lp, $toetsinhalen, $uitsturen, $huiswerk);

						while ($select->fetch()) {
							// $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_schooljaar."54",$huiswerk);
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_schooljaar . "56", $telaat);
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_schooljaar . "57", $absentie);
						}
					}
				}
			}
		} catch (Exception $e) {
			$error = $e->getMessage();
			echo $error;
		}
	}
	function write_houding_data($objPHPExcel, $id_student, $schooljaar, $colum_schooljaar)
	{

		$activesheet_index = 0;
		require_once("DBCreds.php");


		$houding_query = "SELECT id,
		schooljaar,	rapnummer,klas, h1, h2, h3, h4,	h5, h6, h7,	h8,	h9, h10, h11, h12, h13, h14,
		h15, h16, h17, h18, h19, h20, h21, h22,	h23, h24, h25 FROM le_houding WHERE studentid = ? and schooljaar = ? order by schooljaar, rapnummer asc ;";

		try {
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			$mysqli->set_charset('utf8');
			if ($select = $mysqli->prepare($houding_query)) {
				if ($select->bind_param("is", $id_student, $schooljaar)) {
					if ($select->execute()) {
						$select->store_result();
						if ($select->num_rows > 0) {
							$result = 1;
							$select->bind_result(
								$studentid,
								$schooljaar,
								$rapnummer,
								$klas,
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
								$h18,
								$h19,
								$h20,
								$h21,
								$h22,
								$h23,
								$h24,
								$h25
							);
							$c = 0;


							$counter_while = 0;
							$current_schooljaar = "";
							$last_schooljaar = "";



							$Leerhouding = "54";
							$Contact_Leerk = "43";
							$Contact_Leerl = "44";
							$Concentratie = "53";
							$Werktempo =  "51";
							$Nauwkeurigh =  "48";
							$Zelfvert = "45";
							$Doorzetting =  "49";
							$Zelfstand =  "50";
							$Verzorgin = "52";
							$Huiswerk = "55";
							$Muz = "32";
							$Tek = "34";
							$Hv = "33";
							$Lo = "35";
							$Inzicht = "20";
							$Spreken = "26";
							$Luisteren = "27";

							while ($select->fetch()) {

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
								if ($h14 == 1 || !isset($h14)) {
									$_h14 = "A";
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
								if ($h15 == 1 || !isset($h15)) {
									$_h15 = "A";
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
								if ($h16 == 1 || !isset($h16)) {
									$_h16 = "A";
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
								if ($h17 == 1 || !isset($h17)) {
									$_h17 = "A";
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
								if ($h18 == 1 || !isset($h18)) {
									$_h18 = "A";
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
								if ($h19 == 1 || !isset($h19)) {
									$_h19 = "A";
								}
								if ($h19 == 2) {
									$_h19 = "B";
								}
								if ($h19 == 3) {
									$_h19 = "C";
								}
								if ($h19 == 4) {
									$_h19 = "D";
								}
								if ($h19 == 5) {
									$_h19 = "E";
								}
								if ($h19 == 6) {
									$_h19 = "F";
								}
								if ($h20 == 1 || !isset($h20)) {
									$_h20 = "A";
								}
								if ($h20 == 2) {
									$_h20 = "B";
								}
								if ($h20 == 3) {
									$_h20 = "C";
								}
								if ($h20 == 4) {
									$_h20 = "D";
								}
								if ($h20 == 5) {
									$_h20 = "E";
								}
								if ($h20 == 6) {
									$_h20 = "F";
								}
								if ($h21 == 1 || !isset($h21)) {
									$_h21 = "A";
								}
								if ($h21 == 2) {
									$_h21 = "B";
								}
								if ($h21 == 3) {
									$_h21 = "C";
								}
								if ($h21 == 4) {
									$_h21 = "D";
								}
								if ($h21 == 5) {
									$_h21 = "E";
								}
								if ($h21 == 6) {
									$_h21 = "F";
								}
								if ($h22 == 1 || !isset($h22)) {
									$_h22 = "A";
								}
								if ($h22 == 2) {
									$_h22 = "B";
								}
								if ($h22 == 3) {
									$_h22 = "C";
								}
								if ($h22 == 4) {
									$_h22 = "D";
								}
								if ($h22 == 5) {
									$_h22 = "E";
								}
								if ($h22 == 6) {
									$_h22 = "F";
								}
								if ($h23 == 1 || !isset($h23)) {
									$_h23 = "A";
								}
								if ($h23 == 2) {
									$_h23 = "B";
								}
								if ($h23 == 3) {
									$_h23 = "C";
								}
								if ($h23 == 4) {
									$_h23 = "D";
								}
								if ($h23 == 5) {
									$_h23 = "E";
								}
								if ($h23 == 6) {
									$_h23 = "F";
								}
								if ($h24 == 1 || !isset($h24)) {
									$_h24 = "A";
								}
								if ($h24 == 2) {
									$_h24 = "B";
								}
								if ($h24 == 3) {
									$_h24 = "C";
								}
								if ($h24 == 4) {
									$_h24 = "D";
								}
								if ($h24 == 5) {
									$_h24 = "E";
								}
								if ($h24 == 6) {
									$_h24 = "F";
								}
								if ($h25 == 1 || !isset($h25)) {
									$_h25 = "A";
								}
								if ($h25 == 2) {
									$_h25 = "B";
								}
								if ($h25 == 3) {
									$_h25 = "C";
								}
								if ($h25 == 4) {
									$_h25 = "D";
								}
								if ($h25 == 5) {
									$_h25 = "E";
								}
								if ($h25 == 6) {
									$_h25 = "F";
								}


								if ($colum_schooljaar != "") {

									if ($colum_schooljaar == "B") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "B";
										} else if ($rapnummer == "2") {
											$colum_rapport = "C";
										} else {
											$colum_rapport = "D";
										}
									}
									if ($colum_schooljaar == "F") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "F";
										} else if ($rapnummer == "2") {
											$colum_rapport = "H";
										} else {
											$colum_rapport = "H";
										}
									}
									if ($colum_schooljaar == "J") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "J";
										} else if ($rapnummer == "2") {
											$colum_rapport = "K";
										} else {
											$colum_rapport = "L";
										}
									}
									if ($colum_schooljaar == "N") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "N";
										} else if ($rapnummer == "2") {
											$colum_rapport = "O";
										} else {
											$colum_rapport = "P";
										}
									}
									if ($colum_schooljaar == "R") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "R";
										} else if ($rapnummer == "2") {
											$colum_rapport = "S";
										} else {
											$colum_rapport = "T";
										}
									}
									if ($colum_schooljaar == "V") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "V";
										} else if ($rapnummer == "2") {
											$colum_rapport = "W";
										} else {
											$colum_rapport = "X";
										}
									}
									if ($colum_schooljaar == "Z") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "Z";
										} else if ($rapnummer == "2") {
											$colum_rapport = "AA";
										} else {
											$colum_rapport = "AB";
										}
									}
									if ($colum_schooljaar == "AD") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "AD";
										} else if ($rapnummer == "2") {
											$colum_rapport = "AE";
										} else {
											$colum_rapport = "AF";
										}
									}
								} else {

									if ($rapnummer == "1") {
										$colum_rapport = "B";
									} else if ($rapnummer == "2") {
										$colum_rapport = "C";
									} else {
										$colum_rapport = "D";
									}
								}

								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Leerhouding, $_h1);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Contact_Leerk, $_h2);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Contact_Leerl, $_h3);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Concentratie, $_h4);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Werktempo, $_h5);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Nauwkeurigh, $_h6);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Zelfvert, $_h7);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Doorzetting, $_h8);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Zelfstand, $_h9);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Verzorgin, $_h10);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Huiswerk, $_h11);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Muz, $_h12);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Tek, $_h13);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Hv, $_h14);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Lo, $_h15);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Inzicht, $_h16);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Spreken, $_h17);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . $Luisteren, $_h18);
							}
						}
					}
				}
			}
		} catch (Exception $e) {
			$error = $e->getMessage();
			echo $error;
		}
	}
	function write_avi_data($objPHPExcel, $id_student, $schooljaar_rapport, $colum_schooljaar)
	{

		$_default_avi = "B";

		$_default_period_1 = "B";
		$_default_period_2 = "C";
		$_default_period_3 = "D";
		$_colum_rapport = "";

		$activesheet_index = 0;
		require_once("DBCreds.php");


		$avi_query = "SELECT distinct level, period,schooljaar from avi where id_student = ? and schooljaar = ? order by schooljaar asc;";

		try {

			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			$mysqli->set_charset('utf8');
			if ($select = $mysqli->prepare($avi_query)) {
				if ($select->bind_param("is", $id_student, $schooljaar_rapport)) {
					if ($select->execute()) {
						$select->store_result();
						if ($select->num_rows > 0) {
							$result = 1;
							$select->bind_result($level, $rapnummer, $schooljaar);

							$c = 0;
							$counter_while = 0;
							$current_schooljaar = "";
							$last_schooljaar = "";

							while ($select->fetch()) {
								if ($colum_schooljaar != "") {
									if ($colum_schooljaar == "B") {
										$current_schooljaar = $schooljaar;
										if ($rapnummer == "1") {
											$colum_rapport = "B";
										} else if ($rapnummer == "2") {
											$colum_rapport = "C";
										} else {
											$colum_rapport = "D";
										}
									}
									if ($colum_schooljaar == "F") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "F";
										} else if ($rapnummer == "2") {
											$colum_rapport = "H";
										} else {
											$colum_rapport = "H";
										}
									}
									if ($colum_schooljaar == "J") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "J";
										} else if ($rapnummer == "2") {
											$colum_rapport = "K";
										} else {
											$colum_rapport = "L";
										}
									}
									if ($colum_schooljaar == "N") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "N";
										} else if ($rapnummer == "2") {
											$colum_rapport = "O";
										} else {
											$colum_rapport = "P";
										}
									}
									if ($colum_schooljaar == "R") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "R";
										} else if ($rapnummer == "2") {
											$colum_rapport = "S";
										} else {
											$colum_rapport = "T";
										}
									}
									if ($colum_schooljaar == "V") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "V";
										} else if ($rapnummer == "2") {
											$colum_rapport = "W";
										} else {
											$colum_rapport = "X";
										}
									}
									if ($colum_schooljaar == "Z") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "Z";
										} else if ($rapnummer == "2") {
											$colum_rapport = "AA";
										} else {
											$colum_rapport = "AB";
										}
									}
									if ($colum_schooljaar == "AD") {
										$current_schooljaar = $schooljaar;

										if ($rapnummer == "1") {
											$colum_rapport = "AD";
										} else if ($rapnummer == "2") {
											$colum_rapport = "AE";
										} else {
											$colum_rapport = "AF";
										}
									}
								} else {

									if ($rapnummer == "1") {
										$colum_rapport = "B";
									} else if ($rapnummer == "2") {
										$colum_rapport = "C";
									} else {
										$colum_rapport = "D";
									}
								}


								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport . "12", $level);
								$counter_while++;
							}
						}
					}
				}
			}
		} catch (Exception $e) {
			$error = $e->getMessage();
			echo $error;
		}
	}
}
