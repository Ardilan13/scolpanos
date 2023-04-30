<?php 

class spn_vakken
{
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";

  public $debug = true;
  public $error = "";
  public $errormessage = "";

function getvakken ($schoolid,$klas) {

$returnarr = "";

$sql_query = "select id from le_vakken where schoolid=? and klas=?";

mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($select->bind_param("is",$schoolid,$klas))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($Xvak);
            $select->store_result();

            if($select->num_rows > 0)
            {
              while($select->fetch())
              {
               $returnarr[] = $Xvak;
            }
          }
            else
            {
              $returnarr["results"] = 0;
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

      $returnvalue = $returnarr;
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
    