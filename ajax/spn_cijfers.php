<?php
require_once("spn_audit.php");
require_once("spn_setting.php");
class spn_cijfers
{
  public $tablename_cijfers = "le_cijfers";
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $debug = true;
  public $sp_update_le_cijfersextra = "sp_update_le_cijfersextra";
  public $sp_read_le_cijfersextra = "sp_read_le_cijfersextra";
  public $sp_read_le_cijferswaarde = "sp_read_le_cijferswaarde";
  public $sp_get_cijfers_by_student = "sp_get_cijfers_by_student";
  public $sp_read_le_cijfer_graph = "sp_read_le_cijfer_graph";
  public $sp_read_le_cijfer_graph_by_student = "sp_read_le_cijfer_graph_by_student";
  public $sp_read_le_cijfer_graph_by_class = "sp_read_le_cijfer_graph_by_class";
  //TEACHER
  public $sp_get_schools_by_teacher = 'sp_get_schools_by_teacher';
  public $sp_get_vak_by_teacher = 'sp_get_vak_by_teacher';
  public $sp_get_class_by_teacher = 'sp_get_class_by_teacher';


  //select s.id, c.id, s.class, s.firstname, s.lastname, s.sex, c.c1, c.c2, c.c3, c.c4, c.c5, c.c6, c.c7, c.c8, c.c9, c.c10, c.c11, c.c12, c.c13, c.c14, c.c15, c.c16, c.c17, c.c18, c.c19, c.c20, c.gemiddelde from students s left join le_cijfers c on s.id = c.studentid left join le_vakken v on v.vakid = c.vak where s.class = "6A" and (c.rapnummer = "2" or c.rapnummer is null) or (v.vakid = 21 or v.vakid = null) order by s.firstname asc;

