<?php

class leerlingen
{

	public $tablename_students = "students"; 		/* Covernotes table */
	public $debug = false;
	public $error = "";
	public $errormessage = "";


	/* First Name, Last Name, Email, Company, BrokerCode, Action(enable), Action(disable), ResetPassword */
	function liststudents()
	{
		$returnvalue = "";
		$user_permission = "";
		$sql_query = "";
		$htmlcontrol = "";

		mysqli_report(MYSQLI_REPORT_STRICT);


		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		if (isset($_SESSION["UserRights"]) && isset($_SESSION["User"]) && isset($_SESSION["BrokerCode"])) {
			$user_name = $_SESSION["User"];
			$broker_code = $_SESSION["BrokerCode"];
			$user_permission = $_SESSION["UserRights"];

			/* Replace all non letters with spaces */
			preg_replace("/[^A-Za-z]/", "", $broker_code);

			switch ($user_permission) {
				case "ADMIN":
					$sql_query = "select studentnumber,voornamen,achternaam,geslacht,geboortedatum,klas from students";
					break;
				case "BROKERADMIN":
					$sql_query = "select studentnumber,voornamen,achternaam,geslacht,geboortedatum,klas from students";
					break;
				default:
					$returnvalue = "no user to list";
					return $returnvalue;
					break;
			}
		} else {
			$returnvalue = "no user to list";
		}


		try {
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema);

			if ($select = $mysqli->prepare($sql_query)) {
				if ($select->bind_param("s", $user_name)) {
					if ($select->execute()) {
						$this->error = false;
						$result = 1;

						$select->bind_result($studentnumber, $voornamen, $achternaam, $geslacht, $geboortedatum, $klas);
						$select->store_result();

						if ($select->num_rows > 0) {
							$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">";
							$htmlcontrol .= "<thead><tr><th>Student#</th><th>Voornamen</th><th>Achternaam</th><th>Geslacht</th><th>Geboortedatum</th></th><th>klas</th><th>Details</th></tr></thead>";
							$htmlcontrol .= "<tbody>";

							while ($select->fetch()) {
								$fullusername = $firstname . " " . $lastname;

								$htmlcontrol .= "<td>" . htmlentities($studentnumber) . "</td><td>" . htmlentities($Voo) . "</td><td></td><td></td><td></td><td></td><td><a href='/leerling_details.php' class='link quaternary-color'>show more <i class='fa fa-angle-double-right quaternary-color'></i></a></td>	";
							}

							$htmlcontrol .= "</tbody>";
							$htmlcontrol .= "</table>";
						} else {
							$htmlcontrol .= "No results to show";
						}
					} else {
						/* error executing query */
						$this->error = true;
						$this->errormessage = $mysqli->error;
						$result = 0;

						if ($this->debug) {
							print "error executing query" . "<br />";
							print "error" . $mysqli->error;
						}
					}
				} else {
					/* error binding parameters */
					$this->error = true;
					$this->errormessage = $mysqli->error;
					$result = 0;

					if ($this->debug) {
						print "error binding parameters";
					}
				}
			} else {
				/* error preparing query */
				$this->error = true;
				$this->errormessage = $mysqli->error;
				$result = 0;

				if ($this->debug) {
					print "error preparing query";
				}
			}

			$returnvalue = $htmlcontrol;
			return $returnvalue;
		} catch (Exception $e) {
			$this->error = true;
			$this->errormessage = $e->getMessage();
			$result = 0;

			if ($this->debug) {
				print "exception: " . $e->getMessage();
			}
		}

		return $returnvalue;
	}


	function _generate_user_button($action, $username, $buttontext)
	{
		$returnvalue = "";

		$btnclass_enable = "btn btn-success btn-action";
		$btnclass_disable = "btn btn-danger btn-action";
		$btnclass_resetpwd = "btn btn-link resetpassword";

		switch ($action) {
			case "enable":
				$returnvalue = "<a href=\"#\" class=\"$btnclass_enable\" data-action-user=\"enable\" data-username=\"$username\">$buttontext</a>";
				break;
			case "disable":
				$returnvalue = "<a href=\"#\" class=\"$btnclass_disable\" data-action-user=\"disable\" data-username=\"$username\">$buttontext</a>";
				break;
			case "reset":
				$returnvalue = "<a href=\"#\" class=\"$btnclass_resetpwd\" data-toggle=\"modal\" data-target=\"#user\" data-username=\"$username\">$buttontext</a>";
				break;
			default:

				break;
		}

		return $returnvalue;
	}
}
