<?php
class spn_controls
{

	/* Application settings */
	public $debug = true;									/* Debug variable, set to true -> enable debug messages, set to false -> disable debug messages */
	public $error = false;
	public $showphperrors = true;
	public $errormsg_internal = "";
	public $maintenancemode = false;						/* User will be redirected to maintenance mode if this is set to true */
	public $friendlyerrormessage = "";
	public $apperrorpage = "erorrpage.php";					/* Application error page */
	/* Application settings */

	/* Tablenames */
	private $tablename_app_vakken = "le_vakken";
	private $tablename_app_studenten = "students";
	private $tablename_app_subscriptiontype = "app_subscriptiontype";
	private $tablename_app_subscriptioncategory = "app_subscriptioncategory";
	/* Tablenames */


	/* class constructor */
	function __construct()
	{
		if($this->showphperrors)
		{
			error_reporting(-1);
		}
		else
		{
			error_reporting(0);
		}
	}
	/* class constructor */
	/*  Get klassen types in JSON format for dropdown */
	function getdistinctklassen_json($schoolid,$class)
	{
		$json = array();
		$result=false;  /* function will return this variable, true if auth successful, false if auth unsuccessful */
		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			$query_HS = false;
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			if($class == "ALL")
			{
				$sql_query_text = "select distinct v.Klas from" . chr(32) . $this->tablename_app_vakken . chr(32) . "v where v.SchoolID = ? order by v.Klas asc;";
			}
			else
			{
				$sql_query_text=  "select distinct v.Klas from" . chr(32) . $this->tablename_app_vakken . chr(32) .  "v where v.SchoolID = ? and v.Klas = ? order by v.Klas asc;";
			}
			if ($_SESSION['SchoolType']==2 && $_SESSION["UserRights"] == 'DOCENT'){
				$sql_query_text=  "SELECT distinct klas FROM user_hs where SchoolID = ".$_SESSION['SchoolID']." and user_guid = '".$_SESSION['UserGUID']."' order by Klas asc;";
				$query_HS = true;
			}

			if($select=$mysqli->prepare($sql_query_text))
			{
				if (!$query_HS){
					if($class == "ALL"){
						if($select->bind_param("i",$schoolid)){
							if($select->execute()){
								$select->store_result();
								$select->bind_result($klas);
								$count=$select->num_rows;
								if($count >= 1)
								{
									while($row = $select->fetch())
									{
										$json[] = array("klas"=>$klas);
									}
									$json[] = array("klas"=>"SN");
								}
								else
								{
									/* No klassen found */
									$json[] = array("klas"=>"NONE");
									$this->errormsg_internal=  "no klasssen exist";
									if($this->debug)
									{
										print "no klassen exist";
									}
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
					else{
						if($select->bind_param("is",$schoolid,$class)){
							if($select->execute()){
								$select->store_result();
								$select->bind_result($klas);
								$count=$select->num_rows;
								if($count >= 1)
								{
									while($row = $select->fetch())
									{
										$json[] = array("klas"=>$klas);
									}
									$json[] = array("klas"=>"SN");
								}
								else
								{
									/* No klassen found */
									$json[] = array("klas"=>"NONE");
									$this->errormsg_internal=  "no klasssen exist";
									if($this->debug)
									{
										print "no klassen exist";
									}
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
						else{
							$result = 0;
							$this->mysqlierror = $mysqli->error;
							$this->mysqlierrornumber = $mysqli->errno;
						}
					}
				}
				else{
					if($select->execute()){
						$select->store_result();
						$select->bind_result($klas);
						$count=$select->num_rows;
						if($count >= 1)
						{
							while($row = $select->fetch())
							{
								$json[] = array("klas"=>$klas);
							}
							$json[] = array("klas"=>"SN");
						}
						else
						{
							/* No klassen found */
							$json[] = array("klas"=>"NONE");
							$this->errormsg_internal=  "no klasssen exist";
							if($this->debug)
							{
								print "no klassen exist";
							}
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
			/* handle error authenticating
			maybe db offline or something else */
			return $result;
		}
	}
	/*  Get klassen types */
	function getklassen($schoolid,$class)
	{
		$result="";
		$htmlcontrol="";
		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
			{
				$sql_query_text = "select distinct v.Klas from" . chr(32) . $this->tablename_app_vakken . chr(32) . "v where v.SchoolID = ? order by v.Klas asc;";
				if($select=$mysqli->prepare($sql_query_text))
				{
					if($select->bind_param("i",$schoolid))
					{
						if($select->execute())
						{
							$select->store_result();
							$select->bind_result($klas);
							$count=$select->num_rows;
							if($count >= 1)
							{
								if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
								{
									$htmlcontrol .= "<select id=\"cijfers_klassen_lijst\" name=\"cijfers_klassen_lijst\" class=\"form-control\">";
									$htmlcontrol .= "<option id=\"selectOneDocent\">Select One Klassen</option>";
								}
								while($row = $select->fetch())
								{
									$htmlcontrol .= "<option value=". htmlentities($klas) ." >". htmlentities($klas) ."</option>";
								}
							}
							else
							{
								/* No klassen found */
								$this->errormsg_internal=  "no klasssen exist";
								if($this->debug)
								{
									print "no klassen exist";
								}
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
					$result = 0;
					$this->mysqlierror = $mysqli->error;
					$this->mysqlierrornumber = $mysqli->errno;
				}
			}
			else
			{
				$sql_query_text=  "select distinct v.Klas from" . chr(32) . $this->tablename_app_vakken . chr(32) .  "v where v.SchoolID = ? and v.Klas = ? order by v.Klas asc;";
				if($select=$mysqli->prepare($sql_query_text))
				{
					if($select->bind_param("is",$schoolid,$class))
					{
						if($select->execute())
						{
							$select->store_result();
							$select->bind_result($klas);
							$count=$select->num_rows;
							if($count >= 1)
							{
								while($row = $select->fetch())
								{
									$htmlcontrol .= "<input class=\"form-control\" type=\"text\" name=\"cijfers_klassen_lijst\" id=\"cijfers_klassen_lijst\" value=\"". htmlentities($klas) ."\" readonly />";
								}
							}
							else
							{
								/* No klassen found */
								$this->errormsg_internal=  "no klasssen exist";
								if($this->debug)
								{
									print "no klassen exist";
								}
							}
							$result = 1;
							$select->close();
							$mysqli->close();
						}
						else{
							$result = 0;
							$this->mysqlierror = $mysqli->error;
							$this->mysqlierrornumber = $mysqli->errno;
						}
					}
					else{
						$result = 0;
						$this->mysqlierror = $mysqli->error;
						$this->mysqlierrornumber = $mysqli->errno;
					}
				}
				else{
					$result = 0;
					$this->mysqlierror = $mysqli->error;
					$this->mysqlierrornumber = $mysqli->errno;
				}
			}
			$result = $htmlcontrol;
			return $result;
		}
		catch (Exception $e)
		{
			$this->error = true;
			/* handle error authenticating
			maybe db offline or something else */
			return $result;
		}
	}
	/*  Get klassen types in JSON format for dropdown */
	function getdistinctvakken_json($schoolid,$class)
	{
		$json = array();
		$result=false;  /* function will return this variable, true if auth successful, false if auth unsuccessful */
		mysqli_report(MYSQLI_REPORT_STRICT);
		try{
			$query_HS = false;
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			if($class == "ALL"){
				$sql_query_text = "select distinct v.ID, v.volledigenaamvak from" . chr(32) . $this->tablename_app_vakken . chr(32) . "v where v.SchoolID = ? and volgorde <> 99 order by v.volgorde asc;";
			}
			else{
				$sql_query_text = "select distinct v.ID, v.volledigenaamvak from" . chr(32) . $this->tablename_app_vakken . chr(32) . "v where v.SchoolID = ? and v.Klas = ? and volgorde <> 99 order by v.volgorde asc;";
			}
			if ($_SESSION['SchoolType']==2 && $_SESSION["UserRights"]=='DOCENT'){
				$schooljaar = $_SESSION['SchoolJaar'];
				$user = $_SESSION["UserGUID"];
				$tutor = "SELECT id from user_hs where schoolid = '$schoolid'  and klas = '$class' and Schooljaar = '$schooljaar' and tutor='Yes' AND user_GUID = '$user';";
				if($result = $mysqli->query($tutor)){
					$IsTutor = $result->num_rows;
				} else {
					$IsTutor = 0;
				}

				
				if ($IsTutor >= 1){
					$sql_query_text = "select distinct v.ID, v.volledigenaamvak from" . chr(32) . $this->tablename_app_vakken . chr(32) . "v where v.SchoolID = ? and v.Klas = ? and volgorde <> 99 order by v.volgorde asc;";
				}
				else{
					$sql_query_text=  "SELECT v.id, v.volledigenaamvak FROM le_vakken v inner join user_hs u on v.klas = u.klas and v.SchoolID = u.SchoolID and u.vak = v.id where user_guid = ? and v.klas = ? and volgorde <> 99 order by v.volgorde asc ";
					$query_HS = true;

				}
			}
			if($select=$mysqli->prepare($sql_query_text)){
				if (!$query_HS){
					if($class == "ALL"){
						if($select->bind_param("i",$schoolid)){
							if($select->execute()){
								$select->store_result();
								$select->bind_result($id,$volledigenaamvak);
								$count=$select->num_rows;
								if($count >= 1){
									while($row = $select->fetch()){
										$json[] = array("id"=>$id,"vak"=>$volledigenaamvak);
									}
								}
								else{
									/* No klassen found */
									$json[] = array("id"=>"NONE","vak"=>"NONE");
									$this->errormsg_internal=  "no vakken exist";
									if($this->debug){
										print "no vakken exist";
									}
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
					else{
						if($select->bind_param("is",$schoolid,$class)){
							if($select->execute()){
								$select->store_result();
								$select->bind_result($id,$volledigenaamvak);
								$count=$select->num_rows;

								if($count >= 1){
									while($row = $select->fetch()){
										$json[] = array("id"=>$id,"vak"=>$volledigenaamvak);
									}
								}
								else{
									/* No klassen found */
									$json[] = array("id"=>"NONE","vak"=>$volledigenaamvak);
									$this->errormsg_internal=  "no vakken exist";
									if($this->debug){
										print "no vakken exist";
									}
								}

								$result = 1;
								$select->close();
								$mysqli->close();
							}
							else{
								$result = 0;
								$this->mysqlierror = $mysqli->error;
								$this->mysqlierrornumber = $mysqli->errno;
							}
						}
						else{
							$result = 0;
							$this->mysqlierror = $mysqli->error;
							$this->mysqlierrornumber = $mysqli->errno;
						}
					}
				}
				else{
					if($select->bind_param("ss",$_SESSION['UserGUID'], $class)){
						if($select->execute()){
							$select->store_result();
							$select->bind_result($id,$volledigenaamvak);
							$count=$select->num_rows;

							if($count >= 1){
								while($row = $select->fetch()){
									$json[] = array("id"=>$id,"vak"=>$volledigenaamvak);
								}
							}
							else{
								/* No klassen found */
								$json[] = array("id"=>"NONE","vak"=>$volledigenaamvak);
								$this->errormsg_internal=  "no vakken exist";
								if($this->debug){
									print "no vakken exist";
								}
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
					else{
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
			/* handle error authenticating
			maybe db offline or something else */
			return $result;
		}


	}


	/*  Get studenten in JSON format for dropdown */
	function getdistinctstudenten_json($schoolid,$classid)
	{
		$json = array();
		$result=false;  /* function will return this variable, true if auth successful, false if auth unsuccessful */

		mysqli_report(MYSQLI_REPORT_STRICT);

		try
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if($classid === "ALL")
			{
				$sql_query_text = "select distinct s.ID, s.FirstName, s.LastName from" . chr(32) . $this->tablename_app_studenten . chr(32) . "s where s.SchoolID = ? and s.Status = 1 order by s.ID asc;";
			}
			else
			{
				$sql_query_text = "select distinct s.ID, s.FirstName, s.LastName from" . chr(32) . $this->tablename_app_studenten . chr(32) . "s where s.SchoolID = ? and s.Class = ? and s.Status = 1 order by s.ID asc;";
			}


			if($select=$mysqli->prepare($sql_query_text))
			{
				if($classid === "ALL")
				{
					if($select->bind_param("i",$schoolid))
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
									$json[] = array("id"=>$id,"student"=>$firstname . chr(32) . $lastname);
								}
							}
							else
							{
								/* No students found */
								$json[] = array("id"=>"0","student"=>"NONE");
								$this->errormsg_internal=  "no students exist";
								if($this->debug)
								{
									print "no students exist";
								}
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
					if($select->bind_param("is",$schoolid,$classid))
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
									$json[] = array("id"=>$id,"student"=>$firstname . chr(32) . $lastname);
								}
							}
							else
							{
								/* No klassen found */
								$json[] = array("id"=>"0","student"=>"NONE");
								$this->errormsg_internal=  "no students exist";
								if($this->debug)
								{
									print "no klassen exist";
								}
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
			/* handle error
			maybe db offline or something else */
			return $result;
		}


	}



	/*  Get studenten in JSON format for TYPEAHEAD */
	function getdistinctstudenten_typeahead_json($schoolid,$classid)
	{
		$json = array();
		$result=false;  /* function will return this variable, true if auth successful, false if auth unsuccessful */

		mysqli_report(MYSQLI_REPORT_STRICT);

		try
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if($classid === "ALL")
			{
				$sql_query_text = "select distinct s.studentnumber, s.FirstName, s.LastName from" . chr(32) . $this->tablename_app_studenten . chr(32) . "s where s.SchoolID = ? and s.Status = 1 order by s.ID asc;";
			}
			else
			{
				$sql_query_text = "select distinct s.studentnumber, s.FirstName, s.LastName from" . chr(32) . $this->tablename_app_studenten . chr(32) . "s where s.SchoolID = ? and s.Class = ? and s.Status = 1 order by s.ID asc;";
			}


			if($select=$mysqli->prepare($sql_query_text))
			{
				if($classid === "ALL")
				{
					if($select->bind_param("i",$schoolid))
					{
						if($select->execute())
						{
							$select->store_result();
							$select->bind_result($studentnumber,$firstname,$lastname);
							$count=$select->num_rows;

							if($count >= 1)
							{
								while($row = $select->fetch())
								{
									$json[] = $studentnumber ."-". $firstname . chr(32) . $lastname;
								}
							}
							else
							{
								/* No students found */
								// $json[] = array("id"=>"0","student"=>"NONE");
								$this->errormsg_internal=  "no students exist";
								if($this->debug)
								{
									$json[] = 'No students found';
								}
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
					if($select->bind_param("is",$schoolid,$classid))
					{
						if($select->execute())
						{
							$select->store_result();
							$select->bind_result($studentnumber, $firstname,$lastname);
							$count=$select->num_rows;

							if($count >= 1)
							{
								while($row = $select->fetch())
								{
									$json[] = $studentnumber ."-". $firstname . chr(32) . $lastname;
								}
							}
							else
							{
								/* No klassen found */
								$json[] = array("id"=>"0","student"=>"NONE");
								$this->errormsg_internal=  "no students exist";
								if($this->debug)
								{
									print "no klassen exist";
								}
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
			/* handle error
			maybe db offline or something else */
			return $result;
		}
	}
}
