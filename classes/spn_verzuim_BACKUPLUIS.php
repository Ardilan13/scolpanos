<?php

require_once ("spn_setting.php");

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
    if($this->showphperrors)
    {
      error_reporting(-1);
    }
    else
    {
      error_reporting(0);
    }
  }

  function createverzuim($studentid,$schooljaar,$rapnummer,$klas,$datum,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk)
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

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if($stmt=$mysqli->prepare("insert into " . $this->tablename_verzuim . "(created,studentid,schooljaar,klas,datum,opmerking,user,telaat,absentie,LP,toetsinhalen,uitsturen,huiswerk) values (?,?,?,?,?,?,?,?,?,?,?,?,?)"))
      {
        if($stmt->bind_param("sisssssiiiiii",$_DateTime,$studentid,$schooljaar,$klas,$datum,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk))
        {
          if($stmt->execute())
          {

            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'verzuim','create verzuim',appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */

            $result = 1;
            $stmt->close();
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
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }


    return $result;


  }

  function editverzuim($verzuimid,$studentid,$schooljaar,$klas,$datum,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 0;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');

      if($stmt=$mysqli->prepare("update " . $this->tablename_verzuim . " set lastchanged = ?, studentid = ?, schooljaar = ?,  klas = ?, datum = ?, opmerking = ?, user =?, telaat= ?, absentie = ?, LP = ?, toetsinhalen = ?, uitsturen = ?, huiswerk = ? where id = ?"))
      {
        if($stmt->bind_param("sisssssiiiiiii",$_DateTime,$studentid,$schooljaar,$klas,$datum,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk,$verzuimid))
        {
          if($stmt->execute())
          {

            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'verzuim','update verzuim',appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            if($mysqli->affected_rows >= 1)
            {
              $result = 1;
            }
            $stmt->close();
            $mysqli->close();
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
          }

        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
        }

      }
      else
      {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
      }


    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }


    return $result;


  }

  function listverzuim($schoolid,$klas_in,$datum_in,$sort_order)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if($utils->converttomysqldate($datum_in) != false)
    {
      $_datum_in = $utils->converttomysqldate($datum_in);
    }
    else
    {
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
    if ($s->_setting_mj)
    {
      $sql_query .= " sex ". $s->_setting_sort . ", " . $sql_order;
    }
    else
    {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if($select=$mysqli->prepare($sql_query)){
        //if($select->bind_param("sissisis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$klas_in,$schoolid,$_datum_in)) {
        if($select->bind_param("sissis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$_datum_in)) {
          if($select->execute())
          {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'verzuim','list verzuim',appconfig::GetDummy());
            $this->error = false;
            $result=1;

            $select->bind_result($studentid,$verzuimid,$klas,$firstname,$lastname,$sex,$telaat,$absent,$toetsinhalen,$uitsturen,$huiswerk,$lp,$opmerking);
            $select->store_result();

            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-bordered table-colored table-houding\" data-table=\"no\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID</th><th class=\"btn-m-w\">Naam</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>".($_SESSION['SchoolType'] == 1 ? 'Naar huis': 'Spijbelen')."</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";

              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;


              $xx = 1;

              //print $select->num_rows;

              while($select->fetch())
              {


                $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$klas_in\"  data-datum=\"$datum_in\"><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";
                $htmlcontrol .= "<td valign=\"middle\"><input name =\"telaat\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control telaat-field\"" . ($telaat == 1 ? "checked=\"checked\"": "") . "></td>";
                $htmlcontrol .= "<td><input name =\"absentie\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control absentie-field\"". ($absent == 1 ? "checked=\"checked\"": "") ." ></td>";
                $htmlcontrol .= "<td><input name =\"toetsinhalen\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control toetsinhalen-field\"". ($toetsinhalen == 1 ? "checked=\"checked\"": "") ." ></td>";
                $htmlcontrol .= "<td><input name =\"uitsturen\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control uitsturen-field\"". ($uitsturen == 1 ? "checked=\"checked\"": "") ."></td>";
                /* remember to upercase */
                $htmlcontrol .= "<td><select name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"": "") .">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"": "") .">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"": "") .">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"": "") .">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"": "") .">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"": "") .">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"": "") ."D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"": "") .">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"": "") .">P2</option></select></td>";
                $htmlcontrol .="<td><input name =\"huiswerk\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control geenhuiswerk-field\"". ($huiswerk == 1 ? "checked": "") ." ></td>";
                //  $htmlcontrol .="<td><textarea name =\"lbl_opmerking\" id=\"lbl_opmerking\" class=\"editable\" rows=\"4 \" cols=\"65 \" type = 'text' value=\"".$opmerking."\"></textarea></td>";
                $htmlcontrol .="<td width =\"300px\"><input name =\"opmerking\" id=\"lblopmerking \" data-toggle=\"tooltip \" class = 'editable lblopmerking form-control opmerking-input' type = 'text' value=\"".$opmerking."\" title=\"".$opmerking."\"  style = 'display:block'/></td>";

                $x++;

                $xx++;

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

  function listverzuimprint($schoolid,$klas_in,$datum_in,$sort_order)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if($utils->converttomysqldate($datum_in) != false)
    {
      $_datum_in = $utils->converttomysqldate($datum_in);
    }
    else
    {
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
    if ($s->_setting_mj)
    {
      $sql_query .= " sex ". $s->_setting_sort . ", " . $sql_order;
    }
    else
    {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if($select=$mysqli->prepare($sql_query)){
        //if($select->bind_param("sissisis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$klas_in,$schoolid,$_datum_in)) {
        if($select->bind_param("sissis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$_datum_in)) {
          if($select->execute())
          {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'verzuim','list verzuim',appconfig::GetDummy());
            $this->error = false;
            $result=1;

            $select->bind_result($studentid,$verzuimid,$klas,$firstname,$lastname,$sex,$telaat,$absent,$toetsinhalen,$uitsturen,$huiswerk,$lp,$opmerking);
            $select->store_result();

            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-bordered table-colored table-houding\" data-table=\"no\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID</th><th class=\"btn-m-w\">Naam</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>".($_SESSION['SchoolType'] == 1 ? 'Naar huis': 'Spijbelen')."</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";

              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;


              $xx = 1;

              //print $select->num_rows;

              while($select->fetch())
              {


                $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$klas_in\"  data-datum=\"$datum_in\"><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";
                $htmlcontrol .= "<td><input name =\"telaat\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control telaat-field\"" . ($telaat == 1 ? "checked=\"checked\"": "") . "></td>";
                $htmlcontrol .= "<td><input name =\"absentie\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control absentie-field\"". ($absent == 1 ? "checked=\"checked\"": "") ." ></td>";
                $htmlcontrol .= "<td><input name =\"toetsinhalen\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'class=\"form-control toetsinhalen-field\"". ($toetsinhalen == 1 ? "checked=\"checked\"": "") ." ></td>";
                $htmlcontrol .= "<td><input name =\"uitsturen\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control uitsturen-field\"". ($uitsturen == 1 ? "checked=\"checked\"": "") ."></td>";
                /* remember to upercase */
                $htmlcontrol .= "<td><select name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"": "") .">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"": "") .">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"": "") .">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"": "") .">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"": "") .">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"": "") .">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"": "") ."D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"": "") .">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"": "") .">P2</option></select></td>";
                $htmlcontrol .="<td><input name =\"huiswerk\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control geenhuiswerk-field\"". ($huiswerk == 1 ? "checked": "") ." ></td>";
                //  $htmlcontrol .="<td><textarea name =\"lbl_opmerking\" id=\"lbl_opmerking\" class=\"editable\" rows=\"4 \" cols=\"65 \" type = 'text' value=\"".$opmerking."\"></textarea></td>";
                $htmlcontrol .="<td width =\"800px\"><span name =\"opmerking\" id=\"lblopmerking \" class=\"editable lblopmerking\">".$opmerking."</span></td>";

                //$htmlcontrol .="<input name =\"opmerking\" id=\"lblopmerking \" class = 'editable lblopmerking form-control opmerking-input' type = 'text' value=\"".$opmerking."\"  style = 'display:block'/>"
                //$htmlcontrol .="<td><span name =\"opmerking\" id=\"lblopmerking \" class=\"editable lblopmerking\">".$opmerking."</span></td>";

                /* increment variable with one */
                $x++;

                $xx++;

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

  function saveverzuim($schooljaar, $schoolid_in,$studentid_in,$telaat_in,$absentie_in,$toetsinhalen_in,$uitsturen_in,$huiswerk_in,$lp_in,$opmerking_in,$klas_in,$datum_in)
  {
    $telaat = 0;
    $absentie = 0;
    $toetsinhalen = 0;
    $uitsturen = 0;
    $huiswerk = 0;

    print 'save verzuim :' .$schoolid_in .'-'.  $studentid_in .'-'. $telaat_in.'-'.$absentie_in.'-'.$toetsinhalen_in.'-'.$uitsturen_in.'-'.$huiswerk_in.'-'.$lp_in.'-'.$opmerking_in.'-'.$klas_in.'-'.$datum_in;

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if($utils->converttomysqldate($datum_in) != false)
    {
      $_datum_in = $utils->converttomysqldate($datum_in);
    }
    else
    {
      $_datum_in = null;
    }

    if ($telaat_in === 'true'){
      $telaat = 1;
    }
    else{
      $telaat = 0;
    }

    if ($absentie_in === 'true'){
      $absentie = 1;
    }
    else{
      $absentie = 0;
    }

    if ($toetsinhalen_in === 'true'){
      $toetsinhalen = 1;
    }
    else{
      $toetsinhalen = 0;
    }

    if ($uitsturen_in === 'true'){
      $uitsturen = 1;
    }
    else{
      $uitsturen = 0;
    }

    if ($huiswerk_in === 'true'){
      $huiswerk = 1;
    }
    else{
      $huiswerk = 0;
    }

    $verzuim_count = $this->_getverzuimcount($schoolid_in,$studentid_in,$klas_in,$_datum_in);
    if($verzuim_count == 1)
    {

      return $this->editverzuim($this->Xverzuimid,$studentid_in,$schooljaar,$klas_in,$_datum_in,$opmerking_in,"",$telaat,$absentie,$lp_in,$toetsinhalen,$uitsturen,$huiswerk);
    }
    else
    {

      return $this->createverzuim($studentid_in,$schooljaar,"",$klas_in,$_datum_in,$opmerking_in,"",$telaat,$absentie,$lp_in,$toetsinhalen,$uitsturen,$huiswerk);
    }


  }

  function _getverzuimcount($schoolid,$studentid_in,$klas_in,$datum_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select v.id from le_verzuim v where v.studentid = ? and v.datum = ?";

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if($select=$mysqli->prepare($sql_query)) {
        if($select->bind_param("ss",$studentid_in, $datum_in))   {
          if($select->execute())
          {

            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'verzuim','read verzuim',appconfig::GetDummy());
            $this->error = false;
            $result=1;

            $select->store_result();
            $select->bind_result($vid);
            while($select->fetch()){
              $this->Xverzuimid = $vid;

            }

            $returnvalue = $select->num_rows;



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

  function list_verzuim_by_student($schooljaar,$studentid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol="";

    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort,$dummy);
        $sp_get_verzuim_by_student = "sp_get_verzuim_by_student";
        $mysqli->set_charset('utf8');
        if($stmt=$mysqli->prepare("CALL " . $this->$sp_get_verzuim_by_student . "(?,?)"))
        {
          if ($stmt->bind_param("si",$schooljaar, $studentid))
          {

            if($stmt->execute())
            {

              // $spn_audit = new spn_audit();
              // $UserGUID = $_SESSION['UserGUID'];
              // $spn_audit->create_audit($UserGUID, 'verzuim','list verzuim',appconfig::GetDummy());
              $this->error = false;
              $result=1;
              $stmt->bind_result($schooljaar_, $id_verzuim, $datum, $telaat, $absentie, $toetsinhalen, $uitsturen,$lp, $huiswerk, $opmerking);
              $stmt->store_result();

              if($stmt->num_rows > 0)

              {
                $htmlcontrol .= "<div class=\"table-responsive\">";

                $htmlcontrol .= "<table id=\"tbl_verzuim_by_idstudent\" class=\"table table-bordered table-colored\" data-table=\"no\">";
                $htmlcontrol .= "<thead>";
                $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID Verzuim</th><th>Schooljaar</th><th>Datum</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>".($_SESSION['SchoolType'] == 1 ? 'Naar huis': 'Spijbelen')."</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th></tr>";
                $htmlcontrol .= "</thead>";
                $htmlcontrol .= "<tbody>";
                while($stmt->fetch())
                {
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td align=\"center\">". htmlentities($id_verzuim) ."</td>";
                  $htmlcontrol .= "<td>". htmlentities($schooljaar) ."</td>";
                  $htmlcontrol .= "<td>". htmlentities($datum) ."</td>";
                  $htmlcontrol .= "<td><i name =\"telaat\"" . ($telaat == 1 ? "class=\"fa fa-check\"": "") . " ></td>";
                  $htmlcontrol .= "<td><i name =\"absentie\"". ($absentie == 1 ? "class=\"fa fa-check\"": "") ." ></td>";
                  // $htmlcontrol .= "<td><input name =\"absentie\" type=\"checkbox\" class=\"form-control absentie-field\"". ($absentie == 1 ? "checked=\"checked\"": "") ." ></td>";
                  $htmlcontrol .= "<td><i name =\"toetsinhalen\"". ($toetsinhalen == 1 ? "class=\"fa fa-check\"": "") ." ></td>";
                  $htmlcontrol .= "<td><i name =\"uitsturen\"". ($uitsturen == 1 ?  "class=\"fa fa-check\"": "") ."></td>";
                  /* remember to upercase */
                  $htmlcontrol .= "<td><select disabled=\"disabled\" name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"": "") .">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"": "") .">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"": "") .">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"": "") .">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"": "") .">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"": "") .">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"": "") ."D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"": "") .">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"": "") .">P2</option></select></td>";
                  $htmlcontrol .="<td><i name =\"huiswerk\"". ($huiswerk == 1 ? "class=\"fa fa-check\"": "") ." ></td>";
                  $htmlcontrol .="<td><span name =\"opmerking\" id=\"lblopmerking \" class=\"editable lblopmerking\">".$opmerking."</span></td>";
                }
                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";
                $htmlcontrol .= "</table>";
                $htmlcontrol .= "</div>";
              }
              else
              {
                $htmlcontrol .= "No results to show";
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
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }

      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }

  function listverzuim_tutor($schoolid,$klas_in,$datum_in,$period, $vak)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

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

    if($utils->converttomysqldate($datum_in) != false)
    {
      $_datum_in = $utils->converttomysqldate($datum_in);
    }
    else
    {
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
    if ($s->_setting_mj)
    {
      $sql_query .= " sex ". $s->_setting_sort . ", " . $sql_order;
    }
    else
    {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)
    // print($sql_query);
    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if($select=$mysqli->prepare($sql_query)){
        //if($select->bind_param("sissisis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$klas_in,$schoolid,$_datum_in)) {
        if($select->bind_param("sisissis",$klas_in, $schoolid, $datum_in, $period, $vak, $klas_in, $schoolid, $datum_in)) {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($studentid,$verzuimid,$klas,$lastname,$firstname,$sex,$telaat,$absent,$toetsinhalen,$uitsturen,$huiswerk,$lp,$opmerking);
            $select->store_result();

            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-bordered table-colored table-houding\" data-table=\"no\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID</th><th class=\"btn-m-w\">Naam</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>".($_SESSION['SchoolType'] == 1 ? 'Naar huis': 'Spijbelen')."</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th><th>L1</th><th>L2</th><th>L3</th><th>L4</th><th>L5</th><th>L6</th><th>L7</th><th>L8</th><th>L9</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";
              $x = 1;
              $xx = 1;
              while($select->fetch())
              {
                $l1 = $this->get_lessuur_value($studentid,$klas_in,$datum_in,1);
                $l2 = $this->get_lessuur_value($studentid,$klas_in,$datum_in,2);
                $l3 = $this->get_lessuur_value($studentid,$klas_in,$datum_in,3);
                $l4 = $this->get_lessuur_value($studentid,$klas_in,$datum_in,4);
                $l5 = $this->get_lessuur_value($studentid,$klas_in,$datum_in,5);
                $l6 = $this->get_lessuur_value($studentid,$klas_in,$datum_in,6);
                $l7 = $this->get_lessuur_value($studentid,$klas_in,$datum_in,7);
                $l8 = $this->get_lessuur_value($studentid,$klas_in,$datum_in,8);
                $l9 = $this->get_lessuur_value($studentid,$klas_in,$datum_in,9);

                $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$klas_in\"  data-datum=\"$datum_in\"><td>$x</td><td>" . $lastname . ', ' . $firstname . "</td>";
                $htmlcontrol .= "<td valign=\"middle\"><i name =\"telaat\"" . ($telaat == 1 ? "class=\"fa fa-check\"": "") . " ></td>";
                $htmlcontrol .= "<td><i name =\"absentie\"". ($absent == 1 ? "class=\"fa fa-check\"": "") ." ></td>";
                // $htmlcontrol .= "<td><input name =\"absentie\" type=\"checkbox\" class=\"form-control absentie-field\"". ($absentie == 1 ? "checked=\"checked\"": "") ." ></td>";
                $htmlcontrol .= "<td><i name =\"toetsinhalen\"". ($toetsinhalen == 1 ? "class=\"fa fa-check\"": "") ." ></td>";
                $htmlcontrol .= "<td><i name =\"uitsturen\"". ($uitsturen == 1 ?  "class=\"fa fa-check\"": "") ."></td>";
                /* remember to upercase */
                $htmlcontrol .= "<td><select disabled=\"disabled\" name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"": "") .">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"": "") .">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"": "") .">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"": "") .">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"": "") .">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"": "") .">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"": "") ."D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"": "") .">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"": "") .">P2</option></select></td>";
                $htmlcontrol .="<td><i name =\"huiswerk\"". ($huiswerk == 1 ? "class=\"fa fa-check\"": "") ." ></td>";
                $htmlcontrol .="<td><span name =\"opmerking\" id=\"lblopmerking \" class=\"editable lblopmerking\">".$opmerking."</span></td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l1[1]."\"  style=\"width: 60px\">" . $l1[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l2[1]."\"  style=\"width: 60px\">" . $l2[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l3[1]."\"  style=\"width: 60px\">" . $l3[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l4[1]."\"  style=\"width: 60px\">" . $l4[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l5[1]."\"  style=\"width: 60px\">" . $l5[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l6[1]."\"  style=\"width: 60px\">" . $l6[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l7[1]."\"  style=\"width: 60px\">" . $l7[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l8[1]."\"  style=\"width: 60px\">" . $l8[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l9[1]."\"  style=\"width: 60px\">" . $l9[0] . "</td>";
                $x++;
                $xx++;
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
  function createverzuim_hs($studentid,$schooljaar,$rapnummer,$klas,$datum,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk, $period)
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

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if($stmt=$mysqli->prepare("insert into " . $this->tablename_verzuim . "(created,studentid,schooljaar,klas,datum,lesuur, opmerking,user,telaat,absentie,LP,toetsinhalen,uitsturen,huiswerk) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))
      {
        if($stmt->bind_param("sisssissiiiiii",$_DateTime,$studentid,$schooljaar,$klas,$datum,$period, $opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk))
        {
          if($stmt->execute())
          {
            /*
            Need to check for errors on database side for primary key errors etc.
            */

            $result = 1;
            $stmt->close();
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
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }


    return $result;


  }

  function saveverzuim_hs($schooljaar, $schoolid_in,$studentid_in,$telaat_in,$absentie_in,$toetsinhalen_in,$uitsturen_in,$huiswerk_in,$lp_in,$opmerking_in,$klas_in,$datum_in, $period)
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

    if($utils->converttomysqldate($datum_in) != false)
    {
      $_datum_in = $utils->converttomysqldate($datum_in);
    }
    else
    {
      $_datum_in = null;
    }

    if ($telaat_in === 'true'){
      $telaat = 1;
    }
    else{
      $telaat = 0;
    }

    if ($absentie_in === 'true'){
      $absentie = 1;
    }
    else{
      $absentie = 0;
    }

    if ($toetsinhalen_in === 'true'){
      $toetsinhalen = 1;
    }
    else{
      $toetsinhalen = 0;
    }

    if ($uitsturen_in === 'true'){
      $uitsturen = 1;
    }
    else{
      $uitsturen = 0;
    }

    if ($huiswerk_in === 'true'){
      $huiswerk = 1;
    }
    else{
      $huiswerk = 0;
    }
    $_verzuim = $this->_getverzuimcount_hs($schoolid_in,$studentid_in,$klas_in,$_datum_in, $period);
    if($_verzuim >0 )
    {
      return $this->editverzuim_hs($_verzuim,$studentid_in,$schooljaar,$klas_in,$_datum_in,$opmerking_in,"SPN",$telaat,$absentie,$lp_in,$toetsinhalen,$uitsturen,$huiswerk, $period);
    }
    else
    {
      return $this->createverzuim_hs($studentid_in,$schooljaar,"",$klas_in,$_datum_in,$opmerking_in,"",$telaat,$absentie,$lp_in,$toetsinhalen,$uitsturen,$huiswerk,$period);
    }


  }

  function editverzuim_hs($_verzuimid,$studentid,$schooljaar,$klas,$datum,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk, $period)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $sql_query = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      $sql_query = "update le_verzuim set lastchanged = ?, studentid = ?, schooljaar = ?,  klas = ?, datum = ?, opmerking = ?, user =?, telaat= ?, absentie = ?, LP = ?, toetsinhalen = ?, uitsturen = ?, huiswerk = ?, lesuur= ? where id = ?";
      if($stmt=$mysqli->prepare($sql_query))
      {
        if($stmt->bind_param("sisssssiiiiiiii",$_DateTime,$studentid,$schooljaar,$klas,$datum,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk,$period, $_verzuimid))
        {
          if($stmt->execute())
          {
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            if($mysqli->affected_rows >= 1)
            {
              $result = 1;
            }
            $stmt->close();
            $mysqli->close();
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
          }

        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
        }

      }
      else
      {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
      }


    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }


    return $result;


  }

  function listverzuim_hs($schoolid,$klas,$datum,$period, $vak)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

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

    if($utils->converttomysqldate($datum) != false)
    {
      $datum = $utils->converttomysqldate($datum);
    }
    else
    {
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
    if ($s->_setting_mj)
    {
      $sql_query .= " sex ". $s->_setting_sort . ", " . $sql_order;
    }
    else
    {
      $sql_query .=  $sql_order;
    }

    // print($sql_query);
    // End change settings (laldana@caribedev)
    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if($select=$mysqli->prepare($sql_query)){
        //if($select->bind_param("sissisis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$klas_in,$schoolid,$_datum_in)) {
        if($select->bind_param("sissis",$klas, $schoolid, $_SESSION['SchoolJaar'],$datum,$period, $vak)) {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($studentid,$verzuimid,$_klas,$lastname, $firstname,$sex,$telaat,$absent,$toetsinhalen,$uitsturen,$huiswerk,$lp,$opmerking);
            $select->store_result();

            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<div class='col-md-12'>";
              $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-bordered table-colored table-houding\" data-table=\"no\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID</th><th class=\"btn-m-w\">Naam</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>".($_SESSION['SchoolType'] == 1 ? 'Naar huis': 'Spijbelen')."</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th><th>L1</th><th>L2</th><th>L3</th><th>L4</th><th>L5</th><th>L6</th><th>L7</th><th>L8</th><th>L9</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";
              $x = 1;
              $xx = 1;
              while($select->fetch())
              {
                $l1 = $this->get_lessuur_value($studentid,$klas,$datum,1);
                $l2 = $this->get_lessuur_value($studentid,$klas,$datum,2);
                $l3 = $this->get_lessuur_value($studentid,$klas,$datum,3);
                $l4 = $this->get_lessuur_value($studentid,$klas,$datum,4);
                $l5 = $this->get_lessuur_value($studentid,$klas,$datum,5);
                $l6 = $this->get_lessuur_value($studentid,$klas,$datum,6);
                $l7 = $this->get_lessuur_value($studentid,$klas,$datum,7);
                $l8 = $this->get_lessuur_value($studentid,$klas,$datum,8);
                $l9 = $this->get_lessuur_value($studentid,$klas,$datum,9);

                $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$_klas\"  data-datum=\"$datum\"><td>$x</td><td>" .$lastname  .', '. $firstname . "</td>";
                $htmlcontrol .= "<td valign=\"middle\"><input name =\"telaat\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;' class=\"form-control telaat-field\"" . ($telaat == 1 ? "checked=\"checked\"": "") . "></td>";
                $htmlcontrol .= "<td><input name =\"absentie\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control absentie-field\"". ($absent == 1 ? "checked=\"checked\"": "") ." ></td>";
                $htmlcontrol .= "<td><input name =\"toetsinhalen\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control toetsinhalen-field\"". ($toetsinhalen == 1 ? "checked=\"checked\"": "") ." ></td>";
                $htmlcontrol .= "<td><input name =\"uitsturen\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control uitsturen-field\"". ($uitsturen == 1 ? "checked=\"checked\"": "") ."></td>";
                /* remember to upercase */
                $htmlcontrol .= "<td><select name =\"lp\" class=\"form-control lp-field\"><option value=\"0\"> </option><option value=\"1\"" . ($lp === 1 ? "selected = \"selected\"": "") .">Z1</option> <option value=\"2\"" . ($lp === 2 ? "selected = \"selected\"": "") .">Z2</option><option value=\"3\"" . ($lp === 3 ? "selected = \"selected\"": "") .">V1</option><option value=\"4\"" . ($lp === 4 ? "selected = \"selected\"": "") .">V2</option><option value=\"5\"" . ($lp === 5 ? "selected = \"selected\"": "") .">V3</option><option value=\"6\"" . ($lp === 6 ? "selected = \"selected\"": "") .">V4</option><option value=\"7\">" . ($lp === 7 ? "selected = \"selected\"": "") ."D</option><option value=\"8\"" . ($lp === 8 ? "selected = \"selected\"": "") .">P1</option><option value=\"9\"" . ($lp === 9 ? "selected = \"selected\"": "") .">P2</option></select></td>";
                $htmlcontrol .="<td><input name =\"huiswerk\"  type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control geenhuiswerk-field\"". ($huiswerk == 1 ? "checked": "") ." ></td>";
                //  $htmlcontrol .="<td><textarea name =\"lbl_opmerking\" id=\"lbl_opmerking\" class=\"editable\" rows=\"4 \" cols=\"65 \" type = 'text' value=\"".$opmerking."\"></textarea></td>";
                $htmlcontrol .="<td width =\"300px\"><input name =\"opmerking\" id=\"lblopmerking \" data-toggle=\"tooltip \" class = 'editable lblopmerking form-control opmerking-input' type = 'text' value=\"".$opmerking."\" title=\"".$opmerking."\"  style = 'display:block'/></td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l1[1]."\"  style=\"width: 60px\">" . $l1[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l2[1]."\"  style=\"width: 60px\">" . $l2[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l3[1]."\"  style=\"width: 60px\">" . $l3[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l4[1]."\"  style=\"width: 60px\">" . $l4[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l5[1]."\"  style=\"width: 60px\">" . $l5[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l6[1]."\"  style=\"width: 60px\">" . $l6[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l7[1]."\"  style=\"width: 60px\">" . $l7[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l8[1]."\"  style=\"width: 60px\">" . $l8[0] . "</td>";
                $htmlcontrol .= "<td valign=\"middle\" data-toggle=\"tooltip\"  title=\"Opmerking: " .$l9[1]."\"  style=\"width: 60px\">" . $l9[0] . "</td>";

                $x++;
                $xx++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
              $htmlcontrol .= "</div>";
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

  function create_le_verzuim_student($schoolID, $schooljaar,$klas, $period, $datum, $vak)
  {
    $result = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    require_once("spn_utils.php");
    $utils = new spn_utils();
    try
    {
      $datum = $utils->converttomysqldate($datum);
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($stmt=$mysqli->prepare("CALL sp_create_le_verzuim_students (?,?,?,?,?,?)"))
      {
        if($stmt->bind_param("ississ",$schoolID, $schooljaar,$klas,$period, $datum, $vak))
        {
          if($stmt->execute())
          {
            $stmt->close();
            $mysqli->close();
          }else{
            $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            echo "# ".  $this->mysqlierrornumber = $mysqli->errno;
          }
        }else{
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          echo "# ".       $this->mysqlierrornumber = $mysqli->errno;
        }
      }else{
        $result = 0;
        echo $this->mysqlierror = $mysqli->error;
        echo "# ".   $this->mysqlierrornumber = $mysqli->errno;
      }
    }catch(Exception $e){
      $result = -2;
      //$this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }

  function _getverzuimcount_hs($schoolid,$studentid_in,$klas_in,$datum_in, $period)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";
    $verzuimid = null;
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "select v.id from le_verzuim v where v.studentid = ? and v.datum = ? and v.lesuur=? and v.klas =? and v.schooljaar = ?";

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      // print(" studentID: ".$studentid_in ." - datum: ". $datum_in." - period: ". $period." - klas: ". $klas_in." - schooljaar: ". $_SESSION['SchoolJaar']);
      // PRINT($sql_query);
      if($select=$mysqli->prepare($sql_query)) {
        if($select->bind_param("isiss",$studentid_in, $datum_in, $period, $klas_in, $_SESSION['SchoolJaar']))   {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->store_result();
            $select->bind_result($vid);
            while($select->fetch()){
              $verzuimid = $vid;
             }

            $returnvalue = $verzuimid;
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

  function get_lessuur_value($studentid,$klas,$datum,$period)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol= array();

    mysqli_report(MYSQLI_REPORT_STRICT);

    require_once("spn_utils.php");
    $utils = new spn_utils();

    $_datum_in = null;

    if($utils->converttomysqldate($datum) != false)
    {
      $datum = $utils->converttomysqldate($datum);
    }
    else
    {
      $datum = null;
    }

    $variale_to_find = 'l'.strval($period);

    // End change settings (laldana@caribedev)

    $sql_query = "SELECT $variale_to_find, opmerking FROM verzuim_extended where studentid = $studentid  and klas = '$klas'  and datum= '$datum' and lesuur = $period";
    // print($sql_query);


    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if($select=$mysqli->prepare($sql_query)){
        //if($select->bind_param("sissisis",$klas_in,$schoolid,$_datum_in,$klas_in,$schoolid,$klas_in,$schoolid,$_datum_in)) {

          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($value_lesuur, $opmerking);
            $select->store_result();

            if($select->num_rows > 0)
            {

              while($select->fetch())
              {
                array_push($htmlcontrol,$value_lesuur,$opmerking);
                // $htmlcontrol .= $value_lesuur;
              }

            }
            else
            {
                array_push($htmlcontrol,'','');
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
