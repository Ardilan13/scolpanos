<?php

class spn_authentication
{
	/* Password hashing settings */
	public $hashmethod = "sha512";	/* Hashing algorithm */
	public $sp_get_leerling_by_studentnumber_securepin = "sp_get_leerling_by_studentnumber_securepin";
	/* Password hashing settings */

	/* */
	public $passwordreset_tokenhashmethod = "sha512";
	public $passwordreset_salt = "%23ifnzsY&*@pajdbT^@";	/* */

	/* Authentication settings */
	public $application_id = "AW-SCOLPANOS-V2.0";	/* This is the application ID to distinguish from multiple apps using same login code */
	public $loginretries = 5; 								/* Login attempts user will be allowed */
	public $accountlocked = false;							/* ??? Variable is not used - EL ??? */
	/* Authentication settings */

	/* Application settings */
	public $debug = false;									/* Debug variable, set to true -> enable debug messages, set to false -> disable debug messages */
	public $error = true;
	public $errormsg_internal = "";
	public $maintenancemode = false;						/* User will be redirected to maintenance mode if this is set to true */
	public $username_casesensitive = false; 				/* set to true if username should be evaluated case-sensitive */
	public $redirectafterlogin = false;
	public $redirecttoapperrorpage = false;

	public $password_settings_length = 10;					/* Password length */
	public $password_settings_min_number = 1;				/*  Password must include minimal one number */
	public $password_settings_min_lowercase_letter = 1;		/* Password must include minimal one number */
	public $friendlyerrormessage = "";
	public $loginpage = "login.php";
	public $mainpage = "home.php";
	public $maintenancepage = "maintenance.php";
	public $changepwdpage = "changepass.php"; 				/* Change password page */
	public $apperrorpage = "erorrpage.php";					/* Application error page */
	/* Application settings */

	/* Tablenames */
	public $tablename_app_useraccounts = "app_useraccounts";
	public $tablename_sec_login_attempts = "sec_login_attempts";
	public $tablename_schools = "schools";
	/* Tablenames */


	function __construct()
	{
		if ($this->debug) {
			error_reporting(-1);
		} else {
			error_reporting(0);
		}
	}


	function HashPassword($password, $salt = "")
	{
		/* 	This function hashes the password
			Function expects plaintext password and salt */
		try {
			//check if salt is empty
			if (empty($salt)) {
				$returnhash = hash($this->hashmethod, $password);
			} else {
				$returnhash = hash($this->hashmethod, $salt . $password);
			}
		} catch (Exception $e) {
			/* throw an exception */
			$this->error = true;
			$returnhash = "";
			return $returnhash;
		}

		return $returnhash;
	}

	function CreateSalt()
	{
		/* Create random salt */
		$bytes = random_bytes(20);
		return $bytes;
		/* 		return base64_encode(mcrypt_create_iv(20, MCRYPT_DEV_URANDOM));
 */
	}

