<?php



require_once("spn_setting_mobile.php");



class spn_verzuim_mobile

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

  public $sp_get_verzuim_by_student_parent = "sp_get_verzuim_by_student_parent";

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

              $htmlcontrol .= "<div id=\"dataRequest-student_detail\" class=\"table-responsive\">";



              $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-striped\" data-table=\"no\">";

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

                $htmlcontrol .= "<td><input name =\"telaat\" type=\"checkbox\" class=\"form-control telaat-field\"" . ($telaat == 1 ? "checked=\"checked\"" : "") . "></td>";

                $htmlcontrol .= "<td><input name =\"absentie\" type=\"checkbox\" class=\"form-control absentie-field\"" . ($absent == 1 ? "checked=\"checked\"" : "") . " ></td>";

                $htmlcontrol .= "<td><input name =\"toetsinhalen\"  type=\"checkbox\" class=\"form-control toetsinhalen-field\"" . ($toetsinhalen == 1 ? "checked=\"checked\"" : "") . " ></td>";

                $htmlcontrol .= "<td><input name =\"uitsturen\" type=\"checkbox\" class=\"form-control uitsturen-field\"" . ($uitsturen == 1 ? "checked=\"checked\"" : "") . "></td>";

                /* remember to upercase */

                $htmlcontrol .= "<td><select name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"" : "") . ">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"" : "") . ">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"" : "") . ">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"" : "") . ">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"" : "") . ">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"" : "") . ">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"" : "") . "D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"" : "") . ">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"" : "") . ">P2</option></select></td>";

                $htmlcontrol .= "<td><input name =\"huiswerk\"  type=\"checkbox\" class=\"form-control geenhuiswerk-field\"" . ($huiswerk == 1 ? "checked" : "") . " ></td>";

                $htmlcontrol .= "<td><span name =\"opmerking\" id=\"lblopmerking \" class=\"editable lblopmerking\">" . $opmerking . "</span></td>";



                /* increment variable with one */

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

  function listverzuim_mobile($schoolid, $klas_in, $datum_in, $sort_order)

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



    $s = new spn_setting_mobile();

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

            // $UserGUID = $_SESSION['UserGUID'];

            // $spn_audit->create_audit($UserGUID, 'verzuim','list verzuim',appconfig::GetDummy());



            $result = 1;



            $select->bind_result($studentid, $verzuimid, $klas, $firstname, $lastname, $sex, $telaat, $absent, $toetsinhalen, $uitsturen, $huiswerk, $lp, $opmerking);

            $select->store_result();



            if ($select->num_rows > 0) {



              $htmlcontrol .= "<div class=\"col-md-10 table-responsive\">";

              $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-striped\" data-table=\"no\">";

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

                $htmlcontrol .= "<td><input name =\"telaat\" disabled=\"disabled\" type=\"checkbox\" class=\"form-control telaat-field\"" . ($telaat == 1 ? "checked=\"checked\"" : "") . "></td>";

                $htmlcontrol .= "<td><input name =\"absentie\" disabled=\"disabled\" type=\"checkbox\" class=\"form-control absentie-field\"" . ($absent == 1 ? "checked=\"checked\"" : "") . " ></td>";

                $htmlcontrol .= "<td><input name =\"toetsinhalen\" disabled=\"disabled\"  type=\"checkbox\" class=\"form-control toetsinhalen-field\"" . ($toetsinhalen == 1 ? "checked=\"checked\"" : "") . " ></td>";

                $htmlcontrol .= "<td><input name =\"uitsturen\" disabled=\"disabled\" type=\"checkbox\" class=\"form-control uitsturen-field\"" . ($uitsturen == 1 ? "checked=\"checked\"" : "") . "></td>";

                /* remember to upercase */

                $htmlcontrol .= "<td><select name =\"lp\"  disabled=\"disabled\" class=\"form-control lp-field c\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"" : "") . ">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"" : "") . ">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"" : "") . ">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"" : "") . ">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"" : "") . ">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"" : "") . ">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"" : "") . "D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"" : "") . ">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"" : "") . ">P2</option></select></td>";

                $htmlcontrol .= "<td><input name =\"huiswerk\" disabled=\"disabled\"  type=\"checkbox\" class=\"form-control geenhuiswerk-field\"" . ($huiswerk == 1 ? "checked" : "") . " ></td>";

                $htmlcontrol .= "<td><span name =\"opmerking\" id=\"lblopmerking \" disabled=\"disabled\" class=\"editable lblopmerking\">" . $opmerking . "</span></td>";



                /* increment variable with one */

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







  function saveverzuim($schoolid_in, $studentid_in, $telaat_in, $absentie_in, $toetsinhalen_in, $uitsturen_in, $huiswerk_in, $lp_in, $opmerking_in, $klas_in, $datum_in)

  {

    $telaat = 0;

    $absentie = 0;

    $toetsinhalen = 0;

    $uitsturen = 0;

    $huiswerk = 0;



    print 'save verzuim :' . $schoolid_in . '-' .  $studentid_in . '-' . $telaat_in . '-' . $absentie_in . '-' . $toetsinhalen_in . '-' . $uitsturen_in . '-' . $huiswerk_in . '-' . $lp_in . '-' . $opmerking_in . '-' . $klas_in . '-' . $datum_in;



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

    if ($verzuim_count == 1) {



      return $this->editverzuim($this->Xverzuimid, $studentid_in, "", $klas_in, $_datum_in, $opmerking_in, "", $telaat, $absentie, $lp_in, $toetsinhalen, $uitsturen, $huiswerk);
    } else {



      return $this->createverzuim($studentid_in, "", "", $klas_in, $_datum_in, $opmerking_in, "", $telaat, $absentie, $lp_in, $toetsinhalen, $uitsturen, $huiswerk);
    }
  }



  function _getverzuimcount($schoolid, $studentid_in, $klas_in, $datum_in)

  {

    $returnvalue = 0;

    $user_permission = "";

    $sql_query = "";

    $htmlcontrol = "";



    mysqli_report(MYSQLI_REPORT_STRICT);



    $sql_query = "select v.id from le_verzuim v where v.studentid = ? and v.datum = ?";



    try {

      require_once("DBCreds.php");

      $DBCreds = new DBCreds();

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      $mysqli->set_charset('utf8');

      if ($select = $mysqli->prepare($sql_query)) {

        if ($select->bind_param("ss", $studentid_in, $datum_in)) {

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



  function list_verzuim_by_student($schooljaar, $schoolID, $studentid, $dummy)
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

        $sp_get_verzuim_by_student_parent = "sp_get_verzuim_by_student_parent";

        $mysqli->set_charset('utf8');

        if ($stmt = $mysqli->prepare("CALL " . $this->$sp_get_verzuim_by_student_parent . "(?,?)")) {

          if ($stmt->bind_param("ss", $schooljaar, $studentid)) {



            if ($stmt->execute()) {



              $spn_audit = new spn_audit();

              $UserGUID = $_SESSION['UserGUID'];

              $spn_audit->create_audit($UserGUID, 'verzuim', 'list verzuim', appconfig::GetDummy());



              $result = 1;

              $stmt->bind_result($schooljaar, $id_verzuim, $datum, $telaat, $absentie, $toetsinhalen, $uitsturen, $lp, $huiswerk, $opmerking);

              $stmt->store_result();



              if ($stmt->num_rows > 0) {



                $htmlcontrol .= "<div class=\"table-responsive\">";

                $htmlcontrol .= "<table id=\"tbl_verzuim_by_idstudent\" class=\"table table-bordered table-colored\" data-table=\"no\">";

                $htmlcontrol .= "<thead>";

                /*                 $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID Verzuim</th><th>Schooljaar</th><th>Datum</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>" . ($_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen') . "</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th></tr>";*/

                $htmlcontrol .= "<tr class=\"text-align-center\"><th>Datum</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>" . ($_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen') . "</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th></tr>";

                $htmlcontrol .= "</thead>";

                $htmlcontrol .= "<tbody>";

                while ($stmt->fetch()) {

                  $htmlcontrol .= "<tr>";

                  /* $htmlcontrol .= "<td data-titulo='ID:' align=\"center\">" . htmlentities($id_verzuim) . "</td>";

                  $htmlcontrol .= "<td data-titulo='Schooljaar:'>" . htmlentities($schooljaar) . "</td>"; */

                  $htmlcontrol .= "<td data-titulo='Datum:'>" . htmlentities($datum) . "</td>";

                  $htmlcontrol .= "<td data-titulo='Te Laar:'><i name =\"telaat\"" . ($telaat == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";

                  $htmlcontrol .= "<td data-titulo='Absentie:'><i name =\"absentie\"" . ($absentie == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";

                  // $htmlcontrol .= "<td><input name =\"absentie\" type=\"checkbox\" class=\"form-control absentie-field\"". ($absentie == 1 ? "checked=\"checked\"": "") ." ></td>";

                  $htmlcontrol .= "<td data-titulo='Toetsinhalen:'><i name =\"toetsinhalen\"" . ($toetsinhalen == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";

                  $htmlcontrol .= "<td data-titulo='Uitsturen:'><i name =\"uitsturen\"" . ($uitsturen == 1 ?  "class=\"fa fa-check\"" : "") . "></td>";

                  /* remember to upercase */

                  $htmlcontrol .= "<td data-titulo='Lp:'><select disabled=\"disabled\" name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"" : "") . ">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"" : "") . ">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"" : "") . ">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"" : "") . ">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"" : "") . ">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"" : "") . ">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"" : "") . "D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"" : "") . ">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"" : "") . ">P2</option></select></td>";

                  $htmlcontrol .= "<td data-titulo='Huiswerk:'><i name =\"huiswerk\"" . ($huiswerk == 1 ? "class=\"fa fa-check\"" : "") . " ></td>";

                  $htmlcontrol .= "<td data-titulo='Opmerking:'><span name =\"opmerking\" id=\"lblopmerking \" class=\"editable lblopmerking\">" . $opmerking . "</span></td>";

                  $htmlcontrol .= "</tr>";
                }

                $htmlcontrol .= "</tbody>";

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


  function list_verzuim_by_student_hs($schooljaar, $schoolID, $studentid, $dummy)
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

        if ($stmt = $mysqli->prepare("CALL " . "sp_get_verzuim_by_student_parent_hs" . "(?,?)")) {
          if ($stmt->bind_param("ss", $schooljaar, $studentid)) {
            if ($stmt->execute()) {
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'verzuim', 'list verzuim', appconfig::GetDummy());


              $result = 1;

              $stmt->bind_result($datum, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $dag);
              $stmt->store_result();


              if ($stmt->num_rows > 0) {

                $htmlcontrol .= "<br/><div class=\"table-responsive\">";

                $htmlcontrol .= "<table id=\"tbl_verzuim_by_idstudent\" class=\"table table-bordered table-colored\" data-table=\"no\">";

                $htmlcontrol .= "<thead>";

                $htmlcontrol .= "<tr class=\"text-align-center\"><th>Datum</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>Dag</th></tr>";

                $htmlcontrol .= "</thead>";

                $htmlcontrol .= "<tbody>";

                while ($stmt->fetch()) {
                  $htmlcontrol .= "<tr><td data-titulo='Datum:'>$datum</td><td data-titulo='1:'>$p1</td><td data-titulo='2:'>$p2</td><td data-titulo='3:'>$p3</td><td data-titulo='4:'>$p4</td><td data-titulo='5:'>$p5</td><td data-titulo='6:'>$p6</td><td data-titulo='7:'>$p7</td><td data-titulo='8:'>$p8</td><td data-titulo='9:'>$p9</td><td data-titulo='Dag:'>$dag</td>";
                  $htmlcontrol .= "<tr>";
                }

                $htmlcontrol .= "</tbody>";
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
}
