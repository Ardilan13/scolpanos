<?php


class spn_opmerking
{

  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";

  public $sp_create_opmerking = "sp_create_opmerking";
  public $sp_read_opmerking = "sp_read_opmerking";
  public $sp_update_opmerking = "sp_update_opmerking";


  public $debug = true;
  public $error = "";
  public $errormessage = "";


  function create_opmerking($id_student, $SchoolJaar, $SchoolID, $klas, $opmerking1, $opmerking2, $opmerking3, $advies, $dummy)
  {
    $result = 0;

    require_once("DBCreds.php");

    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      if ($dummy == 1)
        $result = 1;
      else {

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

        if ($stmt = $mysqli->prepare("CALL " . $this->sp_create_opmerking . " (?,?,?,?,?,?,?,?)")) {

          if ($stmt->bind_param("isisssss", $id_student, $SchoolJaar, $SchoolID, $klas, $opmerking1, $opmerking2, $opmerking3, $advies)) {
            if ($stmt->execute()) {
              // Audit by Caribe Developers
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'opmerking', 'create opmerking', appconfig::GetDummy());
              $result = 1;
              $stmt->close();
              $mysqli->close();
            } else {
              $result = 0;
              echo $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
    } catch (Exception $e) {
      $result = -2;
      echo $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }
  function update_opmerking($id_student, $SchoolJaar, $SchoolID, $klas, $opmerking1, $opmerking2, $opmerking3, $advies, $dummy)
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

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

        if ($stmt = $mysqli->prepare("CALL " . $this->sp_update_opmerking . " (?,?,?,?,?,?,?,?)")) {

          if ($stmt->bind_param("isisssss", $id_student, $SchoolJaar, $SchoolID, $klas, $opmerking1, $opmerking2, $opmerking3, $advies)) {
            if ($stmt->execute()) {
              // Audit by Caribe Developers
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'Opmerking', 'update opmerking', appconfig::GetDummy());
              $result = 1;
              $stmt->close();
              $mysqli->close();
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }
  function read_opmerking($SchoolJaar, $id_student, $SchoolID, $klas)
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
    $json_result = "";

    mysqli_report(MYSQLI_REPORT_STRICT);


    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

      //if($stmt=$mysqli->prepare("CALL " . $this->sp_create_event . " (?,?,?,?,?,?,?,?)"))

      if ($select = $mysqli->prepare("CALL " . $this->sp_read_opmerking . " (?,?,?,?)")) {
        if ($select->bind_param("siis", $SchoolJaar, $id_student, $SchoolID, $klas)) {
          if ($select->execute()) {
            $this->error = false;
            $result = 1;

            $select->bind_result($id_opmerking, $studentid, $opmerking_1, $opmerking_2, $opmerking_3, $advies);

            $select->store_result();
            if ($select->num_rows > 0) {
              $json_result = array();

              while ($select->fetch()) {

                $json_result[] = array("id_opmerking" => $id_opmerking, "opmerking_1" => $opmerking_1, "opmerking_2" => $opmerking_2, "opmerking_3" => $opmerking_3, "advies" => $advies);
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
  function check_opmerking_student($id_student, $SchoolJaar, $SchoolID, $klas)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "SELECT id_opmerking FROM opmerking where studentid = ? and SchoolID = ? and schooljaar = ? and klas=?";

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("iiss", $id_student, $SchoolID, $SchoolJaar, $klas)) {
          if ($select->execute()) {

            $this->error = false;
            $result = 1;

            $select->store_result();

            $returnvalue = $select->num_rows;
          } else {
            /* error executing query */
            $this->error = true;
            $this->mysqlierror = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->mysqlierror = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->mysqlierror = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }
    } catch (Exception $e) {
      $this->error = true;
      $this->mysqlierror = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        //print "exception: " . $e->getMessage();
        $result = 0;
        return $result;
      }
    }

    return $returnvalue;
  }
  function save_opmerking($id_student, $SchoolJaar, $SchoolID, $klas, $opmerking1, $opmerking2, $opmerking3, $advies, $dummy)
  {

    $opmerking_count = $this->check_opmerking_student($id_student, $SchoolJaar, $SchoolID, $klas);
    if ($opmerking_count == 1) {

      return $this->update_opmerking($id_student, $SchoolJaar, $SchoolID, $klas, $opmerking1, $opmerking2, $opmerking3, $advies, $dummy);
    } else {

      return $this->create_opmerking($id_student, $SchoolJaar, $SchoolID, $klas, $opmerking1, $opmerking2, $opmerking3, $advies, $dummy);
    }
  }
  function liststudentbyclass($class, $schoolid, $all, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";

    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);
        $mysqli->set_charset('utf8');


        if ($stmt = $mysqli->prepare("CALL sp_read_studentbyclass (?,?)")) {
          if ($stmt->bind_param("si", $class, $schoolid)) {
            if ($stmt->execute()) {
              $this->error = false;
              $result = 1;
              $stmt->bind_result($id_student, $id_family, $student_name);
              $stmt->store_result();

              if ($stmt->num_rows > 0) {
                if ($all == 1) {
                  $htmlcontrol .= "<option value='all' id=\"all\"selected>All</option>";
                } else {
                  $htmlcontrol .= "<option id=\"selectOneStudent\"selected>Select One Student</option>";
                }
                while ($stmt->fetch()) {
                  if ($id_family != null && $id_family != 0 && $id_family != "") {
                    $htmlcontrol .= "<option family=" . htmlentities($id_family) . " value=" . htmlentities($id_student) . ">" . htmlentities($student_name) . "</option>";
                  } else {
                    $htmlcontrol .= "<option value=" . htmlentities($id_student) . ">" . htmlentities($student_name) . "</option>";
                  }
                }
              } else {
                $htmlcontrol .= "No results to show";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }
  function print_opmerking($SchoolJaar, $id_student, $SchoolID, $klas)
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
    $json_result = "";

    mysqli_report(MYSQLI_REPORT_STRICT);


    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

      //if($stmt=$mysqli->prepare("CALL " . $this->sp_create_event . " (?,?,?,?,?,?,?,?)"))

      if ($select = $mysqli->prepare("CALL " . $this->sp_read_opmerking . " (?,?,?,?)")) {
        if ($select->bind_param("siis", $SchoolJaar, $id_student, $SchoolID, $klas)) {
          if ($select->execute()) {
            $this->error = false;
            $result = 1;

            $select->bind_result($id_opmerking, $studentid, $opmerking_1, $opmerking_2, $opmerking_3);

            $select->store_result();
            if ($select->num_rows > 0) {
              $htmlcontrol = "";

              while ($select->fetch()) {
                $htmlcontrol .= "<div class=\"row\">";
                $htmlcontrol .= "<div class=\"col-md-12 full-inset\">";
                $htmlcontrol .= "<div class=\"sixth-bg-color brd-full\">";
                $htmlcontrol .= "<div class=\"box\">";
                $htmlcontrol .= "<div class=\"box-content full-inset\">";
                $htmlcontrol .= "<div class=\"col-md-2\">";
                $htmlcontrol .= "<label>Opmerking rap 1:</label>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "<div class=\"form-group\">";
                $htmlcontrol .= "<div class=\"col-md-8\">";
                $htmlcontrol .= "<textarea class=\"form-control\" name=\"opmerking_1\" id=\"opmerking_1\">" . htmlentities($opmerking_1) . "</textarea>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "<div class=\"col-md-2\">";
                $htmlcontrol .= "<label>Opmerking rap 2:</label>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "<div class=\"form-group\">";
                $htmlcontrol .= "<div class=\"col-md-8\">";
                $htmlcontrol .= "<textarea class=\"form-control\" name=\"opmerking_2\" id=\"opmerking_2\">" . htmlentities($opmerking_2) . "</textarea>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "<div class=\"col-md-2\">";
                $htmlcontrol .= "<label>Opmerking rap 3:</label>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "<div class=\"form-group\">";
                $htmlcontrol .= "<div class=\"col-md-8\">";
                $htmlcontrol .= "<textarea class=\"form-control\" name=\"opmerking_3\" id=\"opmerking_3\">" . htmlentities($opmerking_3) . "</textarea>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "<div class=\"form-group pull-right\">";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "<br>";
                $htmlcontrol .= "<br>";
                $htmlcontrol .= "<div class=\"data-display\" style=\"overflow-x:auto\"></div>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "<br/>";
                $htmlcontrol .= "</div>";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

              $htmlcontrol = $result;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $htmlcontrol = $result;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;

          $htmlcontrol = $result;
        }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;

        $htmlcontrol = $result;
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
      $result = $e->getMessage();

      $htmlcontrol = $result;
    }

    return $htmlcontrol;
  }
}
