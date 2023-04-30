<?php

class spn_audit{
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $debug = true;
  public $error = "";
  public $errormessage = "";
  public $sp_create_audit = "sp_create_audit";


  function create_audit($UserGUID, $module,$audit_function,$dummy)
  {
    $result = 0;
    if($dummy)
    {
      $result = 1;
    }else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if($stmt=$mysqli->prepare("CALL " . $this->sp_create_audit . " (?,?,?)"))
        {
          if($stmt->bind_param("sss",$UserGUID,$module,$audit_function))
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
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->create_audit();
      }
    }
    return $result;
  }
}
?>
