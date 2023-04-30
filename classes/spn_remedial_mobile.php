<?php

	class spn_remedial_mobile
	{
		public $exceptionvalue = "";
		public $mysqlierror = "";
		public $mysqlierrornumber = "";
		public $sp_create_remedial = "sp_create_remedial";
		public $sp_read_remedial = "sp_read_remedial";
		public $sp_update_remedial = "sp_update_remedial";
		public $sp_delete_remedial = "sp_delete_remedial";
		public $error = "";
		public $errormessage = "";
		public $sp_create_remedial_detail = "sp_create_remedial_detail";
		public $sp_read_remedial_detail = "sp_read_remedial_detail";
		public $sp_update_remedial_detail = "sp_update_remedial_detail";
		public $sp_delete_remedial_detail = "sp_delete_remedial_detail";

		function create_remedial($school_year,$begin_date, $end_date, $subject, $class, $docent, $id_student,$dummy)
		{
			$result = 0;
			if ($dummy)
			$result = 1;
			else
			{
				require_once("DBCreds.php");
				$DBCreds = new DBCreds();
				$status = 1;

				mysqli_report(MYSQLI_REPORT_STRICT);

				try
				{
					$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
					if($select =$mysqli->prepare("CALL " . $this->sp_create_remedial . " (?,?,?,?,?,?,?)"))
					{
						if($select->bind_param("ssssssi", $school_year, $begin_date, $end_date, $subject, $class, $docent, $id_student))
						{
							if($select->execute())
							{
								/* Audit by Caribe Developers */
								require_once ("spn_audit.php");
								$spn_audit = new spn_audit();
								$UserGUID = $_SESSION['UserGUID'];
								$spn_audit->create_audit($UserGUID, 'remedial','create remedial',appconfig::GetDummy());
								/* Need to check for errors on database side for primary key errors etc.*/
								$result = 1;
								$select->close();
								$mysqli->close();
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
							$result = 0;
							$this->mysqlierror = $mysqli->error;
							$this->mysqlierrornumber = $mysqli->errno;

							$result = $mysqli->error;

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
				catch(Exception $e)
				{
					$result = -2;
					$this->exceptionvalue = $e->getMessage();
					$result = $e->getMessage();
				}

				return $result;

			}
		}

		function get_remedial($schooljaar,$id_remedial, $id_student, $dummy)
		{
			$returnvalue = "";
			$sql_query = "";
			$htmlcontrol="";

			mysqli_report(MYSQLI_REPORT_STRICT);

			try
			{
				require_once("spn_utils.php");
				$u = new spn_utils();

				require_once("DBCreds.php");
				$DBCreds = new DBCreds();
				$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

				if($select =$mysqli->prepare("CALL " . $this->sp_read_remedial ."(?,?)"))
				{
					if ($select->bind_param("si",$schooljaar,$id_student))
					{
						if($select->execute())
						{
							/* Audit by Caribe Developers */
							require_once ("spn_audit.php");
							$spn_audit = new spn_audit();
							$UserGUID = $_SESSION['UserGUID'];
							$spn_audit->create_audit($UserGUID, 'remedial','get remedial',appconfig::GetDummy());
							$this->error = false;
							$result=1;
							$select->bind_result($id_remedial, $school_year,$begin_date, $end_date, $subject, $class, $docent);
							$select->store_result();

							if($select->num_rows > 0)
							{

								$htmlcontrol .= "<table id=\"dataRequest-remedial\" class=\"table table-striped\" data-table=\"yes\">";
								$htmlcontrol .= "<thead><tr><th>Subject</th><th>Class</th><th>School Year</th><th>Begin Date</th><th>End Date</th><th>Detail</th></tr></thead>";
								$htmlcontrol .= "<tbody>";

								while($select->fetch())
								{
									$htmlcontrol .= "<tr>";
									$htmlcontrol .= "<td>". htmlentities($subject) ."</td>";
									$htmlcontrol .= "<td>". htmlentities($class) ."</td>";
									$htmlcontrol .= "<td>". htmlentities($school_year) ."</td>";
									$htmlcontrol .= "<td>". $u->convertfrommysqldate(htmlentities($begin_date)) ."</td>";
									$htmlcontrol .= "<td>". $u->convertfrommysqldate(htmlentities($end_date));
									$htmlcontrol .= "<input type='hidden' name='id_remedial' value='". htmlentities($id_remedial) . "'><input type='hidden' name='remedial_docent' value='". htmlentities($docent) . "'>";
									$htmlcontrol .= "</td>";
									$htmlcontrol .= "<td><a id='link-remedial-detail' href='#'  onClick='go_remedial_detail(". htmlentities($id_remedial) .")' value='". htmlentities($id_remedial) ."' >DETAIL <i class=\"fa fa-angle-double-right quaternary-color\"></i></a></td>";
									$htmlcontrol .= "</tr>";
								}

								$htmlcontrol .= "</tbody>";
								$htmlcontrol .= "</table>";
							}
							else
							$htmlcontrol .= "No results to show";
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

			return $htmlcontrol;

		}

		function delete_remedial($id_remedial, $dummy)
		{
			$result = 0;
			if ($dummy)
			$result = 1;
			else
			{
				require_once("DBCreds.php");
				$DBCreds = new DBCreds();
				date_default_timezone_set("America/Aruba");
				$_DateTime = date("Y-m-d H:i:s");
				$status = 1;
				$result = 0;

				mysqli_report(MYSQLI_REPORT_STRICT);

				try
				{
					$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

					if($stmt=$mysqli->prepare("CALL ". $this->sp_delete_remedial ." (?)"))
					{
						if($stmt->bind_param("i",$id_remedial))
						{
							if($stmt->execute())
							{
								/* Audit by Caribe Developers */
								require_once ("spn_audit.php");
								$spn_audit = new spn_audit();
								$UserGUID = $_SESSION['UserGUID'];
								$spn_audit->create_audit($UserGUID, 'vakken','get vakken',appconfig::GetDummy());
								$result = 1;
								$stmt->close();
								$mysqli->close();
							}
							else
							{
								$result = 0;
								$this->mysqlierror = $mysqli->error;
								$this->mysqlierrornumber = $mysqli->errno;

								$result = $this->mysqlierrornumber;
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
				}
			}

			return $result;

		}

		function update_remedial($school_year, $begin_date, $end_date, $subject, $class, $docent, $id_remedial, $dummy)
		{
			$result = 0;
			if ($dummy)
			$result = 1;
			else
			{
				require_once("DBCreds.php");
				$DBCreds = new DBCreds();
				$status = 1;

				mysqli_report(MYSQLI_REPORT_STRICT);

				try
				{
					$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

					if($select =$mysqli->prepare("CALL " . $this->sp_update_remedial . " (?,?,?,?,?,?,?)"))
					{
						if($select->bind_param("ssssssi", $school_year, $begin_date, $end_date, $subject, $class, $docent, $id_remedial))
						{
							if($select->execute())
							{
								/* Audit by Caribe Developers */
								require_once ("spn_audit.php");
								$spn_audit = new spn_audit();
								$UserGUID = $_SESSION['UserGUID'];
								$spn_audit->create_audit($UserGUID, 'remedial','update remedial',appconfig::GetDummy());
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
			}

			return $result;
		}

		function create_remedial_detail($observation, $date, $id_remedial,$dummy)
		{
			$result = 0;
			if ($dummy)
			$result = 1;
			else
			{
				require_once("DBCreds.php");
				$DBCreds = new DBCreds();
				$status = 1;

				mysqli_report(MYSQLI_REPORT_STRICT);

				try
				{
					$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

					if($select =$mysqli->prepare("CALL " . $this->sp_create_remedial_detail . " (?,?,?)"))
					{
						if($select->bind_param("ssi", $observation, $date,  $id_remedial))
						{
							if($select->execute())
							{
								/* Audit by Caribe Developers */
								require_once ("spn_audit.php");
								$spn_audit = new spn_audit();
								$UserGUID = $_SESSION['UserGUID'];
								$spn_audit->create_audit($UserGUID, 'remedial','create remedial detail',appconfig::GetDummy());
								$result = 1;
								$select->close();
								$mysqli->close();
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

				return $result;

			}

		}

		/// Sumary of list_remedial_detail
		/// List detail of a remedial Student
		/// Paramenters in: id remedial detail (optional), id remedial, dummy
		/// Parametres out: html table
		function get_remedial_detail($id_remedial_detail, $id_remedial, $dummy)
		{
			$returnvalue = "";
			$sql_query = "";
			$htmlcontrol="";

			mysqli_report(MYSQLI_REPORT_STRICT);

			try
			{
				require_once("spn_utils.php");
				$u = new spn_utils();

				require_once("DBCreds.php");
				$DBCreds = new DBCreds();
				$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

				if ($select =$mysqli->prepare("CALL " . $this->sp_read_remedial_detail ."(?)"))
				{
					if ($select->bind_param("i", $id_remedial))
					{
						if($select->execute())
						{
							/* Audit by Caribe Developers */
							require_once ("spn_audit.php");
							$spn_audit = new spn_audit();
							$UserGUID = $_SESSION['UserGUID'];
							$spn_audit->create_audit($UserGUID, 'remedial','get remedial detail',appconfig::GetDummy());
							$this->error = false;
							$result = 1;
							$select->bind_result($id_remedial_detail, $date, $observation, $id_remedial);
							$select->store_result();

							if($select->num_rows > 0)
							{
								$htmlcontrol .= "<table id=\"dataRequest-remedial-detail\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
								$htmlcontrol .= "<thead><tr><th>Date</th><th>Observation</th></tr></thead>";
								$htmlcontrol .= "<tbody>";

								while($select->fetch())
								{
									$htmlcontrol .= "<tr>";
									$htmlcontrol .= "<td>". $u->convertfrommysqldate(htmlentities($date)) ."</td>";
									$htmlcontrol .= "<td>";
									$htmlcontrol .=  htmlentities($observation);
									$htmlcontrol .= "<input type='hidden' name='id_remedial_detail' value='". htmlentities($id_remedial_detail) . "'><input type='hidden' name='id_remedial' value='". htmlentities($id_remedial) . "'>";
									$htmlcontrol .= "</td>";
									$htmlcontrol .= "</td>";
									$htmlcontrol .= "</tr>";
								}

								$htmlcontrol .= "</tbody>";
								$htmlcontrol .= "</table>";
							}
							else
							$htmlcontrol .= "No results to show";
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

			return $htmlcontrol;

		}

		/// Sumary of delete_remedial_detail
		/// Delete a remedial detail
		/// Paramenters in: id remedial detail
		/// Parametres out:
		function delete_remedial_detail($id_remedial_detail)
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			date_default_timezone_set("America/Aruba");
			$_DateTime = date("Y-m-d H:i:s");

			$result = 0;
			$status = 1;

			mysqli_report(MYSQLI_REPORT_STRICT);

			try
			{
				$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

				if($stmt=$mysqli->prepare("CALL ". $this->sp_delete_remedial_detail ." (?)"))
				{
					if($stmt->bind_param("i",$id_remedial_detail))
					{
						if($stmt->execute())
						{
							/* Audit by Caribe Developers */
							require_once ("spn_audit.php");
							$spn_audit = new spn_audit();
							$UserGUID = $_SESSION['UserGUID'];
							$spn_audit->create_audit($UserGUID, 'remedial','delete remedial detail',appconfig::GetDummy());
							$result = 1;
							$stmt->close();
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
					$result = 0;
					$this->mysqlierror = $mysqli->error;
					$this->mysqlierrornumber = $mysqli->errno;
				}
			}
			catch(Exception $e)
			{
				$result = -2;
				$this->exceptionvalue = $e->getMessage();
			}

			return $result;

		}

		/// Sumary of update_remedial_detail
		/// Update a remedial detail
		/// Paramenters in: date, id remedial detail
		/// Parametres out:
		function update_remedial_detail($observation, $date, $id_remedial_detail, $dummy)
		{
			$result = 0;
			if ($dummy)
			$result = 1;
			else
			{
				require_once("DBCreds.php");
				$DBCreds = new DBCreds();
				date_default_timezone_set("America/Aruba");
				$_DateTime = date("Y-m-d H:i:s");
				$status = 1;

				mysqli_report(MYSQLI_REPORT_STRICT);

				try
				{
					$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

					if ($select =$mysqli->prepare("CALL " . $this->sp_update_remedial_detail . " (?,?,?)"))
					{
						if ($select->bind_param("ssi", $observation, $date, $id_remedial_detail))
						{
							if ($select->execute())
							{
								/* Audit by Caribe Developers */
								require_once ("spn_audit.php");
								$spn_audit = new spn_audit();
								$UserGUID = $_SESSION['UserGUID'];
								$spn_audit->create_audit($UserGUID, 'vakken','get vakken',appconfig::GetDummy());
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

				return $result;
			}
		}
	}
