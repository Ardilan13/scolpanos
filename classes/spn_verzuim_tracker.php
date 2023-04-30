<?php



class spn_verzuim_tracker

{

  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $debug = true;
  public $error = "";
  public $errormessage = "";

  function create_verzuim_tracker($group_name, $datum, $lessur, $schooljaar, $schoolid, $_UserGUID, $dummy){
    $result = 0;

    require_once("DBCreds.php");

    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;


    require_once("spn_utils.php");
    $utils = new spn_utils();
    $_datum_in = null;
    if($utils->converttomysqldate($datum) != false)
    {
      $_datum_in = $utils->converttomysqldate($datum);
    }
    else
    {
      $_datum_in = null;
    }

    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
      if ($dummy == 1)
      $result = 1;
      else
      {

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if($stmt=$mysqli->prepare("CALL sp_create_verzuim_tracker (?,?,?,?,?,?)"))
        {

          if($stmt->bind_param("ssisis",$group_name, $_datum_in, $lessur, $schooljaar, $schoolid, $_UserGUID))
          {
            if($stmt->execute())
            {
              $result = 1;
              $stmt->close();
              $mysqli->close();
            }
            else
            {
              $result = 0;
              echo $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }

          }
          else
          {
            $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }

        }
        else
        {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }

    }
    catch(Exception $e)
    {
      $result = -2;
      echo $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }

  function check_verzuim_tracker_exist_by_docent( $_group, $_date, $_lesuur , $_schooljaar,$_schoolid , $_userGUID,  $dummy){

    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $dummy = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      require_once("spn_utils.php");
      $utils = new spn_utils();
      $_datum_in = null;
      if($utils->converttomysqldate($_date) != false)
      {
        $_datum_in = $utils->converttomysqldate($_date);
      }
      else
      {
        $_datum_in = null;
      }


      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select = $mysqli->prepare("CALL sp_read_verzuim_tracker_by_docent (?,?,?,?,?,?)"))
      {
        if($select->bind_param("ssisis",$_group, $_datum_in, $_lesuur , $_schooljaar,$_schoolid , $_userGUID ))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($id,$userGUID,$datum,$group_name,$lesuur,$schoolid,$schooljaar);

            $select->store_result();

            if($select->num_rows > 0 )
            {
              while($select->fetch())
              {
                $htmlcontrol = 1;
              }
            }

            else
            {
              $htmlcontrol .= "0";
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
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      else
      {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result=0;

        print "error del sql: ". $this->errormessage;

        if($this->debug)
        {
          print "error preparing query";
        }
      }
      // Cierre del prepare
      $returnvalue = $htmlcontrol;
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

  function list_verzuim_tracker_by_date($datum, $_schooljaar,$_schoolid , $dummy){
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $dummy = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($select = $mysqli->prepare("CALL sp_read_verzuim_tracker_by_date (?,?,?)"))
      {
        if($select->bind_param("ssi",$datum,$_schooljaar, $_schoolid))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;
            $select->bind_result(	$id, $userGUID, $firstname, $lastname,$initials, $datum, $group_name, $lesuur, $schoolid, $schooljaar  );
            $select->store_result();

            if($select->num_rows > 0){

              $htmlcontrol .= "<table id=\"tbl_verzuim_records_result\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
              $htmlcontrol .= "<thead><tr><th>Date</th><th>First name</th><th>Last Name</th><th>Initials</th><th>Group</th><th>Lesuur</th></tr></thead>";
              $htmlcontrol .= "<tbody>";
              while($select->fetch())
              {

                $htmlcontrol .= "<tr>";
                // $htmlcontrol .= "<td></td>";
                $htmlcontrol .= "<td>";
                $htmlcontrol .= "<input type='hidden' name='GUID' value='". $id ."'>";
                $htmlcontrol .= htmlentities($datum) ."</td>";
                $htmlcontrol .= "<td>". htmlentities($firstname) ."</td>";
                $htmlcontrol .= "<td>". htmlentities($lastname) ."</td>";
                $htmlcontrol .= "<td>". htmlentities($initials) ."</td>";
                $htmlcontrol .= "<td>". htmlentities($group_name) ."</td>";
                $htmlcontrol .= "<td>". htmlentities($lesuur) ."</td>";
                // $htmlcontrol .= "<td>". htmlentities($Class) ."</td>";

                // $htmlcontrol .= "<td><a href=\"$baseurl/$user_detail" . "?userGUID=" . htmlentities($_UserGUID) . "\" class=\"link quaternary-color\">MEER <i class=\"fa fa-angle-double-right quaternary-color\"></i></a>";
                $htmlcontrol .= "</tr>";
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
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      else
      {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result=0;
        print "error del sql: ". $this->errormessage;
        if($this->debug)
        {
          print "error preparing query";
        }
      }
      // Cierre del prepare
      $returnvalue = $htmlcontrol;
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
