<?php


class spn_calendar
{

  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";

  public $sp_create_calendar = "sp_create_calendar";
  public $sp_read_calendar = "sp_read_calendar";
  public $sp_read_calendar_hs = "sp_read_calendar_hs";
  public $sp_update_calendar = "sp_update_calendar";
  public $sp_delete_calendar = "sp_delete_calendar";


  public $debug = true;
  public $error = "";
  public $errormessage = "";

  function create_calendar($docent, $class, $date_calendar, $subject_calendar, $observations, $schoolID, $schoolJaar, $dummy, $vak)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $dummy = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare("CALL sp_create_calendar (?,?,?,?,?,?,?,?)")) {
        if ($select->bind_param("sssssiss", $docent, $class, $date_calendar, $subject_calendar, $observations, $schoolID, $schoolJaar, $vak)) {
          if ($select->execute()) {
            $this->error = false;

            /* require_once("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($docent, 'Calendar', 'Add Calendar', appconfig::GetDummy()); */
            $result = 1;

            $select->bind_result($last_calendar_id);

            $select->store_result();
            if ($select->num_rows > 0) {
              while ($select->fetch()) {
                $htmlcontrol .= $last_calendar_id;
              }
            } else {
              $htmlcontrol .= "0";
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
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;

        print "error del sql: " . $this->errormessage;

        if ($this->debug) {
          print "error preparing query";
        }
      }
      // Cierre del prepare
      $returnvalue = $htmlcontrol;
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

  function update_calendar($docent, $class, $date_calendar, $subject_calendar, $observations)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $dummy = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      $update = "UPDATE calendar SET class = '$class', date = '$date_calendar', subject = '$subject_calendar', observations = '$observations' WHERE id_calendar = " . $_POST['id_calendar'];
      $result = mysqli_query($mysqli, $update);
      if ($result) {
        $this->error = false;
        $returnvalue = 1;
      }
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

  function get_exams($class, $date_calendar, $subject_calendar, $schoolID)
  {
    require_once("spn_utils.php");
    $result = 0;
    if ($subject_calendar == 'Exam') {
      $exam = 2;
    } else if ($subject_calendar == 'Test') {
      $exam = 1;
    }
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      $select = "SELECT subject FROM calendar WHERE class = '$class' AND date = '$date_calendar' AND SchoolID = '$schoolID'";
      $result = mysqli_query($mysqli, $select);
      while ($row = mysqli_fetch_array($result)) {
        if ($row['subject'] == 'Exam') {
          $exam = $exam + 2;
        } else if ($row['subject'] == 'Test') {
          $exam = $exam + 1;
        }
      }
      $returnvalue = $exam;
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

  function delete_calendar($id_calendar, $dummy)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      if ($dummy)
        $result = 1;
      else {
        $UserGUID = $_SESSION['UserGUID'];
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        $select = "SELECT docent FROM calendar WHERE docent = '$UserGUID' AND id_calendar = '$id_calendar' LIMIT 1";
        $resultado1 = mysqli_query($mysqli, $select);
        if ($resultado1->num_rows == 1 || $_SESSION['UserRights'] == 'BEHEER') {
          if ($stmt = $mysqli->prepare("CALL " . $this->sp_delete_calendar . " (?)")) {

            if ($stmt->bind_param("i", $id_calendar)) {
              if ($stmt->execute()) {
                require_once("spn_audit.php");
                $spn_audit = new spn_audit();
                $spn_audit->create_audit($UserGUID, 'Calendar', 'Delete Calendar', appconfig::GetDummy());
                $result = 1;
                $stmt->close();
                $mysqli->close();
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $result = $mysqli->error;
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

              $result = $mysqli->error;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $result = $mysqli->error;
          }
        } else {
          $result = 2;
        }
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();

      $result = $e->getMessage();
    }

    return $result;
  }

  function read_calendar_json($klas, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $json_result = "";
    $indexvaluetoshow = 3;
    $inloop = false;
    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL " . $this->sp_read_calendar . " (?,?,?)")) {
          if ($select->bind_param("sis", $klas, $_SESSION['SchoolID'], $_SESSION['SchoolJaar'])) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;
              $select->bind_result($idcalendar, $date, $subjet, $observations, $DocentLastName);
              $select->store_result();

              if ($select->num_rows > 0) {
                $json_result .= "{";
                $json_result .= "\"success\": \"1\",";
                $json_result .= "\"result\": [";

                while ($select->fetch()) {
                  if (!$inloop) {
                    $json_result .= "{";
                    $inloop = true;
                  } else
                    $json_result .= ",{";

                  $json_result .= "\"id\": \"" . $idcalendar . "\",";
                  $json_result .= "\"title\": \"" . $DocentLastName . " >> " . $subjet . " : " . $observations . "\",";

                  if ($_SESSION['UserRights'] == 'PARENTS') {
                    $json_result .= "\"url\": \"javascript:open_document(" . $idcalendar . ");\",";
                  } else {
                    $json_result .= "\"url\": \"javascript:delete_calendar(" . $idcalendar . ");\",";
                  }
                  switch ($subjet) {
                    case 'Homework':
                      $json_result .= "\"class\": \"event-info\",";
                      break;
                    case 'Test':
                      $json_result .= "\"class\": \"event-success\",";
                      break;
                    case 'Exam':
                      $json_result .= "\"class\": \"event-warning\",";
                      break;
                    case 'Other':
                      $json_result .= "\"class\": \"event-special\",";
                      break;
                    default:
                      $json_result .= "\"class\": \"event-important\",";
                      break;
                  }
                  //   print($date);
                  //   $date = new DateTime($date);
                  // $date->add(new DateInterval('P1D'));
                  // print($date);

                  $json_result .= "\"start\": \"" . strtotime($date . ' + 1 days') * 1000 . "\",";
                  $json_result .= "\"end\": \"" . strtotime($date . ' + 1 days') * 1000 . "\"";

                  $json_result .= "}";
                }

                $json_result .= "]";
                $json_result .= "}";
              } else {
                $json_result .= "{";
                $json_result .= "\"success\": 0,";
                $json_result .= "\"result\": []";
                $json_result .= "}";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

              $json_result = $result;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $json_result = $result;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;

          $json_result = $result;
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();

        $json_result = $result;
      }

      return $json_result;
    }
  }
  function read_calendar_json_hs($dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $json_result = "";
    $indexvaluetoshow = 3;
    $inloop = false;
    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        $UserRights = $_SESSION['UserRights'];
        if ($UserRights == "ADMINISTRATIE" || $UserRights == "ONDERSTEUNING" || $UserRights == "BEHEER") {

          $sql_query = " Select c.id_calendar,c.date, c.subject, c.observations, u.lastname, c.class, k.volledigenaamvak FROM calendar c INNER JOIN app_useraccounts u
                            ON u.UserGUID = c.docent 
                            left JOIN	
                            le_vakken k
		                        ON
                            c.vak = k.id WHERE  c.SchoolID = ? and c.SchoolJaar = ?; ";

          if ($select = $mysqli->prepare($sql_query)) {
            if ($select->bind_param("is", $_SESSION['SchoolID'], $_SESSION['SchoolJaar'])) {
              if ($select->execute()) {
                $this->error = false;
                $result = 1;
                $select->bind_result($idcalendar, $date, $subjet, $observations, $DocentLastName, $class, $volledigenaamvak);
                $select->store_result();

                if ($select->num_rows > 0) {
                  $json_result .= "{";
                  $json_result .= "\"success\": \"1\",";
                  $json_result .= "\"result\": [";

                  while ($select->fetch()) {
                    if (!$inloop) {
                      $json_result .= "{";
                      $inloop = true;
                    } else
                      $json_result .= ",{";

                    $observations = str_replace(array("\r", "\n", "	"), "", $observations);

                    $json_result .= "\"id\": \"" . $idcalendar . "\",";

                    if ($volledigenaamvak <> '') {
                      $json_result .= "\"title\": \"" . $DocentLastName . " >>" . " Klas: " . $class . " >> " . " Vak: " . $volledigenaamvak . " >> " . $subjet . " : " . preg_replace("/\r|\n/", "", $observations)  . "\",";
                    } else {
                      $json_result .= "\"title\": \"" . $DocentLastName . " >>" . " Klas: " . $class . " >> " . $subjet . " : " . preg_replace("/\r|\n/", "", $observations)  . "\",";
                    }

                    $json_result .= "\"url\": \"javascript:update_calendar(" . $idcalendar . ",'" . $DocentLastName . "','" .  $class . "','" . $volledigenaamvak . "','" . $subjet . "','" . $observations . "','" . $date . "');\",";
                    /*                     $json_result .= "\"url\": \"javascript:delete_calendar(" . $idcalendar . ");\",";*/
                    switch ($subjet) {
                      case 'Homework':
                        $json_result .= "\"class\": \"event-info\",";
                        break;
                      case 'Test':
                        $json_result .= "\"class\": \"event-success\",";
                        break;
                      case 'Exam':
                        $json_result .= "\"class\": \"event-warning\",";
                        break;
                      case 'Other':
                        $json_result .= "\"class\": \"event-special\",";
                        break;
                      default:
                        $json_result .= "\"class\": \"event-important\",";
                        break;
                    }
                    //   print($date);
                    //   $date = new DateTime($date);
                    // $date->add(new DateInterval('P1D'));
                    // print($date);

                    $json_result .= "\"start\": \"" . strtotime($date . ' + 1 days') * 1000 . "\",";
                    $json_result .= "\"end\": \"" . strtotime($date . ' + 1 days') * 1000 . "\"";

                    $json_result .= "}";
                  }

                  $json_result .= "]";
                  $json_result .= "}";
                } else {
                  $json_result .= "{";
                  $json_result .= "\"success\": 0,";
                  $json_result .= "\"result\": []";
                  $json_result .= "}";
                }
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $json_result = $result;
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

              $json_result = $result;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $json_result = $result;
          }
        } else {

          $sql_query = "Select c.id_calendar,c.date, c.subject, REPLACE(REPLACE(REPLACE(c.observations,'	',''), CHAR(13, 10), ''),'\n','') observations, u.lastname, c.docent, c.class FROM calendar c INNER JOIN app_useraccounts u ON u.UserGUID = c.docent
                          WHERE  c.SchoolID = ? and c.SchoolJaar = ? and c.class in (select hs.klas from user_hs hs where  hs.user_guid = ? )";

          if ($select = $mysqli->prepare($sql_query)) {
            if ($select->bind_param("iss", $_SESSION['SchoolID'], $_SESSION['SchoolJaar'], $_SESSION['UserGUID'])) {
              if ($select->execute()) {
                $this->error = false;
                $result = 1;
                $select->bind_result($idcalendar, $date, $subjet, $observations, $DocentLastName, $DocentGuid, $class);
                $select->store_result();

                if ($select->num_rows > 0) {
                  $json_result .= "{";
                  $json_result .= "\"success\": \"1\",";
                  $json_result .= "\"result\": [";

                  while ($select->fetch()) {
                    if (!$inloop) {
                      $json_result .= "{";
                      $inloop = true;
                    } else
                      $json_result .= ",{";

                    $json_result .= "\"id\": \"" . $idcalendar . "\",";
                    //$json_result .= "\"title\": \"". $DocentLastName . " >> ". $subjet . " : ". $observations ."\",";
                    $json_result .= "\"title\": \"" . $DocentLastName . " >>" . " Klas: " . $class . " >> " . $subjet . " : " . $observations . "\",";
                    //$json_result .= "\"title\": \"". $DocentLastName . " >>" . " Klas: " . $class . " >> " . " Vak: " . $volledigenaamvak . " >> " . $subjet . " : " . preg_replace( "/\r|\n/", "", $observations )  ."\",";


                    if ($_SESSION['UserGUID'] == $DocentGuid) {
                      $json_result .= "\"url\": \"javascript:delete_calendar(" . $idcalendar . ");\",";
                    }

                    switch ($subjet) {
                      case 'Homework':
                        $json_result .= "\"class\": \"event-info\",";
                        break;
                      case 'Test':
                        $json_result .= "\"class\": \"event-success\",";
                        break;
                      case 'Exam':
                        $json_result .= "\"class\": \"event-warning\",";
                        break;
                      case 'Other':
                        $json_result .= "\"class\": \"event-special\",";
                        break;
                      default:
                        $json_result .= "\"class\": \"event-important\",";
                        break;
                    }
                    //   print($date);
                    //   $date = new DateTime($date);
                    // $date->add(new DateInterval('P1D'));
                    // print($date);

                    $json_result .= "\"start\": \"" . strtotime($date . ' + 1 days') * 1000 . "\",";
                    $json_result .= "\"end\": \"" . strtotime($date . ' + 1 days') * 1000 . "\"";

                    $json_result .= "}";
                  }

                  $json_result .= "]";
                  $json_result .= "}";
                } else {
                  $json_result .= "{";
                  $json_result .= "\"success\": 0,";
                  $json_result .= "\"result\": []";
                  $json_result .= "}";
                }
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $json_result = $result;
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

              $json_result = $result;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $json_result = $result;
          }
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();

        $json_result = $result;
      }

      return $json_result;
    }
  }

  function read_calendar_json_hs_verzuim($dummy, $class_)
  {
    $returnvalue = "";
    $sql_query = "";
    $json_result = "";
    $indexvaluetoshow = 3;
    $inloop = false;
    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        $UserRights = $_SESSION['UserRights'];
        if ($UserRights == "ADMINISTRATIE" || $UserRights == "ONDERSTEUNING" || $UserRights == "BEHEER") {

          $sql_query = " Select c.id_calendar,c.date, c.subject, c.observations, u.lastname, c.class, k.volledigenaamvak FROM calendar c INNER JOIN app_useraccounts u
                            ON u.UserGUID = c.docent 
                            left JOIN	
                            le_vakken k
		                        ON
                            c.vak = k.id WHERE  c.SchoolID = ? and c.SchoolJaar = ?; ";

          if ($select = $mysqli->prepare($sql_query)) {
            if ($select->bind_param("is", $_SESSION['SchoolID'], $_SESSION['SchoolJaar'])) {
              if ($select->execute()) {
                $this->error = false;
                $result = 1;
                $select->bind_result($idcalendar, $date, $subjet, $observations, $DocentLastName, $class, $volledigenaamvak);
                $select->store_result();

                if ($select->num_rows > 0) {
                  $json_result .= "{";
                  $json_result .= "\"success\": \"1\",";
                  $json_result .= "\"result\": [";

                  while ($select->fetch()) {
                    if (!$inloop) {
                      $json_result .= "{";
                      $inloop = true;
                    } else
                      $json_result .= ",{";

                    $observations = str_replace(array("\r", "\n", "	"), "", $observations);

                    $json_result .= "\"id\": \"" . $idcalendar . "\",";

                    if ($volledigenaamvak <> '') {
                      $json_result .= "\"title\": \"" . $DocentLastName . " >>" . " Klas: " . $class . " >> " . " Vak: " . $volledigenaamvak . " >> " . $subjet . " : " . preg_replace("/\r|\n/", "", $observations)  . "\",";
                    } else {
                      $json_result .= "\"title\": \"" . $DocentLastName . " >>" . " Klas: " . $class . " >> " . $subjet . " : " . preg_replace("/\r|\n/", "", $observations)  . "\",";
                    }

                    $json_result .= "\"url\": \"javascript:delete_calendar(" . $idcalendar . ");\",";
                    switch ($subjet) {
                      case 'Homework':
                        $json_result .= "\"class\": \"event-info\",";
                        break;
                      case 'Test':
                        $json_result .= "\"class\": \"event-success\",";
                        break;
                      case 'Exam':
                        $json_result .= "\"class\": \"event-warning\",";
                        break;
                      case 'Other':
                        $json_result .= "\"class\": \"event-special\",";
                        break;
                      default:
                        $json_result .= "\"class\": \"event-important\",";
                        break;
                    }
                    //   print($date);
                    //   $date = new DateTime($date);
                    // $date->add(new DateInterval('P1D'));
                    // print($date);

                    $json_result .= "\"start\": \"" . strtotime($date . ' + 1 days') * 1000 . "\",";
                    $json_result .= "\"end\": \"" . strtotime($date . ' + 1 days') * 1000 . "\"";

                    $json_result .= "}";
                  }

                  $json_result .= "]";
                  $json_result .= "}";
                } else {
                  $json_result .= "{";
                  $json_result .= "\"success\": 0,";
                  $json_result .= "\"result\": []";
                  $json_result .= "}";
                }
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $json_result = $result;
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

              $json_result = $result;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $json_result = $result;
          }
        } else {

          $sql_query = "Select c.id_calendar,c.date, c.subject, REPLACE(REPLACE(REPLACE(c.observations,'	',''), CHAR(13, 10), ''),'\n','') observations, u.lastname, c.docent, c.class FROM calendar c INNER JOIN app_useraccounts u ON u.UserGUID = c.docent
                          WHERE  c.SchoolID = ? and c.SchoolJaar = ? and c.class in (select hs.klas from user_hs hs where  hs.user_guid = ? )";

          if ($select = $mysqli->prepare($sql_query)) {
            if ($select->bind_param("iss", $_SESSION['SchoolID'], $_SESSION['SchoolJaar'], $_SESSION['UserGUID'])) {
              if ($select->execute()) {
                $this->error = false;
                $result = 1;
                $select->bind_result($idcalendar, $date, $subjet, $observations, $DocentLastName, $DocentGuid, $class);
                $select->store_result();

                if ($select->num_rows > 0) {
                  $json_result .= "{";
                  $json_result .= "\"success\": \"1\",";
                  $json_result .= "\"result\": [";

                  while ($select->fetch()) {
                    if (!$inloop) {
                      $json_result .= "{";
                      $inloop = true;
                    } else
                      $json_result .= ",{";

                    $json_result .= "\"id\": \"" . $idcalendar . "\",";
                    //$json_result .= "\"title\": \"". $DocentLastName . " >> ". $subjet . " : ". $observations ."\",";
                    $json_result .= "\"title\": \"" . $DocentLastName . " >>" . " Klas: " . $class . " >> " . $subjet . " : " . $observations . "\",";
                    //$json_result .= "\"title\": \"". $DocentLastName . " >>" . " Klas: " . $class . " >> " . " Vak: " . $volledigenaamvak . " >> " . $subjet . " : " . preg_replace( "/\r|\n/", "", $observations )  ."\",";


                    if ($_SESSION['UserGUID'] == $DocentGuid) {
                      $json_result .= "\"url\": \"javascript:delete_calendar(" . $idcalendar . ");\",";
                    }

                    switch ($subjet) {
                      case 'Homework':
                        $json_result .= "\"class\": \"event-info\",";
                        break;
                      case 'Test':
                        $json_result .= "\"class\": \"event-success\",";
                        break;
                      case 'Exam':
                        $json_result .= "\"class\": \"event-warning\",";
                        break;
                      case 'Other':
                        $json_result .= "\"class\": \"event-special\",";
                        break;
                      default:
                        $json_result .= "\"class\": \"event-important\",";
                        break;
                    }
                    //   print($date);
                    //   $date = new DateTime($date);
                    // $date->add(new DateInterval('P1D'));
                    // print($date);

                    $json_result .= "\"start\": \"" . strtotime($date . ' + 1 days') * 1000 . "\",";
                    $json_result .= "\"end\": \"" . strtotime($date . ' + 1 days') * 1000 . "\"";

                    $json_result .= "}";
                  }

                  $json_result .= "]";
                  $json_result .= "}";
                } else {
                  $json_result .= "{";
                  $json_result .= "\"success\": 0,";
                  $json_result .= "\"result\": []";
                  $json_result .= "}";
                }
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $json_result = $result;
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

              $json_result = $result;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $json_result = $result;
          }
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();

        $json_result = $result;
      }

      return $json_result;
    }
  }
}
