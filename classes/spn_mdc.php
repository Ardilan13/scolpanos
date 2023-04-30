<?php








class spn_mdc


{





  public $exceptionvalue = "";


  public $mysqlierror = "";


  public $mysqlierrornumber = "";





  public $sp_create_mdc = "sp_create_mdc";


  public $sp_read_mdc = "sp_read_mdc";


  public $sp_update_mdc = "sp_update_mdc";


  public $sp_delete_mdc = "sp_delete_mdc";








  public $debug = true;


  public $error = "";


  public $errormessage = "";





  function create_mdc($school_year, $date, $reason, $class, $observations, $pending, $id_student, $dummy)


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





        if($stmt=$mysqli->prepare("CALL " . $this->sp_create_mdc . " (?,?,?,?, ?,?,?)"))


        {


         if($stmt->bind_param("sssssii", $school_year, $date, $reason, $class,$observations, $pending, $id_student))


         {


          if($stmt->execute())


          {


                $result = 1;


                $stmt->close();


                $mysqli->close();


                //Audit by Caribe Developers


                require_once ("spn_audit.php");


                $spn_audit = new spn_audit();


                $spn_audit->create_audit($_SESSION['UserGUID'], 'MDC','Create MDC',false);








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





  function update_mdc($mdc_id, $school_year, $date, $reason, $class, $observations, $pending, $dummy)


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





        if($stmt=$mysqli->prepare("CALL " . $this->sp_update_mdc . " (?,?,?,?,?,?,?)"))


        {





         if($stmt->bind_param("isssssi", $mdc_id, $school_year, $date, $reason, $class, $observations, $pending))


         {


          if($stmt->execute())


          {


                $result = 1;


                $stmt->close();


                $mysqli->close();


                //Audit by Caribe Developers


                require_once ("spn_audit.php");


                $spn_audit = new spn_audit();


                $spn_audit->create_audit($_SESSION['UserGUID'], 'MDC','Update MDC',false);


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





  function get_mdc($schooljaar,$id_mdc, $id_student, $dummy)


  {


    require_once("spn_utils.php");





    $sql_query = "";


    $htmlcontrol="";


    $result = 0;


    $returnvalue = "";





    if ($dummy)


    {


      $htmlcontrol .= "<table id=\"dataRequest-mdc" ."\" class=\"table table-bordered table-colored\" data-table=\"yes\">";


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


      //Audit by Caribe Developers


      require_once ("spn_audit.php");


      $spn_audit = new spn_audit();


      $spn_audit->create_audit($_SESSION['UserGUID'], 'MDC','Get MDC',false);





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




        $sql = "SELECT * FROM mdc m WHERE m.id_student = ?;";
        if($select = $mysqli->prepare($sql))


        {


          if($select->bind_param("i", $id_student))


          {


            if($select->execute())


            {


              $this->error = false;


              $result = 1;





              $select->bind_result($id_mdc, $school_year, $date, $reason, $id_class, $observations, $pending, $id_student);


              $select->store_result();


              //Audit by Caribe Developers


              require_once ("spn_audit.php");


              $spn_audit = new spn_audit();


              $spn_audit->create_audit($_SESSION['UserGUID'], 'MDC','Get MDC',false);





              if ($id_mdc == "" || $id_mdc == 0  || $id_mdc == "null")


              {


                // List all the MDC's


                if($select->num_rows > 0)


                {


                  $htmlcontrol .= "<table id=\"dataRequest-mdc" ."\" class=\"table table-bordered table-colored\" data-table=\"yes\">";


                  //$htmlcontrol .= "<thead><tr><th>School Year</th><th>Date</th><th>Reason</th><th>Class</th><th>Observations</th><th>Pending</th><th>Select</th></tr></thead>";


                  //$htmlcontrol .= "<thead><tr><th>School Year</th><th>Date</th><th>Reason</th><th>Class</th><th>Pending</th><th>Select</th></tr></thead>";


                  $htmlcontrol .= "<thead><tr><th>School Year</th><th>Date</th><th>Reason</th><th>Class</th><th>Pending</th></tr></thead>";


                  $htmlcontrol .= "<tbody>";





                  $u = new spn_utils();





                  while($select->fetch())


                  {


                      $htmlcontrol .= "<tr>";


                      $htmlcontrol .= "<td>". htmlentities($school_year) ."</td>";


                      $htmlcontrol .= "<td>". $u->convertfrommysqldate(htmlentities($date)) ."</td>";


                      $htmlcontrol .= "<td>". htmlentities($reason) ."</td>";


                      $htmlcontrol .= "<td>". htmlentities($id_class) ."</td>";


                      $htmlcontrol .= "<td>";


                      $htmlcontrol .= ($pending ? "Pending" : "No pending");


                      $htmlcontrol .= "<input type='hidden' name='observations_val' value='". htmlentities($observations) . "'>";


                      $htmlcontrol .= "<input type='hidden' name='id_mdc' value='". htmlentities($id_mdc) . "'>";


                      $htmlcontrol .= "</td>";


                      //$htmlcontrol .= "<td><a href='javascript:delete_mdc(". $id_mdc .")' src='assets/js/app_v1.1.js' id='delete_mdc_link'class='link quaternary-color'> Delete</a></td></td>";


                      $htmlcontrol .= "</tr>";





                  }





                  $htmlcontrol .= "</tbody>";


                  $htmlcontrol .= "</table>";


                }


                else


                {


                  $htmlcontrol .= "No results to show";


                }


                $returnvalue = $htmlcontrol;


              }


              else


              {


                // Obtain a MDC


                if($select->num_rows > 0)


                {


                  while($select->fetch())


                  {


                    $htmlcontrol .= "<tr>";


                    $htmlcontrol .= "<td>". htmlentities($school_year) ."</td>";


                    $htmlcontrol .= "<td>". htmlentities($date) ."</td>";


                    $htmlcontrol .= "<td>". htmlentities($reason) ."</td>";


                    $htmlcontrol .= "<td>". htmlentities($id_class) ."</td>";


                    $htmlcontrol .= "<td>". htmlentities($observations) ."</td>";


                    $htmlcontrol .= "<td>". htmlentities($pending) ."</td>";


                    $htmlcontrol .= "<td>". htmlentities($id_mdc) ."</td>";


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





  function delete_mdc($id_mdc, $dummy)


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





        if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_mdc . " (?)"))


        {





         if($stmt->bind_param("i", $id_mdc))


         {


          if($stmt->execute())


          {


                $result = 1;


                $stmt->close();


                $mysqli->close();


                //Audit by Caribe Developers


                require_once ("spn_audit.php");


                $spn_audit = new spn_audit();


                $spn_audit->create_audit($_SESSION['UserGUID'], 'MDC','Delete MDC',false);


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





}





?>


