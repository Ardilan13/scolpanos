<?php



class spn_le_vakken

{

  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $debug = true;
  public $error = "";
  public $errormessage = "";
  public $sp_list_le_vakken = "sp_list_le_vakken";
  public $sp_create_le_vakken = "sp_create_le_vakken";
  public $sp_delete_le_vakken = "sp_delete_le_vakken";
  public $sp_update_le_vakken = "sp_update_le_vakken";
  public $sp_list_schools_names = "sp_list_schools_names";
  public $sp_list_class_by_schools = 'sp_list_class_by_schools';
  public $sp_add_docent_group = 'sp_add_docent_group';
  public $sp_list_groups = 'sp_list_groups';

  function create_le_vakken($SchoolID,$Klas,$grade,$volledigenaamvak,$volgorde,$x_index,$y_index,$dummy){
    $result = 0;

    require_once("DBCreds.php");

    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      if ($dummy == 1)
      $result = 1;
      else
      {

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if($stmt=$mysqli->prepare("CALL " . $this->sp_create_le_vakken . " (?,?,?,?,?,?,?)"))
        {

          if($stmt->bind_param("isssiii", $SchoolID,$Klas,$grade,$volledigenaamvak,$volgorde,$x_index,$y_index))
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

  function create_le_group($group_name,$vakid,$dummy){
    $result = 0;

    require_once("DBCreds.php");

    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      if ($dummy == 1)
      $result = 1;
      else
      {

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if($stmt=$mysqli->prepare("CALL sp_create_group (?,?,?,?)"))
        {

          if($stmt->bind_param("siis", $group_name,$vakid,$_SESSION['SchoolID'],$_SESSION['SchoolJaar']))
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

  function update_le_vakken($vakid, $Klas,$volledigenaamvak,$volgorde,$x_index, $y_index,$dummy){
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      if ($dummy)
      $result = 1;
      else
      {
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if($stmt=$mysqli->prepare("CALL " . $this->sp_update_le_vakken . " (?,?,?,?,?,?)"))
        {

          if($stmt->bind_param("issiii", $vakid, $Klas,$volledigenaamvak,$volgorde,$x_index, $y_index))
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

    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();

    }



    return $result;

  }

  function read_le_vakken($_UserGUID,$dummy){

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



      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);



      if($select = $mysqli->prepare("CALL ".$this->sp_read_user_account." (?)"))

      {

        if($select->bind_param("i",$_UserGuid))

        {

          if($select->execute())

          {

            // Audit by Caribe Developers

            require_once ("spn_audit.php");

            $spn_audit = new spn_audit();

            $UserGUID = $_SESSION['UserGUID'];

            $spn_audit->create_audit($UserGUID, 'app_useraccount','Read User Account',appconfig::GetDummy());





            $this->error = false;

            $result = 1;



            $select->bind_result($_UserGUID);



            $select->store_result();

            if($select->num_rows > 0)

            {

              /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */

              $htmlcontrol .= "<table id=\"dataRequest-event\" class=\"table table-bordered table-colored\" data-table=\"yes\">";



              $htmlcontrol .= "<thead><tr><th>Event Date</th><th>Due Date</th><th>Reason</th><th>Involverd</th><th>Observations</th></tr></thead>";

              $htmlcontrol .= "<tbody>";

              $u = new spn_utils();

              while($select->fetch())

              {

                $htmlcontrol .="<tr><td>". $u->convertfrommysqldate(htmlentities($event_date)) ."</td><td>". $u->convertfrommysqldate(htmlentities($due_date)) ."</td><td>". htmlentities($reason) ."</td><td>". htmlentities($involved) ."</td><td>". htmlentities($observations) ."</td><td hidden>". htmlentities($private) ."</td><td hidden>". htmlentities($important_notice) ."</td><td hidden>". htmlentities($id_user_account) ."</td><td hidden>". htmlentities($id_student) ."</td>";              // <td><a href=\"#\" class=\"accion link quaternary-color\"  title=\"delete_user_account\" name=\"$id_user_account\">Delete <i class=\"fa fa-angle-double-right quaternary-color\"></i></a></td></tr>";



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

  function delete_le_vakken($id_le_vakken,$dummy){

    $result = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
      if ($dummy)
      $result = 1;
      else
      {
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);
        if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_le_vakken . " (?)"))
        {
          if($stmt->bind_param("i", $id_le_vakken))
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

    }
    catch(Exception $e)
    {
      $result = -2;
      echo $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }

  function list_le_vakken($SchoolID,$dummy){
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



      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select = $mysqli->prepare("CALL ".$this->sp_list_groups." (?,?)"))

      {

        if($select->bind_param("is",$SchoolID, $_SESSION['SchoolJaar']))

        {

          if($select->execute())

          {





            $this->error = false;

            $result = 1;



            $select->bind_result(	$groupid,$klas, $groupname, $vakid, $x_index,$y_index);



            $select->store_result();

            if($select->num_rows > 0)

            {  /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */

              $htmlcontrol .= "<table id=\"tbl_list_le_vakken\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

              $htmlcontrol .= "<thead><tr><th>Group Name</th><th>X-Index</th><th>Y-Index</th></tr></thead>";

              $htmlcontrol .= "<tbody>";



              while($select->fetch()) {



                if (strpos($groupname, '|') !== false) {

                  $groupname = str_replace("|","&",$groupname);

                }



                $htmlcontrol .= "<tr>";

                // $htmlcontrol .= "<td></td>";

                $htmlcontrol .= "<td>";

                $htmlcontrol .= "<input type='hidden' vakid='id' value='". $groupid ."' />";

                $htmlcontrol .= htmlentities($groupname) ."</td>";

                // $htmlcontrol .= "<td>". htmlentities($volgorde) ."</td>";

                $htmlcontrol .= "<td>". htmlentities($x_index) ."</td>";

                $htmlcontrol .= "<td>". htmlentities($y_index) ."</td>";

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

  function list_all_schools($SchoolAdmin,$dummy){
    $htmlcontrol ="";
    $returnvalue = "";
    $sql_query = "";
    $json_result ="";
    $indexvaluetoshow = 3;
    $inloop = false;
    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select =$mysqli->prepare("CALL " . $this->sp_list_schools_names ." (?)"))
        {
          if ($select->bind_param("i", $SchoolAdmin))
          {
            if($select->execute())
            {
              // require_once ("spn_audit.php");
              // $spn_audit = new spn_audit();
              // $UserGUID = $_SESSION['UserGUID'];
              // $spn_audit->create_audit($UserGUID, 'cijfers','get cijfers graph',appconfig::GetDummy());
              $this->error = false;
              $result=1;
              $select->bind_result($SchoolID, $schoolname);
              $select->store_result();

              if($select->num_rows > 0)
              {
                $htmlcontrol ="";
                $htmlcontrol .= "<option selected value=\"-1\">Select One School</option>";
                while($select->fetch())
                {
                  $htmlcontrol .= "<option value=". htmlentities($SchoolID) .">". htmlentities($schoolname) ."</option>";
                }

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
  }//End function get_dashboard_by_school_group

  function list_class_by_school($school_id,$dummy){
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

        if($stmt=$mysqli->prepare("CALL " . $this->sp_list_class_by_schools . "(?)"))
        {
          if ($stmt->bind_param("i",$school_id))
          {
            if($stmt->execute())
            {
              $this->error = false;
              $result=1;
              $stmt->bind_result($class);
              $stmt->store_result();

              if($stmt->num_rows > 0)
              {

                $htmlcontrol .= "<option selected value=\"-1\">Select One Class</option>";
                while($stmt->fetch())
                {
                  $htmlcontrol .= "<option value=". htmlentities($class) .">". htmlentities($class) ."</option>";
                }

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

  function check_vak_have_cijfers($vakid, $dummy){
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

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select = $mysqli->prepare("CALL sp_check_vak_have_cijfers (?)"))
      {
        if($select->bind_param("i", $vakid))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($count);

            $select->store_result();

            if($select->num_rows > 0 )
            {
              while($select->fetch())
              {
                $htmlcontrol = $count;
              }
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

  function disable_le_vakken($vakid, $dummy){

    $result = 0;



    require_once("DBCreds.php");

    $DBCreds = new DBCreds();

    date_default_timezone_set("America/Aruba");

    $_DateTime = date("Y-m-d H:i:s");

    $status = 1;



    mysqli_report(MYSQLI_REPORT_STRICT);



    try

    {

      if ($dummy)

      $result = 1;

      else

      {

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if($stmt=$mysqli->prepare("CALL sp_disable_group (?)"))

        {



          if($stmt->bind_param("i", $vakid))

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



    }

    catch(Exception $e)

    {

      $result = -2;

      $this->exceptionvalue = $e->getMessage();

    }



    return $result;

  }

  function check_vak_if_exist($group_name, $dummy){

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



      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);



      if($select = $mysqli->prepare("CALL sp_check_vak_if_exist (?,?,?)"))

      {

        if($select->bind_param("sis", $group_name,$_SESSION['SchoolID'], $_SESSION['SchoolJaar']))

        {

          if($select->execute())

          {

            $this->error = false;

            $result = 1;



            $select->bind_result($count);



            $select->store_result();



            if($select->num_rows > 0 )

            {

              while($select->fetch())

              {

                $htmlcontrol = $count;

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

  function list_class_by_grade($grade,$dummy){
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

        if($stmt=$mysqli->prepare("CALL sp_list_class_by_grade (?,?)"))
        {
          if ($stmt->bind_param("si",$grade, $_SESSION['SchoolID']))
          {
            if($stmt->execute())
            {
              $this->error = false;
              $result=1;
              $stmt->bind_result($class);
              $stmt->store_result();

              if($stmt->num_rows > 0)
              {
                // $htmlcontrol .= "<option selected value='All' >All Students for this grade</option>";
                while($stmt->fetch())
                {
                  $htmlcontrol .= "<option value=". htmlentities($class) .">". htmlentities($class) ."</option>";
                }
              }
              else
              {
                $htmlcontrol .= "<option value=''>There are no students</option>";
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

  function list_class_by_grade_by_mentor($grade,$dummy){
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

        if($stmt=$mysqli->prepare("CALL sp_list_class_by_grade_by_mentor (?,?)"))
        {
          if ($stmt->bind_param("ss",$_SESSION['UserGUID'], $grade))
          {
            if($stmt->execute())
            {
              $this->error = false;
              $result=1;
              $stmt->bind_result($class);
              $stmt->store_result();

              if($stmt->num_rows > 0)
              {
                // $htmlcontrol .= "<option selected value='All' >All Students for this grade</option>";
                while($stmt->fetch())
                {
                  $htmlcontrol .= "<option value=". htmlentities($class) .">". htmlentities($class) ."</option>";
                }
              }
              else
              {
                $htmlcontrol .= "<option value=''>There are no students</option>";
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

  function get_students_list($grade, $klas){

    $json = array();

    $result=false;  /* function will return this variable, true if auth successful, false if auth unsuccessful */



    mysqli_report(MYSQLI_REPORT_STRICT);



    try

    {

      require_once("DBCreds.php");

      $DBCreds = new DBCreds();

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      $mysqli->set_charset('utf8');



      if($klas == "All")

      {

        $sql_query_text = "select s.id, s.firstname, s.lastname from students s inner join class c on c.name_class = s.class where c.level_class = ? and s.schoolid = ? order by s.lastname;";



      }

      else

      {

        $sql_query_text = "select id, firstname, lastname from students where class = ? and schoolid = ? order by lastname;";



      }

      if($select=$mysqli->prepare($sql_query_text))

      {

        if($klas == "All")

        {

          if($select->bind_param("si",$grade, $_SESSION['SchoolID']))

          {

            if($select->execute())

            {

              $select->store_result();

              $select->bind_result($id,$firstname,$lastname);

              $count=$select->num_rows;



              if($count >= 1)

              {

                while($row = $select->fetch())

                {

                  $json[] = array("id"=>$id,"student"=>$lastname.', '.$firstname );

                }



              }

              else

              {

                /* No students found */

                $json[] = array("id"=>"0","student"=>"NONE");

                // $this->errormsg_internal=  "no students exist";

                // if($this->debug)

                // {

                //   print "no students exist";

                // }

              }



              $result = 1;

              $select->close();

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



          if($select->bind_param("si",$klas, $_SESSION['SchoolID']))

          {

            if($select->execute())

            {

              $select->store_result();

              $select->bind_result($id,$firstname,$lastname);

              $count=$select->num_rows;



              if($count >= 1)

              {

                while($row = $select->fetch())

                {

                  // $json[] = array("id"=>$id,"student"=>$firstname . chr(32) . $lastname);

                  $json[] = array("id"=>$id,"student"=>$lastname.', '.$firstname );

                  // print($id);

                }

              }

              else

              {

                /* No klassen found */

                $json[] = array("id"=>"0","student"=>"NONE");

                // $this->errormsg_internal=  "no students exist";

                // if($this->debug)

                // {

                //   print "no klassen exist";

                // }

              }



              $result = 1;

              $select->close();

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



      }

      else

      {

        $result = 0;

        $this->mysqlierror = $mysqli->error;

        $this->mysqlierrornumber = $mysqli->errno;

      }

      $result = json_encode($json);



      return $result;





    }

    catch (Exception $e)

    {

      $this->error = true;

      $this->errormsg_internal= $e-getMessage();

      /* handle error

      maybe db offline or something else */

      return $result;

    }





  }

  function get_group_list($grade, $suffix){

    $json = array();

    $result=false;  /* function will return this variable, true if auth successful, false if auth unsuccessful */



    mysqli_report(MYSQLI_REPORT_STRICT);

    try

    {

      require_once("DBCreds.php");

      $DBCreds = new DBCreds();

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);



      if ($suffix == "All"){

        $sql_query_text = "SELECT g.id, g.groupname from groups g inner join le_vakken v on v.id = g.vakid where v.grade = ? and g.schoolid = ? and g.enable = 1  order by groupname desc;";



      }

      else{

        $sql_query_text = "SELECT g.id, g.groupname from groups g inner join le_vakken v on v.id = g.vakid where v.grade = ? and g.schoolid = ?  and g.groupname like '%-$suffix' and g.enable = 1  order by groupname desc;";



      }

      // print($sql_query_text);



      if($select=$mysqli->prepare($sql_query_text))

      {



        if($select->bind_param("si",$grade, $_SESSION['SchoolID']))

        {

          if($select->execute())

          {

            $select->store_result();

            $select->bind_result($id,$groupname);

            $count=$select->num_rows;



            if($count >= 1)

            {

              while($row = $select->fetch())

              {

                $json[] = array("id"=>$id,"group_name"=>$groupname);

              }

            }

            else

            {

              /* No students found */

              $json[] = array("id"=>"0","group_name"=>"NONE");

              // $this->errormsg_internal=  "no klas exist";

              // if($this->debug)

              // {

              //   print "no students exist";

              // }

            }



            $result = 1;

            $select->close();

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



      $result = json_encode($json);



      return $result;





    }

    catch (Exception $e)

    {

      $this->error = true;

      $this->errormsg_internal= $e-getMessage();

      /* handle error

      maybe db offline or something else */

      return $result;

    }





  }

  function list_students_to_groups($grade,$klas,$suffix){

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
    $check_student_in_group = 0;

    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
      $students_list = json_decode($this->get_students_list($grade,$klas));
      $group_list = json_decode($this->get_group_list($grade, $suffix));


      $group_name = "";



      $htmlcontrol .= "<div id='fixed-table-container-1' class='fixed-table-container'>";



      // $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" style=\"overflow-x:auto\" data-table=\"yes\">";

      $htmlcontrol .= "<table id=\"tbl_group_students\"  class=\"table table-bordered table-colored table-responsive\">";

      $htmlcontrol .= "<thead><tr>";

      // $htmlcontrol .= "<th>Nr</th>";

      $htmlcontrol .= "<th>Student</th>";



      if($group_list[0]->id != 0){

        foreach($group_list as $group_data)

        {

          if (strpos($group_data->group_name, '|') !== false) {

            $group_name = str_replace("|","&",$group_data->group_name);

          }

          else{

            $group_name = $group_data->group_name;

          }

          $htmlcontrol .= "<th style='text-align: center;'>".$group_name."&nbsp &nbsp<input class='check_colum_group_students' type='checkbox'></th>";

        }

      }

      $htmlcontrol .= "</tr></thead>";

      $htmlcontrol .= "<tbody>";



      $i=0;

      if($students_list[0]->id != 0 && $group_list[0]->id != 0){



        foreach($students_list as $student_data)

        {

          $i++;



          $htmlcontrol .= "<tr>";

          // $htmlcontrol .= "<td>$i</td>";

          $htmlcontrol .= "<td>";

          $htmlcontrol .= "<input type='hidden' name='group_value' vakid=".$group_data->id." studentid='". $student_data->id ."' />";

          $htmlcontrol .= htmlentities($i.' - '.$student_data->student) ."</td>";





          foreach($group_list as $group_data)

          {

            // $htmlcontrol .= "<td>". htmlentities($group_data->group_name) ."</td>";

            $check_student_in_group = $this->check_student_in_group($group_data->id, $student_data->id , false);



            $htmlcontrol .= "<td><input studentid='$student_data->id' groupid='$group_data->id' name ='group_value' type='checkbox'onclick=\"updateStudentGroup($student_data->id,$group_data->id)\" style='margin-left:auto; margin-right:auto;'  class='form-control group-field' ". ($check_student_in_group == 1 ? "checked=\"checked\"": "") ."></td>";

            // $htmlcontrol .= "<td><input name =\"group_value\" type=\"checkbox\" style='margin-left:auto; margin-right:auto;'  class=\"form-control group-field\"". ($absent == 1 ? "checked=\"checked\"": "") ." ></td>";



          }

          $htmlcontrol .= "</tr>";

        }



      }





      $htmlcontrol .= "</tbody>";

      $htmlcontrol .= "</table>";

      $htmlcontrol .= "</div>";



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

  function check_student_in_group($vakid, $studentid, $dummy){

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

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select = $mysqli->prepare("CALL sp_check_students_in_group (?,?,?,?)"))
      {
        if($select->bind_param("iiis", $vakid, $studentid, $_SESSION['SchoolID'], $_SESSION['SchoolJaar']))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($enable);
            $select->store_result();
            if($select->num_rows > 0 )
            {
              while($select->fetch())
              {
                $htmlcontrol = $enable;
              }
            }
            else
            {
              $htmlcontrol .= -1; //NO EXIST;
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

  function create_or_update_student_in_group($vakid, $studentid, $dummy){

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
    $sp_user_group = "";
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      $check_student_in_group = $this->check_student_in_group($vakid, $studentid , false);

      // print($check_student_in_group);

      if ($check_student_in_group == 1){
        $sp_user_group = 'sp_disable_student_in_group';
      }
      else if ($check_student_in_group == 0){
        $sp_user_group = 'sp_enable_student_in_group';
      }
      else{
        $sp_user_group = 'sp_create_student_in_group';
      }

      if($stmt = $mysqli->prepare("CALL ".$sp_user_group." (?,?,?,?)"))
      {
        if($stmt->bind_param("iiis", $vakid, $studentid, $_SESSION['SchoolID'], $_SESSION['SchoolJaar']))
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

  function list_groups($schoolID,$dummy=false){
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

        $sp_read_docent = "sp_read_docent";



        if($stmt=$mysqli->prepare("CALL sp_list_groups_by_school (?,?)"))

        {

          if ($stmt->bind_param("is", $schoolID, $_SESSION['SchoolJaar']))

          {

            if($stmt->execute())

            {

              $this->error = false;

              $result=1;

              $stmt->bind_result($vakid, $volledigenaamvak);

              $stmt->store_result();



              if($stmt->num_rows > 0)



              {

                while($stmt->fetch())

                {

                  // print('ok');



                  if (strpos($volledigenaamvak, '|') !== false) {

                    $volledigenaamvak = str_replace("|","&",$volledigenaamvak);

                  }



                  $htmlcontrol .= "<option value=\"$vakid\">".htmlentities($volledigenaamvak) ."</option>";

                }

              }

              else

              {

                $htmlcontrol .= "";

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

  function list_groups_by_docent($userGUID,$dummy=false){

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

        $sp_read_docent = "sp_read_docent";



        if($stmt=$mysqli->prepare("CALL sp_list_group_by_docent (?)"))

        {

          if ($stmt->bind_param("s", $userGUID))

          {

            if($stmt->execute())

            {

              $this->error = false;

              $result=1;

              $stmt->bind_result($vakid, $volledigenaamvak);

              $stmt->store_result();



              if($stmt->num_rows > 0)



              {

                while($stmt->fetch())

                {

                  if (strpos($volledigenaamvak, '|') !== false) {

                    $volledigenaamvak = str_replace("|","&",$volledigenaamvak);

                  }



                  $htmlcontrol .= "<option value=\"$vakid\">".htmlentities($volledigenaamvak) ."</option>";

                }

              }

              else

              {

                $htmlcontrol .= "";

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

  function Add_docent_group($userGUID, $vak, $group_name, $schoolid, $schooljaar, $dummy){

    $result = 0;



    require_once("DBCreds.php");



    $DBCreds = new DBCreds();

    date_default_timezone_set("America/Aruba");

    $_DateTime = date("Y-m-d H:i:s");

    $status = 1;



    $klasArray = array();



    mysqli_report(MYSQLI_REPORT_STRICT);



    try

    {

      if ($dummy == 1)

      $result = 1;

      else

      {

        require_once("spn_controls.php");

        $control = new spn_controls();

        $klasArray = $control->get_klass_array_by_group($group_name);









        $length = count($klasArray);

        for ($i = 0; $i < $length; $i++) {





          require_once("spn_user_hs_account.php");

          $control = new spn_user_hs_account();

          $counKlas = $control->check_if_docent_have_klas($klasArray[$i], $userGUID, false);



          if ($counKlas == 0){

            $control->add_klas_and_vack_hs_account($userGUID,0,$klasArray[$i], 'No', appconfig::GetDummy());

          }







          $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);



          if($stmt=$mysqli->prepare("CALL " . $this->sp_add_docent_group . " (?,?,?,?,?)"))

          {

            if($stmt->bind_param("ssiis", $userGUID, $klasArray[$i], $vak, $schoolid, $schooljaar))

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





          }

          else

          {

            $result = 0;

            echo $this->mysqlierror = $mysqli->error;

            $this->mysqlierrornumber = $mysqli->errno;

          }

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

  function list_vaks_by_grade($grade, $dummy = false){

    $returnvalue = "";

    $sql_query = "";

    $htmlcontrol="";

    $V = "";

    $vakname ="";



    $result = 0;

    if ($dummy)

    $result = 1;

    else

    {

      //   print('enre aqui');

      mysqli_report(MYSQLI_REPORT_STRICT);

      $utils = new spn_utils();

      try

      {

        require_once("DBCreds.php");

        $DBCreds = new DBCreds();

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort,$dummy);



        if($stmt=$mysqli->prepare("CALL sp_list_vak_by_grade (?,?)"))

        {

          if ($stmt->bind_param("si", $grade,$_SESSION['SchoolID']))

          {

            if($stmt->execute())

            {

              $this->error = false;

              $result=1;

              $stmt->bind_result($volledigenaamvak);

              $stmt->store_result();



              if($stmt->num_rows > 0)

              {

                $htmlcontrol .= "<option value=\"\">Select a vak</option>";

                while($stmt->fetch())

                {

                  //   print('ok');

                  $V=strtoupper($volledigenaamvak);

                  if (strpos($V, '|') !== false) {

                    $vakname = str_replace("|","&",$V);

                  }

                  else{

                    $vakname = $V;

                  }



                  $htmlcontrol .= "<option value=\"$V\">".$vakname."</option>";

                }

              }

              else

              {

                $htmlcontrol .= "";

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

  function get_vak_by_grade_and_sufix($grade, $suffix, $vak,$dummy){

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



      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);



      if($select = $mysqli->prepare("CALL sp_get_vak_by_grade_suffix (?,?,?,?)"))

      {

        if($select->bind_param("sssi", $grade, $suffix, $vak, $_SESSION['SchoolID']))

        {

          if($select->execute())

          {

            $this->error = false;

            $result = 1;



            $select->bind_result($id);



            $select->store_result();



            if($select->num_rows > 0 )

            {

              while($select->fetch())

              {

                $htmlcontrol = $id;

              }

            }



            else

            {

              $htmlcontrol = null;

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

  function list_vak_by_grade_and_klas($grade,$klas,$dummy){

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



        if($stmt=$mysqli->prepare("CALL sp_list_vaks_by_grade_and_klas (?,?,?)"))

        {

          if ($stmt->bind_param("ssi",$grade,$klas, $_SESSION['SchoolID']))

          {

            if($stmt->execute())

            {

              $this->error = false;

              $result=1;

              $stmt->bind_result($id, $vak);

              $stmt->store_result();



              if($stmt->num_rows > 0)

              {

                // $htmlcontrol .= "<option selected value='All' >All Students for this grade</option>";

                while($stmt->fetch())

                {

                  if (strpos($vak, '|') !== false) {

                    $vak = str_replace("|","&",$vak);

                  }

                  $htmlcontrol .= "<option value=". htmlentities($id) .">". htmlentities($vak) ."</option>";

                }

              }

              else

              {

                $htmlcontrol .= "<option value=''>There are no Vaks</option>";

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

}
