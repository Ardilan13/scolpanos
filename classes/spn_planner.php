<?php


class spn_planner
{

  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";

  public $sp_create_planner = "sp_create_planner";
  public $sp_read_planner = "sp_read_planner";
  public $sp_update_planner = "sp_update_planner";
  public $sp_delete_planner = "sp_delete_planner";


  public $debug = true;
  public $error = "";
  public $errormessage = "";

  function create_planner($docent, $class,$week_planner,$subject_planner,$observations,$dummy)
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


          if($stmt=$mysqli->prepare("CALL " . $this->sp_create_planner . " (?,?,?,?,?)"))
          {

           if($stmt->bind_param("sssss", $docent, $class,$week_planner,$subject_planner,$observations))
           {
            if($stmt->execute())
            {

                require_once ("spn_audit.php");
                  $spn_audit = new spn_audit();
                  $UserGUID = $_SESSION['UserGUID'];
                  $spn_audit->create_audit($UserGUID, 'planner','create planner',appconfig::GetDummy());

              $result = 1;
                  $stmt->close();
                  $mysqli->close();
                }
                else
                {
                   $result = 0;
                  echo $this->mysqlierror = $mysqli->error;
                  echo $this->mysqlierrornumber = $mysqli->errno;

                  echo  $result = $mysqli->error;
                }

              }
              else
              {
                $result = 0;
              echo $this->mysqlierror = $mysqli->error;
              echo $this->mysqlierrornumber = $mysqli->errno;

              echo $result = $mysqli->error;
             }

           }
           else
           {
             echo $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            echo $this->mysqlierrornumber = $mysqli->errno;

            echo $result = $mysqli->error;
           }

       }
       catch(Exception $e)
       {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
      }
    }
    return $result;
  }

  function delete_planner($id_planner, $dummy)
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


        if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_planner . " (?)"))
        {

         if($stmt->bind_param("i", $id_planner))
         {
          if($stmt->execute())
          {
            require_once ("spn_audit.php");
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'planner','delete planner',appconfig::GetDummy());
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

  function read_planner_json($docent,$dummy)
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

          if ($select =$mysqli->prepare("CALL " . $this->sp_read_planner ." (?)"))
          {
            if ($select->bind_param("s", $docent))
            {
              if($select->execute())
              {
                require_once ("spn_audit.php");
                    $spn_audit = new spn_audit();
                    $UserGUID = $_SESSION['UserGUID'];
                    $spn_audit->create_audit($UserGUID, 'planner','read planner',appconfig::GetDummy());
                $this->error = false;
                $result=1;
                $select->bind_result($idplanner, $week, $week_end, $subjet, $observations);
                $select->store_result();

                if($select->num_rows > 0)
                {
                  $json_result .= "{";
                  $json_result .= "\"success\": \"1\",";
                  $json_result .= "\"result\": [";

                  while($select->fetch())
                  {
                    if (!$inloop){
                      $json_result .= "{";
                      $inloop = true;
                    }
                    else
                      $json_result .= ",{";

                    $json_result .= "\"id\": \"". $idplanner ."\",";
                    $json_result .= "\"title\": \"". $subjet . " : ". $observations ."\",";
                    $json_result .= "\"url\": \"javascript:delete_planner(" . $idplanner . ");\",";
                    switch ($subjet) {
                                          case 'Homework':
                                            $json_result .= "\"class\": \"event-info\",";
                                            break;
                                          case 'Test':
                                            $json_result .= "\"class\": \"event-success\",";
                                            break;
                                          case 'Exam':
                                            $json_result .= "\"class\": \"event-warning\",";
                                            break;
                                          case 'Other':
                                            $json_result .= "\"class\": \"event-special\",";
                                            break;
                                          default:
                                            $json_result .= "\"class\": \"event-important\",";
                                            break;
                                        }                    $json_result .= "\"start\": \"". strtotime($week) * 1000 ."\",";
                    $json_result .= "\"end\": \"". strtotime($week_end) * 1000 ."\"";

                    $json_result .= "}";
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
