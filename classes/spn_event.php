<?php


class spn_event
{

  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";

  public $sp_create_event = "sp_create_event";
  public $sp_list_event = "sp_list_event";
  public $sp_read_event = "sp_read_event";
  public $sp_update_event = "sp_update_event";
  public $sp_delete_event = "sp_delete_event";


  public $debug = true;
  public $error = "";
  public $errormessage = "";


  function create_event($event_date,$due_date,$reason,$involved,$observations,$private,$important_notice,$id_student,$schooljaar,$dummy)
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
      if ($dummy == 1)
      $result = 1;
      else
      {

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

        if($stmt=$mysqli->prepare("CALL " . $this->sp_create_event . " (?,?,?,?,?,?,?,?,?)"))
        {

          if($stmt->bind_param("sssssiiis", $event_date, $due_date, $reason, $involved,$observations, $private, $important_notice,$id_student,$schooljaar))
          {
            if($stmt->execute())
            {
              // Audit by Caribe Developers
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'event','create event',appconfig::GetDummy());
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

  function update_event($id_event,
  $event_date,
  $due_date,
  $reason,
  $involved,
  $observations,
  $private,
  $important_notice,
  $dummy)
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
      if ($dummy)
      $result = 1;
      else
      {

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

        if($stmt=$mysqli->prepare("CALL " . $this->sp_update_event . " (?,?,?,?,?,?,?,?)"))
        {

          if($stmt->bind_param("isssssii",
          $id_event,
          $event_date,
          $due_date,
          $reason,
          $involved,
          $observations,
          $private,
          $important_notice))
          {
            if($stmt->execute())
            {
              // Audit by Caribe Developers
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'event','update event',appconfig::GetDummy());
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

  function list_event($schooljaar, $id_event,$id_student, $dummy)
  {
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
      // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

      //if($stmt=$mysqli->prepare("CALL " . $this->sp_create_event . " (?,?,?,?,?,?,?,?)"))

      if($select = $mysqli->prepare("CALL ".$this->sp_read_event." (?,?,?)"))
      {
        if($select->bind_param("sii",$schooljaar,$id_event,$id_student))
        {
          if($select->execute())
          {
            // Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'event','list event',appconfig::GetDummy());


            $this->error = false;
            $result = 1;

            $select->bind_result(
            $id_event,
            $event_date,
            $due_date,
            $reason,
            $involved,
            $observations,
            $private,
            $important_notice,
            $id_student,
            $schooljaar);

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
                $htmlcontrol .="<tr><td>". $u->convertfrommysqldate(htmlentities($event_date)) ."</td><td>". $u->convertfrommysqldate(htmlentities($due_date)) ."</td><td>". htmlentities($reason) ."</td><td>". htmlentities($involved) ."</td><td>". htmlentities($observations) ."</td><td hidden>". htmlentities($private) ."</td><td hidden>". htmlentities($important_notice) ."</td><td hidden>". htmlentities($id_event) ."</td><td hidden>". htmlentities($id_student) ."</td>";
                // <td><a href=\"#\" class=\"accion link quaternary-color\"  title=\"delete_event\" name=\"$id_event\">Delete <i class=\"fa fa-angle-double-right quaternary-color\"></i></a></td></tr>";




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

  function delete_event($id_event, $dummy)
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
      if ($dummy)
      $result = 1;
      else
      {

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

        if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_event . " (?)"))
        {

          if($stmt->bind_param("i", $id_event))
          {
            if($stmt->execute())
            {
              // Audit by Caribe Developers
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'event','delete event',appconfig::GetDummy());
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


  // function get_event($id_event,$id_student = null)
  // {
  //
  //     $array_event = array();
  //
  //     mysqli_report(MYSQLI_REPORT_STRICT);
  //
  //     try
  //     {
  //         require_once("DBCreds.php");
  //         $DBCreds = new DBCreds();
  //
  //
  //         $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
  //         // $mysqli=new mysqli("127.0.0.1", "root", "", "scola_test", "3306");
  //
  //         if($select = $mysqli->prepare("CALL ".$this->sp_read_event. "(?,?)"))
  //         {
  //             if($select->bind_param("ii",$id_event,$id_student))
  //             {
  //                 if($select->execute())
  //                 {
  //                     $this->error = false;
  //                     $result = 1;
  //
  //
  //                     $select->bind_result(
  //                         $id_event,
  //                         $event_date,
  //                         $due_date,
  //                         $reason,
  //                         $involved,
  //                         $observations,
  //                         $private,
  //                         $important_notice,
  //                         $id_student);
  //                     $select->store_result();
  //
  //
  //                     if($select->num_rows > 0)
  //                     {
  //                         while($select->fetch())
  //                         {
  //                             $array_event["id_event"] = $id_event;
  //                             $array_event["event_date"] = $event_date;
  //                             $array_event["due_date"] = $due_date;
  //                             $array_event["reason"] = $reason;
  //                             $array_event["involved"] = $involved;
  //                             $array_event["observations"] = $observations;
  //                             $array_event["private"] = $private;
  //                             $array_event["important_notice"] = $important_notice;
  //                             $array_event["id_student"] = $id_student;
  //
  //                         }
  //
  //                     }
  //                     else
  //                     {
  //                         $this->error = true;
  //                         $this->errormessage = $mysqli->error;
  //                         echo  "No results to show". $this->errormessage = $mysqli->error;
  //                     }
  //
  //                 }
  //                 else
  //                 {
  //                     /* error executing query */
  //                     $this->error = true;
  //                     $this->errormessage = $mysqli->error;
  //                     $result=0;
  //
  //                     if($this->debug)
  //                     {
  //                         print "error executing query" . "<br />";
  //                         print "error" . $mysqli->error;
  //                     }
  //                 }
  //             }
  //             else
  //             {
  //                 $result = 0;
  //                 $this->mysqlierror = $mysqli->error;
  //                 $this->mysqlierrornumber = $mysqli->errno;
  //
  //                 $array_event .= $mysqli->error;
  //             }
  //         }
  //         else
  //         {
  //             /* error preparing query */
  //             $this->error = true;
  //             $this->errormessage = $mysqli->error;
  //             $result=0;
  //
  //             print "error del sql: ". $this->errormessage;
  //
  //             if($this->debug)
  //             {
  //                 print "error preparing query";
  //             }
  //         }
  //         // Cierre del prepare
  //
  //
  //         return $array_event;
  //
  //     }
  //     catch(Exception $e)
  //     {
  //         $this->error = true;
  //         $this->errormessage = $e->getMessage();
  //         $result=0;
  //
  //         if($this->debug)
  //         {
  //             print "exception: " . $e->getMessage();
  //         }
  //     }
  //
  //     return $array_event;
  // }


}