  function createcijfers($studentid, $schooljaar, $rapnummer, $vak, $klas, $user, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($stmt = $mysqli->prepare("insert into " . $this->tablename_cijfers . "(created,studentid,schooljaar,rapnummer,vak,klas,user,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,c15,c16,c17,c18,c19,c20,gemiddelde) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
        if ($stmt->bind_param("sisisssiiiiiiiiiiiiiiiiiiiii", $_DateTime, $studentid, $schooljaar, $rapnummer, $vak, $klas, $user, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'create cijfers', appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */

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
    } catch (Exception $e) {
      $result = -2;
      //  $this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }

  function editcijfers($cijfersid, $studentid, $schooljaar, $rapnummer, $vak, $klas, $user, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 0;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($stmt = $mysqli->prepare("update " . $this->tablename_cijfers . " set lastchanged = ?, studentid = ?, schooljaar = ?, rapnummer = ?, vak = ?, klas = ?, user =?, c1 = ?, c2 = ?, c3 = ?, c4 = ?, c5 = ?, c6 = ?, c7 = ?, c8 = ?, c9 = ?, c10 = ?, c11 = ?, c12 = ?, c13 = ?, c14 = ?, c15 = ?, c16 = ?, c17 = ?, c18 = ?, c19 = ?, c20 = ?, gemiddelde = ? where id = ?")) {
        if ($stmt->bind_param("sisisssiiiiiiiiiiiiiiiiiiiiii", $_DateTime, $studentid, $schooljaar, $rapnummer, $vak, $klas, $user, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde, $cijfersid)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'update cijfers', appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            if ($mysqli->affected_rows >= 1) {
              $result = 1;
            }
            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
        }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
      }
    } catch (Exception $e) {
      $result = -2;
      //    $this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  function listcijfers($schoolid, $klas_in, $vak_in, $rap_in, $sort_order, $schooljaar)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $htmlcijferswaarde = "";

    // if($this->debug)
    // {
    //   print "schoolid: " . $schoolid . "<br />";
    //   print "klas_in: " . $klas_in . "<br />";
    //   print "vak_in: " . $vak_in . "<br />";
    //   print "rap_in: " . $rap_in . "<br />";
    //   print "sort_order: " . $sort_order . "<br />";
    // }

    mysqli_report(MYSQLI_REPORT_STRICT);

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    if ($_SESSION['SchoolID'] == 8 || $_SESSION["SchoolID"] == 18) {
      $sql_query = "SELECT distinct s.id, c.id, s.class, s.firstname, s.lastname, s.sex, c.c1, c.c2, c.c3, c.c4, c.c5, c.c6, c.c7, c.c8, c.c9, c.c10, c.c11, c.c12, c.c13, c.c14, c.c15, c.c16, c.c17, c.c18, c.c19, c.c20, c.gemiddelde FROM students s LEFT JOIN le_cijfers c ON s.id = c.studentid LEFT JOIN le_vakken v ON c.vak = v.id WHERE s.class = ? AND v.id = ? AND c.rapnummer = ? AND c.schooljaar = ? ORDER BY ";
    } else {
      $sql_query = "SELECT distinct s.id, c.id, s.class, s.firstname, s.lastname, s.sex, c.c1, c.c2, c.c3, c.c4, c.c5, c.c6, c.c7, c.c8, c.c9, c.c10, c.c11, c.c12, c.c13, c.c14, c.c15, c.c16, c.c17, c.c18, c.c19, c.c20, c.gemiddelde FROM students s LEFT JOIN le_cijfers_ps c ON s.id = c.studentid AND s.schoolid = ?  LEFT JOIN le_vakken_ps v ON c.vak = v.id WHERE s.class = ? AND v.id = ? AND c.rapnummer = ? AND c.schooljaar = ? ORDER BY ";
    }

    $sql_order = " s.lastname, s.firstname ";
    if ($s->_setting_mj) {
      $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)


    /* TODO: QUERY NEEDS TO BE FIXED , EL */


    try {
      require_once("DBCreds.php");
      require_once("spn_utils.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("isiss", $schoolid, $klas_in, $vak_in, $rap_in, $schooljaar)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers', appconfig::GetDummy());
            $this->error = false;
            $result = 1;

            $select->bind_result($studentid, $cijferid, $klas, $firstname, $lastname, $sex, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde);
            $select->store_result();

            if ($select->num_rows > 0) {

              //BEGIN le_cijferswaarde (ejaspe@caribedev)
              $u = new spn_utils();
              if ($stmtcijferswaarde = $mysqli->prepare("CALL " . $this->sp_read_le_cijferswaarde . " (?,?,?,?)")) {
                if ($stmtcijferswaarde->bind_param("ssis", $klas_in, $vak_in, $rap_in, $schooljaar)) {
                  if ($stmtcijferswaarde->execute()) {
                    $stmtcijferswaarde->bind_result($cijferswaardeid, $klas, $schooljaar, $rapnummer, $vak, $cijferswaarde1, $cijferswaarde2, $cijferswaarde3, $cijferswaarde4, $cijferswaarde5, $cijferswaarde6, $cijferswaarde7, $cijferswaarde8, $cijferswaarde9, $cijferswaarde10, $cijferswaarde11, $cijferswaarde12, $cijferswaarde13, $cijferswaarde14, $cijferswaarde15, $cijferswaarde16, $cijferswaarde17, $cijferswaarde18, $cijferswaarde19, $cijferswaarde20);
                    $stmtcijferswaarde->store_result();

                    if ($stmtcijferswaarde->num_rows > 0) {
                      if ($rap_in == 1) {
                        if ($_SESSION["UserRights"] != "ASSISTENT") {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      } elseif ($rap_in == 2) {
                        if ($_SESSION["UserRights"] != "ASSISTENT") {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      } elseif ($rap_in == 3) {
                        if ($_SESSION["UserRights"] != "ASSISTENT") {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      }
                    } else {
                      // Changes settings (ladalan@caribedev)

                      if (($s->_setting_rapnumber_1 && $rap_in != 1 && $_SESSION["UserRights"] != "ASSISTENT") || ($s->_setting_rapnumber_2 && $rap_in == 2 && $_SESSION["UserRights"] != "ASSISTENT") || ($s->_setting_rapnumber_3 && $rap_in == 3 && $_SESSION["UserRights"] != "ASSISTENT")) {
                        //NO DATA FIELDS
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                      } else {
                        //NO DATA FIELDS
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                      }
                    }

                    // End changes settings (laldana@caribedev)
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
              //END le_cijferswaarde


              /* begin drawing table */
              $htmlcontrol .= "<div class=\"table-responsive\">
              <table id=\"vak\" class=\"table table-bordered table-colored table-vak\">
              <thead>
              <tr>


              <th colspan=\"2\">Vul hierboven de bijhorende norm: </th>";
              // <th class=\"btn-m-w\"></th>";


              $htmlcontrol .= $htmlcijferswaarde;

              // Changes settings (ladalan@caribedev)

              if (($rap_in == 1 && $_SESSION["UserRights"] != "ASSISTENT") || ($rap_in == 2 && $_SESSION["UserRights"] != "ASSISTENT") || ($rap_in == 3 && $_SESSION["UserRights"] != "ASSISTENT")) {

                $htmlcontrol .= "<th></th>
                </tr>
                <tr class=\"text-align-center\">
                <th>#</th>
                <th class=\"btn-m-w\">Naam</th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"1\">1 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"2\">2 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"3\">3 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"4\">4 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"5\">5 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"6\">6 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"7\">7 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"8\">8 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"9\">9 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"10\">10 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"11\">11 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"12\">12 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"13\">13 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"14\">14 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"15\">15 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"16\">16 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"17\">17 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"18\">18 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"19\">19 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"20\">20 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                Gemiddeld
                </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                <td></td>
                <td class=\"btn-w-m align-right\">Gemiddeld</td>
                <td><div id=\"gemiddeld_1\">0</div></td>
                <td><div id=\"gemiddeld_2\">0</div></td>
                <td><div id=\"gemiddeld_3\">0</div></td>
                <td><div id=\"gemiddeld_4\">0</div></td>
                <td><div id=\"gemiddeld_5\">0</div></td>
                <td><div id=\"gemiddeld_6\">0</div></td>
                <td><div id=\"gemiddeld_7\">0</div></td>
                <td><div id=\"gemiddeld_8\">0</div></td>
                <td><div id=\"gemiddeld_9\">0</div></td>
                <td><div id=\"gemiddeld_10\">0</div></td>
                <td><div id=\"gemiddeld_11\">0</div></td>
                <td><div id=\"gemiddeld_12\">0</div></td>
                <td><div id=\"gemiddeld_13\">0</div></td>
                <td><div id=\"gemiddeld_14\">0</div></td>
                <td><div id=\"gemiddeld_15\">0</div></td>
                <td><div id=\"gemiddeld_16\">0</div></td>
                <td><div id=\"gemiddeld_17\">0</div></td>
                <td><div id=\"gemiddeld_18\">0</div></td>
                <td><div id=\"gemiddeld_19\">0</div></td>
                <td><div id=\"gemiddeld_20\">0</div></td>
                <td><div id=\"gemiddeld_total\">0</div></td>
                </tr>
                </tfoot>
                <tbody>";
              } else {
                $htmlcontrol .= "<th></th>
                </tr>
                <tr class=\"text-align-center\">
                <th>#</th>
                <th class=\"btn-m-w\">Naam</th>
                <th>
                1
                </th>
                <th>
                2
                </th>
                <th>
                3
                </th>
                <th>
                4
                </th>
                <th>
                5
                </th>
                <th>
                6
                </th>
                <th>
                7
                </th>
                <th>
                8
                </th>
                <th>
                9
                </th>
                <th>
                10
                </th>
                <th>
                11
                </th>
                <th>
                12
                </th>
                <th>
                13
                </th>
                <th>
                14
                </th>
                <th>
                15
                </th>
                <th>
                16
                </th>
                <th>
                17
                </th>
                <th>
                18
                </th>
                <th>
                19
                </th>
                <th>
                20
                </th>
                <th>
                Gemiddeld
                </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                <td></td>
                <td class=\"btn-w-m align-right\">Gemiddeld</td>
                <td><div id=\"gemiddeld_1\">0</div></td>
                <td><div id=\"gemiddeld_2\">0</div></td>
                <td><div id=\"gemiddeld_3\">0</div></td>
                <td><div id=\"gemiddeld_4\">0</div></td>
                <td><div id=\"gemiddeld_5\">0</div></td>
                <td><div id=\"gemiddeld_6\">0</div></td>
                <td><div id=\"gemiddeld_7\">0</div></td>
                <td><div id=\"gemiddeld_8\">0</div></td>
                <td><div id=\"gemiddeld_9\">0</div></td>
                <td><div id=\"gemiddeld_10\">0</div></td>
                <td><div id=\"gemiddeld_11\">0</div></td>
                <td><div id=\"gemiddeld_12\">0</div></td>
                <td><div id=\"gemiddeld_13\">0</div></td>
                <td><div id=\"gemiddeld_14\">0</div></td>
                <td><div id=\"gemiddeld_15\">0</div></td>
                <td><div id=\"gemiddeld_16\">0</div></td>
                <td><div id=\"gemiddeld_17\">0</div></td>
                <td><div id=\"gemiddeld_18\">0</div></td>
                <td><div id=\"gemiddeld_19\">0</div></td>
                <td><div id=\"gemiddeld_20\">0</div></td>
                <td><div id=\"gemiddeld_total\">0</div></td>
                </tr>
                </tfoot>
                <tbody>";
              }

              // End changes settings (ladalan@caribedev)

              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-cijfer attribute */
              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {

                $htmlcontrol .= "<tr><td>$x</td><td><input class=\"hidden\" studentidarray=\"$studentid\"></input>" . utf8_encode($firstname) . chr(32) . utf8_encode($lastname) . "</td>";

                for ($y = 1; $y <= 20; $y++) {
                  $htmlcontrol .= "";

                  $_cijfer_number = 0;
                  /* check dimension of the cijfers */
                  switch ($y) {
                    case 1:
                      $c1 = ($c1 == 0.0 ? "" : $c1);
                      $_cijfer_number = $c1;
                      break;

                    case 2:
                      $c2 = ($c2 == 0.0 ? "" : $c2);
                      $_cijfer_number = $c2;
                      break;

                    case 3:
                      $c3 = ($c3 == 0.0 ? "" : $c3);
                      $_cijfer_number = $c3;
                      break;

                    case 4:
                      $c4 = ($c4 == 0.0 ? "" : $c4);
                      $_cijfer_number = $c4;
                      break;

                    case 5:
                      $c5 = ($c5 == 0.0 ? "" : $c5);
                      $_cijfer_number = $c5;
                      break;

                    case 6:
                      $c6 = ($c6 == 0.0 ? "" : $c6);
                      $_cijfer_number = $c6;
                      break;

                    case 7:
                      $c7 = ($c7 == 0.0 ? "" : $c7);
                      $_cijfer_number = $c7;
                      break;

                    case 8:
                      $c8 = ($c8 == 0.0 ? "" : $c8);
                      $_cijfer_number = $c8;
                      break;

                    case 9:
                      $c9 = ($c9 == 0.0 ? "" : $c9);
                      $_cijfer_number = $c9;
                      break;

                    case 10:
                      $c10 = ($c10 == 0.0 ? "" : $c10);
                      $_cijfer_number = $c10;
                      break;

                    case 11:
                      $c11 = ($c11 == 0.0 ? "" : $c11);
                      $_cijfer_number = $c11;
                      break;

                    case 12:
                      $c12 = ($c12 == 0.0 ? "" : $c12);
                      $_cijfer_number = $c12;
                      break;

                    case 13:
                      $c13 = ($c13 == 0.0 ? "" : $c13);
                      $_cijfer_number = $c13;
                      break;

                    case 14:
                      $c14 = ($c14 == 0.0 ? "" : $c14);
                      $_cijfer_number = $c14;
                      break;

                    case 15:
                      $c15 = ($c15 == 0.0 ? "" : $c15);
                      $_cijfer_number = $c15;
                      break;

                    case 16:
                      $c16 = ($c16 == 0.0 ? "" : $c16);
                      $_cijfer_number = $c16;
                      break;

                    case 17:
                      $c17 = ($c17 == 0.0 ? "" : $c17);
                      $_cijfer_number = $c17;
                      break;

                    case 18:
                      $c18 = ($c18 == 0.0 ? "" : $c18);
                      $_cijfer_number = $c18;
                      break;

                    case 19:
                      $c19 = ($c19 == 0.0 ? "" : $c19);
                      $_cijfer_number = $c19;
                      break;

                    case 20:
                      $c20 = ($c20 == 0.0 ? "" : $c20);
                      $_cijfer_number = $c20;
                      break;

                    default:
                      $_cijfer_number = 0;
                      break;
                  }

                  // Changes settings (ladalan@caribedev)

                  if (($rap_in == 1 && $_SESSION["UserRights"] != "ASSISTENT") || ($rap_in == 2 && $_SESSION["UserRights"] != "ASSISTENT") || ($rap_in == 3 && $_SESSION["UserRights"] != "ASSISTENT")) {
                    $cell = 'x' . $x . 'y' . $y;
                    $htmlcontrol .= "<td><span id=\"lblName1\" disabled='true' data-row=\"$x\" id_cell_cijfer= \"$cell\" id_cijfer_table = \"$cijferid\" data-student-id=\"$studentid\" data-cijfer=\"c$y\" data-klas=\"$klas_in\" data-vak=\"$vak_in\" data-rapport=\"$rap_in\" class=\"editable\">$_cijfer_number</span></td>";
                  } else {
                    $cell = 'x' . $x . 'y' . $y;
                    $htmlcontrol .= "<td><span id=\"lblName1\" data-row=\"$x\" id_cell_cijfer= \"$cell\" id_cijfer_table = \"$cijferid\" data-student-id=\"$studentid\" data-cijfer=\"c$y\" data-klas=\"$klas_in\" data-vak=\"$vak_in\" data-rapport=\"$rap_in\">$_cijfer_number</span></td>";
                  }

                  // End changes settings (ladalan@caribedev)

                  $xx++;
                }

                $htmlcontrol .= "<td id=\"ge$x\">$gemiddelde</td></tr>";

                /* increment variable with one */
                $x++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
              $htmlcontrol .= "</div>";
              //BEGIN CaribeDevelopers - Janio Acero
              $u = new spn_utils();
              $mysqli->close();
              $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
              if ($stmt = $mysqli->prepare("CALL " . $this->sp_read_le_cijfersextra . " (?,?,?,?)")) {
                if ($stmt->bind_param("ssis", $klas_in, $vak_in, $rap_in, $schooljaar)) {
                  if ($stmt->execute()) {
                    $stmt->bind_result(
                      $cijfersextraid,
                      $klas,
                      $schooljaar,
                      $rapnummer,
                      $vak,
                      $oc1,
                      $oc2,
                      $oc3,
                      $oc4,
                      $oc5,
                      $oc6,
                      $oc7,
                      $oc8,
                      $oc9,
                      $oc10,
                      $oc11,
                      $oc12,
                      $oc13,
                      $oc14,
                      $oc15,
                      $oc16,
                      $oc17,
                      $oc18,
                      $oc19,
                      $oc20,
                      $dc1,
                      $dc2,
                      $dc3,
                      $dc4,
                      $dc5,
                      $dc6,
                      $dc7,
                      $dc8,
                      $dc9,
                      $dc10,
                      $dc11,
                      $dc12,
                      $dc13,
                      $dc14,
                      $dc15,
                      $dc16,
                      $dc17,
                      $dc18,
                      $dc19,
                      $dc20
                    );
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                      while ($stmt->fetch()) {
                        $htmlcontrol .= "<input type=\"hidden\" id=\"id_cijfersextra\" name=\"id_cijfersextra\" value=\"" . htmlentities($cijfersextraid) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"klas_cijfersextra\" name=\"klas_cijfersextra\" value=\"" . htmlentities($klas) . "\">";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"schooljaar_cijfersextra\" name=\"schooljaar_cijfersextra\" value=\"" . htmlentities($schooljaar) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"rapnummer_cijfersextra\" name=\"rapnummer_cijfersextra\" value=\"" . htmlentities($rapnummer) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"vak_cijfersextra\" name=\"vak_cijfersextra\" value=\"" . htmlentities($vak) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc1_cijfersextra\" name=\"oc1_cijfersextra\" value=\"" . htmlentities($oc1) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc2_cijfersextra\" name=\"oc2_cijfersextra\" value=\"" . htmlentities($oc2) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc3_cijfersextra\" name=\"oc3_cijfersextra\" value=\"" . htmlentities($oc3) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc4_cijfersextra\" name=\"oc4_cijfersextra\" value=\"" . htmlentities($oc4) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc5_cijfersextra\" name=\"oc5_cijfersextra\" value=\"" . htmlentities($oc5) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc6_cijfersextra\" name=\"oc6_cijfersextra\" value=\"" . htmlentities($oc6) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc7_cijfersextra\" name=\"oc7_cijfersextra\" value=\"" . htmlentities($oc7) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc8_cijfersextra\" name=\"oc8_cijfersextra\" value=\"" . htmlentities($oc8) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc9_cijfersextra\" name=\"oc9_cijfersextra\" value=\"" . htmlentities($oc9) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc10_cijfersextra\" name=\"oc10_cijfersextra\" value=\"" . htmlentities($oc10) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc11_cijfersextra\" name=\"oc11_cijfersextra\" value=\"" . htmlentities($oc11) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc12_cijfersextra\" name=\"oc12_cijfersextra\" value=\"" . htmlentities($oc12) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc13_cijfersextra\" name=\"oc13_cijfersextra\" value=\"" . htmlentities($oc13) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc14_cijfersextra\" name=\"oc14_cijfersextra\" value=\"" . htmlentities($oc14) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc15_cijfersextra\" name=\"oc15_cijfersextra\" value=\"" . htmlentities($oc15) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc16_cijfersextra\" name=\"oc16_cijfersextra\" value=\"" . htmlentities($oc16) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc17_cijfersextra\" name=\"oc17_cijfersextra\" value=\"" . htmlentities($oc17) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc18_cijfersextra\" name=\"oc18_cijfersextra\" value=\"" . htmlentities($oc18) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc19_cijfersextra\" name=\"oc19_cijfersextra\" value=\"" . htmlentities($oc19) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc20_cijfersextra\" name=\"oc20_cijfersextra\" value=\"" . htmlentities($oc20) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc1_cijfersextra\" name=\"dc1_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc1)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc2_cijfersextra\" name=\"dc2_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc2)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc3_cijfersextra\" name=\"dc3_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc3)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc4_cijfersextra\" name=\"dc4_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc4)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc5_cijfersextra\" name=\"dc5_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc5)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc6_cijfersextra\" name=\"dc6_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc6)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc7_cijfersextra\" name=\"dc7_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc7)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc8_cijfersextra\" name=\"dc8_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc8)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc9_cijfersextra\" name=\"dc9_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc9)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc10_cijfersextra\" name=\"dc10_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc10)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc11_cijfersextra\" name=\"dc11_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc11)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc12_cijfersextra\" name=\"dc12_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc12)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc13_cijfersextra\" name=\"dc13_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc13)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc14_cijfersextra\" name=\"dc14_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc14)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc15_cijfersextra\" name=\"dc15_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc15)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc16_cijfersextra\" name=\"dc16_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc16)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc17_cijfersextra\" name=\"dc17_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc17)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc18_cijfersextra\" name=\"dc18_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc18)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc19_cijfersextra\" name=\"dc19_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc19)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc20_cijfersextra\" name=\"dc20_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc20)) . "\"/>";
                      }
                    } else {
                      //POR AHORA NADA
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
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $result = $mysqli->error;
              }
              //END CaribeDevelopers - Janio Acero
            } else {
              $htmlcontrol .= "No results to show";
            }
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


      // $htmlcontrol .= "<input type=\"hidden\" id=\"klas_cijferswaarde\" name=\"klas_cijferswaarde\" value=\"" . htmlentities($klas_in) . "\">";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"schooljaar_cijferswaarde\" name=\"schooljaar_cijferswaarde\" value=\"" . htmlentities($schooljaar) . "\"/>";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"rapnummer_cijferswaarde\" name=\"rapnummer_cijferswaarde\" value=\"" . htmlentities($rap_in) . "\"/>";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"vak_cijferswaarde\" name=\"vak_cijferswaarde\" value=\"" . htmlentities($vak_in) . "\"/>";



      $returnvalue = $htmlcontrol;
      return $returnvalue;
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

  function savecijfer($schooljaar, $schoolid, $id_cijfer, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in)
  {

    $cijfer_count = $this->_getcijfercount("", $studentid_in, $klas_in, $rap_in, $vak_in);
    if ($cijfer_count == 1) {
      /* TODO: Validate  */
      if ($cijfer_number_in == "c1" || $cijfer_number_in == "c2" || $cijfer_number_in == "c3" ||  $cijfer_number_in == "c4" || $cijfer_number_in == "c5" || $cijfer_number_in == "c6" || $cijfer_number_in == "c7" || $cijfer_number_in == "c8" || $cijfer_number_in == "c9" || $cijfer_number_in == "c10" ||  $cijfer_number_in == "c11" || $cijfer_number_in == "c12" || $cijfer_number_in == "c13" || $cijfer_number_in == "c14" || $cijfer_number_in == "c15" || $cijfer_number_in == "c16" || $cijfer_number_in == "c17" ||  $cijfer_number_in == "c18" || $cijfer_number_in == "c19" || $cijfer_number_in == "c20") {
        if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
          return $this->_updatecijfer_ps($schooljaar, $schoolid, $id_cijfer, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in);
        } else {
          return $this->_updatecijfer($schooljaar, $schoolid, $id_cijfer, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in);
        }
      }
    } else {
      /* TODO: Validate  */
      if ($cijfer_number_in == "c1" || $cijfer_number_in == "c2" || $cijfer_number_in == "c3" ||  $cijfer_number_in == "c4" || $cijfer_number_in == "c5" || $cijfer_number_in == "c6" || $cijfer_number_in == "c7" || $cijfer_number_in == "c8" || $cijfer_number_in == "c9" || $cijfer_number_in == "c10" ||  $cijfer_number_in == "c11" || $cijfer_number_in == "c12" || $cijfer_number_in == "c13" || $cijfer_number_in == "c14" || $cijfer_number_in == "c15" || $cijfer_number_in == "c16" || $cijfer_number_in == "c17" ||  $cijfer_number_in == "c18" || $cijfer_number_in == "c19" || $cijfer_number_in == "c20") {
        if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
          return $this->_updatecijfer_ps($schooljaar, $schoolid, $id_cijfer, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in);
        } else {
          return $this->_updatecijfer($schooljaar, $schoolid, $id_cijfer, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in);
        }
        //return $this->_insertcijfer($schooljaar, $schoolid,$studentid_in,$cijfer_number_in,$cijfer_value_in,$klas_in,$rap_in,$vak_in);
      }
    }
  }

  function _getcijfercount($schoolid, $studentid_in, $klas_in, $rap_in, $vak_in)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select c.id from le_cijfers c where c.studentid = ? and c.rapnummer = ? and c.vak = ?";

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("sss", $studentid_in, $rap_in, $vak_in)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'read cijfers count', appconfig::GetDummy());
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

  function getgemiddelde($schoolid, $studentid_in, $klas_in, $rap_in, $vak_in)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select c.gemiddelde from le_cijfers c where c.studentid = ? and c.rapnummer = ? and c.vak = ?";

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("sss", $studentid_in, $rap_in, $vak_in)) {
          if ($select->execute()) {
            // $spn_audit = new spn_audit();
            // $UserGUID = $_SESSION['UserGUID'];
            // $spn_audit->create_audit($UserGUID, 'cijfers','read cijfers middelde',appconfig::GetDummy());
            $this->error = false;
            $result = 1;

            $select->bind_result($gemiddelde);
            $select->store_result();

            while ($select->fetch()) {
              $returnvalue = $gemiddelde;
            }
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

  function getgemiddelde_ps($schoolid, $studentid_in, $klas_in, $rap_in, $vak_in)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select c.gemiddelde from le_cijfers_ps c where c.studentid = ? and c.rapnummer = ? and c.vak = ?";

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("sss", $studentid_in, $rap_in, $vak_in)) {
          if ($select->execute()) {
            // $spn_audit = new spn_audit();
            // $UserGUID = $_SESSION['UserGUID'];
            // $spn_audit->create_audit($UserGUID, 'cijfers','read cijfers middelde',appconfig::GetDummy());
            $result = 1;

            $select->bind_result($gemiddelde);
            $select->store_result();

            while ($select->fetch()) {
              $returnvalue = $gemiddelde;
            }
          } else {
            /* error executing query */
            $this->mysqlierror = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->mysqlierror = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->mysqlierror = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }
    } catch (Exception $e) {
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

  function _updatecijfer($schooljaar, $schoolid, $id_cijfers, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      //update le_cijfer h inner join students s on h.studentid = s.id set h.h1 = 3 where s.id = 24 and s.class = "1A" and h.rapnummer = 1
      if ($stmt = $mysqli->prepare("update" . chr(32) . $this->tablename_cijfers . chr(32) . "c join students s on c.studentid = s.id set c.schooljaar = ?," . chr(32) . "c." . $cijfer_number_in . chr(32) . " = ? where s.id = ? and s.class = ? and c.rapnummer = ? and c.vak = ? and c.ID = ?;")) {
        if ($stmt->bind_param("ssssssi", $schooljaar, $cijfer_value_in, $studentid_in, $klas_in, $rap_in, $vak_in, $id_cijfers)) {
          if ($stmt->execute()) {
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            // function _savecijfersgemiddelde($schoolid,$studentid_in,$klas_in,$rap_in,$vak_in)
            $result = $this->_savecijfersgemiddelde($schooljaar, $schoolid, $studentid_in, $klas_in, $rap_in, $vak_in);
            if ($this->debug) {
              print "result = " . $result . "</br>";
              print "sudentid = " . $studentid_in . "</br>";
              print "klas = " . $klas_in . "</br>";
              print "rap = " . $rap_in . "</br>";
              print "vak = " . $vak_in . "</br>";
              print "cijfer = " . $cijfer_value_in . "</br>";
            }
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
    } catch (Exception $e) {
      $result = -2;
      //$this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }

  function _updatecijfer_ps($schooljaar, $schoolid, $id_cijfers, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      //update le_cijfer h inner join students s on h.studentid = s.id set h.h1 = 3 where s.id = 24 and s.class = "1A" and h.rapnummer = 1
      if ($stmt = $mysqli->prepare("update le_cijfers_ps c join students s on c.studentid = s.id set c.schooljaar = ?," . chr(32) . "c." . $cijfer_number_in . chr(32) . " = ? where s.id = ? and s.class = ? and c.rapnummer = ? and c.vak = ? and c.id = ?;")) {
        if ($stmt->bind_param("ssssssi", $schooljaar, $cijfer_value_in, $studentid_in, $klas_in, $rap_in, $vak_in, $id_cijfers)) {
          if ($stmt->execute()) {
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            // function _savecijfersgemiddelde($schoolid,$studentid_in,$klas_in,$rap_in,$vak_in)
            $result = $this->_savecijfersgemiddelde_ps($schooljaar, $schoolid, $studentid_in, $klas_in, $rap_in, $vak_in);
            if ($this->debug) {
              print "result = " . $result . "</br>";
              print "sudentid = " . $studentid_in . "</br>";
              print "klas = " . $klas_in . "</br>";
              print "rap = " . $rap_in . "</br>";
              print "vak = " . $vak_in . "</br>";
              print "cijfer = " . $cijfer_value_in . "</br>";
            }
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
    } catch (Exception $e) {
      $result = -2;
      //$this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }

  function _savecijfersgemiddelde($schooljaar, $schoolid, $studentid_in, $klas_in, $rap_in, $vak_in)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select distinct s.id, c.id, s.class, s.firstname, s.lastname, s.sex,
    c.c1, c.c2, c.c3, c.c4, c.c5, c.c6, c.c7, c.c8, c.c9, c.c10, c.c11, c.c12, c.c13, c.c14, c.c15, c.c16, c.c17, c.c18, c.c19, c.c20,
    cw.c1, cw.c2, cw.c3, cw.c4, cw.c5, cw.c6, cw.c7, cw.c8, cw.c9, cw.c10, cw.c11, cw.c12, cw.c13, cw.c14, cw.c15, cw.c16, cw.c17, cw.c18, cw.c19, cw.c20,
    c.gemiddelde from students s
    left join le_cijfers c on s.id = c.studentid
    left join le_vakken v on c.vak = v.id
    inner join le_cijferswaarde cw on cw.vak = v.id
    and cw.schooljaar = c.schooljaar
    and cw.rapnummer = c.rapnummer
    where s.class = ? and v.id = ?
    and c.rapnummer = ?
    and c.studentid = ?
    and c.schooljaar = ? order by s.firstname asc LIMIT 1";
    /* TODO: QUERY NEEDS TO BE FIXED , EL */


    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siiss", $klas_in, $vak_in, $rap_in, $studentid_in, $schooljaar)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers middelde', appconfig::GetDummy());
            $this->error = false;
            $result = 1;

            $select->bind_result(
              $studentid,
              $cijferid,
              $klas,
              $firstname,
              $lastname,
              $sex,
              $c1,
              $c2,
              $c3,
              $c4,
              $c5,
              $c6,
              $c7,
              $c8,
              $c9,
              $c10,
              $c11,
              $c12,
              $c13,
              $c14,
              $c15,
              $c16,
              $c17,
              $c18,
              $c19,
              $c20,
              $w1,
              $w2,
              $w3,
              $w4,
              $w5,
              $w6,
              $w7,
              $w8,
              $w9,
              $w10,
              $w11,
              $w12,
              $w13,
              $w14,
              $w15,
              $w16,
              $w17,
              $w18,
              $w19,
              $w20,
              $gemiddelde
            );
            $select->store_result();


            if ($select->num_rows == 1) {

              while ($select->fetch()) {
                $gemid = $this->_gemiddeldebasis(
                  $c1,
                  $c2,
                  $c3,
                  $c4,
                  $c5,
                  $c6,
                  $c7,
                  $c8,
                  $c9,
                  $c10,
                  $c11,
                  $c12,
                  $c13,
                  $c14,
                  $c15,
                  $c16,
                  $c17,
                  $c18,
                  $c19,
                  $c20,
                  $w1,
                  $w2,
                  $w3,
                  $w4,
                  $w5,
                  $w6,
                  $w7,
                  $w8,
                  $w9,
                  $w10,
                  $w11,
                  $w12,
                  $w13,
                  $w14,
                  $w15,
                  $w16,
                  $w17,
                  $w18,
                  $w19,
                  $w20
                );

                if ($gemid == 0) {
                  $gemid = NULL;
                }

                //if ($_SESSION['SchoolType']>=2){
                $gemid = round($gemid, 1);
                //}
                print $gemid;

                //$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
                $sql_querygemid = "update  le_cijfers set gemiddelde =  ?  where id = ?";

                if ($update = $mysqli->prepare($sql_querygemid)) {
                  if ($update->bind_param("si", $gemid, $cijferid)) {
                    if ($update->execute()) {
                      $returnvalue = 1;
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
                    print "error preparing query _savecijfersgemiddelde";
                  }
                }
              }
            } else {
              $returnvalue = 0;
            }
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
          print "error preparing query _savecijfersgemiddelde2";
        }
      }
    } catch (Exception $e) {
      $this->error = true;
      $this->mysqlierror = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function _savecijfersgemiddelde_ps($schooljaar, $schoolid, $studentid_in, $klas_in, $rap_in, $vak_in)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select distinct s.id, c.id, s.class, s.firstname, s.lastname, s.sex,
    c.c1, c.c2, c.c3, c.c4, c.c5, c.c6, c.c7, c.c8, c.c9, c.c10, c.c11, c.c12, c.c13, c.c14, c.c15, c.c16, c.c17, c.c18, c.c19, c.c20,
    cw.c1, cw.c2, cw.c3, cw.c4, cw.c5, cw.c6, cw.c7, cw.c8, cw.c9, cw.c10, cw.c11, cw.c12, cw.c13, cw.c14, cw.c15, cw.c16, cw.c17, cw.c18, cw.c19, cw.c20,
    c.gemiddelde from students s
    left join le_cijfers_ps c on s.id = c.studentid
    left join le_vakken_ps v on c.vak = v.id
    inner join le_cijferswaarde cw on cw.vak = v.id
    and cw.schooljaar = c.schooljaar
    and cw.rapnummer = c.rapnummer
    where s.class = ? and v.id = ?
    and c.rapnummer = ?
    and c.studentid = ?
    and c.schooljaar = ? order by s.firstname asc LIMIT 1";
    /* TODO: QUERY NEEDS TO BE FIXED , EL */


    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siiss", $klas_in, $vak_in, $rap_in, $studentid_in, $schooljaar)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers middelde', appconfig::GetDummy());
            $this->error = false;
            $result = 1;

            $select->bind_result(
              $studentid,
              $cijferid,
              $klas,
              $firstname,
              $lastname,
              $sex,
              $c1,
              $c2,
              $c3,
              $c4,
              $c5,
              $c6,
              $c7,
              $c8,
              $c9,
              $c10,
              $c11,
              $c12,
              $c13,
              $c14,
              $c15,
              $c16,
              $c17,
              $c18,
              $c19,
              $c20,
              $w1,
              $w2,
              $w3,
              $w4,
              $w5,
              $w6,
              $w7,
              $w8,
              $w9,
              $w10,
              $w11,
              $w12,
              $w13,
              $w14,
              $w15,
              $w16,
              $w17,
              $w18,
              $w19,
              $w20,
              $gemiddelde
            );
            $select->store_result();


            if ($select->num_rows == 1) {

              while ($select->fetch()) {
                $gemid = $this->_gemiddeldebasis(
                  $c1,
                  $c2,
                  $c3,
                  $c4,
                  $c5,
                  $c6,
                  $c7,
                  $c8,
                  $c9,
                  $c10,
                  $c11,
                  $c12,
                  $c13,
                  $c14,
                  $c15,
                  $c16,
                  $c17,
                  $c18,
                  $c19,
                  $c20,
                  $w1,
                  $w2,
                  $w3,
                  $w4,
                  $w5,
                  $w6,
                  $w7,
                  $w8,
                  $w9,
                  $w10,
                  $w11,
                  $w12,
                  $w13,
                  $w14,
                  $w15,
                  $w16,
                  $w17,
                  $w18,
                  $w19,
                  $w20
                );

                if ($gemid == 0) {
                  $gemid = NULL;
                }

                //if ($_SESSION['SchoolType']>=2){
                /*                 $gemid = round($gemid, 1);
 */                //}
                print $gemid;

                //$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
                $sql_querygemid = "update le_cijfers_ps set gemiddelde =  ? where id = ?";

                if ($update = $mysqli->prepare($sql_querygemid)) {
                  if ($update->bind_param("si", $gemid, $cijferid)) {
                    if ($update->execute()) {
                      $returnvalue = 1;
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
                    print "error preparing query _savecijfersgemiddelde";
                  }
                }
              }
            } else {
              $returnvalue = 0;
            }
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
          print "error preparing query _savecijfersgemiddelde2";
        }
      }
    } catch (Exception $e) {
      $this->error = true;
      $this->mysqlierror = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function _gemiddeldebasis(
    $c1,
    $c2,
    $c3,
    $c4,
    $c5,
    $c6,
    $c7,
    $c8,
    $c9,
    $c10,
    $c11,
    $c12,
    $c13,
    $c14,
    $c15,
    $c16,
    $c17,
    $c18,
    $c19,
    $c20,
    $w1,
    $w2,
    $w3,
    $w4,
    $w5,
    $w6,
    $w7,
    $w8,
    $w9,
    $w10,
    $w11,
    $w12,
    $w13,
    $w14,
    $w15,
    $w16,
    $w17,
    $w18,
    $w19,
    $w20
  ) {

    $index = 0;
    $samen  = 0;

    for ($i = 1; $i < 21; $i++) {
      if (${'c' . $i} > 0 && ${'c' . $i} <= 10) {
        $samen = $samen + (${'c' . $i} * ${'w' . $i});
        $index = $index + ${'w' . $i};
      }
      //  else{
      //
      //  }
    }

    if ($this->debug) {
      print 'c1 =' . $c1 . 'w1=' . $w1 . '<br>';
      print "index" . $index . " samen " . $samen;
    }
    if ($index > 0 && $samen > 0) {

      $Xgemid = ($samen / $index);
      echo "this is the final Gemiddelde: ";
      echo $Xgemid;
      // echo "    <-";

      if ($Xgemid == 10) {
        $result = $Xgemid;
      } else {

        $result = substr($Xgemid, 0, 4);
        //if ($_SESSION['SchoolType']>=2){
        //  $result = substr($Xgemid,0,4);
        //}
        //else{
        //  $result = substr($Xgemid,0,3);
        //}
      }
    } else {
      $result = 0;
    }

    return $result;
  }

  function _insertcijfer($schooljaar, $schoolid, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in)
  {

    // $schooljaar_temp = "2016-2017";
    $user_temp = "test_user";

    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      //update le_cijfer h inner join students s on h.studentid = s.id set h.h1 = 3 where s.id = 24 and s.class = "1A" and h.rapnummer = 1
      if ($stmt = $mysqli->prepare("insert into" . chr(32) . $this->tablename_cijfers . chr(32) . "(studentid, created, schooljaar,rapnummer,vak, klas, user," . chr(32) . $cijfer_number_in . ") values (?,?,?,?,?,?,?,?);")) {
        if ($stmt->bind_param("ssssisss", $studentid_in, $_DateTime, $schooljaar, $rap_in, $vak_in, $klas_in, $_SESSION['UserGUID'], $cijfer_value_in)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'create cijfers', appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */

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

      //print "mysqli error: " . $this->mysqlierror;


    } catch (Exception $e) {
      $result = -2;
      //  $this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }
  //BEGIN CaribeDevelopers - Janio Acero
  function updatecijfersextra($idcijfersextra, $klas, $schooljaar, $rapnummer, $vak, $index, $oc, $dc)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($stmt = $mysqli->prepare("CALL " . $this->sp_update_le_cijfersextra . " (?,?,?,?,?,?,?,?)")) {
        $result = 0;
        if ($stmt->bind_param("issisiss", $idcijfersextra, $klas, $schooljaar, $rapnummer, $vak, $index, $oc, $dc)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'update cijfers extra', appconfig::GetDummy());
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
    } catch (Exception $e) {
      $result = -2;
      //  $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }

  function _getcijfersocdcvalue($index, $i, $value)
  {
    if ($index == $i) {
      return $value;
    } else {
      return "";
    }
  }
  //END CaribeDevelopers - Janio Acero

  //CREATE CIJFERSWAARDEE (ejaspe@caribedev)
  function createcijferswaarde($klas, $schooljaar, $rapnummer, $vak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($stmt = $mysqli->prepare("CALL sp_create_le_cijferswaarde (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
        if ($stmt->bind_param("ssisiiiiiiiiiiiiiiiiiiii", $klas, $schooljaar, $rapnummer, $vak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'create cijfers waarde', appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */

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
      }
    } catch (Exception $e) {
      $result = -2;
    }


    return $result;
  }
  //Caribe Dev
  function create_le_cijfer_student($schooljaar, $rapnummer, $klass, $schoolID, $vakid)
  {
    $result = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($stmt = $mysqli->prepare("CALL sp_create_le_cijfers_student (?,?,?,?,?)")) {
        if ($stmt->bind_param("ssssi", $schooljaar, $rapnummer, $klass, $schoolID, $vakid)) {
          if ($stmt->execute()) {
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            //$result = 1;
            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            echo "# " .  $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          echo "# " .       $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $result = 0;
        echo $this->mysqlierror = $mysqli->error;
        echo "# " .   $this->mysqlierrornumber = $mysqli->errno;
      }
    } catch (Exception $e) {
      $result = -2;
      //$this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }

  function create_le_cijfer_student_ps($schooljaar, $rapnummer, $klas, $schoolID, $vakid)
  {
    $result = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

    $sql1 = "SELECT @cijfers_count := COUNT(lc.studentid) 
      FROM 
        le_cijfers_ps lc 
      INNER JOIN 
        students s on lc.studentid = s.id 
      WHERE s.schoolid = '$schoolID'
      AND lc.schooljaar = '$schooljaar'
      AND lc.rapnummer = '$rapnummer'
      AND lc.klas = '$klas' AND lc.vak = '$vakid';";
    if ($resultado = $mysqli->query($sql1)) {
      $fila = $resultado->fetch_row();
      $student_count = $fila[0];

      if ($student_count == 0 || $student_count == '') {
        $sqlI = "INSERT INTO
        le_cijfers_ps
        (`studentid`,`lastchanged`,`created`,`schooljaar`,`school_id`,`rapnummer`,`vak`,`klas`)
      SELECT DISTINCT
        st.id,null,now(),'$schooljaar','$schoolID','$rapnummer','$vakid','$klas'
      FROM 
        students st
      INNER JOIN
        le_vakken_ps lv
      WHERE
        lv.id = '$vakid' AND st.status = 1 AND st.class = '$klas' AND st.schoolid = '$schoolID';";
        $resultado123 = mysqli_query($mysqli, $sqlI);
        if ($resultado123) {
          $mysqli->close();
        } else {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          echo "# " .   $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $sqlI1 = "SELECT @student_count := (COUNT(id)) FROM students where schoolid = '$schoolID' and class = '$klas';";
        if ($resultado1 = $mysqli->query($sqlI1)) {
          $fila = $resultado1->fetch_row();
          $student_cuenta = $fila[0];
          $sqlI2 = "SELECT @cijfer_count := (count(id)) FROM le_cijfers_ps where schooljaar = '$schooljaar' and school_id = '$schoolID' and vak = '$vakid' and klas = '$klas' and rapnummer = '$rapnummer';";
          if ($resultado2 = $mysqli->query($sqlI2)) {
            $fila = $resultado2->fetch_row();
            $cijfer_cuenta = $fila[0];
            if ($student_cuenta != $cijfer_cuenta) {
              $query = "SELECT id FROM students WHERE id NOT IN (SELECT studentid as id FROM le_cijfers_ps 
              WHERE schooljaar = '$schooljaar' and vak = '$vakid' and klas = '$klas' and rapnummer = '$rapnummer' and school_id = '$schoolID') 
              AND SchoolID = '$schoolID' and class = '$klas' and status = 1";
              $resultado3 = mysqli_query($mysqli, $query);
              while ($row = mysqli_fetch_array($resultado3)) {
                $studentId = $row['id'];
                $dateNow = date('Y-m-d H:i:s');
                echo $studentId;
                $insert = "INSERT INTO le_cijfers_ps (studentid,lastchanged,created,schooljaar,rapnummer,school_id,vak,klas) VALUES ('$studentId',null,'$dateNow','$schooljaar','$rapnummer','$schoolID','$vakid','$klas')";
                $resultado4 = mysqli_query($mysqli, $insert);
              }
            }
          }
        } else {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          echo "# " .   $this->mysqlierrornumber = $mysqli->errno;
        }
      }

      $resultado->close();
    }
    return $result;
  }

  function list_cijfers_by_student_ps($schooljaar, $studentid, $dummy)
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
        $sp_get_cijfers_by_student_parent = "SELECT id FROM students where uuid = ? limit 1;";

        if ($stmt = $mysqli->prepare($sp_get_cijfers_by_student_parent)) {
          if ($stmt->bind_param("s", $studentid)) {
            if ($stmt->execute()) {
              $stmt->bind_result($studentid);
              $stmt->store_result();
              if ($stmt->num_rows > 0) {
                while ($stmt->fetch()) {
                  echo "</br>";
                }
              }
              $sp_get_cijfers_by_student = "SELECT DISTINCT
              le_cijfers_ps.klas,
              le_cijfers_ps.studentid,
              le_cijfers_ps.lastchanged,
              le_cijfers_ps.schooljaar,
              le_cijfers_ps.rapnummer,
              le_cijfers_ps.vak,
              le_vakken_ps.vak_naam, 
              le_cijfers_ps.c1,
              le_cijfers_ps.c2,
              le_cijfers_ps.c3,
              le_cijfers_ps.c4,
              le_cijfers_ps.c5,
              le_cijfers_ps.c6,
              le_cijfers_ps.c7,
              le_cijfers_ps.c8,
              le_cijfers_ps.c9,
              le_cijfers_ps.c10,
              le_cijfers_ps.c11,
              le_cijfers_ps.c12,
              le_cijfers_ps.c13,
              le_cijfers_ps.c14,
              le_cijfers_ps.c15,
              le_cijfers_ps.c16,
              le_cijfers_ps.c17,
              le_cijfers_ps.c18,
              le_cijfers_ps.c19,
              le_cijfers_ps.c20,
              le_cijfers_ps.gemiddelde,
              le_cijfers_ps.hergemiddelde
              
              FROM
              le_cijfers_ps
              INNER JOIN le_vakken_ps ON le_vakken_ps.ID = le_cijfers_ps.vak
              left join le_cijfersextra on le_vakken_ps.ID = le_cijfersextra.vak
              where le_cijfers_ps.studentid = ? and le_cijfers_ps.schooljaar = ?
              and
              (le_cijfers_ps.c1 is not null or
              le_cijfers_ps.c2 is not null or
              le_cijfers_ps.c3 is not null or
              le_cijfers_ps.c4 is not null or
              le_cijfers_ps.c5 is not null or
              le_cijfers_ps.c6 is not null or
              le_cijfers_ps.c7 is not null or
              le_cijfers_ps.c8 is not null or
              le_cijfers_ps.c9 is not null or
              le_cijfers_ps.c10 is not null or
              le_cijfers_ps.c11 is not null or
              le_cijfers_ps.c12 is not null or
              le_cijfers_ps.c13 is not null or
              le_cijfers_ps.c14 is not null or
              le_cijfers_ps.c15 is not null or
              le_cijfers_ps.c16 is not null or
              le_cijfers_ps.c17 is not null or
              le_cijfers_ps.c18 is not null or
              le_cijfers_ps.c19 is not null or
              le_cijfers_ps.c20 is not null)
              GROUP BY klas,vak,rapnummer order by le_cijfers_ps.schooljaar desc, le_cijfers_ps.rapnummer desc,le_cijfers_ps.vak desc;";
              if ($stmt = $mysqli->prepare($sp_get_cijfers_by_student)) {
                if ($stmt->bind_param("ss", $studentid, $schooljaar)) {
                  if ($stmt->execute()) {
                    $spn_audit = new spn_audit();
                    $UserGUID = $_SESSION['UserGUID'];
                    $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers by student', appconfig::GetDummy());
                    $this->error = false;
                    $result = 1;
                    $stmt->bind_result($klas, $id_studente, $lastchanged, $schooljaar, $rapnummer, $vak, $volledigenaamvak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde, $hergemiddelde);
                    //$stmt->bind_result($klas,$id_studente,$lastchanged,$schooljaar,$rapnummer,$vak,$volledigenaamvak,$gemiddelde,$hergemiddelde);
                    $stmt->store_result();



                    if ($stmt->num_rows > 0) {
                      $htmlcontrol .= "<div class=\"col-xs-11 table-responsive\">";
                      $htmlcontrol .= "<table id=\"tbl_cijfers_by_student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

                      $htmlcontrol .= "<thead><tr><th>Schooljaar</th><th>Klass</th><th>Rapport#</th><th>Vak</th><th>Chat</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>Gemiddeld</th></tr></thead>";
                      $htmlcontrol .= "<tbody>";

                      while ($stmt->fetch()) {
                        $htmlcontrol .= "<tr>";
                        $htmlcontrol .= "<td>" . htmlentities($schooljaar) . "</td>";
                        $htmlcontrol .= "<td>" . htmlentities($klas) . "</td>";
                        $htmlcontrol .= "<td>" . htmlentities($rapnummer) . "</td>";
                        $htmlcontrol .= "<td>" . htmlentities($volledigenaamvak) . "</td>";

                        // $htmlcontrol .= "<td>". ($c1 >= 1 && $c1 <= 5.5 ? "class=\"quaternary-bg-color default-secondary-color\"": "") ."</td>";

                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc1')\" title=\" \" name =\"c1\"" . ($c1 >= 1 && $c1 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c1 == 0.0 ? "" : htmlentities($c1)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc2')\" title=\" \" name =\"c1\"" . ($c2 >= 1 && $c2 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c2 == 0.0 ? "" : htmlentities($c2)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc3')\" title=\" \" name =\"c1\"" . ($c3 >= 1 && $c3 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c3 == 0.0 ? "" : htmlentities($c3)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc4')\" title=\" \" name =\"c1\"" . ($c4 >= 1 && $c4 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c4 == 0.0 ? "" : htmlentities($c4)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc5')\" title=\" \" name =\"c1\"" . ($c5 >= 1 && $c5 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c5 == 0.0 ? "" : htmlentities($c5)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc6')\" title=\" \" name =\"c1\"" . ($c6 >= 1 && $c6 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c6 == 0.0 ? "" : htmlentities($c6)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc7')\" title=\" \" name =\"c1\"" . ($c7 >= 1 && $c7 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c7 == 0.0 ? "" : htmlentities($c7)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc8')\" title=\" \" name =\"c1\"" . ($c8 >= 1 && $c8 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c8 == 0.0 ? "" : htmlentities($c8)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc9')\" title=\" \" name =\"c1\"" . ($c9 >= 1 && $c9 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c9 == 0.0 ? "" : htmlentities($c9)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc10')\" title=\" \" name =\"c1\"" . ($c10 >= 1 && $c10 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c10 == 0.0 ? "" : htmlentities($c10)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc11')\" title=\" \" name =\"c1\"" . ($c11 >= 1 && $c11 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c11 == 0.0 ? "" : htmlentities($c11)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc12')\" title=\" \" name =\"c1\"" . ($c12 >= 1 && $c12 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c12 == 0.0 ? "" : htmlentities($c12)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc13')\" title=\" \" name =\"c1\"" . ($c13 >= 1 && $c13 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c13 == 0.0 ? "" : htmlentities($c13)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc14')\" title=\" \" name =\"c1\"" . ($c14 >= 1 && $c14 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c14 == 0.0 ? "" : htmlentities($c14)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc15')\" title=\" \" name =\"c1\"" . ($c15 >= 1 && $c15 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c15 == 0.0 ? "" : htmlentities($c15)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc16')\" title=\" \" name =\"c1\"" . ($c16 >= 1 && $c16 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c16 == 0.0 ? "" : htmlentities($c16)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc17')\" title=\" \" name =\"c1\"" . ($c17 >= 1 && $c17 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c17 == 0.0 ? "" : htmlentities($c17)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc18')\" title=\" \" name =\"c1\"" . ($c18 >= 1 && $c18 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c18 == 0.0 ? "" : htmlentities($c18)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc19')\" title=\" \" name =\"c1\"" . ($c19 >= 1 && $c19 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c19 == 0.0 ? "" : htmlentities($c19)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','oc20')\" title=\" \" name =\"c1\"" . ($c20 >= 1 && $c20 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c20 == 0.0 ? "" : htmlentities($c20)) . "</td>";

                        $htmlcontrol .= "<td name =\"gemiddelde\"" . ($gemiddelde >= 1 && $gemiddelde <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . htmlentities($gemiddelde) . "</td>";
                        // $htmlcontrol .= "<td>". htmlentities($gemiddelde) ."</td>";

                      }

                      $htmlcontrol .= "</tbody>";
                      $htmlcontrol .= "</table>";
                      $htmlcontrol .= "</div>";
                    } else {
                      $htmlcontrol .= "No results to show";
                    }
                  }
                }
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
        // $this->exceptionvalue = $e->getMessage();
        //$result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }


  function list_cijfers_by_student($schooljaar, $studentid, $dummy)
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
        $sp_get_cijfers_by_student = "sp_get_cijfers_by_student";

        if ($stmt = $mysqli->prepare("CALL " . $this->$sp_get_cijfers_by_student . "(?,?)")) {
          if ($stmt->bind_param("ss", $schooljaar, $studentid)) {
            if ($stmt->execute()) {
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers by student', appconfig::GetDummy());
              $this->error = false;
              $result = 1;
              $stmt->bind_result($firstname, $lastname, $klas, $id_studente, $lastchanged, $schooljaar, $rapnummer, $vak, $volledigenaamvak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde, $hergemiddelde);
              $stmt->store_result();



              if ($stmt->num_rows > 0) {
                $htmlcontrol .= "<table id=\"tbl_cijfers_by_student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

                $htmlcontrol .= "<thead><tr><th>Schooljaar</th><th>Klass</th><th>Rapport#</th><th>Vak</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>Gemiddeld</th></tr></thead>";
                $htmlcontrol .= "<tbody>";

                while ($stmt->fetch()) {
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>" . htmlentities($schooljaar) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($klas) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($rapnummer) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($volledigenaamvak) . "</td>";
                  // $htmlcontrol .= "<td>". ($c1 >= 1 && $c1 <= 5.5 ? "class=\"bg-danger\"": "") ."</td>";

                  $htmlcontrol .= "<td name =\"c1\"" . ($c1 >= 1 && $c1 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c1 == 0.0 ? "" : htmlentities($c1)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c2 >= 1 && $c2 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c2 == 0.0 ? "" : htmlentities($c2)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c3 >= 1 && $c3 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c3 == 0.0 ? "" : htmlentities($c3)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c4 >= 1 && $c4 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c4 == 0.0 ? "" : htmlentities($c4)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c5 >= 1 && $c5 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c5 == 0.0 ? "" : htmlentities($c5)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c6 >= 1 && $c6 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c6 == 0.0 ? "" : htmlentities($c6)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c7 >= 1 && $c7 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c7 == 0.0 ? "" : htmlentities($c7)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c8 >= 1 && $c8 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c8 == 0.0 ? "" : htmlentities($c8)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c9 >= 1 && $c9 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c9 == 0.0 ? "" : htmlentities($c9)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c10 >= 1 && $c10 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c10 == 0.0 ? "" : htmlentities($c10)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c11 >= 1 && $c11 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c11 == 0.0 ? "" : htmlentities($c11)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c12 >= 1 && $c12 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c12 == 0.0 ? "" : htmlentities($c12)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c13 >= 1 && $c13 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c13 == 0.0 ? "" : htmlentities($c13)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c14 >= 1 && $c14 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c14 == 0.0 ? "" : htmlentities($c14)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c15 >= 1 && $c15 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c15 == 0.0 ? "" : htmlentities($c15)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c16 >= 1 && $c16 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c16 == 0.0 ? "" : htmlentities($c16)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c17 >= 1 && $c17 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c17 == 0.0 ? "" : htmlentities($c17)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c18 >= 1 && $c18 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c18 == 0.0 ? "" : htmlentities($c18)) . "</td>";;
                  $htmlcontrol .= "<td name =\"c1\"" . ($c19 >= 1 && $c19 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c19 == 0.0 ? "" : htmlentities($c19)) . "</td>";;
                  $htmlcontrol .= "<td name =\"c1\"" . ($c20 >= 1 && $c20 <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . ($c20 == 0.0 ? "" : htmlentities($c20)) . "</td>";

                  $htmlcontrol .= "<td name =\"gemiddelde\"" . ($gemiddelde >= 1 && $gemiddelde <= 5.5 ?  "class=\"bg-danger\"" : "") . ">" . htmlentities($gemiddelde) . "</td>";
                  // $htmlcontrol .= "<td>". htmlentities($gemiddelde) ."</td>";

                }

                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";
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
        // $this->exceptionvalue = $e->getMessage();
        //$result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }

  function get_cijfers_graph($schooljaar, $schoolid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $json_result = "";
    $indexvaluetoshow = 3;
    $inloop = false;
    $result = 0;
    if ($dummy) {
      $result = 1;
    } else {
      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL " . $this->sp_read_le_cijfer_graph . " (?,?)")) {
          if ($select->bind_param("ss", $schooljaar, $schoolid)) {
            if ($select->execute()) {
              require_once("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'cijfers', 'get cijfers graph', appconfig::GetDummy());
              $this->error = false;
              $result = 1;
              $select->bind_result($volledigenaamvak, $period1, $period2, $period3);
              $select->store_result();

              if ($select->num_rows > 0) {
                $json_result .= "[";

                while ($select->fetch()) {
                  if (!$inloop) {
                    $json_result .= "{";
                    $inloop = true;
                  } else
                    $json_result .= ",{";

                  $json_result .= "\"vakken\": \"" . $volledigenaamvak . "\",";
                  $json_result .= "\"unit\": \"Cijfers\",";
                  $json_result .= "\"periode2\": \"" . $period2 . "\",";
                  $json_result .= "\"periode3\": \"" . $period3 . "\",";
                  $json_result .= "\"periode1\": \"" . $period1 . "\"";
                  // $json_result .= "\"periode2\": \"". 4 . "\",";
                  // $json_result .= "\"periode3\": \"". 5 . "\",";
                  // $json_result .= "\"periode1\": \"". 6 . "\"";

                  $json_result .= "}";
                }

                $json_result .= "]";
              } else {
                $json_result .= "{ [] }";
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

  function get_cijfers_graph_by_class($schooljaar, $schoolid, $studentid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $json_result = "";
    $inloop = false;
    $indexvaluetoshow = 3;
    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL " . $this->sp_read_le_cijfer_graph_by_class . " (?,?,?)")) {
          if ($select->bind_param("sii", $schooljaar, $schoolid, $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;
              $select->bind_result($volledigenaamvak, $period1, $period2, $period3);
              $select->store_result();

              if ($select->num_rows > 0) {
                $json_result .= "[";

                while ($select->fetch()) {
                  if (!$inloop) {
                    $json_result .= "{";
                    $inloop = true;
                    $json_result .= "\"vakken\": \"" . $volledigenaamvak . "\",";
                    $json_result .= "\"unit\": \"Cijfers\",";
                    $json_result .= "\"periode2\": \"" . $period2 . "\",";
                    $json_result .= "\"periode3\": \"" . $period3 . "\",";
                    $json_result .= "\"periode1\": \"" . $period1 . "\"";
                    $json_result .= "}";
                  } else {
                    $json_result .= ",{";

                    $json_result .= "\"vakken\": \"" . $volledigenaamvak . "\",";
                    $json_result .= "\"unit\": \"Cijfers\",";
                    $json_result .= "\"periode2\": \"" . $period2 . "\",";
                    $json_result .= "\"periode3\": \"" . $period3 . "\",";
                    $json_result .= "\"periode1\": \"" . $period1 . "\"";
                    $json_result .= "}";
                  }
                }
                $json_result .= "]";
              } else {
                $json_result .= "{ [] }";
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

  function get_cijfers_graph_by_student($schooljaar, $schoolid, $studentid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $json_result = "";
    $indexvaluetoshow = 3;
    $inloop = false;
    $result = 0;
    if ($dummy) {
      $result = 1;
    } else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL " . $this->sp_read_le_cijfer_graph_by_student . " (?,?,?)")) {
          if ($select->bind_param("ssi", $schooljaar, $schoolid, $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;
              $select->bind_result($volledigenaamvak, $period1, $period2, $period3);
              $select->store_result();

              if ($select->num_rows > 0) {
                $json_result .= "[";

                while ($select->fetch()) {
                  if (!$inloop) {
                    $json_result .= "{";
                    $inloop = true;
                    $json_result .= "\"vakken\": \"" . $volledigenaamvak . "\",";
                    $json_result .= "\"unit\": \"Cijfers\",";
                    $json_result .= "\"periode2\": \"" . $period2 . "\",";
                    $json_result .= "\"periode3\": \"" . $period3 . "\",";
                    $json_result .= "\"periode1\": \"" . $period1 . "\"";
                    $json_result .= "}";
                  } else {
                    $json_result .= ",{";
                    $json_result .= "\"vakken\": \"" . $volledigenaamvak . "\",";
                    $json_result .= "\"unit\": \"Cijfers\",";
                    $json_result .= "\"periode2\": \"" . $period2 . "\",";
                    $json_result .= "\"periode3\": \"" . $period3 . "\",";
                    $json_result .= "\"periode1\": \"" . $period1 . "\"";
                    $json_result .= "}";
                  }
                }
                $json_result .= "]";
              } else {
                $json_result .= "{ [] }";
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

  function list_school_name_by_teacher($userGUID, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";

    $result = 0;
    if ($dummy) {
      $result = 1;
    } else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);
        $sp_get_schools_by_teacher = 'sp_get_schools_by_teacher';
        if ($stmt = $mysqli->prepare("CALL " . $this->sp_get_schools_by_teacher . "(?)")) {
          if ($stmt->bind_param("s", $userGUID)) {
            if ($stmt->execute()) {
              $this->error = false;
              $result = 1;
              $stmt->bind_result($id_school, $name_school);
              $stmt->store_result();

              if ($stmt->num_rows > 0) {
                // $htmlcontrol .= "<select id=\"list_school_teacher\" name=\"list_school_teacher\" class=\"form-control\">";
                $htmlcontrol .= "<option selected>Select One School</option>";
                while ($stmt->fetch()) {
                  $htmlcontrol .= "<option value=" . htmlentities($id_school) . ">" . htmlentities($name_school) . "</option>";
                }
                // $htmlcontrol .= "</select>";
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

  function list_class_by_teacher($schoolid, $userGUID, $dummy)
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
        $sp_get_class_by_teacher = 'sp_get_class_by_teacher';
        if ($stmt = $mysqli->prepare("CALL " . $this->sp_get_class_by_teacher . "(?,?)")) {
          if ($stmt->bind_param("is", $schoolid, $userGUID)) {
            if ($stmt->execute()) {
              $this->error = false;
              $result = 1;
              $stmt->bind_result($class);
              $stmt->store_result();

              if ($stmt->num_rows > 0) {
                $htmlcontrol .= "<select id=\"list_class_teacher\" name=\"list_class_teacher\" class=\"form-control\">";
                $htmlcontrol .= "<option selected></option>";
                while ($stmt->fetch()) {
                  $htmlcontrol .= "<option value=" . htmlentities($class) . ">" . htmlentities($class) . "</option>";
                }
                $htmlcontrol .= "</select>";
              } else {
                $htmlcontrol .= "---";
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

  function list_vak_by_teacher($schoolid, $userGUID, $klas, $dummy)
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
        $sp_get_vak_by_teacher = 'sp_get_vak_by_teacher';
        if ($stmt = $mysqli->prepare("CALL " . $this->$sp_get_vak_by_teacher . "(?,?,?)")) {
          if ($stmt->bind_param("iss", $schoolid, $userGUID, $klas)) {
            if ($stmt->execute()) {
              $this->error = false;
              $result = 1;
              $stmt->bind_result($id_vak, $complete_name);
              $stmt->store_result();

              if ($stmt->num_rows > 0) {
                $htmlcontrol .= "<select id=\"list_vak_teacher\" name=\"list_vak_teacher\" class=\"form-control\">";

                while ($stmt->fetch()) {
                  $htmlcontrol .= "<option value=" . htmlentities($id_vak) . ">" . htmlentities($complete_name) . "</option>";
                }
                $htmlcontrol .= "</select>";
              } else {
                $htmlcontrol .= "---";
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
  function createcijferswaarde_first_time($klas, $schoolid, $schooljaar, $rapnummer, $vak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($stmt = $mysqli->prepare("CALL sp_create_le_cijferswaarde_first_time (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
        if ($stmt->bind_param("sssisiiiiiiiiiiiiiiiiiiii", $klas, $schoolid, $schooljaar, $rapnummer, $vak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'create cijfers waarde', appconfig::GetDummy());
            /*
                        Need to check for errors on database side for primary key errors etc.
                        */

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
        // $this->mysqlierror = $mysqli->error;
        //$this->mysqlierrornumber = $mysqli->errno;
      }
    } catch (Exception $e) {
      $result = -2;
      //$this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  function createcijferswaarde_first_time_ps($klas, $schooljaar, $rapnummer, $vak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);


    $sql = "SELECT @cijfers_warde_count := COUNT(lc.id) FROM le_cijferswaarde lc 
INNER JOIN le_vakken_ps lv ON lc.vak = lv.id 
      WHERE lc.klas = '$klas' 
AND lc.schooljaar = '$schooljaar'
      AND lc.rapnummer = '$rapnummer'
      AND lv.id = '$vak';";
    if ($resultado = $mysqli->query($sql)) {
      $fila = $resultado->fetch_row();
      $vakken_count = $fila[0];

      if ($vakken_count == 0) {
        $sqlI = "INSERT INTO `le_cijferswaarde`
        (`klas`,`schooljaar`,`rapnummer`,`vak`,
        `c1`,`c2`,`c3`,`c4`,`c5`,`c6`,`c7`,`c8`,`c9`,`c10`,
        `c11`,`c12`,`c13`,`c14`,`c15`,`c16`,`c17`,`c18`,`c19`,`c20`)
        VALUES
          ('$klas','$schooljaar','$rapnummer','$vak','$c1','$c2','$c3','$c4','$c5','$c6','$c7','$c8','$c9','$c10','$c11','$c12','$c13','$c14','$c15','$c16','$c17','$c18','$c19','$c20');";

        $resultado123 = mysqli_query($mysqli, $sqlI);
        if ($resultado123) {
          $result = 1;
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }

      return $result;
    }
  }


  function listcijfers_for_tutor($schoolid, $klas_in, $vak_in, $rap_in, $sort_order, $schooljaar)
  {

    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $htmlcijferswaarde = "";
    mysqli_report(MYSQLI_REPORT_STRICT);

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    $sql_query = "SELECT distinct s.id, c.id, s.class,s.lastname, s.firstname, s.sex, c.c1, c.c2, c.c3, c.c4, c.c5, c.c6, c.c7, c.c8, c.c9, c.c10, c.c11, c.c12, c.c13, c.c14, c.c15, c.c16, c.c17, c.c18, c.c19, c.c20, c.gemiddelde FROM students s LEFT JOIN le_cijfers c ON s.id = c.studentid LEFT JOIN le_vakken v ON c.vak = v.id WHERE s.class = ? AND v.id = ? AND c.rapnummer = ? AND c.schooljaar = ? ORDER BY ";

    $sql_order = " s.lastname, s.firstname ";
    if ($s->_setting_mj) {
      $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)


    /* TODO: QUERY NEEDS TO BE FIXED , EL */


    try {
      require_once("DBCreds.php");
      require_once("spn_utils.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siss", $klas_in, $vak_in, $rap_in, $schooljaar)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers', appconfig::GetDummy());
            $this->error = false;
            $result = 1;

            $select->bind_result($studentid, $cijferid, $klas, $lastname, $firstname, $sex, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde);
            $select->store_result();

            if ($select->num_rows > 0) {

              //BEGIN le_cijferswaarde (ejaspe@caribedev)
              $u = new spn_utils();
              if ($stmtcijferswaarde = $mysqli->prepare("CALL " . $this->sp_read_le_cijferswaarde . " (?,?,?,?)")) {
                if ($stmtcijferswaarde->bind_param("ssis", $klas_in, $vak_in, $rap_in, $schooljaar)) {
                  if ($stmtcijferswaarde->execute()) {
                    $stmtcijferswaarde->bind_result($cijferswaardeid, $klas, $schooljaar, $rapnummer, $vak, $cijferswaarde1, $cijferswaarde2, $cijferswaarde3, $cijferswaarde4, $cijferswaarde5, $cijferswaarde6, $cijferswaarde7, $cijferswaarde8, $cijferswaarde9, $cijferswaarde10, $cijferswaarde11, $cijferswaarde12, $cijferswaarde13, $cijferswaarde14, $cijferswaarde15, $cijferswaarde16, $cijferswaarde17, $cijferswaarde18, $cijferswaarde19, $cijferswaarde20);
                    $stmtcijferswaarde->store_result();

                    if ($stmtcijferswaarde->num_rows > 0) {
                      if ($rap_in == 1) {
                        if ($s->_setting_rapnumber_1) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      } elseif ($rap_in == 2) {
                        if ($s->_setting_rapnumber_2) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      } elseif ($rap_in == 3) {
                        if ($s->_setting_rapnumber_3) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      }
                    } else {
                      // Changes settings (ladalan@caribedev)

                      if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {
                        //NO DATA FIELDS
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                      } else {
                        //NO DATA FIELDS
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input disabled id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                      }
                    }

                    // End changes settings (laldana@caribedev)
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
              //END le_cijferswaarde


              /* begin drawing table */
              $htmlcontrol .= "<div class=\"table-responsive\">
                          <table id=\"vak\" class=\"table table-bordered table-colored table-vak\">
                          <thead>
                          <tr>


                          <th colspan=\"2\">Vul hierboven de bijhorende norm: </th>";
              // <th class=\"btn-m-w\"></th>";


              $htmlcontrol .= $htmlcijferswaarde;

              // Changes settings (ladalan@caribedev)

              if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {

                $htmlcontrol .= "<th></th>
                            </tr>
                            <tr class=\"text-align-center\">
                            <th>#</th>
                            <th class=\"btn-m-w\">Naam</th>
                            <th>
                            <a href=\"#\" data-id=\"1\">1 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"2\">2 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"3\">3 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"4\">4 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"5\">5 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"6\">6 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"7\">7 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"8\">8 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"9\">9 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a  href=\"#\" data-id=\"10\">10 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"11\">11 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"12\">12 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"13\">13 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"14\">14 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"15\">15 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"16\">16 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"17\">17 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"18\">18 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"19\">19 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a href=\"#\" data-id=\"20\">20 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            Gemiddeld
                            </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                            <td></td>
                            <td class=\"btn-w-m align-right\">Gemiddeld</td>
                            <td><div id=\"gemiddeld_1\">0</div></td>
                            <td><div id=\"gemiddeld_2\">0</div></td>
                            <td><div id=\"gemiddeld_3\">0</div></td>
                            <td><div id=\"gemiddeld_4\">0</div></td>
                            <td><div id=\"gemiddeld_5\">0</div></td>
                            <td><div id=\"gemiddeld_6\">0</div></td>
                            <td><div id=\"gemiddeld_7\">0</div></td>
                            <td><div id=\"gemiddeld_8\">0</div></td>
                            <td><div id=\"gemiddeld_9\">0</div></td>
                            <td><div id=\"gemiddeld_10\">0</div></td>
                            <td><div id=\"gemiddeld_11\">0</div></td>
                            <td><div id=\"gemiddeld_12\">0</div></td>
                            <td><div id=\"gemiddeld_13\">0</div></td>
                            <td><div id=\"gemiddeld_14\">0</div></td>
                            <td><div id=\"gemiddeld_15\">0</div></td>
                            <td><div id=\"gemiddeld_16\">0</div></td>
                            <td><div id=\"gemiddeld_17\">0</div></td>
                            <td><div id=\"gemiddeld_18\">0</div></td>
                            <td><div id=\"gemiddeld_19\">0</div></td>
                            <td><div id=\"gemiddeld_20\">0</div></td>
                            <td><div id=\"gemiddeld_total\">0</div></td>
                            </tr>
                            </tfoot>
                            <tbody>";
              } else {
                $htmlcontrol .= "<th></th>
                            </tr>
                            <tr class=\"text-align-center\">
                            <th>#</th>
                            <th class=\"btn-m-w\">Naam</th>
                            <th>
                            1
                            </th>
                            <th>
                            2
                            </th>
                            <th>
                            3
                            </th>
                            <th>
                            4
                            </th>
                            <th>
                            5
                            </th>
                            <th>
                            6
                            </th>
                            <th>
                            7
                            </th>
                            <th>
                            8
                            </th>
                            <th>
                            9
                            </th>
                            <th>
                            10
                            </th>
                            <th>
                            11
                            </th>
                            <th>
                            12
                            </th>
                            <th>
                            13
                            </th>
                            <th>
                            14
                            </th>
                            <th>
                            15
                            </th>
                            <th>
                            16
                            </th>
                            <th>
                            17
                            </th>
                            <th>
                            18
                            </th>
                            <th>
                            19
                            </th>
                            <th>
                            20
                            </th>
                            <th>
                            Gemiddeld
                            </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                            <td></td>
                            <td class=\"btn-w-m align-right\">Gemiddeld</td>
                            <td><div id=\"gemiddeld_1\">0</div></td>
                            <td><div id=\"gemiddeld_2\">0</div></td>
                            <td><div id=\"gemiddeld_3\">0</div></td>
                            <td><div id=\"gemiddeld_4\">0</div></td>
                            <td><div id=\"gemiddeld_5\">0</div></td>
                            <td><div id=\"gemiddeld_6\">0</div></td>
                            <td><div id=\"gemiddeld_7\">0</div></td>
                            <td><div id=\"gemiddeld_8\">0</div></td>
                            <td><div id=\"gemiddeld_9\">0</div></td>
                            <td><div id=\"gemiddeld_10\">0</div></td>
                            <td><div id=\"gemiddeld_11\">0</div></td>
                            <td><div id=\"gemiddeld_12\">0</div></td>
                            <td><div id=\"gemiddeld_13\">0</div></td>
                            <td><div id=\"gemiddeld_14\">0</div></td>
                            <td><div id=\"gemiddeld_15\">0</div></td>
                            <td><div id=\"gemiddeld_16\">0</div></td>
                            <td><div id=\"gemiddeld_17\">0</div></td>
                            <td><div id=\"gemiddeld_18\">0</div></td>
                            <td><div id=\"gemiddeld_19\">0</div></td>
                            <td><div id=\"gemiddeld_20\">0</div></td>
                            <td><div id=\"gemiddeld_total\">0</div></td>
                            </tr>
                            </tfoot>
                            <tbody>";
              }

              // End changes settings (ladalan@caribedev)

              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-cijfer attribute */
              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {

                $htmlcontrol .= "<tr><td>$x</td><td><input class=\"hidden\" studentidarray=\"$studentid\"></input>" . utf8_encode($lastname) . ', ' . utf8_encode($firstname) . "</td>";

                for ($y = 1; $y <= 20; $y++) {
                  $htmlcontrol .= "";

                  $_cijfer_number = 0;
                  /* check dimension of the cijfers */
                  switch ($y) {
                    case 1:
                      $c1 = ($c1 == 0.0 ? "" : $c1);
                      $_cijfer_number = $c1;
                      break;

                    case 2:
                      $c2 = ($c2 == 0.0 ? "" : $c2);
                      $_cijfer_number = $c2;
                      break;

                    case 3:
                      $c3 = ($c3 == 0.0 ? "" : $c3);
                      $_cijfer_number = $c3;
                      break;

                    case 4:
                      $c4 = ($c4 == 0.0 ? "" : $c4);
                      $_cijfer_number = $c4;
                      break;

                    case 5:
                      $c5 = ($c5 == 0.0 ? "" : $c5);
                      $_cijfer_number = $c5;
                      break;

                    case 6:
                      $c6 = ($c6 == 0.0 ? "" : $c6);
                      $_cijfer_number = $c6;
                      break;

                    case 7:
                      $c7 = ($c7 == 0.0 ? "" : $c7);
                      $_cijfer_number = $c7;
                      break;

                    case 8:
                      $c8 = ($c8 == 0.0 ? "" : $c8);
                      $_cijfer_number = $c8;
                      break;

                    case 9:
                      $c9 = ($c9 == 0.0 ? "" : $c9);
                      $_cijfer_number = $c9;
                      break;

                    case 10:
                      $c10 = ($c10 == 0.0 ? "" : $c10);
                      $_cijfer_number = $c10;
                      break;

                    case 11:
                      $c11 = ($c11 == 0.0 ? "" : $c11);
                      $_cijfer_number = $c11;
                      break;

                    case 12:
                      $c12 = ($c12 == 0.0 ? "" : $c12);
                      $_cijfer_number = $c12;
                      break;

                    case 13:
                      $c13 = ($c13 == 0.0 ? "" : $c13);
                      $_cijfer_number = $c13;
                      break;

                    case 14:
                      $c14 = ($c14 == 0.0 ? "" : $c14);
                      $_cijfer_number = $c14;
                      break;

                    case 15:
                      $c15 = ($c15 == 0.0 ? "" : $c15);
                      $_cijfer_number = $c15;
                      break;

                    case 16:
                      $c16 = ($c16 == 0.0 ? "" : $c16);
                      $_cijfer_number = $c16;
                      break;

                    case 17:
                      $c17 = ($c17 == 0.0 ? "" : $c17);
                      $_cijfer_number = $c17;
                      break;

                    case 18:
                      $c18 = ($c18 == 0.0 ? "" : $c18);
                      $_cijfer_number = $c18;
                      break;

                    case 19:
                      $c19 = ($c19 == 0.0 ? "" : $c19);
                      $_cijfer_number = $c19;
                      break;

                    case 20:
                      $c20 = ($c20 == 0.0 ? "" : $c20);
                      $_cijfer_number = $c20;
                      break;

                    default:
                      $_cijfer_number = 0;
                      break;
                  }
                  // Changes settings (ladalan@caribedev)

                  $cell = 'x' . $x . 'y' . $y;
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-row=\"$x\" id_cell_cijfer= \"$cell\" id_cijfer_table = \"$cijferid\" data-student-id=\"$studentid\" data-cijfer=\"c$y\" data-klas=\"$klas_in\" data-vak=\"$vak_in\" data-rapport=\"$rap_in\">$_cijfer_number</span></td>";

                  // End changes settings (ladalan@caribedev)

                  $xx++;
                }

                $htmlcontrol .= "<td id=\"ge$x\">$gemiddelde</td></tr>";

                /* increment variable with one */
                $x++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
              $htmlcontrol .= "</div>";
              //BEGIN CaribeDevelopers - Janio Acero
              $u = new spn_utils();
              $mysqli->close();
              $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
              if ($stmt = $mysqli->prepare("CALL " . $this->sp_read_le_cijfersextra . " (?,?,?,?)")) {
                if ($stmt->bind_param("ssis", $klas_in, $vak_in, $rap_in, $schooljaar)) {
                  if ($stmt->execute()) {
                    $stmt->bind_result(
                      $cijfersextraid,
                      $klas,
                      $schooljaar,
                      $rapnummer,
                      $vak,
                      $oc1,
                      $oc2,
                      $oc3,
                      $oc4,
                      $oc5,
                      $oc6,
                      $oc7,
                      $oc8,
                      $oc9,
                      $oc10,
                      $oc11,
                      $oc12,
                      $oc13,
                      $oc14,
                      $oc15,
                      $oc16,
                      $oc17,
                      $oc18,
                      $oc19,
                      $oc20,
                      $dc1,
                      $dc2,
                      $dc3,
                      $dc4,
                      $dc5,
                      $dc6,
                      $dc7,
                      $dc8,
                      $dc9,
                      $dc10,
                      $dc11,
                      $dc12,
                      $dc13,
                      $dc14,
                      $dc15,
                      $dc16,
                      $dc17,
                      $dc18,
                      $dc19,
                      $dc20
                    );
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                      while ($stmt->fetch()) {
                        $htmlcontrol .= "<input type=\"hidden\" id=\"id_cijfersextra\" name=\"id_cijfersextra\" value=\"" . htmlentities($cijfersextraid) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"klas_cijfersextra\" name=\"klas_cijfersextra\" value=\"" . htmlentities($klas) . "\">";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"schooljaar_cijfersextra\" name=\"schooljaar_cijfersextra\" value=\"" . htmlentities($schooljaar) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"rapnummer_cijfersextra\" name=\"rapnummer_cijfersextra\" value=\"" . htmlentities($rapnummer) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"vak_cijfersextra\" name=\"vak_cijfersextra\" value=\"" . htmlentities($vak) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc1_cijfersextra\" name=\"oc1_cijfersextra\" value=\"" . htmlentities($oc1) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc2_cijfersextra\" name=\"oc2_cijfersextra\" value=\"" . htmlentities($oc2) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc3_cijfersextra\" name=\"oc3_cijfersextra\" value=\"" . htmlentities($oc3) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc4_cijfersextra\" name=\"oc4_cijfersextra\" value=\"" . htmlentities($oc4) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc5_cijfersextra\" name=\"oc5_cijfersextra\" value=\"" . htmlentities($oc5) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc6_cijfersextra\" name=\"oc6_cijfersextra\" value=\"" . htmlentities($oc6) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc7_cijfersextra\" name=\"oc7_cijfersextra\" value=\"" . htmlentities($oc7) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc8_cijfersextra\" name=\"oc8_cijfersextra\" value=\"" . htmlentities($oc8) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc9_cijfersextra\" name=\"oc9_cijfersextra\" value=\"" . htmlentities($oc9) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc10_cijfersextra\" name=\"oc10_cijfersextra\" value=\"" . htmlentities($oc10) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc11_cijfersextra\" name=\"oc11_cijfersextra\" value=\"" . htmlentities($oc11) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc12_cijfersextra\" name=\"oc12_cijfersextra\" value=\"" . htmlentities($oc12) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc13_cijfersextra\" name=\"oc13_cijfersextra\" value=\"" . htmlentities($oc13) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc14_cijfersextra\" name=\"oc14_cijfersextra\" value=\"" . htmlentities($oc14) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc15_cijfersextra\" name=\"oc15_cijfersextra\" value=\"" . htmlentities($oc15) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc16_cijfersextra\" name=\"oc16_cijfersextra\" value=\"" . htmlentities($oc16) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc17_cijfersextra\" name=\"oc17_cijfersextra\" value=\"" . htmlentities($oc17) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc18_cijfersextra\" name=\"oc18_cijfersextra\" value=\"" . htmlentities($oc18) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc19_cijfersextra\" name=\"oc19_cijfersextra\" value=\"" . htmlentities($oc19) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc20_cijfersextra\" name=\"oc20_cijfersextra\" value=\"" . htmlentities($oc20) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc1_cijfersextra\" name=\"dc1_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc1)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc2_cijfersextra\" name=\"dc2_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc2)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc3_cijfersextra\" name=\"dc3_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc3)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc4_cijfersextra\" name=\"dc4_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc4)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc5_cijfersextra\" name=\"dc5_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc5)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc6_cijfersextra\" name=\"dc6_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc6)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc7_cijfersextra\" name=\"dc7_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc7)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc8_cijfersextra\" name=\"dc8_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc8)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc9_cijfersextra\" name=\"dc9_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc9)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc10_cijfersextra\" name=\"dc10_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc10)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc11_cijfersextra\" name=\"dc11_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc11)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc12_cijfersextra\" name=\"dc12_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc12)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc13_cijfersextra\" name=\"dc13_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc13)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc14_cijfersextra\" name=\"dc14_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc14)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc15_cijfersextra\" name=\"dc15_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc15)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc16_cijfersextra\" name=\"dc16_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc16)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc17_cijfersextra\" name=\"dc17_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc17)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc18_cijfersextra\" name=\"dc18_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc18)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc19_cijfersextra\" name=\"dc19_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc19)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc20_cijfersextra\" name=\"dc20_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc20)) . "\"/>";
                      }
                    } else {
                      //POR AHORA NADA
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
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $result = $mysqli->error;
              }
              //END CaribeDevelopers - Janio Acero
            } else {
              $htmlcontrol .= "No results to show";
            }
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


      // $htmlcontrol .= "<input type=\"hidden\" id=\"klas_cijferswaarde\" name=\"klas_cijferswaarde\" value=\"" . htmlentities($klas_in) . "\">";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"schooljaar_cijferswaarde\" name=\"schooljaar_cijferswaarde\" value=\"" . htmlentities($schooljaar) . "\"/>";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"rapnummer_cijferswaarde\" name=\"rapnummer_cijferswaarde\" value=\"" . htmlentities($rap_in) . "\"/>";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"vak_cijferswaarde\" name=\"vak_cijferswaarde\" value=\"" . htmlentities($vak_in) . "\"/>";



      $returnvalue = $htmlcontrol;
      return $returnvalue;
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

  function listcijfers_hs($schoolid, $klas_in, $vak_in, $rap_in, $sort_order, $schooljaar)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $htmlcijferswaarde = "";

    // if($this->debug)
    // {
    //   print "schoolid: " . $schoolid . "<br />";
    //   print "klas_in: " . $klas_in . "<br />";
    //   print "vak_in: " . $vak_in . "<br />";
    //   print "rap_in: " . $rap_in . "<br />";
    //   print "sort_order: " . $sort_order . "<br />";
    // }

    mysqli_report(MYSQLI_REPORT_STRICT);

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    $sql_query = "SELECT distinct s.id, c.id, s.class, s.lastname,s.firstname, s.sex, c.c1, c.c2, c.c3, c.c4, c.c5, c.c6, c.c7, c.c8, c.c9, c.c10, c.c11, c.c12, c.c13, c.c14, c.c15, c.c16, c.c17, c.c18, c.c19, c.c20, c.gemiddelde FROM students s LEFT JOIN le_cijfers c ON s.id = c.studentid LEFT JOIN le_vakken v ON c.vak = v.id WHERE s.class = ? AND v.id = ? AND c.rapnummer = ? AND c.schooljaar = ? ORDER BY ";

    $sql_order = " s.lastname, s.firstname ";
    if ($s->_setting_mj) {
      $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)


    /* TODO: QUERY NEEDS TO BE FIXED , EL */


    try {
      require_once("DBCreds.php");
      require_once("spn_utils.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siss", $klas_in, $vak_in, $rap_in, $schooljaar)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers', appconfig::GetDummy());
            $this->error = false;
            $result = 1;

            $select->bind_result($studentid, $cijferid, $klas, $lastname, $firstname, $sex, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde);
            $select->store_result();

            if ($select->num_rows > 0) {

              //BEGIN le_cijferswaarde (ejaspe@caribedev)
              $u = new spn_utils();
              if ($stmtcijferswaarde = $mysqli->prepare("CALL " . $this->sp_read_le_cijferswaarde . " (?,?,?,?)")) {
                if ($stmtcijferswaarde->bind_param("ssis", $klas_in, $vak_in, $rap_in, $schooljaar)) {
                  if ($stmtcijferswaarde->execute()) {
                    $stmtcijferswaarde->bind_result($cijferswaardeid, $klas, $schooljaar, $rapnummer, $vak, $cijferswaarde1, $cijferswaarde2, $cijferswaarde3, $cijferswaarde4, $cijferswaarde5, $cijferswaarde6, $cijferswaarde7, $cijferswaarde8, $cijferswaarde9, $cijferswaarde10, $cijferswaarde11, $cijferswaarde12, $cijferswaarde13, $cijferswaarde14, $cijferswaarde15, $cijferswaarde16, $cijferswaarde17, $cijferswaarde18, $cijferswaarde19, $cijferswaarde20);
                    $stmtcijferswaarde->store_result();

                    if ($stmtcijferswaarde->num_rows > 0) {
                      if ($rap_in == 1) {
                        if ($s->_setting_rapnumber_1) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      } elseif ($rap_in == 2) {
                        if ($s->_setting_rapnumber_2) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      } elseif ($rap_in == 3) {
                        if ($s->_setting_rapnumber_3) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      }
                    } else {
                      // Changes settings (ladalan@caribedev)

                      if ($s->_setting_rapnumber_1  || $s->_setting_rapnumber_2 || $s->_setting_rapnumber_3) {
                        //NO DATA FIELDS
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                      } else {
                        //NO DATA FIELDS
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                      }
                    }

                    // End changes settings (laldana@caribedev)
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
              //END le_cijferswaarde


              /* begin drawing table */
              $htmlcontrol .= "<div class=\"table-responsive\">
                          <table id=\"vak\" class=\"table table-bordered table-colored table-vak\">
                          <thead>
                          <tr>


                          <th colspan=\"2\">Vul hierboven de bijhorende norm: </th>";
              // <th class=\"btn-m-w\"></th>";


              $htmlcontrol .= $htmlcijferswaarde;

              // Changes settings (ladalan@caribedev)

              if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {

                $htmlcontrol .= "<th></th>
                            </tr>
                            <tr class=\"text-align-center\">
                            <th>#</th>
                            <th class=\"btn-m-w\">Naam</th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"1\">1 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"2\">2 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"3\">3 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"4\">4 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"5\">5 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"6\">6 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"7\">7 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"8\">8 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"9\">9 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"10\">10 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"11\">11 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"12\">12 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"13\">13 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"14\">14 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"15\">15 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"16\">16 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"17\">17 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"18\">18 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"19\">19 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"20\">20 <i class=\"fa fa-edit\"></i></a>
                            </th>
                            <th>
                            Gemiddeld
                            </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                            <td></td>
                            <td class=\"btn-w-m align-right\">Gemiddeld</td>
                            <td><div id=\"gemiddeld_1\">0</div></td>
                            <td><div id=\"gemiddeld_2\">0</div></td>
                            <td><div id=\"gemiddeld_3\">0</div></td>
                            <td><div id=\"gemiddeld_4\">0</div></td>
                            <td><div id=\"gemiddeld_5\">0</div></td>
                            <td><div id=\"gemiddeld_6\">0</div></td>
                            <td><div id=\"gemiddeld_7\">0</div></td>
                            <td><div id=\"gemiddeld_8\">0</div></td>
                            <td><div id=\"gemiddeld_9\">0</div></td>
                            <td><div id=\"gemiddeld_10\">0</div></td>
                            <td><div id=\"gemiddeld_11\">0</div></td>
                            <td><div id=\"gemiddeld_12\">0</div></td>
                            <td><div id=\"gemiddeld_13\">0</div></td>
                            <td><div id=\"gemiddeld_14\">0</div></td>
                            <td><div id=\"gemiddeld_15\">0</div></td>
                            <td><div id=\"gemiddeld_16\">0</div></td>
                            <td><div id=\"gemiddeld_17\">0</div></td>
                            <td><div id=\"gemiddeld_18\">0</div></td>
                            <td><div id=\"gemiddeld_19\">0</div></td>
                            <td><div id=\"gemiddeld_20\">0</div></td>
                            <td><div id=\"gemiddeld_total\">0</div></td>
                            </tr>
                            </tfoot>
                            <tbody>";
              } else {
                $htmlcontrol .= "<th></th>
                            </tr>
                            <tr class=\"text-align-center\">
                            <th>#</th>
                            <th class=\"btn-m-w\">Naam</th>
                            <th>
                            1
                            </th>
                            <th>
                            2
                            </th>
                            <th>
                            3
                            </th>
                            <th>
                            4
                            </th>
                            <th>
                            5
                            </th>
                            <th>
                            6
                            </th>
                            <th>
                            7
                            </th>
                            <th>
                            8
                            </th>
                            <th>
                            9
                            </th>
                            <th>
                            10
                            </th>
                            <th>
                            11
                            </th>
                            <th>
                            12
                            </th>
                            <th>
                            13
                            </th>
                            <th>
                            14
                            </th>
                            <th>
                            15
                            </th>
                            <th>
                            16
                            </th>
                            <th>
                            17
                            </th>
                            <th>
                            18
                            </th>
                            <th>
                            19
                            </th>
                            <th>
                            20
                            </th>
                            <th>
                            Gemiddeld
                            </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                            <td></td>
                            <td class=\"btn-w-m align-right\">Gemiddeld</td>
                            <td><div id=\"gemiddeld_1\">0</div></td>
                            <td><div id=\"gemiddeld_2\">0</div></td>
                            <td><div id=\"gemiddeld_3\">0</div></td>
                            <td><div id=\"gemiddeld_4\">0</div></td>
                            <td><div id=\"gemiddeld_5\">0</div></td>
                            <td><div id=\"gemiddeld_6\">0</div></td>
                            <td><div id=\"gemiddeld_7\">0</div></td>
                            <td><div id=\"gemiddeld_8\">0</div></td>
                            <td><div id=\"gemiddeld_9\">0</div></td>
                            <td><div id=\"gemiddeld_10\">0</div></td>
                            <td><div id=\"gemiddeld_11\">0</div></td>
                            <td><div id=\"gemiddeld_12\">0</div></td>
                            <td><div id=\"gemiddeld_13\">0</div></td>
                            <td><div id=\"gemiddeld_14\">0</div></td>
                            <td><div id=\"gemiddeld_15\">0</div></td>
                            <td><div id=\"gemiddeld_16\">0</div></td>
                            <td><div id=\"gemiddeld_17\">0</div></td>
                            <td><div id=\"gemiddeld_18\">0</div></td>
                            <td><div id=\"gemiddeld_19\">0</div></td>
                            <td><div id=\"gemiddeld_20\">0</div></td>
                            <td><div id=\"gemiddeld_total\">0</div></td>
                            </tr>
                            </tfoot>
                            <tbody>";
              }

              // End changes settings (ladalan@caribedev)

              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-cijfer attribute */
              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {

                $htmlcontrol .= "<tr><td>$x</td><td><input class=\"hidden\" studentidarray=\"$studentid\"></input>" . utf8_encode($lastname) . ', ' . utf8_encode($firstname) . "</td>";

                for ($y = 1; $y <= 20; $y++) {
                  $htmlcontrol .= "";

                  $_cijfer_number = 0;
                  /* check dimension of the cijfers */
                  switch ($y) {
                    case 1:
                      $c1 = ($c1 == 0.0 ? "" : $c1);
                      $_cijfer_number = $c1;
                      break;

                    case 2:
                      $c2 = ($c2 == 0.0 ? "" : $c2);
                      $_cijfer_number = $c2;
                      break;

                    case 3:
                      $c3 = ($c3 == 0.0 ? "" : $c3);
                      $_cijfer_number = $c3;
                      break;

                    case 4:
                      $c4 = ($c4 == 0.0 ? "" : $c4);
                      $_cijfer_number = $c4;
                      break;

                    case 5:
                      $c5 = ($c5 == 0.0 ? "" : $c5);
                      $_cijfer_number = $c5;
                      break;

                    case 6:
                      $c6 = ($c6 == 0.0 ? "" : $c6);
                      $_cijfer_number = $c6;
                      break;

                    case 7:
                      $c7 = ($c7 == 0.0 ? "" : $c7);
                      $_cijfer_number = $c7;
                      break;

                    case 8:
                      $c8 = ($c8 == 0.0 ? "" : $c8);
                      $_cijfer_number = $c8;
                      break;

                    case 9:
                      $c9 = ($c9 == 0.0 ? "" : $c9);
                      $_cijfer_number = $c9;
                      break;

                    case 10:
                      $c10 = ($c10 == 0.0 ? "" : $c10);
                      $_cijfer_number = $c10;
                      break;

                    case 11:
                      $c11 = ($c11 == 0.0 ? "" : $c11);
                      $_cijfer_number = $c11;
                      break;

                    case 12:
                      $c12 = ($c12 == 0.0 ? "" : $c12);
                      $_cijfer_number = $c12;
                      break;

                    case 13:
                      $c13 = ($c13 == 0.0 ? "" : $c13);
                      $_cijfer_number = $c13;
                      break;

                    case 14:
                      $c14 = ($c14 == 0.0 ? "" : $c14);
                      $_cijfer_number = $c14;
                      break;

                    case 15:
                      $c15 = ($c15 == 0.0 ? "" : $c15);
                      $_cijfer_number = $c15;
                      break;

                    case 16:
                      $c16 = ($c16 == 0.0 ? "" : $c16);
                      $_cijfer_number = $c16;
                      break;

                    case 17:
                      $c17 = ($c17 == 0.0 ? "" : $c17);
                      $_cijfer_number = $c17;
                      break;

                    case 18:
                      $c18 = ($c18 == 0.0 ? "" : $c18);
                      $_cijfer_number = $c18;
                      break;

                    case 19:
                      $c19 = ($c19 == 0.0 ? "" : $c19);
                      $_cijfer_number = $c19;
                      break;

                    case 20:
                      $c20 = ($c20 == 0.0 ? "" : $c20);
                      $_cijfer_number = $c20;
                      break;

                    default:
                      $_cijfer_number = 0;
                      break;
                  }

                  // Changes settings (ladalan@caribedev)

                  if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {
                    $cell = 'x' . $x . 'y' . $y;
                    $htmlcontrol .= "<td><span id=\"lblName1\" disabled='true' data-row=\"$x\" id_cell_cijfer= \"$cell\" id_cijfer_table = \"$cijferid\" data-student-id=\"$studentid\" data-cijfer=\"c$y\" data-klas=\"$klas_in\" data-vak=\"$vak_in\" data-rapport=\"$rap_in\" class=\"editable\">$_cijfer_number</span></td>";
                  } else {
                    $cell = 'x' . $x . 'y' . $y;
                    $htmlcontrol .= "<td><span id=\"lblName1\" data-row=\"$x\" id_cell_cijfer= \"$cell\" id_cijfer_table = \"$cijferid\" data-student-id=\"$studentid\" data-cijfer=\"c$y\" data-klas=\"$klas_in\" data-vak=\"$vak_in\" data-rapport=\"$rap_in\">$_cijfer_number</span></td>";
                  }

                  // End changes settings (ladalan@caribedev)

                  $xx++;
                }

                $htmlcontrol .= "<td id=\"ge$x\">$gemiddelde</td></tr>";

                /* increment variable with one */
                $x++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
              $htmlcontrol .= "</div>";
              //BEGIN CaribeDevelopers - Janio Acero
              $u = new spn_utils();
              $mysqli->close();
              $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
              if ($stmt = $mysqli->prepare("CALL " . $this->sp_read_le_cijfersextra . " (?,?,?,?)")) {
                if ($stmt->bind_param("ssis", $klas_in, $vak_in, $rap_in, $schooljaar)) {
                  if ($stmt->execute()) {
                    $stmt->bind_result(
                      $cijfersextraid,
                      $klas,
                      $schooljaar,
                      $rapnummer,
                      $vak,
                      $oc1,
                      $oc2,
                      $oc3,
                      $oc4,
                      $oc5,
                      $oc6,
                      $oc7,
                      $oc8,
                      $oc9,
                      $oc10,
                      $oc11,
                      $oc12,
                      $oc13,
                      $oc14,
                      $oc15,
                      $oc16,
                      $oc17,
                      $oc18,
                      $oc19,
                      $oc20,
                      $dc1,
                      $dc2,
                      $dc3,
                      $dc4,
                      $dc5,
                      $dc6,
                      $dc7,
                      $dc8,
                      $dc9,
                      $dc10,
                      $dc11,
                      $dc12,
                      $dc13,
                      $dc14,
                      $dc15,
                      $dc16,
                      $dc17,
                      $dc18,
                      $dc19,
                      $dc20
                    );
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                      while ($stmt->fetch()) {
                        $htmlcontrol .= "<input type=\"hidden\" id=\"id_cijfersextra\" name=\"id_cijfersextra\" value=\"" . htmlentities($cijfersextraid) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"klas_cijfersextra\" name=\"klas_cijfersextra\" value=\"" . htmlentities($klas) . "\">";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"schooljaar_cijfersextra\" name=\"schooljaar_cijfersextra\" value=\"" . htmlentities($schooljaar) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"rapnummer_cijfersextra\" name=\"rapnummer_cijfersextra\" value=\"" . htmlentities($rapnummer) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"vak_cijfersextra\" name=\"vak_cijfersextra\" value=\"" . htmlentities($vak) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc1_cijfersextra\" name=\"oc1_cijfersextra\" value=\"" . htmlentities($oc1) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc2_cijfersextra\" name=\"oc2_cijfersextra\" value=\"" . htmlentities($oc2) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc3_cijfersextra\" name=\"oc3_cijfersextra\" value=\"" . htmlentities($oc3) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc4_cijfersextra\" name=\"oc4_cijfersextra\" value=\"" . htmlentities($oc4) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc5_cijfersextra\" name=\"oc5_cijfersextra\" value=\"" . htmlentities($oc5) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc6_cijfersextra\" name=\"oc6_cijfersextra\" value=\"" . htmlentities($oc6) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc7_cijfersextra\" name=\"oc7_cijfersextra\" value=\"" . htmlentities($oc7) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc8_cijfersextra\" name=\"oc8_cijfersextra\" value=\"" . htmlentities($oc8) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc9_cijfersextra\" name=\"oc9_cijfersextra\" value=\"" . htmlentities($oc9) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc10_cijfersextra\" name=\"oc10_cijfersextra\" value=\"" . htmlentities($oc10) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc11_cijfersextra\" name=\"oc11_cijfersextra\" value=\"" . htmlentities($oc11) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc12_cijfersextra\" name=\"oc12_cijfersextra\" value=\"" . htmlentities($oc12) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc13_cijfersextra\" name=\"oc13_cijfersextra\" value=\"" . htmlentities($oc13) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc14_cijfersextra\" name=\"oc14_cijfersextra\" value=\"" . htmlentities($oc14) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc15_cijfersextra\" name=\"oc15_cijfersextra\" value=\"" . htmlentities($oc15) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc16_cijfersextra\" name=\"oc16_cijfersextra\" value=\"" . htmlentities($oc16) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc17_cijfersextra\" name=\"oc17_cijfersextra\" value=\"" . htmlentities($oc17) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc18_cijfersextra\" name=\"oc18_cijfersextra\" value=\"" . htmlentities($oc18) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc19_cijfersextra\" name=\"oc19_cijfersextra\" value=\"" . htmlentities($oc19) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc20_cijfersextra\" name=\"oc20_cijfersextra\" value=\"" . htmlentities($oc20) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc1_cijfersextra\" name=\"dc1_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc1)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc2_cijfersextra\" name=\"dc2_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc2)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc3_cijfersextra\" name=\"dc3_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc3)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc4_cijfersextra\" name=\"dc4_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc4)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc5_cijfersextra\" name=\"dc5_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc5)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc6_cijfersextra\" name=\"dc6_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc6)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc7_cijfersextra\" name=\"dc7_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc7)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc8_cijfersextra\" name=\"dc8_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc8)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc9_cijfersextra\" name=\"dc9_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc9)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc10_cijfersextra\" name=\"dc10_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc10)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc11_cijfersextra\" name=\"dc11_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc11)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc12_cijfersextra\" name=\"dc12_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc12)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc13_cijfersextra\" name=\"dc13_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc13)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc14_cijfersextra\" name=\"dc14_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc14)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc15_cijfersextra\" name=\"dc15_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc15)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc16_cijfersextra\" name=\"dc16_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc16)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc17_cijfersextra\" name=\"dc17_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc17)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc18_cijfersextra\" name=\"dc18_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc18)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc19_cijfersextra\" name=\"dc19_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc19)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc20_cijfersextra\" name=\"dc20_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc20)) . "\"/>";
                      }
                    } else {
                      //POR AHORA NADA
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
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $result = $mysqli->error;
              }
              //END CaribeDevelopers - Janio Acero
            } else {
              $htmlcontrol .= "No results to show";
            }
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


      // $htmlcontrol .= "<input type=\"hidden\" id=\"klas_cijferswaarde\" name=\"klas_cijferswaarde\" value=\"" . htmlentities($klas_in) . "\">";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"schooljaar_cijferswaarde\" name=\"schooljaar_cijferswaarde\" value=\"" . htmlentities($schooljaar) . "\"/>";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"rapnummer_cijferswaarde\" name=\"rapnummer_cijferswaarde\" value=\"" . htmlentities($rap_in) . "\"/>";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"vak_cijferswaarde\" name=\"vak_cijferswaarde\" value=\"" . htmlentities($vak_in) . "\"/>";



      $returnvalue = $htmlcontrol;
      return $returnvalue;
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
}
