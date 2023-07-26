<?php

require_once("spn_setting.php");

class spn_verzuim
{
  public $tablename_verzuim = "le_verzuim";
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $debug = false;
  public $showphperrors = true;
  public $errormessage = "";
  public $Xverzuimid = null;
  public $sp_get_verzuim_by_student = "sp_get_verzuim_by_student";
  public $verzuimid = null;

  /* debug purposes  - EL*/
  function __construct()
  {
    if ($this->showphperrors) {
      error_reporting(-1);
    } else {
      error_reporting(0);
    }
  }

  function createverzuim($studentid, $schooljaar, $rapnummer, $klas, $datum, $opmerking, $user, $telaat, $absentie, $LP, $toetsinhalen, $uitsturen, $huiswerk)
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
      $mysqli->set_charset('utf8');
      if ($stmt = $mysqli->prepare("insert into " . $this->tablename_verzuim . "(created,studentid,schooljaar,klas,datum,opmerking,user,telaat,absentie,LP,toetsinhalen,uitsturen,huiswerk) values (?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
        if ($stmt->bind_param("sisssssiiiiii", $_DateTime, $studentid, $schooljaar, $klas, $datum, $opmerking, $user, $telaat, $absentie, $LP, $toetsinhalen, $uitsturen, $huiswerk)) {
          if ($stmt->execute()) {

            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'verzuim', 'create verzuim', appconfig::GetDummy());
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
      $this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  function editverzuim($verzuimid, $studentid, $schooljaar, $klas, $datum, $opmerking, $user, $telaat, $absentie, $LP, $toetsinhalen, $uitsturen, $huiswerk)
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
      $mysqli->set_charset('utf8');

      if ($stmt = $mysqli->prepare("update " . $this->tablename_verzuim . " set lastchanged = ?, studentid = ?, schooljaar = ?,  klas = ?, datum = ?, opmerking = ?, user =?, telaat= ?, absentie = ?, LP = ?, toetsinhalen = ?, uitsturen = ?, huiswerk = ? where id = ?")) {
        if ($stmt->bind_param("sisssssiiiiiii", $_DateTime, $studentid, $schooljaar, $klas, $datum, $opmerking, $user, $telaat, $absentie, $LP, $toetsinhalen, $uitsturen, $huiswerk, $verzuimid)) {
          if ($stmt->execute()) {

            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'verzuim', 'update verzuim', appconfig::GetDummy());
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
      $this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  function listverzuim($schoolid, $klas_in, $datum_in, $sort_order)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if ($utils->converttomysqldate($datum_in) != false) {
      $_datum_in = $utils->converttomysqldate($datum_in);
      $timestamp = strtotime($datum_in);
      $_datum_in_1 = date("Y-m-d", $timestamp);
    } else {
      $_datum_in = null;
    }

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    // End change settings (laldana@caribedev)

    $sql_query = "select s.id, v.id, s.class, s.firstname, s.lastname, s.sex,v.telaat,v.absentie,v.toetsinhalen, v.uitsturen, v.huiswerk, v.lp, v.opmerking from students s inner join le_verzuim v on s.id = v.studentid where s.class = ?  and s.schoolid = ? and (replace(v.datum,'/','-') = ? OR datum = ?)
    union
    select s.id, null, s.class, s.firstname, s.lastname, s.sex,0,0,0,0,0,0,''  from students s where s.class = ? and s.schoolid = ? and  s.id not in (select studentid from le_verzuim where (replace(datum,'/','-') = ?) OR datum = ?) order by ";

    $sql_order = " lastname, firstname ";
    if ($s->_setting_mj) {
      $sql_query .= " sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        //if($select->bind_param("sissisis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$klas_in,$schoolid,$_datum_in)) {
        if ($select->bind_param("sisssiss", $klas_in, $schoolid, $_datum_in, $_datum_in_1, $klas_in, $schoolid, $_datum_in, $_datum_in_1)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'verzuim', 'list verzuim', appconfig::GetDummy());

            $result = 1;

            $select->bind_result($studentid, $verzuimid, $klas, $firstname, $lastname, $sex, $telaat, $absent, $toetsinhalen, $uitsturen, $huiswerk, $lp, $opmerking);
            $select->store_result();

            if ($select->num_rows > 0) {
              $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-bordered table-colored table-houding\" data-table=\"no\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID</th><th class=\"btn-m-w\">Naam</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>" . ($_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen') . "</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";

              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;


              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {

                if ($_SESSION["UserRights"] != "ASSISTENT") {
                  $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$klas_in\"  data-datum=\"$datum_in\"><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";
                  $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"telaat\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control telaat-field\"" . ($telaat == 1 ? "checked=\"checked\"" : "") . " ></td>";
                  $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"absentie\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control absentie-field\"" . ($absent == 1 ? "checked=\"checked\"" : "") . " ></td>";
                  $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"toetsinhalen\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control toetsinhalen-field\"" . ($toetsinhalen == 1 ? "checked=\"checked\"" : "") . " ></td>";
                  $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"uitsturen\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control uitsturen-field\"" . ($uitsturen == 1 ? "checked=\"checked\"" : "") . "></td>";
                  /* remember to upercase */
                  $htmlcontrol .= "<td><select verzuim=\"$verzuimid\" name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"" : "") . ">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"" : "") . ">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"" : "") . ">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"" : "") . ">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"" : "") . ">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"" : "") . ">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"" : "") . "D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"" : "") . ">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"" : "") . ">P2</option></select></td>";
                  $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"huiswerk\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control geenhuiswerk-field\"" . ($huiswerk == 1 ? "checked" : "") . " ></td>";
                  //  $htmlcontrol .="<td><textarea name =\"lbl_opmerking\" id=\"lbl_opmerking\" class=\"editable\" rows=\"4 \" cols=\"65 \" type = 'text' value=\"".$opmerking."\"></textarea></td>";
                  $htmlcontrol .= "<td width =\"300px\"><input verzuim=\"$verzuimid\" name =\"opmerking\" id=\"lblopmerking \" data-toggle=\"tooltip \" class = 'editable lblopmerking form-control opmerking-input' type = 'text' value=\"" . $opmerking . "\" title=\"" . $opmerking . "\"  style = 'display:block'/></td>";

                  $x++;

                  $xx++;
                } else {
                  $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$klas_in\"  data-datum=\"$datum_in\"><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";
                  $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"telaat\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control telaat-field\"" . ($telaat == 1 ? "checked=\"checked\"" : "") . " disabled></td>";
                  $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"absentie\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control absentie-field\"" . ($absent == 1 ? "checked=\"checked\"" : "") . " disabled></td>";
                  $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"toetsinhalen\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control toetsinhalen-field\"" . ($toetsinhalen == 1 ? "checked=\"checked\"" : "") . " disabled></td>";
                  $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"uitsturen\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control uitsturen-field\"" . ($uitsturen == 1 ? "checked=\"checked\"" : "") . " disabled></td>";
                  /* remember to upercase */
                  $htmlcontrol .= "<td><select verzuim=\"$verzuimid\" name =\"lp\" class=\"form-control lp-field\" disabled><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"" : "") . ">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"" : "") . ">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"" : "") . ">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"" : "") . ">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"" : "") . ">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"" : "") . ">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"" : "") . "D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"" : "") . ">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"" : "") . ">P2</option></select></td>";
                  $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"huiswerk\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control geenhuiswerk-field\"" . ($huiswerk == 1 ? "checked" : "") . " disabled></td>";
                  //  $htmlcontrol .="<td><textarea name =\"lbl_opmerking\" id=\"lbl_opmerking\" class=\"editable\" rows=\"4 \" cols=\"65 \" type = 'text' value=\"".$opmerking."\"></textarea></td>";
                  $htmlcontrol .= "<td width =\"300px\"><input verzuim=\"$verzuimid\" name =\"opmerking\" id=\"lblopmerking \" data-toggle=\"tooltip \" class = 'editable lblopmerking form-control opmerking-input' type = 'text' value=\"" . $opmerking . "\" title=\"" . $opmerking . "\"  style = 'display:block' disabled/></td>";

                  $x++;

                  $xx++;
                }
              }



              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */

            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */

          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */

        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {

      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function listverzuimprint($schoolid, $klas_in, $datum_in, $sort_order)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if ($utils->converttomysqldate($datum_in) != false) {
      $_datum_in = $utils->converttomysqldate($datum_in);
    } else {
      $_datum_in = null;
    }

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    // End change settings (laldana@caribedev)

    $sql_query = "select s.id, v.id, s.class, s.firstname, s.lastname, s.sex,v.telaat,v.absentie,v.toetsinhalen, v.uitsturen, v.huiswerk, v.lp, v.opmerking from students s inner join le_verzuim v on s.id = v.studentid where s.class = ?  and s.schoolid = ? and v.datum = ?
    union
    select s.id, null, s.class, s.firstname, s.lastname, s.sex,0,0,0,0,0,0,''  from students s where s.class = ? and s.schoolid = ? and  s.id not in (select studentid from le_verzuim where datum = ?) order by ";

    $sql_order = " lastname, firstname ";
    if ($s->_setting_mj) {
      $sql_query .= " sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        //if($select->bind_param("sissisis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$klas_in,$schoolid,$_datum_in)) {
        if ($select->bind_param("sissis", $klas_in, $schoolid, $_datum_in, $klas_in, $schoolid, $_datum_in)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'verzuim', 'list verzuim', appconfig::GetDummy());

            $result = 1;

            $select->bind_result($studentid, $verzuimid, $klas, $firstname, $lastname, $sex, $telaat, $absent, $toetsinhalen, $uitsturen, $huiswerk, $lp, $opmerking);
            $select->store_result();

            if ($select->num_rows > 0) {
              $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-bordered table-colored table-houding\" data-table=\"no\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID</th><th class=\"btn-m-w\">Naam</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>" . ($_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen') . "</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";

              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;


              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {


                $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$klas_in\"  data-datum=\"$datum_in\"><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";
                $htmlcontrol .= "<td><input name =\"telaat\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control telaat-field\"" . ($telaat == 1 ? "checked=\"checked\"" : "") . "></td>";
                $htmlcontrol .= "<td><input name =\"absentie\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control absentie-field\"" . ($absent == 1 ? "checked=\"checked\"" : "") . " ></td>";
                $htmlcontrol .= "<td><input name =\"toetsinhalen\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'class=\"form-control toetsinhalen-field\"" . ($toetsinhalen == 1 ? "checked=\"checked\"" : "") . " ></td>";
                $htmlcontrol .= "<td><input name =\"uitsturen\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control uitsturen-field\"" . ($uitsturen == 1 ? "checked=\"checked\"" : "") . "></td>";
                /* remember to upercase */
                $htmlcontrol .= "<td><select name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"" : "") . ">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"" : "") . ">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"" : "") . ">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"" : "") . ">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"" : "") . ">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"" : "") . ">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"" : "") . "D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"" : "") . ">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"" : "") . ">P2</option></select></td>";
                $htmlcontrol .= "<td><input name =\"huiswerk\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control geenhuiswerk-field\"" . ($huiswerk == 1 ? "checked" : "") . " ></td>";
                //  $htmlcontrol .="<td><textarea name =\"lbl_opmerking\" id=\"lbl_opmerking\" class=\"editable\" rows=\"4 \" cols=\"65 \" type = 'text' value=\"".$opmerking."\"></textarea></td>";
                $htmlcontrol .= "<td width =\"800px\"><span name =\"opmerking\" id=\"lblopmerking \" class=\"editable lblopmerking\">" . $opmerking . "</span></td>";

                //$htmlcontrol .="<input name =\"opmerking\" id=\"lblopmerking \" class = 'editable lblopmerking form-control opmerking-input' type = 'text' value=\"".$opmerking."\"  style = 'display:block'/>"
                //$htmlcontrol .="<td><span name =\"opmerking\" id=\"lblopmerking \" class=\"editable lblopmerking\">".$opmerking."</span></td>";

                /* increment variable with one */
                $x++;

                $xx++;
              }



              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */

            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */

          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */

        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {

      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function saveverzuim($schooljaar, $schoolid_in, $studentid_in, $telaat_in, $absentie_in, $toetsinhalen_in, $uitsturen_in, $huiswerk_in, $lp_in, $opmerking_in, $klas_in, $datum_in)
  {
    $telaat = 0;
    $absentie = 0;
    $toetsinhalen = 0;
    $uitsturen = 0;
    $huiswerk = 0;

    // print 'save verzuim :' .$schoolid_in .'-'.  $studentid_in .'-'. $telaat_in.'-'.$absentie_in.'-'.$toetsinhalen_in.'-'.$uitsturen_in.'-'.$huiswerk_in.'-'.$lp_in.'-'.$opmerking_in.'-'.$klas_in.'-'.$datum_in;

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if ($utils->converttomysqldate($datum_in) != false) {
      $_datum_in = $utils->converttomysqldate($datum_in);
    } else {
      $_datum_in = null;
    }

    if ($telaat_in === 'true') {
      $telaat = 1;
    } else {
      $telaat = 0;
    }

    if ($absentie_in === 'true') {
      $absentie = 1;
    } else {
      $absentie = 0;
    }

    if ($toetsinhalen_in === 'true') {
      $toetsinhalen = 1;
    } else {
      $toetsinhalen = 0;
    }

    if ($uitsturen_in === 'true') {
      $uitsturen = 1;
    } else {
      $uitsturen = 0;
    }

    if ($huiswerk_in === 'true') {
      $huiswerk = 1;
    } else {
      $huiswerk = 0;
    }

    $verzuim_count = $this->_getverzuimcount($schoolid_in, $studentid_in, $klas_in, $_datum_in);
    if ($verzuim_count > 0) {
      return $this->editverzuim($this->Xverzuimid, $studentid_in, $schooljaar, $klas_in, $_datum_in, $opmerking_in, "", $telaat, $absentie, $lp_in, $toetsinhalen, $uitsturen, $huiswerk);
    } else {
      return $this->createverzuim($studentid_in, $schooljaar, "", $klas_in, $_datum_in, $opmerking_in, "", $telaat, $absentie, $lp_in, $toetsinhalen, $uitsturen, $huiswerk);
    }
  }

  function _getverzuimcount($schoolid, $studentid_in, $klas_in, $datum_in)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select v.id from le_verzuim v where v.studentid = ? and v.datum = ? and v.klas = ? ";

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("sss", $studentid_in, $datum_in, $klas_in)) {
          if ($select->execute()) {

            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'verzuim', 'read verzuim', appconfig::GetDummy());

            $result = 1;

            $select->store_result();
            $select->bind_result($vid);
            while ($select->fetch()) {
              $this->Xverzuimid = $vid;
            }

            $returnvalue = $select->num_rows;
          } else {
            /* error executing query */

            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */

          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */

        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }
    } catch (Exception $e) {

      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function list_verzuim_by_student($schooljaar, $studentid, $dummy)
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
        $sp_get_verzuim_by_student = "sp_get_verzuim_by_student";
        $mysqli->set_charset('utf8');
        if ($stmt = $mysqli->prepare("CALL " . $this->$sp_get_verzuim_by_student . "(?,?)")) {
          if ($stmt->bind_param("si", $schooljaar, $studentid)) {

            if ($stmt->execute()) {

              // $spn_audit = new spn_audit();
              // $UserGUID = $_SESSION['UserGUID'];
              // $spn_audit->create_audit($UserGUID, 'verzuim','list verzuim',appconfig::GetDummy());

              $result = 1;
              $stmt->bind_result($schooljaar_, $id_verzuim, $datum, $telaat, $absentie, $toetsinhalen, $uitsturen, $lp, $huiswerk, $opmerking);
              $stmt->store_result();

              if ($stmt->num_rows > 0) {
                $htmlcontrol .= "<div class=\"table-responsive\">";

                $htmlcontrol .= "<table id=\"tbl_verzuim_by_idstudent\" class=\"table table-bordered table-colored\" data-table=\"no\">";
                $htmlcontrol .= "<thead>";
                $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID Verzuim</th><th>Schooljaar</th><th>Datum</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>" . ($_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen') . "</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th></tr>";
                $htmlcontrol .= "</thead>";
                $htmlcontrol .= "<tbody>";
                while ($stmt->fetch()) {
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td align=\"center\">" . htmlentities($id_verzuim) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($schooljaar) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($datum) . "</td>";
                  $htmlcontrol .= "<td><i name =\"telaat\"" . ($telaat == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";
                  $htmlcontrol .= "<td><i name =\"absentie\"" . ($absentie == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";
                  // $htmlcontrol .= "<td><input name =\"absentie\" type=\"checkbox\" class=\"form-control absentie-field\"". ($absentie == 1 ? "checked=\"checked\"": "") ." ></td>";
                  $htmlcontrol .= "<td><i name =\"toetsinhalen\"" . ($toetsinhalen == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";
                  $htmlcontrol .= "<td><i name =\"uitsturen\"" . ($uitsturen == 1 ?  "class=\"fa fa-check\"" : "") . "></td>";
                  /* remember to upercase */
                  $htmlcontrol .= "<td><select disabled=\"disabled\" name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"" : "") . ">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"" : "") . ">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"" : "") . ">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"" : "") . ">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"" : "") . ">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"" : "") . ">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"" : "") . "D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"" : "") . ">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"" : "") . ">P2</option></select></td>";
                  $htmlcontrol .= "<td><i name =\"huiswerk\"" . ($huiswerk == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";
                  $htmlcontrol .= "<td><span name =\"opmerking\" id=\"lblopmerking \" class=\"editable lblopmerking\">" . $opmerking . "</span></td>";
                }
                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";
                $htmlcontrol .= "</table>";
                $htmlcontrol .= "</div>";
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

  function listverzuim_tutor($schoolid, $klas_in, $datum_in, $period, $vak)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    $l1 = array();
    $l2 =  array();
    $l3 =  array();
    $l4 =  array();
    $l5 =  array();
    $l6 =  array();
    $l7 =  array();
    $l8 =  array();
    $l9 =  array();

    mysqli_report(MYSQLI_REPORT_STRICT);

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if ($utils->converttomysqldate($datum_in) != false) {
      $_datum_in = $utils->converttomysqldate($datum_in);
    } else {
      $_datum_in = null;
    }

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    // End change settings (laldana@caribedev)

    $sql_query = "select s.id, v.id, s.class, s.lastname, s.firstname, s.sex,v.telaat,v.absentie,v.toetsinhalen,
    v.uitsturen, v.huiswerk, v.lp, v.opmerking
    from students s inner join le_verzuim v on s.id = v.studentid
    where s.class = ? and s.schoolid = ? and v.datum = ? and v.lesuur = ? and vak= ?
    union select s.id, null, s.class, s.firstname, s.lastname, s.sex,0,0,0,0,0,0,'' from students s where s.class = ? and s.schoolid = ?
    and s.id not in (select studentid from le_verzuim where datum = ?) order by ";

    $sql_order = " lastname, firstname ";
    if ($s->_setting_mj) {
      $sql_query .= " sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)
    // print($sql_query);
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        //if($select->bind_param("sissisis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$klas_in,$schoolid,$_datum_in)) {
        if ($select->bind_param("sisissis", $klas_in, $schoolid, $datum_in, $period, $vak, $klas_in, $schoolid, $datum_in)) {
          if ($select->execute()) {

            $result = 1;

            $select->bind_result($studentid, $verzuimid, $klas, $lastname, $firstname, $sex, $telaat, $absent, $toetsinhalen, $uitsturen, $huiswerk, $lp, $opmerking);
            $select->store_result();

            if ($select->num_rows > 0) {
              $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-bordered table-colored table-houding\" data-table=\"no\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID</th><th class=\"btn-m-w\">Naam</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>" . ($_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen') . "</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th><th>L1</th><th>L2</th><th>L3</th><th>L4</th><th>L5</th><th>L6</th><th>L7</th><th>L8</th><th>L9</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";
              $x = 1;
              $xx = 1;
              while ($select->fetch()) {
                $l1 = $this->get_lessuur_value($studentid, $klas_in, $datum_in, 1);
                $l2 = $this->get_lessuur_value($studentid, $klas_in, $datum_in, 2);
                $l3 = $this->get_lessuur_value($studentid, $klas_in, $datum_in, 3);
                $l4 = $this->get_lessuur_value($studentid, $klas_in, $datum_in, 4);
                $l5 = $this->get_lessuur_value($studentid, $klas_in, $datum_in, 5);
                $l6 = $this->get_lessuur_value($studentid, $klas_in, $datum_in, 6);
                $l7 = $this->get_lessuur_value($studentid, $klas_in, $datum_in, 7);
                $l8 = $this->get_lessuur_value($studentid, $klas_in, $datum_in, 8);
                $l9 = $this->get_lessuur_value($studentid, $klas_in, $datum_in, 9);

                $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$klas_in\"  data-datum=\"$datum_in\"><td>$x</td><td>" . $lastname . ', ' . $firstname . "</td>";
                $htmlcontrol .= "<td valign=\"middle\"><i name =\"telaat\"" . ($telaat == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";
                $htmlcontrol .= "<td><i name =\"absentie\"" . ($absent == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";
                // $htmlcontrol .= "<td><input name =\"absentie\" type=\"checkbox\" class=\"form-control absentie-field\"". ($absentie == 1 ? "checked=\"checked\"": "") ." ></td>";
                $htmlcontrol .= "<td><i name =\"toetsinhalen\"" . ($toetsinhalen == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";
                $htmlcontrol .= "<td><i name =\"uitsturen\"" . ($uitsturen == 1 ?  "class=\"fa fa-check\"" : "") . "></td>";
                /* remember to upercase */
                $htmlcontrol .= "<td><select disabled=\"disabled\" name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"" : "") . ">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"" : "") . ">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"" : "") . ">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"" : "") . ">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"" : "") . ">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"" : "") . ">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"" : "") . "D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"" : "") . ">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"" : "") . ">P2</option></select></td>";
                $htmlcontrol .= "<td><i name =\"huiswerk\"" . ($huiswerk == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";
                $htmlcontrol .= "<td><span name =\"opmerking\" id=\"lblopmerking \" class=\"editable lblopmerking\">" . $opmerking . "</span></td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l1[2] . " | Opmerking: " . $l1[1] . "\"  style=\"width: 60px\">" . $l1[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l1[2] . " | Opmerking: " . $l2[1] . "\"  style=\"width: 60px\">" . $l2[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l1[2] . " | Opmerking: " . $l3[1] . "\"  style=\"width: 60px\">" . $l3[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l1[2] . " | Opmerking: " . $l4[1] . "\"  style=\"width: 60px\">" . $l4[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l1[2] . " | Opmerking: " . $l5[1] . "\"  style=\"width: 60px\">" . $l5[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l1[2] . " | Opmerking: " . $l6[1] . "\"  style=\"width: 60px\">" . $l6[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l1[2] . " | Opmerking: " . $l7[1] . "\"  style=\"width: 60px\">" . $l7[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l1[2] . " | Opmerking: " . $l8[1] . "\"  style=\"width: 60px\">" . $l8[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l1[2] . " | Opmerking: " . $l9[1] . "\"  style=\"width: 60px\">" . $l9[0] . "</td>";
                $x++;
                $xx++;
              }
              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */

            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */

          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */

        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }
      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {

      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }
  function createverzuim_hs($studentid, $schooljaar, $rapnummer, $klas, $datum, $opmerking, $user, $telaat, $absentie, $LP, $toetsinhalen, $uitsturen, $huiswerk, $period)
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
      $mysqli->set_charset('utf8');
      if ($stmt = $mysqli->prepare("insert into " . $this->tablename_verzuim . "(created,studentid,schooljaar,klas,datum,lesuur, opmerking,user,telaat,absentie,LP,toetsinhalen,uitsturen,huiswerk) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
        if ($stmt->bind_param("sisssissiiiiii", $_DateTime, $studentid, $schooljaar, $klas, $datum, $period, $opmerking, $user, $telaat, $absentie, $LP, $toetsinhalen, $uitsturen, $huiswerk)) {
          if ($stmt->execute()) {
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
      $this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  function saveverzuim_hs($schooljaar, $schoolid_in, $studentid_in, $telaat_in, $absentie_in, $toetsinhalen_in, $uitsturen_in, $huiswerk_in, $lp_in, $opmerking_in, $klas_in, $datum_in, $period, $_verzuimid)
  {
    $telaat = 0;
    $absentie = 0;
    $toetsinhalen = 0;
    $uitsturen = 0;
    $huiswerk = 0;
    $_verzuim = null;
    // print 'save verzuim :' .$schoolid_in .'-'.  $studentid_in .'-'. $telaat_in.'-'.$absentie_in.'-'.$toetsinhalen_in.'-'.$uitsturen_in.'-'.$huiswerk_in.'-'.$lp_in.'-'.$opmerking_in.'-'.$klas_in.'-'.$datum_in;

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if ($utils->converttomysqldate($datum_in) != false) {
      $_datum_in = $utils->converttomysqldate($datum_in);
    } else {
      $_datum_in = null;
    }

    if ($telaat_in === 'true') {
      $telaat = 1;
    } else {
      $telaat = 0;
    }

    if ($absentie_in === 'true') {
      $absentie = 1;
    } else {
      $absentie = 0;
    }

    if ($toetsinhalen_in === 'true') {
      $toetsinhalen = 1;
    } else {
      $toetsinhalen = 0;
    }

    if ($uitsturen_in === 'true') {
      $uitsturen = 1;
    } else {
      $uitsturen = 0;
    }

    if ($huiswerk_in === 'true') {
      $huiswerk = 1;
    } else {
      $huiswerk = 0;
    }

    if ($period == '99') {
      return $this->editverzuim_hs_allday($_verzuimid, $studentid_in, $schooljaar, $klas_in, $_datum_in, $opmerking_in, "SPN", $telaat, $absentie, $lp_in, $toetsinhalen, $uitsturen, $huiswerk, $period);
    } else {
      return $this->editverzuim_hs($_verzuimid, $studentid_in, $schooljaar, $klas_in, $_datum_in, $opmerking_in, "SPN", $telaat, $absentie, $lp_in, $toetsinhalen, $uitsturen, $huiswerk, $period);
    }
  }

  function editverzuim_hs($_verzuimid, $studentid, $schooljaar, $klas, $datum, $opmerking, $user, $telaat, $absentie, $LP, $toetsinhalen, $uitsturen, $huiswerk, $period)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $sql_query = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      $sql_query = "update le_verzuim set lastchanged = ?, studentid = ?, schooljaar = ?,  klas = ?, datum = ?, opmerking = ?, user =?, telaat= ?, absentie = ?, LP = ?, toetsinhalen = ?, uitsturen = ?, huiswerk = ?, lesuur= ? where id = ?";
      if ($stmt = $mysqli->prepare($sql_query)) {
        if ($stmt->bind_param("sisssssiiiiiiii", $_DateTime, $studentid, $schooljaar, $klas, $datum, $opmerking, $user, $telaat, $absentie, $LP, $toetsinhalen, $uitsturen, $huiswerk, $period, $_verzuimid)) {
          if ($stmt->execute()) {
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
      $this->exceptionvalue = $e->getMessage();
      return $result;
    }
  }

  function editverzuim_hs_allday($_verzuimid, $studentid, $schooljaar, $klas, $datum, $opmerking, $user, $telaat, $absentie, $LP, $toetsinhalen, $uitsturen, $huiswerk, $period)
  {
    print('entre en edit verzuim, este period:');
    print('<br> verzuim id: ' . $_verzuimid);
    print('<br> $studentid: ' . $studentid);
    print('<br> $schooljaar: ' . $schooljaar);
    print('<br>$klas:' . $klas);
    print('<br>$Datum: ' . $datum);
    print('<br>$opmerking: ' . $opmerking);
    print('<br>$user: ' . $user);
    print('<br>$telaat: ' . $telaat);
    print('<br>$absentie: ' . $absentie);
    print('<br>$LP: ' . $LP);
    print('<br>$toetsinhalen: ' . $toetsinhalen);
    print('<br>$uitsturen: ' . $uitsturen);
    print('<br>$huiswerk :' . $huiswerk);
    print('<br>$period :  ' . $period);

    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $sql_query = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      require_once("spn_utils.php");
      $utils = new spn_utils();
      if ($utils->converttomysqldate($datum) != false) {
        $datum = $utils->converttomysqldate($datum);
      } else {
        $datum = null;
      }

      if ($telaat === 'true') {
        $telaat = 1;
      } else {
        $telaat = 0;
      }

      if ($absentie === 'true') {
        $absentie = 1;
      } else {
        $absentie = 0;
      }

      if ($toetsinhalen === 'true') {
        $toetsinhalen = 1;
      } else {
        $toetsinhalen = 0;
      }

      if ($uitsturen === 'true') {
        $uitsturen = 1;
      } else {
        $uitsturen = 0;
      }

      if ($huiswerk === 'true') {
        $huiswerk = 1;
      } else {
        $huiswerk = 0;
      }
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      $sql_query = "update le_verzuim set lastchanged = '$_DateTime', opmerking = '$opmerking', user = 'SPN', telaat= $telaat, absentie = $absentie, LP = $LP, toetsinhalen = $toetsinhalen, uitsturen = $uitsturen, huiswerk = $huiswerk where studentid = $studentid and schooljaar = '$schooljaar' and  klas = '$klas' and datum = '$datum';";
      print($sql_query);
      if ($stmt = $mysqli->prepare($sql_query)) {
        // if($stmt->bind_param("sssiiiiiiisss",$_DateTime,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk, $studentid,$schooljaar,$klas,$datum))
        // {
        if ($stmt->execute()) {
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

        // }
        // else
        // {
        //   $result = 0;
        //   $this->mysqlierror = $mysqli->error;
        // }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
      return $result;
    }
  }
  function listverzuim_hs($schoolid, $klas, $datum, $period, $vak)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $returdata = "";
    ($_SESSION['UserRights'] == 'BEHEER' || $_SESSION['UserRights'] == 'ADMINISTRATIE') ? $user = '' : $user = 'disabled';
    $l1 = array();
    $l2 =  array();
    $l3 =  array();
    $l4 =  array();
    $l5 =  array();
    $l6 =  array();
    $l7 =  array();
    $l8 =  array();
    $l9 =  array();

    mysqli_report(MYSQLI_REPORT_STRICT);

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if ($utils->converttomysqldate($datum) != false) {
      $datum = $utils->converttomysqldate($datum);
    } else {
      $datum = null;
    }

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);
    //cheking if data exist for that parameters
    $this->create_le_verzuim_student_all($schoolid, $_SESSION['SchoolJaar'], $klas, $datum);

    // End change settings (laldana@caribedev)
    $sql_query = "select s.id, v.id, s.class, s.lastname, s.firstname,s.sex,v.telaat,v.absentie,v.toetsinhalen, v.uitsturen, v.huiswerk, v.lp, v.opmerking,
                  v.p1,v.p2,v.p3,v.p4,v.p5,v.p6,v.p7,v.p8,v.p9,v.p10
                  from students s inner join le_verzuim_hs v on s.id = v.studentid
                  where s.class = ?  and s.schoolid = ? and v.schooljaar = ? and v.datum = ? order by ";

    $sql_order = " lastname, firstname ";
    if ($s->_setting_mj) {
      $sql_query .= " sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    //print($sql_query);
    // End change settings (laldana@caribedev)
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {

        if ($select->bind_param("siss", $klas, $schoolid, $_SESSION['SchoolJaar'], $datum)) {
          if ($select->execute()) {
            $result = 1;

            $select->bind_result($studentid, $verzuimid, $_klas, $lastname, $firstname, $sex, $telaat, $absent, $toetsinhalen, $uitsturen, $huiswerk, $lp, $opmerking, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10);

            $select->store_result();

            if ($select->num_rows > 0) {
              $htmlcontrol .= "<div class='col-md-12'>";
              $htmlcontrol .= "<div style='font-size: 14px;'><span><b>L</b> = Laat   |</span>";
              $htmlcontrol .= "<span><b>   A</b> = Afwezig   |</span>";
              $htmlcontrol .= "<span><b>   X</b> = Afspraak extern en komt terug op school   |</span>";
              $htmlcontrol .= "<span><b>   S</b> = Spijbelen</span><br></br>";
              $htmlcontrol .= "<span><b>M</b> = Met toestemming naar huis   |</span>";
              $htmlcontrol .= "<span><b>   U</b> = Uitgestuurd   |</span>";
              $htmlcontrol .= "<span><b>   T</b> = Time-out(schorsing)</span></div>";


              $htmlcontrol .= "<table class=\"table table-bordered table-colored table-houding\" data-table=\"no\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID</th><th class=\"btn-m-w\">Naam</th>";
              require_once("spn_utils.php");
              $u = new spn_utils();
              $date = $u->convertfrommysqldate_new($datum);
              $select1 = $this->klasenboek_vak($schoolid, $klas, $date, 0);
              if ($select1 != null && $select1 != "") {
                $htmlcontrol .= $select1;
              } else {
                $htmlcontrol .= "<th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th>Dag</th>";
              }
              $disabled = $this->klasenboek_vak_array($schoolid, $klas, $date);
              $htmlcontrol .= "<th>Event</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";
              $x = 1;
              $xx = 1;
              while ($select->fetch()) {
                $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$_klas\"  data-datum=\"$datum\"><td>$x</td><td>" . $lastname  . ', ' . $firstname . "</td>";

                $htmlcontrol .= "<td><input " . $disabled[1] . " class='klasen_p' maxlength='1' style='max-width: 30px;' type='text' onchange='saveverzuimdata(" . "&#39;" . $_SESSION['SchoolJaar'] . "&#39;" . "," . "&#39;" . $schoolid . "&#39;" . "," . "&#39;" . $studentid . "&#39;" . "," . "&#39;" . $_klas . "&#39;" . "," . "&#39;" . $datum . "&#39;" . "," . "&#39;" . $verzuimid . "&#39;" . "," . "&#39;" . "lp1" . $x . "&#39;" .  "," . "&#39;" . "p1" . "&#39;" . ")' verzuim=\"$verzuimid\" id =\"lp1$x\" value=" . $p1 . "></td>";

                $htmlcontrol .= "<td><input " . $disabled[2] . " class='klasen_p' maxlength='1' style='max-width: 30px;' type='text' onchange='saveverzuimdata(" . "&#39;" . $_SESSION['SchoolJaar'] . "&#39;" . "," . "&#39;" . $schoolid . "&#39;" . "," . "&#39;" . $studentid . "&#39;" . "," . "&#39;" . $_klas . "&#39;" . "," . "&#39;" . $datum . "&#39;" . "," . "&#39;" . $verzuimid . "&#39;" . "," . "&#39;" . "lp2" . $x . "&#39;" .  "," . "&#39;" . "p2" . "&#39;" . ")' verzuim=\"$verzuimid\" id =\"lp2$x\" value=" . $p2 . "></td>";

                $htmlcontrol .= "<td><input " . $disabled[3] . " class='klasen_p' maxlength='1' style='max-width: 30px;' type='text' onchange='saveverzuimdata(" . "&#39;" . $_SESSION['SchoolJaar'] . "&#39;" . "," . "&#39;" . $schoolid . "&#39;" . "," . "&#39;" . $studentid . "&#39;" . "," . "&#39;" . $_klas . "&#39;" . "," . "&#39;" . $datum . "&#39;" . "," . "&#39;" . $verzuimid . "&#39;" . "," . "&#39;" . "lp3" . $x . "&#39;" .  "," . "&#39;" . "p3" . "&#39;" . ")' verzuim=\"$verzuimid\" id =\"lp3$x\" value=" . $p3 . "></td>";

                $htmlcontrol .= "<td><input " . $disabled[4] . " class='klasen_p' maxlength='1' style='max-width: 30px;' type='text' onchange='saveverzuimdata(" . "&#39;" . $_SESSION['SchoolJaar'] . "&#39;" . "," . "&#39;" . $schoolid . "&#39;" . "," . "&#39;" . $studentid . "&#39;" . "," . "&#39;" . $_klas . "&#39;" . "," . "&#39;" . $datum . "&#39;" . "," . "&#39;" . $verzuimid . "&#39;" . "," . "&#39;" . "lp4" . $x . "&#39;" .  "," . "&#39;" . "p4" . "&#39;" . ")' verzuim=\"$verzuimid\" id =\"lp4$x\" value=" . $p4 . "></td>";

                $htmlcontrol .= "<td><input " . $disabled[5] . " class='klasen_p' maxlength='1' style='max-width: 30px;' type='text' onchange='saveverzuimdata(" . "&#39;" . $_SESSION['SchoolJaar'] . "&#39;" . "," . "&#39;" . $schoolid . "&#39;" . "," . "&#39;" . $studentid . "&#39;" . "," . "&#39;" . $_klas . "&#39;" . "," . "&#39;" . $datum . "&#39;" . "," . "&#39;" . $verzuimid . "&#39;" . "," . "&#39;" . "lp5" . $x . "&#39;" .  "," . "&#39;" . "p5" . "&#39;" . ")' verzuim=\"$verzuimid\" id =\"lp5$x\" value=" . $p5 . "></td>";

                $htmlcontrol .= "<td><input " . $disabled[6] . " class='klasen_p' maxlength='1' style='max-width: 30px;' type='text' onchange='saveverzuimdata(" . "&#39;" . $_SESSION['SchoolJaar'] . "&#39;" . "," . "&#39;" . $schoolid . "&#39;" . "," . "&#39;" . $studentid . "&#39;" . "," . "&#39;" . $_klas . "&#39;" . "," . "&#39;" . $datum . "&#39;" . "," . "&#39;" . $verzuimid . "&#39;" . "," . "&#39;" . "lp6" . $x . "&#39;" .  "," . "&#39;" . "p6" . "&#39;" . ")' verzuim=\"$verzuimid\" id =\"lp6$x\" value=" . $p6 . "></td>";

                $htmlcontrol .= "<td><input " . $disabled[7] . " class='klasen_p' maxlength='1' style='max-width: 30px;' type='text' onchange='saveverzuimdata(" . "&#39;" . $_SESSION['SchoolJaar'] . "&#39;" . "," . "&#39;" . $schoolid . "&#39;" . "," . "&#39;" . $studentid . "&#39;" . "," . "&#39;" . $_klas . "&#39;" . "," . "&#39;" . $datum . "&#39;" . "," . "&#39;" . $verzuimid . "&#39;" . "," . "&#39;" . "lp7" . $x . "&#39;" .  "," . "&#39;" . "p7" . "&#39;" . ")' verzuim=\"$verzuimid\" id =\"lp7$x\" value=" . $p7 . "></td>";

                $htmlcontrol .= "<td><input " . $disabled[8] . " class='klasen_p' maxlength='1' style='max-width: 30px;' type='text' onchange='saveverzuimdata(" . "&#39;" . $_SESSION['SchoolJaar'] . "&#39;" . "," . "&#39;" . $schoolid . "&#39;" . "," . "&#39;" . $studentid . "&#39;" . "," . "&#39;" . $_klas . "&#39;" . "," . "&#39;" . $datum . "&#39;" . "," . "&#39;" . $verzuimid . "&#39;" . "," . "&#39;" . "lp8" . $x . "&#39;" .  "," . "&#39;" . "p8" . "&#39;" . ")' verzuim=\"$verzuimid\" id =\"lp8$x\" value=" . $p8 . "></td>";

                $htmlcontrol .= "<td><input " . $disabled[9] . " class='klasen_p' maxlength='1' style='max-width: 30px;' type='text' onchange='saveverzuimdata(" . "&#39;" . $_SESSION['SchoolJaar'] . "&#39;" . "," . "&#39;" . $schoolid . "&#39;" . "," . "&#39;" . $studentid . "&#39;" . "," . "&#39;" . $_klas . "&#39;" . "," . "&#39;" . $datum . "&#39;" . "," . "&#39;" . $verzuimid . "&#39;" . "," . "&#39;" . "lp9" . $x . "&#39;" .  "," . "&#39;" . "p9" . "&#39;" . ")' verzuim=\"$verzuimid\" id =\"lp9$x\" value=" . $p9 . "></td>";

                $htmlcontrol .= "<td><select fila='" . $x . "' class='select_dag' " . $user  . " onchange='saveverzuimdata(" . "&#39;" . $_SESSION['SchoolJaar'] . "&#39;" . "," . "&#39;" . $schoolid . "&#39;" . ","  . "&#39;" . $studentid . "&#39;" . "," . "&#39;" . $_klas . "&#39;" . "," . "&#39;" . $datum . "&#39;" . "," . "&#39;" . $verzuimid . "&#39;" . "," . "&#39;" . "lp10" . $x . "&#39;" . "," . "&#39;" . "p10" . "&#39;" . ")' verzuim=\"$verzuimid\" id =\"lp10$x\">
                <option value=\"0\"></option>
                <option value=\"L\"" . ($p10 == 'L' ? "selected" : "") . ">L</option>
                <option value=\"A\"" . ($p10 == 'A' ? "selected" : "") . ">A</option>
                <option value=\"X\"" . ($p10 == 'X' ? "selected" : "") . ">X</option>
                <option value=\"M\"" . ($p10 == 'M' ? "selected" : "") . ">M</option>
               <option value=\"S\"" . ($p10 == 'S' ? "selected" : "") . ">S</option>
               <option value=\"U\"" . ($p10 == 'U' ? "selected" : "") . ">U</option>
               <option value=\"T\"" . ($p10 == 'T' ? "selected" : "") . ">T</option>
                </select></td>";
                $htmlcontrol .= "<td><button class='add_event' event='$studentid' style='background-color: #FFDC66; border: 2px solid black; border-radius: 5px;'>+</button></td>";
                $x++;
                $xx++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";

              $htmlcontrol .= "</div>";
            } else {
              //$htmlcontrol = "INSERT INTO le_verzuim_hs SELECT (select max(id) +1 as next_ from le_verzuim_hs),id,now(),now(),'" . $_SESSION['SchoolJaar'] . "','" . $klas . "','" .  $datum . "', 0,'','','SPN',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 from students where schoolid =" . $schoolid . " and class = '" . $klas . "'";             
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */
            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {
      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function listverzuimprint_hs($schoolid, $klas, $datum)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";
    $user = 'disabled';

    $l1 = array();
    $l2 =  array();
    $l3 =  array();
    $l4 =  array();
    $l5 =  array();
    $l6 =  array();
    $l7 =  array();
    $l8 =  array();
    $l9 =  array();

    mysqli_report(MYSQLI_REPORT_STRICT);

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if ($utils->converttomysqldate($datum) != false) {
      $datum = $utils->converttomysqldate($datum);
    } else {
      $datum = null;
    }

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    // End change settings (laldana@caribedev)
    $sql_query = "select s.id, v.id, s.class, s.lastname, s.firstname,s.sex,v.telaat,v.absentie,v.toetsinhalen, v.uitsturen, v.huiswerk, v.lp, v.opmerking,
                  v.p1,v.p2,v.p3,v.p4,v.p5,v.p6,v.p7,v.p8,v.p9,v.p10
                  from students s inner join le_verzuim_hs v on s.id = v.studentid
                  where s.class = ?  and s.schoolid = ? and v.schooljaar = ? and v.datum = ? order by ";

    $sql_order = " lastname, firstname ";
    if ($s->_setting_mj) {
      $sql_query .= " sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    //print($sql_query);
    // End change settings (laldana@caribedev)
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {

        if ($select->bind_param("siss", $klas, $schoolid, $_SESSION['SchoolJaar'], $datum)) {
          if ($select->execute()) {
            $result = 1;

            $select->bind_result($studentid, $verzuimid, $_klas, $lastname, $firstname, $sex, $telaat, $absent, $toetsinhalen, $uitsturen, $huiswerk, $lp, $opmerking, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10);

            $select->store_result();

            if ($select->num_rows > 0) {
              $htmlcontrol .= "<div class='col-md-12'>";
              $htmlcontrol .= "<div style='font-size: 14px;'><span><b>L</b> = Laat   |</span>";
              $htmlcontrol .= "<span><b>   A</b> = Afwezig   |</span>";
              $htmlcontrol .= "<span><b>   X</b> = Afspraak extern en komt terug op school   |</span>";
              $htmlcontrol .= "<span><b>   S</b> = Spijbelen</span><br></br>";
              $htmlcontrol .= "<span><b>M</b> = Met toestemming naar huis   |</span>";
              $htmlcontrol .= "<span><b>   U</b> = Uitgestuurd   |</span>";
              $htmlcontrol .= "<span><b>   T</b> = Time-out(schorsing)</span></div>";
              $htmlcontrol .= "<b>Datum: </b><label>" . $datum . "      </label><b>       Klas: </b><label>" . $klas . "</label>";


              $htmlcontrol .= "<table class=\"table table-bordered table-colored table-houding\" data-table=\"no\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"><th class=\"btn-m-w\">Naam</th>";
              require_once("spn_utils.php");
              $u = new spn_utils();
              $date = $u->convertfrommysqldate_new($datum);
              $select1 = $this->klasenboek_vak($schoolid, $klas, $date, 1);
              if ($select1 != null && $select1 != "") {
                $htmlcontrol .= $select1;
              } else {
                $htmlcontrol .= "<th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th>Dag</th>";
              }
              $disabled = $this->klasenboek_vak_array($schoolid, $klas, $date);
              $htmlcontrol .= "</tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";
              $x = 1;
              $xx = 1;
              while ($select->fetch()) {
                $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$_klas\"  data-datum=\"$datum\"><td>" . $lastname  . ', ' . $firstname . "</td>";

                $htmlcontrol .= "<td>" . ($p1 == 0 ? "" : $p1) . "</td>";

                $htmlcontrol .= "<td>" . ($p2 == 0 ? "" : $p2) . "</td>";

                $htmlcontrol .= "<td>" . ($p3 == 0 ? "" : $p3) . "</td>";

                $htmlcontrol .= "<td>" . ($p4 == 0 ? "" : $p4) . "</td>";

                $htmlcontrol .= "<td>" . ($p5 == 0 ? "" : $p5) . "</td>";

                $htmlcontrol .= "<td>" . ($p6 == 0 ? "" : $p6) . "</td>";

                $htmlcontrol .= "<td>" . ($p7 == 0 ? "" : $p7) . "</td>";

                $htmlcontrol .= "<td>" . ($p8 == 0 ? "" : $p8) . "</td>";

                $htmlcontrol .= "<td>" . ($p9 == 0 ? "" : $p9) . "</td>";

                $htmlcontrol .= "<td>" . ($p10 == 0 ? "" : $p10) . "</td>";
                $x++;
                $xx++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";

              $htmlcontrol .= "</div>";
            } else {
              //$htmlcontrol = "INSERT INTO le_verzuim_hs SELECT (select max(id) +1 as next_ from le_verzuim_hs),id,now(),now(),'" . $_SESSION['SchoolJaar'] . "','" . $klas . "','" .  $datum . "', 0,'','','SPN',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 from students where schoolid =" . $schoolid . " and class = '" . $klas . "'";             
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */
            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {
      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function klasenboek_vak($schoolID, $klas, $datum, $type)
  {
    $html1 = "";
    $html2 = "";
    $html3 = "";
    $html4 = "";
    $html5 = "";
    $html6 = "";
    $html7 = "";
    $html8 = "";
    $html9 = "";
    $html10 = "";
    $p1 = '';
    $p2 = '';
    $p3 = '';
    $p4 = '';
    $p5 = '';
    $p6 = '';
    $p7 = '';
    $p8 = '';
    $p9 = '';
    $p10 = '';
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $get_vak = "SELECT distinct v.ID, v.volledigenaamvak from le_vakken v where v.SchoolID = $schoolID and v.Klas = '$klas' order by v.volledigenaamvak asc;";
    $result = mysqli_query($mysqli, $get_vak);
    while ($row1 = mysqli_fetch_assoc($result)) {
      $vaks[] = array("id" => $row1['ID'], "vak" => $row1['volledigenaamvak']);
    }

    $day = date('l', strtotime($datum));
    $select_klas = "SELECT id,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10 FROM klassenboek_vak where schoolid = $schoolID and klas = '$klas' and day = '$day' and schooljaar = '" . $_SESSION['SchoolJaar'] . "'";
    $resultado = mysqli_query($mysqli, $select_klas);
    if ($resultado->num_rows > 0) {
      while ($row = mysqli_fetch_assoc($resultado)) {
        $id = $row['id'];
        $p1 = $row['p1'];
        $p2 = $row['p2'];
        $p3 = $row['p3'];
        $p4 = $row['p4'];
        $p5 = $row['p5'];
        $p6 = $row['p6'];
        $p7 = $row['p7'];
        $p8 = $row['p8'];
        $p9 = $row['p9'];
        $p10 = $row['p10'];
      }
    }

    $final = "";

    for ($i = 1; $i <= 10; $i++) {
      $html = "html" . $i;
      if ($type == 0) {
        $$html .= '<th>';
      } else {
        $$html .= '<th style="max-width: 20px;">';
      }
      $x = 'p' . $i;
      $conf = "";
      foreach ($vaks as $vak) {
        if ($vak["id"] == $$x) {
          $conf = $vak["vak"];
        }
      }
      $$html .= ($conf == "") ? ($i == 10 ? 'Dag' : '') : $conf;
      $$html .= '</th>';
      $final .= $$html;
    }

    return $final;
  }

  function klasenboek_vak_array($schoolID, $klas, $datum)
  {
    $p1 = '';
    $p2 = '';
    $p3 = '';
    $p4 = '';
    $p5 = '';
    $p6 = '';
    $p7 = '';
    $p8 = '';
    $p9 = '';
    $p10 = '';
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $get_vak = "SELECT distinct v.ID, v.volledigenaamvak from le_vakken v where v.SchoolID = $schoolID and v.Klas = '$klas' order by v.volledigenaamvak asc;";
    $result = mysqli_query($mysqli, $get_vak);
    while ($row1 = mysqli_fetch_assoc($result)) {
      $vaks[] = array("id" => $row1['ID'], "vak" => $row1['volledigenaamvak']);
    }

    $day = date('l', strtotime($datum));
    $select_klas = "SELECT id,p1,p2,p3,p4,p5,p6,p7,p8,p9 FROM klassenboek_vak where schoolid = $schoolID and klas = '$klas' and day = '$day' and schooljaar = '" . $_SESSION['SchoolJaar'] . "'";
    $resultado = mysqli_query($mysqli, $select_klas);
    if ($resultado->num_rows > 0) {
      while ($row = mysqli_fetch_assoc($resultado)) {
        $id = $row['id'];
        $p1 = $row['p1'];
        $p2 = $row['p2'];
        $p3 = $row['p3'];
        $p4 = $row['p4'];
        $p5 = $row['p5'];
        $p6 = $row['p6'];
        $p7 = $row['p7'];
        $p8 = $row['p8'];
        $p9 = $row['p9'];
      }
    }

    $conf = array();

    for ($i = 1; $i <= 9; $i++) {
      $x = 'p' . $i;
      $conf[$i] = 'disabled';
      foreach ($vaks as $vak) {
        if ($vak["id"] == $$x) {
          $conf[$i] = '';
        }
      }
    }

    return $conf;
  }


  function listverzuim_hsOLD($schoolid, $klas, $datum, $period, $vak)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    $l1 = array();
    $l2 =  array();
    $l3 =  array();
    $l4 =  array();
    $l5 =  array();
    $l6 =  array();
    $l7 =  array();
    $l8 =  array();
    $l9 =  array();

    mysqli_report(MYSQLI_REPORT_STRICT);

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if ($utils->converttomysqldate($datum) != false) {
      $datum = $utils->converttomysqldate($datum);
    } else {
      $datum = null;
    }

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    // End change settings (laldana@caribedev)

    $sql_query = "select s.id, v.id, s.class, s.lastname, s.firstname,s.sex,v.telaat,v.absentie,v.toetsinhalen, v.uitsturen, v.huiswerk, v.lp, v.opmerking
    from students s inner join le_verzuim v on s.id = v.studentid
    where s.class = ?  and s.schoolid = ? and v.schooljaar = ? and v.datum = ? and lesuur = ? and vak= ? order by ";
    $sql_order = " lastname, firstname ";
    if ($s->_setting_mj) {
      $sql_query .= " sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }

    // print($sql_query);
    // End change settings (laldana@caribedev)
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        //if($select->bind_param("sissisis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$klas_in,$schoolid,$_datum_in)) {
        if ($select->bind_param("sissis", $klas, $schoolid, $_SESSION['SchoolJaar'], $datum, $period, $vak)) {
          if ($select->execute()) {

            $result = 1;

            $select->bind_result($studentid, $verzuimid, $_klas, $lastname, $firstname, $sex, $telaat, $absent, $toetsinhalen, $uitsturen, $huiswerk, $lp, $opmerking);
            $select->store_result();

            if ($select->num_rows > 0) {
              $htmlcontrol .= "<div class='col-md-12'>";
              $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-bordered table-colored table-houding\" data-table=\"no\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID</th><th class=\"btn-m-w\">Naam</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>" . ($_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen') . "</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th><th>L1</th><th>L2</th><th>L3</th><th>L4</th><th>L5</th><th>L6</th><th>L7</th><th>L8</th><th>L9</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";
              $x = 1;
              $xx = 1;
              while ($select->fetch()) {
                $l1 = $this->get_lessuur_value($studentid, $klas, $datum, 1);
                $l2 = $this->get_lessuur_value($studentid, $klas, $datum, 2);
                $l3 = $this->get_lessuur_value($studentid, $klas, $datum, 3);
                $l4 = $this->get_lessuur_value($studentid, $klas, $datum, 4);
                $l5 = $this->get_lessuur_value($studentid, $klas, $datum, 5);
                $l6 = $this->get_lessuur_value($studentid, $klas, $datum, 6);
                $l7 = $this->get_lessuur_value($studentid, $klas, $datum, 7);
                $l8 = $this->get_lessuur_value($studentid, $klas, $datum, 8);
                $l9 = $this->get_lessuur_value($studentid, $klas, $datum, 9);

                $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$_klas\"  data-datum=\"$datum\"><td>$x</td><td>" . $lastname  . ', ' . $firstname . "</td>";
                $htmlcontrol .= "<td valign=\"middle\"><input verzuim=\"$verzuimid\"  name =\"telaat\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control telaat-field\"" . ($telaat == 1 ? "checked=\"checked\"" : "") . "></td>";
                $htmlcontrol .= "<td><input verzuim=\"$verzuimid\"  name =\"absentie\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control absentie-field\"" . ($absent == 1 ? "checked=\"checked\"" : "") . " ></td>";
                $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"toetsinhalen\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control toetsinhalen-field\"" . ($toetsinhalen == 1 ? "checked=\"checked\"" : "") . " ></td>";
                $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" ame =\"uitsturen\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control uitsturen-field\"" . ($uitsturen == 1 ? "checked=\"checked\"" : "") . "></td>";
                /* remember to upercase */
                $htmlcontrol .= "<td><select verzuim=\"$verzuimid\" name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"" : "") . ">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"" : "") . ">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"" : "") . ">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"" : "") . ">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"" : "") . ">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"" : "") . ">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"" : "") . "D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"" : "") . ">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"" : "") . ">P2</option></select></td>";
                $htmlcontrol .= "<td><input verzuim=\"$verzuimid\" name =\"huiswerk\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control geenhuiswerk-field\"" . ($huiswerk == 1 ? "checked" : "") . " ></td>";
                //  $htmlcontrol .="<td><textarea name =\"lbl_opmerking\" id=\"lbl_opmerking\" class=\"editable\" rows=\"4 \" cols=\"65 \" type = 'text' value=\"".$opmerking."\"></textarea></td>";
                $htmlcontrol .= "<td width =\"300px\"><input verzuim=\"$verzuimid\"  name =\"opmerking\" id=\"lblopmerking \" data-toggle=\"tooltip \" class = 'editable lblopmerking form-control opmerking-input' type = 'text' value=\"" . $opmerking . "\" title=\"" . $opmerking . "\"  style = 'display:block'/></td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l1[2] . " | Opmerking: " . $l1[1] . "\"  style=\"width: 60px\">" . $l1[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l2[2] . " | Opmerking: " . $l2[1] . "\"  style=\"width: 60px\">" . $l2[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l3[2] . " | Opmerking: " . $l3[1] . "\"  style=\"width: 60px\">" . $l3[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l4[2] . " | Opmerking: " . $l4[1] . "\"  style=\"width: 60px\">" . $l4[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l5[2] . " | Opmerking: " . $l5[1] . "\"  style=\"width: 60px\">" . $l5[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l6[2] . " | Opmerking: " . $l6[1] . "\"  style=\"width: 60px\">" . $l6[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l7[2] . " | Opmerking: " . $l7[1] . "\"  style=\"width: 60px\">" . $l7[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l8[2] . " | Opmerking: " . $l8[1] . "\"  style=\"width: 60px\">" . $l8[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Vak: " . $l9[2] . " | Opmerking: " . $l9[1] . "\"  style=\"width: 60px\">" . $l9[0] . "</td>";

                $x++;
                $xx++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
              $htmlcontrol .= "</div>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */

            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */

        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {

      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function create_le_verzuim_student($schoolID, $schooljaar, $klas, $period, $datum, $vak)
  {
    $result = 99;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    require_once("spn_utils.php");
    $utils = new spn_utils();
    try {
      $datum = $utils->converttomysqldate($datum);
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($stmt = $mysqli->prepare("CALL sp_create_le_verzuim_students (?,?,?,?,?,?)")) {
        if ($stmt->bind_param("ississ", $schoolID, $schooljaar, $klas, $period, $datum, $vak)) {
          if ($stmt->execute()) {
            $stmt->close();
            $mysqli->close();
            $result = 100;
          } else {
            $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            echo "# " . $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          echo "# " . $this->mysqlierrornumber = $mysqli->errno;
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
  function create_le_verzuim_student_new($schoolID, $schooljaar, $klas, $datum, $vak, $studentid, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10)
  {
    $result = 99;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    require_once("spn_utils.php");
    $utils = new spn_utils();
    try {
      //$datum = $utils->converttomysqldate($datum);
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($stmt = $mysqli->prepare("CALL sp_create_le_verzuim_students_new (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
        if ($stmt->bind_param("isssssssssssssss", $schoolID, $schooljaar, $klas, $datum, $vak, $studentid, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10)) {
          if ($stmt->execute()) {
            $stmt->close();
            $mysqli->close();
            $result = 100;
            echo " creando complerte";
            echo $p1 . " " . $p2 . " " . $p3 . " " . $p4 . " " . $p5 . " " . $p6 . " " . $p7 . " " . $p8 . " " . $p9 . " " . $p10;
          } else {
            $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            echo "# " . $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          echo "# " . $this->mysqlierrornumber = $mysqli->errno;
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
    echo "    SP nuevo   ";
    return $result;
  }

  /* function list_rapport_verzuim_hs($schoolID, $schooljaar,$klas, $datum, $vak, $studentid, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10) {
    if($p10 == "L")
    require_once("DBCreds.php");
    $verzuim_hs = "INSERT INTO le_verzuim_hs (schooljaar,klas,datum,vak,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10) values ()";
    $DBCreds = new DBCreds();
    $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $mysqli

  } */

  function create_le_verzuim_student_all($schoolID, $schooljaar, $klas, $datum)
  {
    $result = 99;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    require_once("spn_utils.php");
    $utils = new spn_utils();
    try {
      //$datum = $utils->converttomysqldate($datum);
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($stmt = $mysqli->prepare("CALL sp_create_le_verzuim_students_all (?,?,?,?)")) {
        if ($stmt->bind_param("isss", $schoolID, $schooljaar, $klas, $datum)) {
          if ($stmt->execute()) {
            $stmt->close();
            $mysqli->close();
            $result = 100;
          } else {
            $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            echo "# " . $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          echo "# " . $this->mysqlierrornumber = $mysqli->errno;
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
    return "OK";
  }

  function _getverzuimcount_hs($schoolid, $studentid_in, $klas_in, $datum_in, $period)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $verzuimid = null;
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "select v.id from le_verzuim v where v.studentid = ? and v.datum = ? and v.lesuur=? and v.klas =? and v.schooljaar = ?";

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      // print(" studentID: ".$studentid_in ." - datum: ". $datum_in." - period: ". $period." - klas: ". $klas_in." - schooljaar: ". $_SESSION['SchoolJaar']);
      // PRINT($sql_query);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("isiss", $studentid_in, $datum_in, $period, $klas_in, $_SESSION['SchoolJaar'])) {
          if ($select->execute()) {

            $result = 1;

            $select->store_result();
            $select->bind_result($vid);
            while ($select->fetch()) {
              $verzuimid = $vid;
            }

            $returnvalue = $verzuimid;
          } else {
            /* error executing query */

            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */

          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */

        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }
    } catch (Exception $e) {

      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function get_lessuur_value($studentid, $klas, $datum, $period)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = array();

    $array_filled = false;

    mysqli_report(MYSQLI_REPORT_STRICT);

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if ($utils->converttomysqldate($datum) != false) {
      $datum = $utils->converttomysqldate($datum);
    } else {
      $datum = null;
    }

    $variale_to_find = 'l' . strval($period);

    // End change settings (laldana@caribedev)

    $sql_query = "SELECT $variale_to_find, opmerking, volledigenaamvak FROM verzuim_extended where studentid = $studentid  and klas = '$klas'  and datum= '$datum' and lesuur = $period";
    // $sql_query = "SELECT $variale_to_find, ve.opmerking, v.volledigenaamvak FROM verzuim_extended ve inner join le_vakken v on ve.vak = v.id where ve.studentid = $studentid  and ve.klas = '$klas'  and ve.datum= '$datum' and ve.lesuur = $period";
    // print($sql_query);


    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        //if($select->bind_param("sissisis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$klas_in,$schoolid,$_datum_in)) {

        if ($select->execute()) {

          $result = 1;

          $select->bind_result($value_lesuur, $opmerking, $volledigenaamvak);
          $select->store_result();

          if ($select->num_rows > 0) {
            $c = 0;
            while ($select->fetch()) {

              if ($value_lesuur == 'L' || $value_lesuur == 'A' && $array_filled == null) {
                array_push($htmlcontrol, $value_lesuur, $opmerking, $volledigenaamvak);
                $array_filled = true;
              }
            }
            if (count($htmlcontrol) == 0) {
              array_push($htmlcontrol, '', '', '');
            }
          } else {
            array_push($htmlcontrol, '', '', '');
          }
        } else {
          /* error executing query */

          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error executing query" . "<br />";
            print "error" . $mysqli->error;
          }
        }
      } else {
        /* error preparing query */

        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {

      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }
}
