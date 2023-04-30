<?php


class spn_dashboard
{
  public $tablename_dashboard = "";
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";


  public $aantal_studenten = "";
  public $aantal_telaat = "";
  public $aantal_absentie = "";
  public $aantal_huiswerk = "";
  public $aantal_uitgestuurd = "";

  public $skoa_dash_topxtelaat = "";
  public $skoa_dash_topxabsentie = "";
  public $skoa_dash_inschrijvingen = "";

  public $last_insert_id = "";

  public $debug = false;
  public $error = "";
  public $errormessage = "";



  function dash_aantalstudenten($schooljaar, $schoolid_in,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";

    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      $sql_query = "select count(*) from students where status = 1  and schoolid=? AND (class = '1A'
                                                                                    		OR class = '1B'
                                                                                    		OR class = '1C'
                                                                                    		OR class = '2A'
                                                                                    		OR class = '2B'
                                                                                    		OR class = '2C'
                                                                                    		OR class = '2A'
                                                                                    		OR class = '2B'
                                                                                    		OR class = '3C'
                                                                                    		OR class = '3A'
                                                                                    		OR class = '3B'
                                                                                    		OR class = '3C'
                                                                                    		OR class = '4A'
                                                                                    		OR class = '4B'
                                                                                    		OR class = '4C'
                                                                                    		OR class = '5A'
                                                                                    		OR class = '5B'
                                                                                    		OR class = '5C'
                                                                                    		OR class = '6A'
                                                                                    		OR class = '6B'
                                                                                    		OR class = '6C') ;";
    }
    else
    {
      $sql_query = "select count(*) from students where status = 1  and schoolid=? and class= ? ";
    }

    require_once("spn_utils.php");
    $utils = new spn_utils();

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {

        if($klas_in == "ALL")
        {
            if($select->bind_param("i",$schoolid_in))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($_aantal_studenten);
            $select->store_result();

             if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $this->aantal_studenten = $_aantal_studenten;

                    if($this->debug)
                    {
                      print "total students: " . $_aantal_studenten . "<br />";
                    }

                  }
                }
                else
                {

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
            if($select->bind_param("is",$schoolid_in,$klas_in))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($_aantal_studenten);
            $select->store_result();

             if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $this->aantal_studenten = $_aantal_studenten;

                    if($this->debug)
                    {
                      print "total students: " . $_aantal_studenten . "<br />";
                    }

                  }
                }
                else
                {

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

      $returnvalue = $this->aantal_studenten;
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

    $returnvalue = 0;
    $this->aantal_studenten = 0;
    return $returnvalue;
  }

  function dash_absentie($schooljaar, $schoolid_in,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";

    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = CURDATE() and v.absentie = 1 and s.schoolid=? and v.schooljaar = ? AND (class = '1A'
                                                                                                                                                            		OR class = '1B'
                                                                                                                                                            		OR class = '1C'
                                                                                                                                                            		OR class = '2A'
                                                                                                                                                            		OR class = '2B'
                                                                                                                                                            		OR class = '2C'
                                                                                                                                                            		OR class = '2A'
                                                                                                                                                            		OR class = '2B'
                                                                                                                                                            		OR class = '3C'
                                                                                                                                                            		OR class = '3A'
                                                                                                                                                            		OR class = '3B'
                                                                                                                                                            		OR class = '3C'
                                                                                                                                                            		OR class = '4A'
                                                                                                                                                            		OR class = '4B'
                                                                                                                                                            		OR class = '4C'
                                                                                                                                                            		OR class = '5A'
                                                                                                                                                            		OR class = '5B'
                                                                                                                                                            		OR class = '5C'
                                                                                                                                                            		OR class = '6A'
                                                                                                                                                            		OR class = '6B'
                                                                                                                                                            		OR class = '6C') ;";
    }
    else
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = CURDATE() and v.absentie = 1 and s.class = ? and s.schoolid=? and v.schooljaar = ? ;";
    }

    require_once("spn_utils.php");
    $utils = new spn_utils();

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($klas_in == "ALL")
        {
            if($select->bind_param("is",$schoolid_in,$schooljaar))
            {
              if($select->execute())
              {
                $this->error = false;
                $result=1;

                $select->bind_result($_aantal_absentie);
                $select->store_result();

                if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $this->aantal_absentie = $_aantal_absentie;

                    if($this->debug)
                    {
                      print "absentie count: " . $_aantal_absentie . "<br />";
                    }

                  }
                }
                else
                {

                }

            //$select->close();
            //$mysqli->close();

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
            if($select->bind_param("sis",$klas_in,$schoolid_in,$schooljaar))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($_aantal_absentie);
            $select->store_result();


            while($select->fetch())
              {
                $this->aantal_absentie= $_aantal_absentie;

                if($this->debug)
                    {
                      print "absentie count: " . $_aantal_absentie . "<br />";
                    }
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

      $returnvalue = $this->aantal_absentie;
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

    $returnvalue = 0;
    $this->aantal_absentie = 0;
    return $returnvalue;
  }

  function dash_telaat($schooljaar,$schoolid_in,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";

    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = CURDATE() and v.telaat = 1 and s.schoolid=? and v.schooljaar = ? AND (class = '1A'
                                                                                                                                                            		OR class = '1B'
                                                                                                                                                            		OR class = '1C'
                                                                                                                                                            		OR class = '2A'
                                                                                                                                                            		OR class = '2B'
                                                                                                                                                            		OR class = '2C'
                                                                                                                                                            		OR class = '2A'
                                                                                                                                                            		OR class = '2B'
                                                                                                                                                            		OR class = '3C'
                                                                                                                                                            		OR class = '3A'
                                                                                                                                                            		OR class = '3B'
                                                                                                                                                            		OR class = '3C'
                                                                                                                                                            		OR class = '4A'
                                                                                                                                                            		OR class = '4B'
                                                                                                                                                            		OR class = '4C'
                                                                                                                                                            		OR class = '5A'
                                                                                                                                                            		OR class = '5B'
                                                                                                                                                            		OR class = '5C'
                                                                                                                                                            		OR class = '6A'
                                                                                                                                                            		OR class = '6B'
                                                                                                                                                            		OR class = '6C') ;";
    }
    else
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = CURDATE() and v.telaat = 1 and s.class = ? and s.schoolid=? and v.schooljaar = ?;";
    }

    require_once("spn_utils.php");
    $utils = new spn_utils();

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($klas_in == "ALL")
        {
            if($select->bind_param("is",$schoolid_in, $schooljaar))
            {
              if($select->execute())
              {
                $this->error = false;
                $result=1;

                $select->bind_result($_aantal_telaat);
                $select->store_result();

                if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $this->aantal_telaat = $_aantal_telaat;

                    if($this->debug)
                    {
                      print "telaat count: " . $_aantal_telaat . "<br />";
                    }

                  }
                }
                else
                {

                }

            //$select->close();
            //$mysqli->close();

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
            if($select->bind_param("sis",$klas_in,$schoolid_in,$schooljaar))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($_aantal_telaat);
            $select->store_result();


            while($select->fetch())
              {
                $this->aantal_telaat= $_aantal_telaat;

                if($this->debug)
                    {
                      print "telaat count: " . $_aantal_telaat . "<br />";
                    }
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

      $returnvalue = $this->aantal_telaat;
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

    $returnvalue = 0;
    $this->aantal_telaat = 0;
    return $returnvalue;
  }

  function dash_uitgestuurd($schooljaar,$schoolid_in,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";

    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = CURDATE() and v.uitsturen = 1 and s.schoolid=? and v.schooljaar = ? AND (class = '1A'
                                                                                                                                                            		OR class = '1B'
                                                                                                                                                            		OR class = '1C'
                                                                                                                                                            		OR class = '2A'
                                                                                                                                                            		OR class = '2B'
                                                                                                                                                            		OR class = '2C'
                                                                                                                                                            		OR class = '2A'
                                                                                                                                                            		OR class = '2B'
                                                                                                                                                            		OR class = '3C'
                                                                                                                                                            		OR class = '3A'
                                                                                                                                                            		OR class = '3B'
                                                                                                                                                            		OR class = '3C'
                                                                                                                                                            		OR class = '4A'
                                                                                                                                                            		OR class = '4B'
                                                                                                                                                            		OR class = '4C'
                                                                                                                                                            		OR class = '5A'
                                                                                                                                                            		OR class = '5B'
                                                                                                                                                            		OR class = '5C'
                                                                                                                                                            		OR class = '6A'
                                                                                                                                                            		OR class = '6B'
                                                                                                                                                            		OR class = '6C') ;";
    }
    else
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = CURDATE() and v.uitsturen = 1 and s.class = ? and s.schoolid=? and v.schooljaar = ? ;";
    }

    require_once("spn_utils.php");
    $utils = new spn_utils();

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($klas_in == "ALL")
        {
            if($select->bind_param("is",$schoolid_in, $schooljaar ))
            {
              if($select->execute())
              {
                $this->error = false;
                $result=1;

                $select->bind_result($_aantal_uitgestuurd);
                $select->store_result();

                if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $this->aantal_uitgestuurd = $_aantal_uitgestuurd;

                    if($this->debug)
                    {
                      print "uitgestuurd count: " . $_aantal_uitgestuurd . "<br />";
                    }

                  }
                }
                else
                {

                }

            //$select->close();
            //$mysqli->close();

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
            if($select->bind_param("sis",$klas_in,$schoolid_in,$schooljaar))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($_aantal_uitgestuurd);
            $select->store_result();


            while($select->fetch())
              {
                $this->aantal_uitgestuurd= $_aantal_uitgestuurd;

                if($this->debug)
                    {
                      print "uitgestuurd count: " . $_aantal_uitgestuurd . "<br />";
                    }
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

      $returnvalue = $this->aantal_uitgestuurd;
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

    $returnvalue = 0;
    $this->aantal_uitgestuurd = 0;
    return $returnvalue;
  }

  function dash_geenhuiswerk($schooljaar, $schoolid_in,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";

    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = CURDATE() and v.huiswerk = 1 and s.schoolid=? and v.schooljaar = ? AND (class = '1A'
                                                                                                                                                            		OR class = '1B'
                                                                                                                                                            		OR class = '1C'
                                                                                                                                                            		OR class = '2A'
                                                                                                                                                            		OR class = '2B'
                                                                                                                                                            		OR class = '2C'
                                                                                                                                                            		OR class = '2A'
                                                                                                                                                            		OR class = '2B'
                                                                                                                                                            		OR class = '3C'
                                                                                                                                                            		OR class = '3A'
                                                                                                                                                            		OR class = '3B'
                                                                                                                                                            		OR class = '3C'
                                                                                                                                                            		OR class = '4A'
                                                                                                                                                            		OR class = '4B'
                                                                                                                                                            		OR class = '4C'
                                                                                                                                                            		OR class = '5A'
                                                                                                                                                            		OR class = '5B'
                                                                                                                                                            		OR class = '5C'
                                                                                                                                                            		OR class = '6A'
                                                                                                                                                            		OR class = '6B'
                                                                                                                                                            		OR class = '6C') ;";
    }
    else
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = CURDATE() and v.huiswerk = 1 and s.class = ? and s.schoolid=? and v.schooljaar = ? ;";
    }

    require_once("spn_utils.php");
    $utils = new spn_utils();

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($klas_in == "ALL")
        {
            if($select->bind_param("is",$schoolid_in,$schooljaar))
            {
              if($select->execute())
              {
                $this->error = false;
                $result=1;

                $select->bind_result($_aantal_huiswerk);
                $select->store_result();

                if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $this->aantal_huiswerk = $_aantal_huiswerk;

                    if($this->debug)
                    {
                      print "huiswerk count: " . $_aantal_huiswerk . "<br />";
                    }

                  }
                }
                else
                {

                }

            //$select->close();
            //$mysqli->close();

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
            if($select->bind_param("sis",$klas_in,$schoolid_in,$schooljaar))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($_aantal_huiswerk);
            $select->store_result();


            while($select->fetch())
              {
                $this->aantal_huiswerk= $_aantal_huiswerk;

                if($this->debug)
                    {
                      print "huiswerk count: " . $_aantal_huiswerk . "<br />";
                    }
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

      $returnvalue = $this->aantal_huiswerk;
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

    $returnvalue = 0;
    $this->aantal_huiswerk = 0;
    return $returnvalue;
  }

   function dash_cijfers($schoolid_in,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";

    $return_array = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      $sql_query = "select avg(c.gemiddelde) , c.klas, c.rapnummer, v.schoolid from le_cijfers as c  , le_vakken as v where c.vak = v.id and v.SchoolID =? group by  c.klas ,c.rapnummer order by c.klas, c.rapnummer ";
    }
    else
    {
      $sql_query = "select avg(c.gemiddelde) , c.klas, c.rapnummer, v.schoolid from le_cijfers as c  , le_vakken as v where c.vak = v.id and c.klas = ? and v.SchoolID =? group by  c.klas ,c.rapnummer order by c.klas, c.rapnummer ";
    }

    require_once("spn_utils.php");
    $utils = new spn_utils();

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($klas_in == "ALL")
        {
            if($select->bind_param("i",$schoolid_in))
            {
              if($select->execute())
              {
                $this->error = false;
                $result=1;

                $select->bind_result($gemiddelde,$klas,$rapnummer,$schoolid);
                $select->store_result();

                if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $_gemiddelde = "";
                    if(is_numeric($gemiddelde))
                    {
                      $_gemiddelde = $gemiddelde;
                    }
                    else if(is_null($gemiddelde))
                    {
                      $_gemiddelde = "0";
                    }
                    else
                    {
                      $_gemiddelde = $gemiddelde;
                    }

                    $return_array[] = array("SchoolID" => $schoolid, "Gemiddelde" => $_gemiddelde,"Klas" => $klas, "RapNummer" => $rapnummer);

                    if($this->debug)
                    {
                      print "array row: " . $schoolid . ", " . $_gemiddelde . ", " . $klas . ", " . $rapnummer;
                    }

                  }
                }
                else
                {

                }

            //$select->close();
            //$mysqli->close();

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
            if($select->bind_param("sis",$klas_in,$schoolid_in,$schooljaar))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($gemiddelde,$klas,$rapnummer,$schoolid);
            $select->store_result();


            if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $_gemiddelde = "";
                    if(is_numeric($gemiddelde))
                    {
                      $_gemiddelde = $gemiddelde;
                    }
                    else if(is_null($gemiddelde))
                    {
                      $_gemiddelde = "0";
                    }
                    else
                    {
                      $_gemiddelde = $gemiddelde;
                    }

                    $return_array[] = array("SchoolID" => $schoolid, "Gemiddelde" => $_gemiddelde,"Klas" => $klas, "RapNummer" => $rapnummer);

                    if($this->debug)
                    {
                      print "array row: " . $schoolid . ", " . $_gemiddelde . ", " . $klas . ", " . $rapnummer;
                    }

                  }
                }
                else
                {

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

      $returnvalue = $return_array;
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

    $returnvalue = 0;
    return $returnvalue;
  }

  function dash_klassenboek($schooljaar, $schoolid,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    require_once("spn_utils.php");
    $utils = new spn_utils();

    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d");

    $_datum_in = $_DateTime;

    //$_datum_in = null;

    // if($utils->converttomysqldate($datum_in) != false)
    // {
    //   $_datum_in = $utils->converttomysqldate($datum_in);
    // }
    // else
    // {
    //   $_datum_in = null;
    // }

    if ($_SESSION['SchoolType']==2){
      if($klas_in == "ALL")
      {
        $sql_query = "select s.id as studentid, v.id, s.class, s.lastname, s.firstname, s.sex,v.telaat,v.absentie,v.toetsinhalen, v.uitsturen, v.huiswerk, v.lp, v.opmerking
        from students s left join le_verzuim v on s.id = v.studentid
        where s.schoolid = ? and v.datum = ?  and v.schooljaar = ? and studentid
        NOT in(select studentid from le_verzuim where telaat = 0 and absentie = 0 and lp = 0 and toetsinhalen = 0 and uitsturen = 0 and huiswerk = 0 and minutes is null) order by s.class , firstname";

      }
      else
      {
        $sql_query = "select s.id as studentid, v.id, s.class, s.lastname, s.firstname, s.sex,v.telaat,v.absentie,v.toetsinhalen, v.uitsturen, v.huiswerk, v.lp, v.opmerking
        from students s left join le_verzuim v on s.id = v.studentid
        where s.class = ? and s.schoolid = ? and v.datum = ?  and v.schooljaar = ? and studentid
        NOT in(select studentid from le_verzuim where telaat = 0 and absentie = 0 and lp = 0 and toetsinhalen = 0 and uitsturen = 0 and huiswerk = 0 and minutes is null) order by s.class , firstname";
      }


    }
    else{
      if($klas_in == "ALL")
      {
        $sql_query = "select s.id as studentid, v.id, s.class, s.firstname, s.lastname, s.sex,v.telaat,v.absentie,v.toetsinhalen, v.uitsturen, v.huiswerk, v.lp, v.opmerking
        from students s left join le_verzuim v on s.id = v.studentid
        where s.schoolid = ? and v.datum = ?  and v.schooljaar = ? and studentid
        NOT in(select studentid from le_verzuim where telaat = 0 and absentie = 0 and lp = 0 and toetsinhalen = 0 and uitsturen = 0 and huiswerk = 0 and minutes is null) order by s.class , firstname";

      }
      else
      {
        $sql_query = "select s.id as studentid, v.id, s.class, s.firstname, s.lastname, s.sex,v.telaat,v.absentie,v.toetsinhalen, v.uitsturen, v.huiswerk, v.lp, v.opmerking
        from students s left join le_verzuim v on s.id = v.studentid
        where s.class = ? and s.schoolid = ? and v.datum = ?  and v.schooljaar = ? and studentid
        NOT in(select studentid from le_verzuim where telaat = 0 and absentie = 0 and lp = 0 and toetsinhalen = 0 and uitsturen = 0 and huiswerk = 0 and minutes is null) order by s.class , firstname";
      }
    }




    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if($select=$mysqli->prepare($sql_query)){
        if($klas_in == "ALL")
        {
          if($select->bind_param("iss",$schoolid,$_datum_in,$schooljaar)) {
            if($select->execute())
            {
              $this->error = false;
              $result=1;

              if ($_SESSION['SchoolType']==2){
                $select->bind_result($studentid,$verzuimid,$klas,$lastname, $firstname,$sex,$telaat,$absent,$toetsinhalen,$uitsturen,$huiswerk,$lp,$opmerking);
              }
              else{
                $select->bind_result($studentid,$verzuimid,$klas,$firstname,$lastname,$sex,$telaat,$absent,$toetsinhalen,$uitsturen,$huiswerk,$lp,$opmerking);

              }
              $select->store_result();

              if($select->num_rows > 0)
              {
                $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-bordered table-colored\" data-table=\"no\">";
                $htmlcontrol .= "<thead>";
                $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID</th><th class=\"btn-m-w\">Naam</th><th>Klas</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>".($_SESSION['SchoolType'] == 1 ? 'Naar huis': 'Spijbelen')."</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th></tr>";
                $htmlcontrol .= "</thead>";
                $htmlcontrol .= "<tbody>";

                /* initialize counter variable, this variable is used to display student count number */
                $x = 1;


                $xx = 1;

                  //print $select->num_rows;

                while($select->fetch())
                {
                  if ($_SESSION['SchoolType']==2){
                    $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$klas_in\"  data-datum=\"$_datum_in\"><td>$x</td><td>" . $lastname . ', ' . $firstname . "</td>";
                  }
                  else{
                    $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$klas_in\"  data-datum=\"$_datum_in\"><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";

                  }
                  $htmlcontrol .= "<td>$klas</td>";
                  $htmlcontrol .= "<td>" . ($telaat == 1 ? "<i class=\"fa fa-check\"></i>": "") . "</td>";
                  $htmlcontrol .= "<td>". ($absent == 1 ? "<i class=\"fa fa-check\"></i>": "") ."</td>";
                  $htmlcontrol .= "<td>". ($toetsinhalen == 1 ? "<i class=\"fa fa-check\"></i>": "") ."</td>";
                  $htmlcontrol .= "<td>". ($uitsturen == 1 ? "<i class=\"fa fa-check\"></i>": "") ."</td>";

                  $htmlcontrol .= "<td>";
                  switch($lp)
                  {
                    case 0:
                    $htmlcontrol .= "";
                    break;

                    case 1:
                    $htmlcontrol .= "Z1";
                    break;

                    case 2:
                    $htmlcontrol .= "Z2";
                    break;

                    case 3:
                    $htmlcontrol .= "V1";
                    break;

                    case 4:
                    $htmlcontrol .= "V2";
                    break;

                    case 5:
                    $htmlcontrol .= "V3";
                    break;

                    case 6:
                    $htmlcontrol .= "V4";
                    break;

                    case 7:
                    $htmlcontrol .= "D";
                    break;

                    case 8:
                    $htmlcontrol .= "P1";
                    break;

                    case 9:
                    $htmlcontrol .= "P2";
                    break;

                  }
                  $htmlcontrol .= "</td>";
                  $htmlcontrol .="<td>". ($huiswerk == 1 ? "<i class=\"fa fa-check\"></i>": "") ."</td>";
                  $htmlcontrol .="<td><span name =\"opmerking\" id=\"lblopmerking$xx \" class=\"editable lblopmerking\">".$opmerking."</span></td>";
                  $htmlcontrol .+ "</tr>";

                  /* increment variable with one */
                  $x++;

                  $xx++;

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
          if($select->bind_param("siss",$klas_in,$schoolid,$_datum_in,$schooljaar)) {
            if($select->execute())
            {
              $this->error = false;
              $result=1;

              $select->bind_result($studentid,$verzuimid,$klas,$firstname,$lastname,$sex,$telaat,$absent,$toetsinhalen,$uitsturen,$huiswerk,$lp,$opmerking);
              $select->store_result();

              if($select->num_rows > 0)
              {
                $htmlcontrol .= "<table id=\"dataRequest-verzuim\" class=\"table table-bordered table-colored\" data-table=\"no\">";
                $htmlcontrol .= "<thead>";
                $htmlcontrol .= "<tr class=\"text-align-center\"> <th>ID</th><th class=\"btn-m-w\">Naam</th><th>Te laat</th><th>Absent</th><th>Toets inhalen</th><th>".($_SESSION['SchoolType'] == 1 ? 'Naar huis': 'Spijbelen')."</th><th>LP</th><th>Geen huiswerk</th><th>Opmerking</th></tr>";
                $htmlcontrol .= "</thead>";
                $htmlcontrol .= "<tbody>";

                /* initialize counter variable, this variable is used to display student count number */
                $x = 1;


                $xx = 1;

                  //print $select->num_rows;

                while($select->fetch())
                {
                  $htmlcontrol .= "<tr data-student-id=\"$studentid\" data-klas=\"$klas_in\"  data-datum=\"$_datum_in\"><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";
                  $htmlcontrol .= "<td>" . ($telaat == 1 ? "<i class=\"fa fa-check\"></i>": "") . "</td>";
                  $htmlcontrol .= "<td>". ($absent == 1 ? "<i class=\"fa fa-check\"></i>": "") ."</td>";
                  $htmlcontrol .= "<td>". ($toetsinhalen == 1 ? "<i class=\"fa fa-check\"></i>": "") ."</td>";
                  $htmlcontrol .= "<td>". ($uitsturen == 1 ? "<i class=\"fa fa-check\"></i>": "") ."</td>";

                  $htmlcontrol .= "<td>";
                  switch($lp)
                  {
                    case 0:
                    $htmlcontrol .= "";
                    break;

                    case 1:
                    $htmlcontrol .= "Z1";
                    break;

                    case 2:
                    $htmlcontrol .= "Z2";
                    break;

                    case 3:
                    $htmlcontrol .= "V1";
                    break;

                    case 4:
                    $htmlcontrol .= "V2";
                    break;

                    case 5:
                    $htmlcontrol .= "V3";
                    break;

                    case 6:
                    $htmlcontrol .= "V4";
                    break;

                    case 7:
                    $htmlcontrol .= "D";
                    break;

                    case 8:
                    $htmlcontrol .= "P1";
                    break;

                    case 9:
                    $htmlcontrol .= "P2";
                    break;

                  }
                  $htmlcontrol .= "</td>";
                  $htmlcontrol .="<td>". ($huiswerk == 1 ? "<i class=\"fa fa-check\"></i>": "") ."</td>";
                  $htmlcontrol .="<td><span name =\"opmerking\" id=\"lblopmerking$xx \" class=\"editable lblopmerking\">".$opmerking."</span></td>";
                  $htmlcontrol .+ "</tr>";
                  /* increment variable with one */
                  $x++;

                  $xx++;

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

      $returnvalue = $htmlcontrol;
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

    $returnvalue = $htmlcontrol;
    return $returnvalue;
  }

  function dash_skoa_topxabsentie($_topxvalue,$dayorweek = false)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $return_array = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

      //use php date
      $sql_query = "select sh.schoolname, count(v.id) as count from le_verzuim v left join students s on v.studentid = s.id left join schools sh on s.schoolid = sh.id where v.datum = CURDATE() and v.absentie = 1 order by count desc limit ?";

    require_once("spn_utils.php");
    $utils = new spn_utils();

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        /*  */
        if(true)
        {
            if($select->bind_param("i",$_topxvalue))
            {
              if($select->execute())
              {
                $this->error = false;
                $result=1;

                $select->bind_result($_schoolname, $_count);
                $select->store_result();

                 if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $_count_final = "";
                    if(is_numeric($_count))
                    {
                      $_count_final = $_count;
                    }
                    else if(is_null($_count))
                    {
                      $_count_final = "0";
                    }
                    else if($_count < 0)
                    {
                      $_count_final = "0";
                    }
                    else
                    {
                      $_count_final = $_count;
                    }

                    $return_array[] = array("SchoolName" => $_schoolname, "Count" => $_count_final, "Category" => $_schoolname);

                    if($this->debug)
                    {
                      print "array row: " . $_schoolname . ", " . $_count_final;
                    }

                  }
                }
                else
                {

                }

            //$select->close();
            //$mysqli->close();

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
            if($select->bind_param("i",$_topxvalue))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($_schoolname, $_count);
            $select->store_result();


            if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $_count_final = "";
                    if(is_numeric($_count))
                    {
                      $_count_final = $_count;
                    }
                    else if(is_null($_count))
                    {
                      $_count_final = "0";
                    }
                    else if($_count < 0)
                    {
                      $_count_final = "0";
                    }
                    else
                    {
                      $_count_final = $_count;
                    }

                    $return_array[] = array("SchoolName" => $_schoolname, "Count" => $_count_final, "Category" => $_schoolname);

                    if($this->debug)
                    {
                      print "array row: " . $schoolid . ", " . $_gemiddelde . ", " . $klas . ", " . $rapnummer;
                    }

                  }
                }
                else
                {

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

      $returnvalue = $return_array;
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

    $returnvalue = 0;
    return $returnvalue;
  }

  function dash_skoa_topxtelaat($_topxvalue,$dayorweek = false)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $return_array = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

      //use php date
      $sql_query = "select sh.schoolname, count(v.id) as count from le_verzuim v left join students s on v.studentid = s.id left join schools sh on s.schoolid = sh.id where v.datum = CURDATE() and v.telaat = 1 order by count desc limit ?";

    require_once("spn_utils.php");
    $utils = new spn_utils();

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        /*  */
        if(true)
        {
            if($select->bind_param("i",$_topxvalue))
            {
              if($select->execute())
              {
                $this->error = false;
                $result=1;

                $select->bind_result($_schoolname, $_count);
                $select->store_result();

                 if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $_count_final = "";
                    if(is_numeric($_count))
                    {
                      $_count_final = $_count;
                    }
                    else if(is_null($_count))
                    {
                      $_count_final = "0";
                    }
                    else if($_count < 0)
                    {
                      $_count_final = "0";
                    }
                    else
                    {
                      $_count_final = $_count;
                    }

                    //$return_array[] = array("SchoolName" => $_schoolname, "Count" => $_count_final, "Category" => $_schoolname);
                    $return_array[] = array("SchoolName" => $_schoolname, "Count" => $_count_final, "Category" => $_schoolname);

                    if($this->debug)
                    {
                      print "array row: " . $_schoolname . ", " . $_count_final;
                    }

                  }
                }
                else
                {

                }

            //$select->close();
            //$mysqli->close();

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
            if($select->bind_param("i",$_topxvalue))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($_schoolname, $_count);
            $select->store_result();


            if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $_count_final = "";
                    if(is_numeric($_count))
                    {
                      $_count_final = $_count;
                    }
                    else if(is_null($_count))
                    {
                      $_count_final = "0";
                    }
                    else if($_count < 0)
                    {
                      $_count_final = "0";
                    }
                    else
                    {
                      $_count_final = $_count;
                    }

                    $return_array[] = array("SchoolName" => $_schoolname, "Count" => $_count_final, "Category" => $_schoolname);

                    if($this->debug)
                    {
                      print "array row: " . $schoolid . ", " . $_gemiddelde . ", " . $klas . ", " . $rapnummer;
                    }

                  }
                }
                else
                {

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

      $returnvalue = $return_array;
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

    $returnvalue = 0;
    return $returnvalue;
  }



}

?>
