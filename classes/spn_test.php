<?php


class spn_test
{

  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";

  public $sp_create_test = "sp_create_test";
  public $sp_read_test = "sp_read_test";
  public $sp_update_test = "sp_update_test";
  public $sp_delete_test = "sp_delete_test";


  public $debug = true;
  public $error = "";
  public $errormessage = "";

  function create_test($date, $type, $class, $observations, $id_student, $dummy)
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
        // TODO: DELETE
        // $mysqli=new mysqli("127.0.0.1", "root", "", $DBCreds->DBSchema, "3306");


        if($stmt=$mysqli->prepare("CALL " . $this->sp_create_test . " (?,?,?,?,?)"))
        {
         if($stmt->bind_param("ssssi", $date, $type, $class, $observations, $id_student))
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
    }

    return $result;
  }

  function update_test($test_id, $date, $type, $class, $observations, $dummy)
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
        // TODO: DELETE
        // $mysqli=new mysqli("127.0.0.1", "root", "", $DBCreds->DBSchema, "3306");

        if($stmt=$mysqli->prepare("CALL " . $this->sp_update_test . " (?,?,?,?,?)"))
        {

         if($stmt->bind_param("issss", $test_id, $date, $type, $class, $observations))
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

      $result = $mysqli->error;

    }

    return $result;
  }

  function get_test($id_test, $id_student, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $returnvalue = "";

    if ($dummy)
    {
      $htmlcontrol .= "<table id=\"dataRequest-test" ."\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
      $htmlcontrol .= "<thead><tr><th>School Year</th><th>Date</th><th>Reason</th><th>Class</th><th>Observations</th><th>Pending</th></tr></thead>";
      $htmlcontrol .= "<tbody>";
      for ($i = 1; $i <= 10; $i++)
      {
        $htmlcontrol .= "<tr><td>201". htmlentities($i)."</td><td>2016-03-02 16:44:00</td><td>La razon principal del comportamiento se debe a </td>";
        $htmlcontrol .= "<td>". htmlentities($i)."A</td><td>Observacion". htmlentities($i) ."</td><td>". ($i % 2 == 0? "Pending": "No Pending" ) ."</td>";
        $htmlcontrol .= "<td><a href='#' class='link quaternary-color'>MEER <i class='fa fa-angle-double-right quaternary-color'></i></a></td></td>";
      }

      $htmlcontrol .= "</tbody>";
      $htmlcontrol .= "</table>";

      $returnvalue = $htmlcontrol;

    }
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
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", "3306");
        $sql = "SELECT * FROM test t WHERE t.id_student = ?;";
        if($select = $mysqli->prepare($sql))
        {
          if($select->bind_param("i",$id_student))
          {
            if($select->execute())
            {
              $this->error = false;
              $result = 1;

              $select->bind_result($id_test, $date, $type, $class, $observations, $id_student);
              $select->store_result();

              if ($id_test == "" || $id_test == 0  || $id_test == "null")
              {
                // List all the test's
                if($select->num_rows > 0)
                {
                  $htmlcontrol .= "<table id=\"dataRequest-test" ."\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
                  $htmlcontrol .= "<thead><tr><th>Date</th><th>Type</th><th>Class</th><th>Observations</th></tr></thead>";
                  $htmlcontrol .= "<tbody>";

                  $u = new spn_utils();

                  while($select->fetch())
                  {
                    $htmlcontrol .= "<tr>";
                    $htmlcontrol .= "<td>". $u->convertfrommysqldate(htmlentities($date)) ."</td>";
                    $htmlcontrol .= "<td>". htmlentities($type) ."</td>";
                    $htmlcontrol .= "<td>". htmlentities($class) ."</td>";
                    $htmlcontrol .= "<td>". htmlentities($observations);
                    $htmlcontrol .= "<input type='hidden' name='id_test' value='". htmlentities($id_test) . "'>";
                    $htmlcontrol .= "</td>";
                    $htmlcontrol .= "</tr>";

                  }

                  $htmlcontrol .= "</tbody>";
                  $htmlcontrol .= "</table>";
                }
                else
                  $htmlcontrol .= "No results to show";

                $returnvalue = $htmlcontrol;
              }
              else
              {
                // Obtain a test
                if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $htmlcontrol .= "<tr>";
                    $htmlcontrol .= "<td>". $u->convertfrommysqldate(htmlentities($date)) ."</td>";
                    $htmlcontrol .= "<td>". htmlentities($type) ."</td>";
                    $htmlcontrol .= "<td>". htmlentities($class) ."</td>";
                    $htmlcontrol .= "<td>". htmlentities($observations);
                    $htmlcontrol .= "<input type='hidden' name='id_test' value='". htmlentities($id_test) . "'>";
                    $htmlcontrol .= "</td>";
                    $htmlcontrol .= "</tr>";
                  }
                }
                else
                  $htmlcontrol .= "No results to show";
              }
              $returnvalue = $htmlcontrol;
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
    }

    return $returnvalue;

  }

  function delete_test($id_test, $dummy)
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
        // TODO: DELETE
        // $mysqli=new mysqli("127.0.0.1", "root", "", $DBCreds->DBSchema, "3306");

        if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_test . " (?)"))
        {

         if($stmt->bind_param("i", $id_test))
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

      $result = $mysqli->error;
    }

    return $result;
  }

}

?>
