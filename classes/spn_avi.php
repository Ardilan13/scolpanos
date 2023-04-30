<?php


class spn_avi{

  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $sp_create_avi = "sp_create_avi";
  public $sp_read_avi = "sp_read_avi";
  public $sp_update_avi = "sp_update_avi";
  public $sp_delete_avi = "sp_delete_avi";
  public $sp_read_class= "sp_read_class";
  public $sp_read_studentbyclass = "sp_read_studentbyclass";
  public $sp_read_avi_by_student = "sp_read_avi_by_student";

  function check_avi_student($id_student,$schooljaar,$class,$period)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "SELECT id_avi FROM  avi where id_student = ? and class = ? and schooljaar = ? AND period =?";

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query)) {
        if($select->bind_param("issi",$id_student,$class,$schooljaar,$period))   {
          if($select->execute())
          {

            $this->error = false;
            $result=1;

            $select->store_result();

            $returnvalue = $select->num_rows;

          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->mysqlierror = $mysqli->error;
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
          $this->mysqlierror = $mysqli->error;
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
        $this->mysqlierror = $mysqli->error;
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
      $this->mysqlierror = $e->getMessage();
      $result=0;

      if($this->debug)
      {
        //print "exception: " . $e->getMessage();
        $result=0;
        return $result;
      }
    }

    return $returnvalue;

  }
  function save_opmerking($id_student,$SchoolJaar,$SchoolID,$klas,$opmerking1,$opmerking2,$opmerking3,$dummy)
  {

    $opmerking_count = $this->check_opmerking_student($id_student,$SchoolJaar,$SchoolID,$klas,$opmerking1,$opmerking2,$opmerking3,$dummy);
    if($opmerking_count == 1)
    {

      return $this->update_opmerking($id_student,$SchoolJaar,$SchoolID,$klas,$opmerking1,$opmerking2,$opmerking3,$dummy);


    }
    else
    {

      return $this->create_opmerking($id_student,$SchoolJaar,$SchoolID,$klas,$opmerking1,$opmerking2,$opmerking3,$dummy);

    }
  }
  function create_avi($class,$period,$level,$promoted,$mistakes,$time_length,$observation,$id_student,$schooljaar,$dummy)
  {
    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      $avi_count = $this->check_avi_student($id_student,$schooljaar,$class,$period);
      if($avi_count == 1)
      {
        $result = 2;

      }
      else
      {

        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $status = 1;
        mysqli_report(MYSQLI_REPORT_STRICT);
        try
        {
          $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

          if ($select =$mysqli->prepare("CALL " . $this->sp_create_avi . " (?,?,?,?,?,?,?,?,?)"))
          {
            if ($select->bind_param("sssisssis",$class,$period,$level,$promoted,$mistakes,$time_length,$observation,$id_student,$schooljaar))
            {
              if($select->execute())
              {
                require_once ("spn_audit.php");
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'avi','create avi',appconfig::GetDummy());
                /* Need to check for errors on database side for primary key errors etc.*/
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
        }
        catch(Exception $e)
        {
          $result = -2;
          $this->exceptionvalue = $e->getMessage();
        }
        return $result;
      }
      return $result;
    }
  }

  function get_avi($school_id, $class,$schooljaar, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol="";
    $indexvaluetoshow = 3;

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
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        $mysqli->set_charset('utf8');
        if ($select =$mysqli->prepare("CALL " . $this->sp_read_avi ." (?,?,?)"))
        {
          // $class = '';
          if ($select->bind_param("iss", $school_id, $class,$schooljaar))
          {
            if($select->execute())
            {
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'avi','read avi',appconfig::GetDummy());
              $this->error = false;
              $result=1;
              $select->bind_result($idstudent, $class, $student, $period1, $period2, $period3);
              $select->store_result();

              if($select->num_rows > 0)
              {
                $htmlcontrol .= "<table id=\"table_avi_result_detail\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
                $htmlcontrol .= "<thead><tr><th>Student</th><th>Klas</th><th>Begin AVI</th><th>Period 1</th><th>Period 2</th><th>Period 3</th></tr></thead>";
                $htmlcontrol .= "<tbody>";

                while($select->fetch())
                {
                  $htmlcontrol .= "<tr>";

                  $htmlcontrol .= "<td>". htmlentities($student) ."<input type='hidden' name='id_student' value='". htmlentities($idstudent) ."'></td>";
                  $htmlcontrol .= "<td>". htmlentities($class) ."</td>";
                  $_level = $this->get_last_level($_SESSION["SchoolJaar"], $_SESSION["SchoolID"],$idstudent,$class);
                  //$_level = mysql_query("SELECT level FROM avi a inner join students s on a.id_student = s.id where a.class = ". chr(32) . $class . chr(32) . " and a.period ='3' and a.schooljaar = '2016-2017' and a.id_student =  ". chr(32) . $idstudent . chr(32) . ";");
                  $htmlcontrol .= "<td>". htmlentities($_level)."</td>";
                  $htmlcontrol .= "<td>";

                  // Define array controls
                  $array_control_hidden = array("<input type='hidden' name='id_avi_hidden' value='@'>",
                  "<input type='hidden' name='student_id_hidden' value='@'>",
                  "<input type='hidden' name='class_hidden' value='@'>",
                  "<input type='hidden' name='period_hidden' value='@'>",
                  "<input type='hidden' name='level_hidden' value='@'>",
                  "<input type='hidden' name='promoted_hidden' value='@'>",
                  "<input type='hidden' name='mistakes_hidden' value='@'>",
                  "<input type='hidden' name='time_length_hidden' value='@'>",
                  "<input type='hidden' name='observation_hidden' value='@'>");

                  $array_p1 = explode('|', $period1);

                  for ($i=0; $i < sizeof($array_p1);$i++)
                  {
                    if ($i > $indexvaluetoshow) $htmlcontrol .= htmlentities($array_p1[$i]) ."<br>";
                    $htmlcontrol .= str_replace("@", substr($array_p1[$i], strpos($array_p1[$i],":") + 2), $array_control_hidden[$i]);
                  }

                  $htmlcontrol .= "</td>";
                  $htmlcontrol .= "<td>";

                  $array_p2 = explode('|', $period2);
                  for ($i=0; $i < sizeof($array_p2);$i++)
                  {
                    if ($i > $indexvaluetoshow) $htmlcontrol .= htmlentities($array_p2[$i]) ."<br>";
                    $htmlcontrol .= str_replace("@", substr($array_p2[$i], strpos($array_p2[$i],":") + 2), $array_control_hidden[$i]);
                  }

                  $htmlcontrol .= "</td>";
                  $htmlcontrol .= "<td>";

                  $array_p3 = explode('|', $period3);
                  for ($i=0; $i < sizeof($array_p3);$i++)
                  {
                    if ($i > $indexvaluetoshow) $htmlcontrol .= htmlentities($array_p3[$i]) ."<br>";
                    $htmlcontrol .= str_replace("@", substr($array_p3[$i], strpos($array_p3[$i],":") + 2), $array_control_hidden[$i]);
                  }

                  $htmlcontrol .= "</td>";

                  $htmlcontrol .= "</tr>";
                }

                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";

                //$htmlcontrol.= "<table id=\"dataRequest-avi-detail2\" class=\"table table-bordered table-colored\" data-table=\"yes\"><thead><tr><th>Student</th><th>P1</th><th>P2</th><th>P3</th></tr></thead><tbody><tr><td>Rudy Croes</td><td> Level: 9 <br>Promoted: 1 Mistakes: Sin faltas Time length: 1 hora Observation: Sin observacion</td><td> Level: 8 Promoted: 1 Mistakes: 1 falta  Time length: 1 hora Observation: Una pregunta al profesor</td><td> Level: 7 Promoted: 1 Mistakes: 2 faltas Time length: 1 hora Observation: Dos preguntas, una ida al bano</td></tr></tbody></table>";
              }
              else
              $htmlcontrol .= "No results to show";
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

          $htmlcontrol = $mysqli->error;
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


  function update_avi($id_avi, $class, $period, $level, $promoted, $mistakes, $time_length, $observation, $id_student, $dummy)
  {
    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if($select =$mysqli->prepare("CALL " . $this->sp_update_avi . " (?,?,?,?,?,?,?,?,?)"))
        {
          if($select->bind_param("isssisssi",$id_avi,$class,$period,$level,$promoted,$mistakes,$time_length,$observation,$id_student))
          {
            if($select->execute())
            {
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'avi','update avi',appconfig::GetDummy());
              /* Need to check for errors on database side for primary key errors etc.*/
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
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
      }
      return $result;
    }
  }

  function delete_avi($id_avi, $dummy)
  {
    if ($dummy)
    $result = 1;
    else
    {
      $result = 0;

      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_avi . "(?)"))
        {
          if($stmt->bind_param("i", $id_avi))
          {
            if($stmt->execute())
            {
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'avi','delete avi',appconfig::GetDummy());
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
  }

  function list_class($allClass,$userGUID,$school_id,$dummy)
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

        if($stmt=$mysqli->prepare("CALL " . $this->sp_read_class . "(?,?,?)"))
        {
          if ($stmt->bind_param("ssi",$allClass,$userGUID,$school_id))
          {
            if($stmt->execute())
            {
              $this->error = false;
              $result=1;
              $stmt->bind_result($class);
              $stmt->store_result();

              if($stmt->num_rows > 0)
              {
                $htmlcontrol .= "<select id=\"list_class_avi\" name=\"list_class_avi\" class=\"form-control\">";
                $htmlcontrol .= "<option val=\"-1\" selected>Select One Class</option>";
                while($stmt->fetch())
                {
                  $htmlcontrol .= "<option value=". htmlentities($class) .">". htmlentities($class) ."</option>";
                }
                $htmlcontrol .= "</select>";
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

  function list_class_ul($allClass,$userGUID,$school_id,$dummy)
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

        if($stmt=$mysqli->prepare("CALL " . $this->sp_read_class . "(?,?,?)"))
        {
          if ($stmt->bind_param("ssi",$allClass,$userGUID,$school_id))
          {
            if($stmt->execute())
            {
              $this->error = false;
              $result=1;
              $stmt->bind_result($class);
              $stmt->store_result();

              if($stmt->num_rows > 0)
              {
                //  <li id="All"><a>All</a></li>
                //  <li id="1A"><a>1A</a></li>
                //  <li id="1B"><a>1B</a></li>
                //  <li id="2A"><a>2A</a></li>
                //  <li id="2B"><a>2B</a></li>
                //  <li id="3A"><a>3A</a></li>

                $htmlcontrol = "<ul class='dropdown-menu' id='list_class_search' ><li id='All'><a>All</a></li>";
                while($stmt->fetch())
                $htmlcontrol .= "<li id=". htmlentities($class) ."><a>". htmlentities($class) ."</a></li>";
                $htmlcontrol .= "</ul>";
              }
              else
              $htmlcontrol .= "No results to show";
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

  function liststudentbyclass($class, $schoolid, $id_student_param, $dummy)
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
        $mysqli->set_charset('utf8');

        if($stmt=$mysqli->prepare("CALL " . $this->sp_read_studentbyclass . "(?,?)"))
        {
          if ($stmt->bind_param("si", $class, $schoolid))
          {
            if($stmt->execute())
            {
              $this->error = false;
              $result=1;
              $stmt->bind_result($id_student,$id_family, $student_name);
              $stmt->store_result();

              if($stmt->num_rows > 0)

              {
                $htmlcontrol .= "<select id=\"list_student_by_class\" name=\"list_student_by_class\" class=\"form-control\">";
                $htmlcontrol .= "<option id=\"selectOneStudent\"selected>Select One Student</option>";
                while($stmt->fetch())
                {
                  if ($id_student_param == $id_student)
                  $htmlcontrol .= "<option family=". htmlentities($id_family) ." value=". htmlentities($id_student) ." selected>". htmlentities($student_name) ."</option>";
                  else
                  $htmlcontrol .= "<option family=". htmlentities($id_family) ." value=". htmlentities($id_student) .">". htmlentities($student_name) ."</option>";


                }
                $htmlcontrol .= "</select>";
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

  function list_avi_by_student($idstudent,$schooljaar, $dummy)
  {
    
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol="";
    $indexvaluetoshow = 3;

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
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select =$mysqli->prepare("CALL " . $this->sp_read_avi_by_student ." (?,?)"))
        {
          // $class = '';
          if ($select->bind_param("is", $idstudent,$schooljaar))
          {
            if($select->execute())
            {
              $this->error = false;
              $result=1;
              $select->bind_result($class, $period, $level, $promoted, $mistakes, $time_leng, $observation);
              $select->store_result();

              if($select->num_rows > 0)
              {
                $htmlcontrol .= "<table id=\"dataRequest-avi-detail-student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
                $htmlcontrol .= "<thead><tr><th>Class</th><th>Period</th><th>Level</th><th>Promoted</th><th>Mistakes</th><th>Time length</th><th>Observation</th></tr></thead>";
                $htmlcontrol .= "<tbody>";

                while($select->fetch())
                {
                  $htmlcontrol .= "<tr>";

                  $htmlcontrol .= "<td>". htmlentities($class) ."</td>";
                  $htmlcontrol .= "<td>". htmlentities($period) ."</td>";
                  $htmlcontrol .= "<td>". htmlentities($level) ."</td>";
                  $htmlcontrol .= "<td>". (htmlentities($promoted) ? "Yes" : "No") ."</td>";
                  $htmlcontrol .= "<td>". htmlentities($mistakes) ."</td>";
                  $htmlcontrol .= "<td>". htmlentities($time_leng) ."</td>";
                  $htmlcontrol .= "<td>". htmlentities($observation) ."</td>";

                  $htmlcontrol .= "</tr>";
                }

                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";

                //$htmlcontrol.= "<table id=\"dataRequest-avi-detail2\" class=\"table table-bordered table-colored\" data-table=\"yes\"><thead><tr><th>Student</th><th>P1</th><th>P2</th><th>P3</th></tr></thead><tbody><tr><td>Rudy Croes</td><td> Level: 9 <br>Promoted: 1 Mistakes: Sin faltas Time length: 1 hora Observation: Sin observacion</td><td> Level: 8 Promoted: 1 Mistakes: 1 falta  Time length: 1 hora Observation: Una pregunta al profesor</td><td> Level: 7 Promoted: 1 Mistakes: 2 faltas Time length: 1 hora Observation: Dos preguntas, una ida al bano</td></tr></tbody></table>";
              }
              else
              $htmlcontrol .= "No results to show";
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

          $htmlcontrol = $mysqli->error;
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
  function get_last_level($schooljaar, $schoolid,$idstudent,$class)
  {



    $sql_query = "";
    $level="";
    $returnvalue ="";
    $last_schooljaar ="";
    if ($schooljaar=='2019-2020'){
      $last_schooljaar ='2018-2019';
    }
    if ($schooljaar=='2018-2019'){
      $last_schooljaar ='2017-2018';
    }
    if ($schooljaar=='2017-2018'){
      $last_schooljaar ='2016-2017';
    }
    if ($schooljaar=='2016-2017'){
      $last_schooljaar ='2015-2016';
    }
    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "SELECT level FROM avi a inner join students s on a.id_student = s.id where a.class = ? and a.period ='3' and a.schooljaar = ? and a.id_student = ?;";

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($select=$mysqli->prepare($sql_query)) {
        if($select->bind_param("sss",$class,$last_schooljaar,$idstudent))
          {

            if($select->execute())
            {

              $this->error = false;
              $result=1;
              $select->bind_result($level);
              $select->store_result();

              if($select->num_rows > 0)

              {
                while($select->fetch())
                {

                  $returnvalue = $level;

                }
              }
              else
              {
                $returnvalue = "--";
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
      $this->error = true;
      $this->mysqlierror = $e->getMessage();
      $result=0;

      if($this->debug)
      {
        //print "exception: " . $e->getMessage();
        $result=0;
        return $result;
      }
    }

    return $returnvalue;

  }
}