	function CreateUser($username, $password, $firstname, $lastname, $brokercode, $changepwdonlogin, $accountenabled)
	{
		require_once("spn_utils.php");
		$utils = new spn_utils();
		$userguid = $utils->CreateGUID();
		$_salt = $this->CreateSalt();
		$_password = $this->HashPassword($password, $_salt);

		$authretriesleft = $this->loginretries;
		$lockedout = 0;
		$result = 0;

		/* Get Change Password on login values from the form */
		if ($changepwdonlogin == "YES") {
			$_changepwdonlogin = 1;
		} else {
			$_changepwdonlogin = 0;
		}

		/* Get Account state values from the form */
		if ($accountenabled == "ENABLED") {
			$_accountenabled = 1;
		} else {
			$_accountenabled = 0;
		}

		$_portalversion = 2;
		$passwordresettoken = "";
		$userrights = "USER";
		$createddatetime = $utils->getdatetime("UTC");
		$lastpwdchangedatetime = $createddatetime;



		/* print variables for debugging */
		if ($this->debug) {
			print "User " . htmlentities($username) . "created";
			print "guid: " . htmlentities($userguid) . "<br />";
			print "pwd: " . htmlentities($_password) . "<br />";
			print "salt: " . htmlentities($_salt) . "<br />";
			print "auths left: " . htmlentities($authretriesleft) . "<br />";
			print "changepwdonlogin: " . htmlentities($_changepwdonlogin) . "<br />";
			print "accountstate: " . htmlentities($_accountenabled) . "<br />";
			print "userrights: " . htmlentities($userrights) . "<br />";
			print "createddatetime: " . htmlentities($createddatetime) . "<br />";
		}

		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			//global $username, $password, $dbaddr, $schema;
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if ($insert = $mysqli->prepare("insert into " . $this->tablename_app_useraccounts . " (userguid,email,passwd,salt,firstname,lastname,brokercode,authretriesleft,lockedout,changepwdonlogin,passwordresettoken,accountenabled,userrights,portalversion,createddatetime,lastpwdchangedatetime) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);")) {

				if ($insert->bind_param("sssssssiiisisiss", $userguid, $username, $_password, $_salt, $firstname, $lastname, $_brokercode, $authretriesleft, $lockedout, $_changepwdonlogin, $passwordresettoken, $_accountenabled, $userrights, $_portalversion, $createddatetime, $lastpwdchangedatetime)) {
					if ($insert->execute()) {
						$this->error = false;
						$result = 1;
					} else {
						/* error executing query */
						$this->error = true;
						$result = 0;

						if ($this->debug) {
							print "error executing query" . "<br />";
							print "error" . $mysqli->error;
						}
					}
				} else {
					/* error binding parameters */
					$this->error = true;
					$result = 0;

					if ($this->debug) {
						print "error binding parameters";
					}
				}
			} else {
				/* error preparing query */
				$this->error = true;
				$result = 0;

				if ($this->debug) {
					print "error preparing query";
				}
			}

			return $result;
		} catch (Exception $e) {
			$this->error = true;
			$result = 0;

			if ($this->debug) {
				print "exception: " . $e->getMessage();
			}

			return $result;
		}
	}

	function AuthenticateUser($username, $password)
	{
		$_internalusername = "";
		$userguid = "";
		$email = "";
		$passwd = "";
		$salt = "";
		$authretries = 0;
		$lockedout = 0;
		$acctenabled = 0;

		$authresult = false;							/* function will return this variable, true if auth successful, false if auth unsuccessful */

		/* This function checks if we leave the user input as is or convert to lowercase */
		if (!$this->username_casesensitive) {
			$_internalusername = strtolower($username);
		} else {
			$_internalusername = $username;
		}


		if ($this->debug) {
			print "case sens" . $this->username_casesensitive;
			print "<br />";
			print "strtolower: " . $_internalusername;
			print "<br />";
			print "<br />";
		}


		mysqli_report(MYSQLI_REPORT_STRICT);

		try {

			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			$sql_query_text = "select u.UserGUID, u.Email, u.Passwd, u.Salt, u.AuthRetriesLeft, u.LockedOut, u.AccountEnabled from" . chr(32) . $this->tablename_app_useraccounts . chr(32) . "u where u.Email = ? limit 1;";
			if ($select = $mysqli->prepare($sql_query_text)) {

				if ($select->bind_param("s", $_internalusername)) {
					if ($select->execute()) {

						$select->store_result();
						$select->bind_result($dbuserguid, $dbemail, $dbpasswd, $dbsalt, $dbauthretriesleft, $dblockedout, $dbaccountenabled);
						$count = $select->num_rows;

						if ($count === 1) {

							while ($row = $select->fetch()) {
								$userguid = $dbuserguid;
								$email = $dbemail;
								$passwd = $dbpasswd;
								$salt = $dbsalt;
								$authretries = $dbauthretriesleft;
								$lockedout = $dblockedout;
								$acctenabled = $dbaccountenabled;
							}

							/* 18-July 2015: EL - check the database for locked out value, if locked out is not the expected type assume that
								the variable was tampered with, and assume account lockout */
							if (is_int($lockedout) !== true || is_bool($lockedout) !== false) {
								$lockedout = 1;
							} else {
								$lockedout = 0;
							}

							if ($this->debug) {
								print "lockout value: " . $lockedout . "<br />";
								print "db lockout value: " . $dblockedout . "<br />";
								print "db lockout vartype: " . gettype($lockedout) . "<br />";
								print "is_int validation: " . is_int($lockedout)  . "<br />";
							}


							if ($lockedout === 0 || $lockedout === false) {
								/* check if user is enabled */
								if (is_int($acctenabled) || is_bool($acctenabled)) {
									if ($acctenabled === 1 || $acctenabled === true) {
										/* check if username and passwords match */

										/* TODO: EL - This function needs to be replaced by XOR comparison (slow check) */
										if ($_internalusername === $email && $passwd === $this->HashPassword($password, $salt)) {

											$authresult = true;
											$this->ResetAuthRetries($this->loginretries, $_internalusername);
											$this->LogAuthAttempt($userguid, $_internalusername, "User Authenticated", 1);
											if ($this->debug) {
												print "user authenticated";
											}
										} else {
											$authresult = false;
											$this->DecreaseAuthRetries($authretries, $_internalusername);
											$this->LogAuthAttempt($userguid, $_internalusername, "Invalid Password", -1);
											if ($this->debug) {
												print "invalid";
											}
										}
									} else {
										$authresult = false;
										$this->LogAuthAttempt($userguid, $_internalusername, "User Disabled", 0);
										if ($this->debug) {
											print "user disabled";
										}
									}
								} else {
									/* invalid acctenabled state in database, assume account is disabled */
								}
							} else {
								$authresult  = false;
								$this->LogAuthAttempt($userguid, $_internalusername, "Account is locked out", -1);
								if ($this->debug) {
									print "user locked out";
								}
							}
						} else {
							/* User not found */

							$authresult = false;
							$this->LogAuthAttempt("none", $_internalusername, "User does not exist", -2);
							$this->errormsg_internal =  "user $username does not exist";
							if ($this->debug) {
								print "user does not exist";
							}
						}

						/* close db connection */
						$mysqli->close();
					} else {
						/* could not execute query */
						$authresult = false;
						$this->error = true;
						$this->errormsg_internal =  "could not exec query";
					}
				} else {
					/* could not bind parameters */
					$authresult = false;
					$this->error = true;
					$this->errormsg_internal =  "could not bind params";
				}
			} else {
				/* error preparing query */
				$authresult = false;
				$this->error = true;
				$this->errormsg_internal = "error prepping query" . "<p />" . $sql_query_text;
			}





			return $authresult;
		} catch (Exception $e) {
			$authresult = false;
			$this->error = true;
			$this->errormsg_internal = $e;
			/* handle error authenticating
			   maybe db offline or something else */
			return $authresult;
		}
	}


	function CreateSession($username)
	{
		/* set default values for variables */
		$userguid = "";
		$firstname = "";
		$lastname = "";
		$userrights = "DOCENT";					/* default user rights */
		$schoolid = "";
		$class = "";
		$needstochangepassword = false;


		$result = false;	/* function will return this variable, true if retrieval successful, false if unsuccessful */

		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			//global $username, $password, $dbaddr, $schema;

			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			// $sql_query_text = "select u.UserGUID, u.FirstName, u.LastName, u.ChangePwdOnLogin, u.UserRights, u.SchoolID, u.Class from" . chr(32) . $this->tablename_app_useraccounts . chr(32) . "u where u.Email = ? limit 1;";


			$sql_query_text = "select u.UserGUID, u.FirstName, u.LastName, u.ChangePwdOnLogin, u.UserRights, u.SchoolID, u.Class, s.schoolname, s.schooltype, s.schooladmin,  stt.year_period
			from" . chr(32) . $this->tablename_app_useraccounts . chr(32) . "u inner join " . chr(32) . $this->tablename_schools . chr(32) . " s
			on u.schoolid = s.ID inner join setting stt on s.id = stt.schoolid
			where u.Email = ? limit 1;";

			if ($select = $mysqli->prepare($sql_query_text)) {

				if ($select->bind_param("s", $username)) {
					if ($select->execute()) {

						$select->store_result();
						$select->bind_result($dbuserguid, $dbfirstname, $dblastname, $dbchangepwdonlogin, $dbuserrights, $dbschoolid, $dbclass, $dbschoolname, $schooltype, $dbschooladmin, $year_period);
						$count = $select->num_rows;

						if ($count == 1) {

							while ($row = $select->fetch()) {
								$userguid = $dbuserguid;
								$firstname = $dbfirstname;
								$lastname = $dblastname;
								$needstochangepassword = $dbchangepwdonlogin;
								$userrights = $dbuserrights;
								$schoolid = $dbschoolid;
								$class = $dbclass;
								$schoolname = $dbschoolname;
								$schooladmin = $dbschooladmin;
								$schooljaar = $year_period;
							}

							$result = true;
						} else {
							/* User not found */

							$result = false;
						}

						/* close db connection */
						$mysqli->close();
					} else {
						/* could not execute query */
						$result = false;
						$this->errormsg_internal =  "could not exec query";
					}
				} else {
					/* could not bind parameters */
					$result = false;
					$error = true;
					$this->errormsg_internal =  "could not bind parameters";
				}
			} else {
				/* error preparing query */
				$result = false;
				$this->error = true;
				$this->errormsg_internal =  "error prepping query" . "<p />" . $sql_query_text;
			}

			if ($result === true) {

				if (session_status() === PHP_SESSION_NONE) {
					session_start();
				}


				/* -------------------------------------------------------------- */
				/* -----                                                    ----- */
				/* -----               CREATE SESSION VARIABLES             ----- */
				/* -----                                                    ----- */
				/* -------------------------------------------------------------- */

				$_SESSION["UserGUID"] = $userguid;				/* Added 27 Jul 2015 - UserGUID */
				$_SESSION["User"] = $username;
				$_SESSION["AppID"] = $this->application_id;		/* Added 25 Jul 2015 - AppID */
				$_SESSION["LoggedIn"] = true;
				$_SESSION["FirstName"] = $firstname;
				$_SESSION["LastName"] = $lastname;
				$_SESSION["SchoolAdmin"] = $schooladmin;
				$_SESSION["SchoolJaar"] = $schooljaar;
				$_SESSION["SchoolType"] = $schooltype;

				//this line is for the chat, program
				$_SESSION["Identity"] = hash($this->hashmethod, $userguid . '$password');
				/* Check if settings are not empty */
				if (!empty($userrights)) {
					$_SESSION["UserRights"] = $userrights; 		/* either DOCENT or BEHEER */
				} else {
					//redirect to error page
				}

				if (!empty($schoolid)) {
					$_SESSION["SchoolID"] = $schoolid;
				} else {
					/* Currently ID is not required - EL - Aug 26,2015 */
				}

				if (!empty($class)) {
					$_SESSION["Class"] = $class;
				} else {
					$_SESSION["Class"] = NULL;
				}

				if (!empty($schoolname)) {
					$_SESSION["schoolname"] = $schoolname;
				} else {
					$_SESSION["schoolname"] = NULL;
				}

				if (isset($_SERVER)) {
					/* $_SERVER variable is set */
					if (!empty($_SERVER["HTTP_USER_AGENT"])) {
						/* Set the useragent session variable */
						$_SESSION["UserAgent"] = $_SERVER["HTTP_USER_AGENT"];
					}
				} else {
					//redirect to error page
				}

				if (is_int($dbchangepwdonlogin) || is_bool($dbchangepwdonlogin)) {
					if ($dbchangepwdonlogin === 1 || $dbchangepwdonlogin === true) {
						/* Redirect to change password page */
						header("Location:$this->changepwdpage");
					} else {
						if ($this->redirectafterlogin) {
							/* Redirect to Main Page */
							header("Location:$this->mainpage");
						}
					}
				}
			}

			if ($_SESSION["UserRights"] == "TEACHER") {

				$result = 2;
			}


			return $result;
		} catch (Exception $e) {
			$result = false;
			$this->error = true;
			/* handle error authenticating
			   maybe db offline or something else */
			return $result;
		}
	}

	function GetSessionValue($sessionkey)
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		return $_SESSION[$sessionkey];
	}

	function LockUserAccount($username)
	{

		$lockedout = 1;

		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			//global $username, $password, $dbaddr, $schema;
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if ($update = $mysqli->prepare("update " . $this->tablename_app_useraccounts . " set lockedout = ? where email = ?")) {

				if ($update->bind_param("is", $lockedout, $username)) {
					if ($update->execute()) {
						$this->error = false;
						$result = 1;
					} else {
						/* error executing query */
						$this->error = true;
						$result = 0;

						if ($this->debug) {
							print "error executing query" . "<br />";
							print "error" . $mysqli->error;
						}
					}
				} else {
					/* error binding parameters */
					$this->error = true;
					$result = 0;

					if ($this->debug) {
						print "error binding parameters";
					}
				}
			} else {
				/* error preparing query */
				$this->error = true;
				$result = 0;

				if ($this->debug) {
					print "error preparing query";
				}
			}

			return $result;
		} catch (Exception $e) {
			$this->error = true;
			$result = 0;

			if ($this->debug) {
				print "exception: " . $e->getMessage();
			}

			return $result;
		}
	}

	function UnlockUserAccount($username)
	{

		$lockedout = 0;

		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			//global $username, $password, $dbaddr, $schema;
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if ($update = $mysqli->prepare("update " . $this->tablename_app_useraccounts . " set lockedout = ? where email = ?")) {

				if ($update->bind_param("is", $lockedout, $username)) {
					if ($update->execute()) {
						$this->error = false;
						$result = 1;
					} else {
						/* error executing query */
						$this->error = true;
						$result = 0;

						if ($this->debug) {
							print "error executing query" . "<br />";
							print "error" . $mysqli->error;
						}
					}
				} else {
					/* error binding parameters */
					$this->error = true;
					$result = 0;

					if ($this->debug) {
						print "error binding parameters";
					}
				}
			} else {
				/* error preparing query */
				$this->error = true;
				$result = 0;

				if ($this->debug) {
					print "error preparing query";
				}
			}

			return $result;
		} catch (Exception $e) {
			$this->error = true;
			$result = 0;

			if ($this->debug) {
				print "exception: " . $e->getMessage();
			}

			return $result;
		}
	}

	function ResetAuthRetries($defaultretries, $username)
	{
		$retriesleft = 5;

		if (!is_int($defaultretries)) {
			if ($defaultretries >= 1) {
				$retriesleft = $defaultretries;
			}
		}

		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			//global $username, $password, $dbaddr, $schema;
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if ($update = $mysqli->prepare("update " . $this->tablename_app_useraccounts . " set authretriesleft = ? where email = ?")) {

				if ($update->bind_param("is", $retriesleft, $username)) {
					if ($update->execute()) {
						$this->error = false;
						$result = 1;
					} else {
						/* error executing query */
						$this->error = true;
						$result = 0;

						if ($this->debug) {
							print "error executing query" . "<br />";
							print "error" . $mysqli->error;
						}
					}
				} else {
					/* error binding parameters */
					$this->error = true;
					$result = 0;

					if ($this->debug) {
						print "error binding parameters";
					}
				}
			} else {
				/* error preparing query */
				$this->error = true;
				$result = 0;

				if ($this->debug) {
					print "error preparing query";
				}
			}

			return $result;
		} catch (Exception $e) {
			$this->error = true;
			$result = 0;

			if ($this->debug) {
				print "exception: " . $e->getMessage();
			}

			return $result;
		}
	}

	function DecreaseAuthRetries($currentretriesleft, $username)
	{

		if ($this->debug) {
			print "functionname DecreaseAuthRetries() " . "<br />";
			print "current retriesleft (pre db update): " . $currentretriesleft . "<br />";
			print "username: " . $username . "<br />";
		}

		/* init retries left var , set default to 0 */
		$retriesleft = 0;

		/* lock variable, set to true if acct needs to be locked */
		$lockaccount = false;

		if (is_int($currentretriesleft)) {
			if ($currentretriesleft > 1) {
				/* subtract auth retries */
				$retriesleft = --$currentretriesleft;
			} elseif ($currentretriesleft === 1) {
				$retriesleft = --$currentretriesleft;
				$this->LockUserAccount($username);
			} elseif ($currentretriesleft <= 0) {
				/* TODO: Think about checking if the account is already locked before calling lock function */
				$retriesleft = 0;
				$this->LockUserAccount($username);
			}
		} else {
			/* $currentretriesleft is not valid value, use default value of 0 */
			if ($this->debug) {
				print "currentretriesleft != int" . "<br />";
			}
		}

		if ($this->debug) {
			print "current retriesleft (post db update): " . $currentretriesleft . "<br />";
		}

		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			//global $username, $password, $dbaddr, $schema;
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if ($update = $mysqli->prepare("update " . $this->tablename_app_useraccounts . " set authretriesleft = ? where email = ?")) {

				if ($update->bind_param("is", $retriesleft, $username)) {
					if ($update->execute()) {
						$this->error = false;
						$result = 1;
					} else {
						/* error executing query */
						$this->error = true;
						$result = 0;

						if ($this->debug) {
							print "error executing query" . "<br />";
							print "error" . $mysqli->error;
						}
					}
				} else {
					/* error binding parameters */
					$this->error = true;
					$result = 0;

					if ($this->debug) {
						print "error binding parameters";
					}
				}
			} else {
				/* error preparing query */
				$this->error = true;
				$result = 0;

				if ($this->debug) {
					print "error preparing query";
				}
			}

			/* check if the account needs to be locked, call lockaccount() if necessary */
			if ($lockaccount === true) {
				/* $this->lockaccount(); */
			}

			return $result;
		} catch (Exception $e) {
			$this->error = true;
			$result = 0;

			if ($this->debug) {
				print "exception: " . $e->getMessage();
			}

			return $result;
		}
	}

	function ChangePassword($username, $newpassword, $changepwdonlogin)
	{
		/*
		Debugging: 10 Nov 2014
		Issue: Update statement would not work
		Fix: Fixed by creating $_username variable and copying $username parameter from function
		as the $username is also used by the databse username global variable which caused the query to not update anything.
		Elbert Lumenier
		*/

		$_username = $username;
		$_salt = $this->CreateSalt();
		$_password = $this->HashPassword($newpassword, $_salt);
		$_PasswordResetToken = "";

		if (is_int($changepwdonlogin) || is_bool($changepwdonlogin)) {
			if ($changepwdonlogin === 1 || $changepwdonlogin === true) {
				$_changepwdonlogin = 1;
			} else {
				$_changepwdonlogin = 0;
			}
		} else {
			$_changepwdonlogin = 1;
		}

		require_once("spn_utils.php");
		$utils = new spn_utils();
		$_lastpwdchangedatetime = $utils->getdatetime("UTC");

		$result = 0;

		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			//global $username, $password, $dbaddr, $schema;

			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			$mysqli->autocommit(TRUE);
			if ($update = $mysqli->prepare("update " . $this->tablename_app_useraccounts . " set passwd = ?, salt = ?, changepwdonlogin = ?, lastpwdchangedatetime = ?, PasswordResetToken = ? where email = ?;")) {
				if ($update->bind_param("ssisss", $_password, $_salt, $_changepwdonlogin, $_lastpwdchangedatetime, $_PasswordResetToken, $_username)) {
					if ($update->execute()) {
						$this->error = false;
						$result = 1;

						if ($this->debug) {
							print "ChangePassword :: executed query" . "<br />";
							print "SQL error if any" . $mysqli->error . "<br />";
						}
					} else {
						/* error executing query */
						$this->error = true;
						$result = 0;

						if ($this->debug) {
							print "ChangePassword :: error executing query" . "<br />";
							print "error" . $mysqli->error;
						}
					}
				} else {
					/* error binding parameters */
					$this->error = true;
					$result = 0;

					if ($this->debug) {
						print "ChangePassword :: error binding parameters";
					}
				}
			} else {
				/* error preparing query */
				$this->error = true;
				$result = 0;

				if ($this->debug) {
					print "ChangePassword :: error preparing query";
				}
			}
		} catch (Exception $e) {
			$this->error = true;
			$result = 0;

			if ($this->debug) {
				print "exception: " . $e->getMessage();
			}
		}

		print $result;
	}

	/* Finished function - Nov 26, 2015 */
	function ActivateResetPasswordMode($username)
	{
		/* This function activates the password reset mode, it creates a token and sets expiration date to current date + 30M */

		$returnvalue = false;		/* function will return this variable, true if password reset mode set successful, false if unsuccessful */

		try {

			$plaintextpasswordresettoken = $this->GeneratePlaintextPasswordResetToken();
			$hashedpasswordresettoken = $this->GeneratePasswordResetToken($plaintextpasswordresettoken, $this->passwordreset_salt);

			if (!empty($hashedpasswordresettoken)) {
				/*
					not empty, token generated successfully
					check if the token already exists in the database
				*/
				if ($this->TokenExistsInDatabase($hashedpasswordresettoken) != 1) {

					if ($savetokenindbresult = $this->SavePasswordResetTokenInDatabase($username, $hashedpasswordresettoken) == 1) {
						require_once("spn_email.php");
						$email = new spn_email();
						if ($email->SendPasswordResetEmail($hashedpasswordresettoken, $username, "no-reply@qwihi.com", "Scol Pa Nos", $username, $username) == 1) {
							/* email sending succeeded */
							$returnvalue = true;
						}
					} else {
						/* password reset token not saved, check why */
						if ($savetokenindbresult == 0) {
							/* database error */
						} else if ($savetokenindbresult == -1) {
							/* ???? */
						}
					}
				}
			} else {
				/* empty, token not generated */
				$returnvalue = false;
			}
		} catch (Exception $e) {
			/* handle the exception */
			$returnvalue = false;
			$this->error = true;
			$this->errormsg_internal = $e;
			return $returnvalue;
		}

		return $returnvalue;
	}

	/* Finished function - Nov 26, 2015 */
	function GeneratePlainTextPasswordResetToken()
	{

		$plaintextpasswordresettoken = "";

		try {
			$plaintextpasswordresettoken = bin2hex(openssl_random_pseudo_bytes(32));
		} catch (Exception $e) {
			/* throw an exception */
			$this->error = true;
			$$plaintextpasswordresettoken = "";
			return $plaintextpasswordresettoken;
		}

		return $plaintextpasswordresettoken;
	}

	/* Finished function - Nov 26, 2015 */
	function GeneratePasswordResetToken($token, $salt = "")
	{
		/*  Generate random pasword reset token */
		/* 	This function hashes the password reset token
			Function expects plaintext token and salt */

		$hashedtoken = "";

		/* use default salt if no values specified */
		if (empty($salt)) {
			$salt = $this->passwordreset_salt;
		}

		try {
			//check if salt is empty
			if (empty($salt)) {
				$hashedtoken = hash($this->hashmethod, $token);
			} else {
				$hashedtoken = hash($this->hashmethod, $salt . $token);
			}
		} catch (Exception $e) {
			/* throw an exception */
			$this->error = true;
			$hashedtoken = "";
			return $hashedtoken;
		}

		return $hashedtoken;
	}

	/* Finished function - Nov 26, 2015 */
	function TokenExistsInDatabase($hashedtoken)
	{
		$tokenexistsindatabaseresult = true;					/* function will return this variable, defaults to true */


		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			$sql_query_text = "select u.PasswordResetToken from" . chr(32) . $this->tablename_app_useraccounts . chr(32) . "u where u.PasswordResetToken = ?;";

			if ($select = $mysqli->prepare($sql_query_text)) {

				if ($select->bind_param("s", $hashedtoken)) {
					if ($select->execute()) {
						$select->store_result();
						$count = $select->num_rows;

						if ($count >= 1) {
							$tokenexistsindatabaseresult = true;
						} else {
							/* User not found */
							$tokenexistsindatabaseresult = false;
							$this->errormsg_internal = "token already exists in database";
							if ($this->debug) {
								print "token already exists in database";
							}
						}

						/* close db connection */
						$mysqli->close();
					} else {
						/* could not execute query */
						$tokenexistsindatabaseresult = false;
						$this->error = true;
						$this->errormsg_internal =  "could not exec query";
					}
				} else {
					/* could not bind parameters */
					$tokenexistsindatabaseresult = false;
					$this->error = true;
					$this->errormsg_internal =  "could not bind params";
				}
			} else {
				/* error preparing query */
				$tokenexistsindatabaseresult = false;
				$this->error = true;
				$this->errormsg_internal = "error prepping query" . "<p />" . $sql_query_text;
			}
		} catch (Exception $e) {
			$tokenexistsindatabaseresult = false;
			$this->error = true;
			$this->errormsg_internal = $e;
			/* handle error, maybe db offline or something else */
			return $tokenexistsindatabaseresult;
		}

		return $tokenexistsindatabaseresult;
	}

	/* Testing function - Nov 26, 2015 */
	function SavePasswordResetTokenInDatabase($username, $hashedtoken)
	{
		$savetokenresult = false;	/* function will return this variable, true if auth successful, false if auth unsuccessful */

		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			$datenow = new DateTime("now", new DateTimeZone("UTC"));
			$dateexpiration = $datenow->add(new DateInterval("PT10M"));
			$dateexpirationvalue = $dateexpiration->format("Y-m-d h:i:s");

			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			$sql_query_text = "update" . chr(32) . $this->tablename_app_useraccounts . chr(32) . "u set u.PasswordResetToken = ?, u.PasswordResetTokenExpiration = ? where u.Email = ? limit 1;";
			if ($update = $mysqli->prepare($sql_query_text)) {
				if ($update->bind_param("sss", $hashedtoken, $dateexpirationvalue, $username)) {
					if ($update->execute()) {
						$update->store_result();
						$count = $update->affected_rows;

						if ($count === 1) {
							$savetokenresult = true;
						} else {
							/* token could not be saved */
							$savetokenresult = false;
							$this->errormsg_internal = "Token could not be saved in database";
							if ($this->debug) {
								print "Token could not be saved in database";
							}
						}

						/* close db connection */
						$mysqli->close();
					} else {
						/* could not execute query */
						$savetokenresult = false;
						$this->error = true;
						$this->errormsg_internal =  "could not exec query";
					}
				} else {
					/* could not bind parameters */
					$savetokenresult = false;
					$this->error = true;
					$this->errormsg_internal =  "could not bind params";
				}
			} else {
				/* error preparing query */
				$savetokenresult = false;
				$this->error = true;
				$this->errormsg_internal = "error prepping query" . "<p />" . $sql_query_text;
			}
		} catch (Exception $e) {
			$savetokenresult = false;
			$this->error = true;
			$this->errormsg_internal = $e;
			/* handle error authenticating
			   maybe db offline or something else */
			return $savetokenresult;
		}

		return $savetokenresult;
	}

	function InvalidatePasswordResetToken($username)
	{
		$invalidatetokenresult = false;	/* function will return this variable, true if auth successful, false if auth unsuccessful */
		$invalidate_defaultvalue_token = "";
		$invalidate_defaultvalue_expirydate = "0000-00-00 00:00:00";

		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			/* 23 Nov 2015 :: ELBERT: Decision made to allow for token invalidation regardless if user is disabled or locked out */
			$sql_query_text = "update" . chr(32) . $this->tablename_app_useraccounts . chr(32) . "u set u.PasswordResetToken = ?, u.PasswordResetTokenExpiration = ? where u.Email = ? limit 1;";
			if ($update = $mysqli->prepare($sql_query_text)) {
				if ($update->bind_param("sss", $invalidate_defaultvalue_token, $invalidate_defaultvalue_expirydate, $username)) {
					if ($update->execute()) {
						$update->store_result();
						//$update->bind_result($dbemail,$dbpasswordresettoken);
						$count = $update->affected_rows;

						print "total rows affected: " . $count . "<br />";

						if ($count === 1) {
							$invalidatetokenresult = true;
						} else {
							/* user could not be found or no token present for user*/
							$invalidatetokenresult = false;
							$this->LogAuthAttempt("none", $username, "User does not exist or no token present for user", -2);
							$this->errormsg_internal = "$username does not exist or no token present for user";
							if ($this->debug) {
								print "user does not exist or does have a pwd reset token";
							}
						}

						/* close db connection */
						$mysqli->close();
					} else {
						/* could not execute query */
						$invalidatetokenresult = false;
						$this->error = true;
						$this->errormsg_internal =  "could not exec query";
					}
				} else {
					/* could not bind parameters */
					$invalidatetokenresult = false;
					$this->error = true;
					$this->errormsg_internal =  "could not bind params";
				}
			} else {
				/* error preparing query */
				$invalidatetokenresult = false;
				$this->error = true;
				$this->errormsg_internal = "error prepping query" . "<p />" . $sql_query_text;
			}
		} catch (Exception $e) {
			$invalidatetokenresult = false;
			$this->error = true;
			$this->errormsg_internal = $e;
			/* handle error authenticating
			   maybe db offline or something else */
			return $invalidatetokenresult;
		}

		return $invalidatetokenresult;
	}

	function LogAuthAttempt($userguid, $username, $description, $status)
	{
		require_once("spn_utils.php");
		$utils = new spn_utils();
		$result = 0;

		$_userguid = substr($userguid, 0, 60);
		$_username = substr($username, 0, 45);
		$_description = substr($description, 0, 45);
		$_datetime = $utils->getdatetime("UTC");

		if (is_int($status)) {
			$_status = $status;
		} else {
			$_status = -99;
		}

		$_remoteuseragent = (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "Unknown");
		$_remoteipaddress = (isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "Unknown");
		$_remotehost = (isset($_SERVER["REMOTE_HOST"]) ? $_SERVER["REMOTE_HOST"] : "Unknown");

		mysqli_report(MYSQLI_REPORT_STRICT);

		try {
			//global $username, $password, $dbaddr, $schema;

			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

			if (!empty($userguid) && !empty($username) && !empty($description) && !empty($status)) {

				if ($insert = $mysqli->prepare("insert into " . $this->tablename_sec_login_attempts . " (userguid,username,description,datetime,status,useragent,remoteipaddress,remotehost) values (?,?,?,?,?,?,?,?);")) {

					if ($insert->bind_param("ssssisss", $_userguid, $_username, $_description, $_datetime, $_status, $_remoteuseragent, $_remoteipaddress, $_remotehost)) {
						if ($insert->execute()) {
							$this->error = false;
							$result = 1;
						} else {
							/* error executing query */
							$this->error = true;
							$result = 0;

							if ($this->debug) {
								print "LogAuthAttempt :: error executing query" . "<br />";
								print "error" . $mysqli->error;
							}
						}
					} else {
						/* error binding parameters */
						$this->error = true;
						$result = 0;

						if ($this->debug) {
							print "LogAuthAttempt :: error binding parameters";
						}
					}
				} else {
					/* error preparing query */
					$this->error = true;
					$result = 0;

					if ($this->debug) {
						print "LogAuthAttempt :: error preparing query" . "<br />";
						print "error" . $mysqli->error;
					}
				}
			} else {
				if ($this->debug) {
					print "empty";
				}
			}

			return $result;
		} catch (Exception $e) {
			$this->error = true;
			$result = 0;

			if ($this->debug) {
				print "exception: " . $e->getMessage();
			}

			return $result;
		}
	}

	function CheckSessionValidity($mode = "usersession")
	{

		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		$returnvalue = 0;

		//session_regenerate_id(true);

		if (isset($_SESSION["LoggedIn"]) && isset($_SESSION["User"]) && isset($_SESSION["AppID"])) {
			/* User is logged in */

			/* Check the application ID in session variable */
			if ($_SESSION["AppID"] === $this->application_id) {

				/* Check useragent of the browser */
				if (isset($_SESSION["UserAgent"])) {
					if ($_SESSION["UserAgent"] != $_SERVER["HTTP_USER_AGENT"]) {
						/* UserAgent mismatch */
						header("Location:$this->loginpage");
					} else {
						/* authenticated */
						$returnvalue = 1;
					}
				} else {
					/* UserAgent not set, redirect to login page */
					header("Location:$this->loginpage");
				}
			} else {
				/* Incorrect AppID, invalid session */
				header("Location:$this->loginpage");
			}


			if ($this->debug) {
				var_dump($_SESSION);
			}
		} else {
			if ($mode == "usersession") {
				/* User not logged in */
				header("Location:$this->loginpage");
			} else {
				$returnvalue = 0;
			}
		}

		return $returnvalue;
	}
	function signin_parent($studentnumber, $securepin, $baseurl, $schoolid, $dummy)
	{
		$returnvalue = "";
		$sql_query = "";
		$htmlcontrol = "";
		$result = 0;

		mysqli_report(MYSQLI_REPORT_STRICT);
		require_once("spn_utils.php");
		$utils = new spn_utils();
		try {
			require_once("DBCreds.php");
			$DBCreds = new DBCreds();
			$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
			if ($select = $mysqli->prepare("CALL " . $this->sp_get_leerling_by_studentnumber_securepin . "(?,?,?)")) {
				// $class = '';
				if ($select->bind_param("sis", $studentnumber, $securepin, $schoolid)) {
					if ($select->execute()) {
						$this->error = false;
						$result = 1;
						$select->bind_result($id_student, $id_family, $class, $schoolID, $schooljaar, $family_uuid, $uuid_student, $schoolname);
						$select->store_result();

						if ($select->num_rows > 0) {
							while ($select->fetch()) {
								$detailpage = "home_parent.php";
								$htmlcontrol .= "<input class=\"hidden\" id=\"redirect_leerling_parent\" value=\"$baseurl/$detailpage" . "?id=" . htmlentities($uuid_student) . "&id_family=" . htmlentities($family_uuid) . "\">";
								if (session_status() === PHP_SESSION_NONE) {
									session_start();
								}

								if ($schoolID > 11) {
									$_SESSION["SchoolType"] = 2;
								} else {
									$_SESSION["SchoolType"] = 1;
								}
								$_SESSION["User"] = $id_family;
								$_SESSION["AppID"] = $this->application_id;		/* Added 25 Jul 2015 - AppID */
								$_SESSION["LoggedIn"] = true;
								$_SESSION["Class"] = $class;
								$_SESSION["SchoolID"] = $schoolID;
								$_SESSION["UserGUID"] = $uuid_student;
								$_SESSION["SchoolJaar"] = $schooljaar;
								$_SESSION["schoolname"] = $schoolname;
								$_SESSION["UserRights"] = 'PARENTS';
								$_SESSION["FirstName"] = $firstname;
								$_SESSION["LastName"] = $lastname;

								///this is for the chat application
								$_SESSION["Identity"] = hash($this->hashmethod, $uuid_student . '$password');
							}
						} else {
							$htmlcontrol = 0;
						}
					} else {
						$result = 0;
					}
				} else {
					$result = 0;
				}
			} else {
				$result = 0;

				$htmlcontrol = $mysqli->error;
			}
		} catch (Exception $e) {
			$result = -2;
			$result = $e->getMessage();
		}
		return $htmlcontrol;
	}
}
