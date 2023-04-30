<?php


class spn_daily
{

  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";

  public $sp_create_daily = "sp_create_daily";
  public $sp_read_daily = "sp_read_daily";
  public $sp_update_daily = "sp_update_daily";
  public $sp_delete_daily = "sp_delete_daily";


  public $debug = true;
  public $error = "";
  public $errormessage = "";

  function create_daily($docent,$class,$start_date_time,$end_date_time,$subject_daily,$observations,$dummy)
  {
    $result = 0;
    if ($dummy)
      $result = 1;
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
          $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
          if($stmt=$mysqli->prepare("CALL " . $this->sp_create_daily . " (?,?,?,?,?,?)"))
          {

           if($stmt->bind_param("ssssss", $docent,$class,$start_date_time,$end_date_time,$subject_daily,$observations))
           {
            if($stmt->execute())
            {
              require_once ("spn_audit.php");
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'daily','create daily',appconfig::GetDummy());
                  $result = 1;
                  $stmt->close();
                  $mysqli->close();
                }
                else
                {
                  $result = 0;
                  $this->mysqlierror = $mysqli->error;
                  $this->mysqlierrornumber = $mysqli->errno;

                  $result = $mysqli->error;
                }

              }
              else
              {
               $result = 0;
               $this->mysqlierror = $mysqli->error;
               $this->mysqlierrornumber = $mysqli->errno;

               $result = $mysqli->error;
             }

           }
           else
           {
             $result = 0;
             $this->mysqlierror = $mysqli->error;
             $this->mysqlierrornumber = $mysqli->errno;

             $result = $mysqli->error;
           }

       }
       catch(Exception $e)
       {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }
    }
    return $result;
  }

  function delete_daily($id_daily, $dummy)
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

        if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_daily . " (?)"))
        {

         if($stmt->bind_param("i", $id_daily))
         {
          if($stmt->execute())
          {
            require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'daily','delete daily',appconfig::GetDummy());
                $result = 1;
                $stmt->close();
                $mysqli->close();
              }
              else
              {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $result = $mysqli->error;
              }

            }
            else
            {
             $result = 0;
             $this->mysqlierror = $mysqli->error;
             $this->mysqlierrornumber = $mysqli->errno;

             $result = $mysqli->error;
           }

         }
         else
         {
           $result = 0;
           $this->mysqlierror = $mysqli->error;
           $this->mysqlierrornumber = $mysqli->errno;

           $result = $mysqli->error;
         }
      }

     }
     catch(Exception $e)
     {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();

      $result = $e->getMessage();
    }

    return $result;
  }

  function read_daily_json($docent,$dummy)
  {
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

          if ($select =$mysqli->prepare("CALL " . $this->sp_read_daily ." (?)"))
          {
            if ($select->bind_param("s", $docent))
            {
              if($select->execute())
              {
                require_once ("spn_audit.php");
                  $spn_audit = new spn_audit();
                  $UserGUID = $_SESSION['UserGUID'];
                  $spn_audit->create_audit($UserGUID, 'daily','read daily',appconfig::GetDummy());
                $this->error = false;
                $result=1;
                $select->bind_result($iddaily, $start_date, $end_date, $subjet, $observations);
                $select->store_result();

                if($select->num_rows > 0)
                {
                  $json_result .= "{";
                  $json_result .= "\"success\": \"1\",";
                  $json_result .= "\"result\": [";

                    $array_color_event = array("\"class\": \"event-important\",",
                                                  "\"class\": \"event-info\",",
                                                  "\"class\": \"event-warning\",",
                                                  "\"class\": \"event-inverse\",",
                                                  "\"class\": \"event-success\",",
                                                  "\"class\": \"event-special\",");

                    $i = 0;

                    while($select->fetch())
                    {
                      if (!$inloop){
                        $json_result .= "{";
                        $inloop = true;
                      }
                      else
                        $json_result .= ",{";

                    $json_result .= "\"id\": \"". $iddaily ."\",";
                    $json_result .= "\"title\": \"". $subjet . " : ". $observations ."\",";
                    $json_result .= "\"url\": \"javascript:delete_daily(" . $iddaily . ");\",";
                      $json_result .= $array_color_event[$i];
                      $json_result .= "\"start\": \"". strtotime($start_date) * 1000 ."\",";
                      $json_result .= "\"end\": \"". strtotime($end_date) * 1000 ."\"";

                      $json_result .= "}";

                      if ($i < 6)
                        $i = $i +1;
                      else
                        $i = 0;
                    }

                  $json_result .= "]";
                  $json_result .= "}";

                }
                else
                {
                  $json_result .= "{";
                  $json_result .= "\"success\": 0,";
                  $json_result .= "\"result\": []";
                  $json_result .= "}";
                }
              }
              else
              {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $json_result = $result;
              }
            }
            else
            {
               $result = 0;
               $this->mysqlierror = $mysqli->error;
               $this->mysqlierrornumber = $mysqli->errno;

               $json_result = $result;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $json_result = $result;
          }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();

        $json_result = $result;
      }
      return $json_result;
    }
  }

}

?>
