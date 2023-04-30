<?php

class leerlingen
{

	public $tablename_students = "students"; 		/* Covernotes table */
	public $debug = false;
	public $error = "";
	public $errormessage = "";


	/* First Name, Last Name, Email, Company, BrokerCode, Action(enable), Action(disable), ResetPassword */
	function liststudents($klas_in,$schoolid_in)
	{
		$returnvalue = "";
		$user_permission="";
		$sql_query = "";
		$htmlcontrol="";

		mysqli_report(MYSQLI_REPORT_STRICT);


		
		$sql_query = "select id,studentnumber,firstname,lastname,sex,dob,class from students where class=? and schoolid=?";
		
		

		try
		{
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema);
			
			if($select=$mysqli->prepare($sql_query))
			{
				if($select->bind_param("si",$klas_in,$schoolid_in))
				{
					if($select->execute())
					{
						$this->error = false;
						$result=1;

						$select->bind_result($studentid,$studentnumber,$voornamen,$achternaam,$geslacht,$geboortedatum,$klas);
						$select->store_result();

						if($select->num_rows > 0)
						{
							/*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */
							$htmlcontrol .= "<table id=\"dataRequest-student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

							$htmlcontrol .= "<thead><tr><th>Student#</th><th>Voornamen</th><th>Achternaam</th><th>Geslacht</th><th>Geboortedatum</th></th><th>klas</th><th>Details</th></tr></thead>";
							$htmlcontrol .= "<tbody>";

							while($select->fetch())
							{
							

								$htmlcontrol .="<tr><td>". htmlentities($studentnumber) ."</td><td>". htmlentities($voornamen) ."</td><td>". htmlentities($achternaam) ."</td><td>". htmlentities($geslacht) ."</td><td>". htmlentities($geboortedatum) ."</td><td>". htmlentities($klas) ."</td><td><a href=\"/scolpanos/studentendetail.php?=". htmlentities($studentid) ."\" class=\"link quaternary-color\">show more <i class=\"fa fa-angle-double-right quaternary-color\"></i></a></td></tr>";
                                 	
							}

							$htmlcontrol .= "</tbody>";
							$htmlcontrol .= "</table>";
						}
						else
						{
							$htmlcontrol .= "No results to show";
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
					/* error binding parameters */
					$this->error = true;
					$this->errormessage = $mysqli->error;
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
				$this->errormessage = $mysqli->error;
				$result=0;

				if($this->debug)
				{
					print "error preparing query";
				}
			}

			$returnvalue = $htmlcontrol;
			return $returnvalue;
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
}

?>