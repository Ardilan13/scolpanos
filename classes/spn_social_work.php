<?php


	class spn_social_work
	{

		public $exceptionvalue = "";
		public $mysqlierror = "";
		public $mysqlierrornumber = "";

		public $sp_create_social_work = "sp_create_social_work";
		public $sp_read_social_work = "sp_read_social_work";
		public $sp_update_social_work = "sp_update_social_work";
		public $sp_delete_social_work = "sp_delete_social_work";


		public $debug = true;
		public $error = "";
		public $errormessage = "";

		function create_social_work($school_year, $date,$reason,$class,$observations,$pending,$id_student, $dummy)
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
					// $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

					if($stmt=$mysqli->prepare("CALL " . $this->sp_create_social_work . " (?,?,?,?,?,?,?)"))
					{

						if($stmt->bind_param("sssssii", $school_year, $date, $reason, $class,$observations, $pending, $id_student))
						{
							if($stmt->execute())
							{
								$result = 1;
								$stmt->close();
								$mysqli->close();
								/* Audit by Caribe Developers */
								require_once ("spn_audit.php");
								$spn_audit = new spn_audit();
								$UserGUID = $_SESSION['UserGUID'];
								$spn_audit->create_audit($UserGUID, 'social work','social work create',appconfig::GetDummy());

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
				}
			}
			return $result;
		}

		function update_social_work($id_social_work, $school_year, $date, $reason, $class, $observations,$pending, $dummy)
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
					// TODO: DELETE
					// $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

					if($stmt=$mysqli->prepare("CALL " . $this->sp_update_social_work . " (?,?,?,?,?,?,?)"))
					{

						if($stmt->bind_param("isssssi", $id_social_work, $school_year, $date, $reason, $class,$observations, $pending))
						{
							if($stmt->execute())
							{
								$result = 1;
								$stmt->close();
								$mysqli->close();
								/* Audit by Caribe Developers */
								require_once ("spn_audit.php");
								$spn_audit = new spn_audit();
								$UserGUID = $_SESSION['UserGUID'];
								$spn_audit->create_audit($UserGUID, 'social work','social work update',appconfig::GetDummy());
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
				}
			}
			return $result;
		}

		function get_social_work($schooljaar, $id_social_work, $id_student, $dummy)
		{
			require_once("spn_utils.php");

			$sql_query = "";
			$htmlcontrol="";
			$result = 0;
			$returnvalue = "";

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
					// $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);
					$query = "SELECT * FROM social_work sw WHERE sw.id_student = ?;";
					if($select = $mysqli->prepare($query))
					{
						if($select->bind_param("i",$id_student))
						{
							if($select->execute())
							{
								$this->error = false;
								$result = 1;

								$select->bind_result($id_social_work, $school_year, $date, $reason, $id_class, $observations, $pending, $id_student);
								$select->store_result();
								/* Audit by Caribe Developers */
								require_once ("spn_audit.php");
								$spn_audit = new spn_audit();
								$UserGUID = $_SESSION['UserGUID'];
								$spn_audit->create_audit($UserGUID, 'social work','get social work',appconfig::GetDummy());

								if ($id_social_work == "" || $id_social_work == 0  || $id_social_work == "null")
								{
									// List all the Social_Works
									if($select->num_rows > 0)
									{
										$htmlcontrol .= "<table id=\"dataRequest-social_work" ."\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
										$htmlcontrol .= "<thead><tr><th>School Year</th><th>Date</th><th>Reason</th><th>Class</th><th>Pending</th></tr></thead>";
										$htmlcontrol .= "<tbody>";

										$u = new spn_utils();

										while($select->fetch())
										{
											$htmlcontrol .= "<tr>";
											$htmlcontrol .= "<td>". htmlentities($school_year) ."</td>";
											$htmlcontrol .= "<td>". $u->convertfrommysqldate(htmlentities($date)) ."</td>";
											$htmlcontrol .= "<td>". htmlentities(utf8_encode($reason)) ."</td>";
											$htmlcontrol .= "<td>". htmlentities($id_class) ."</td>";
											$htmlcontrol .="<td>". ($pending ? "Pending" : "No pending");
											$htmlcontrol .= "<input type='hidden' name='observations_val' value='". htmlentities(utf8_encode($observations)) . "'>";
											$htmlcontrol .= "<input type='hidden' name='id_social_work' value='". htmlentities($id_social_work) . "'></td>";
											$htmlcontrol .= "</tr>";

										}

										$htmlcontrol .= "</tbody>";
										$htmlcontrol .= "</table>";
									}
									else
									{
										$htmlcontrol .= "No results to show";
									}
									$returnvalue = $htmlcontrol;
								}
								else
								{
									// Obtain a Social Work
									if($select->num_rows > 0)
									{
										while($select->fetch())
										{
											$htmlcontrol .= "<tr>";
											$htmlcontrol .= "<td>". htmlentities($school_year) ."</td>";
											$htmlcontrol .= "<td>". htmlentities($date) ."</td>";
											$htmlcontrol .= "<td>". htmlentities($reason) ."</td>";
											$htmlcontrol .= "<td>". htmlentities($id_class) ."</td>";
											$htmlcontrol .="<td>". htmlentities($pending) ."</td>";
											$htmlcontrol .= "<td>". htmlentities($id_social_work) ."</td>";
											$htmlcontrol .= "</tr>";
										}
									}
									else
									$htmlcontrol .= "No results to show";
								}
								$returnvalue = $htmlcontrol;
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
							$result = 0;
							$this->mysqlierror = $mysqli->error;
							$this->mysqlierrornumber = $mysqli->errno;
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

			if($returnvalue == null || $returnvalue == ''){
				$returnvalue = 'HOLA';
			}

			return $returnvalue;

		}

		function delete_social_work($id_social_work, $dummy)
		{
			$result = 0;

			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			date_default_timezone_set("America/Aruba");
			$_DateTime = date("Y-m-d H:i:s");
			$status = 1;

			mysqli_report(MYSQLI_REPORT_STRICT);

			try
			{
				if ($dummy)
				$result = 1;
				else
				{

					$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
					// TODO: DELETE
					// $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

					if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_social_work . " (?)"))
					{

						if($stmt->bind_param("i", $id_social_work))
						{
							if($stmt->execute())
							{
								$result = 1;
								$stmt->close();
								$mysqli->close();
								/* Audit by Caribe Developers */
								require_once ("spn_audit.php");
								$spn_audit = new spn_audit();
								$UserGUID = $_SESSION['UserGUID'];
								$spn_audit->create_audit($UserGUID, 'social work','delete social work',appconfig::GetDummy());
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

?>
