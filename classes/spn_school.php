<?php
require_once ("spn_audit.php");
require_once ("spn_setting.php");
class spn_school
{
  public $tablename_schools = "schools";
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $debug = true;
  public $sp_get_schools_names = "sp_get_schools_names";
  public $sp_list_schools_names = "sp_get_schools";


  function get_dashboard_by_school_group($SchoolAdmin,$dummy)
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

        if ($select =$mysqli->prepare("CALL " . $this->sp_get_schools_names ." (?)"))
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
                $school_index = 1;
                while($select->fetch())
                {

                  // begin seccion constructor by school
                  $htmlcontrol .= "<div class=\"row\">";
                  $htmlcontrol .= "<div class=\"col-md-12 full-inset\">";
                  $htmlcontrol .= "<div class=\"primary-bg-color brd-full\">";
                  $htmlcontrol .= "<div class=\"box\">";
                  $htmlcontrol .= "<div class=\"box-title full-inset brd-bottom\">";
                  $htmlcontrol .= "<div class=\"row\">";
                  $htmlcontrol .= "<h2 class=\"col-md-12 pull-left\">".htmlentities($schoolname)."";
                  // $htmlcontrol .= "<div class=\"input-group date col-md-3 pull-right\">";
                  // $htmlcontrol .= "<input type=\"text\" id=\"datum_".htmlentities($school_index)."\" name=\"datum\" class=\"form-control input-sm calendar\">";
                  // $htmlcontrol .= "<input type=\"hidden\" id=\"date_charts".htmlentities($school_index)."\" value=\"".htmlentities($SchoolID)."\">";
                  // $htmlcontrol .= "<span class=\"input-group-addon\">";
                  // $htmlcontrol .= "<i class=\"fa fa-calendar\"></i>";
                  // $htmlcontrol .= "</span>";
                  // $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</h2>";
                  // $htmlcontrol .= "<button id=\"btn_charts_by_date_".htmlentities($school_index)."\" data-display=\"data-display\" data-ajax-href=\"ajax/getverzuim_tabel.php\" type=\"submit\" class=\"btn btn-primary btn-m-w btn-m-h col-md-1\">zoeken</button>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<div class=\"box\">";
                  $htmlcontrol .= "<div class=\"col-md-12 full-inset\">";
                  $htmlcontrol .= "<div class=\"row mrg-bottom\">";
                  $htmlcontrol .= "<div class=\"col-md-3\">";
                  $htmlcontrol .= "<div class=\"databox primary-bg-color full-inset\">";
                  $htmlcontrol .= "<div class=\"demo-section k-content wide\">";
                  $htmlcontrol .= "<div id=\"chart_absent_".htmlentities($school_index)."\"></div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<div class=\"col-md-3\">";
                  $htmlcontrol .= "<div class=\"databox primary-bg-color full-inset\">";
                  $htmlcontrol .= "<div class=\"demo-section k-content wide\">";
                  $htmlcontrol .= "<div id=\"chart_laat_".htmlentities($school_index)."\"></div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<div class=\"col-md-3\">";
                  $htmlcontrol .= "<div class=\"databox primary-bg-color full-inset\">";
                  $htmlcontrol .= "<div class=\"demo-section k-content wide\">";
                  $htmlcontrol .= "<div id=\"chart_uitgestuurd_".htmlentities($school_index)."\"></div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<div class=\"col-md-3\">";
                  $htmlcontrol .= "<div class=\"databox primary-bg-color full-inset\">";
                  $htmlcontrol .= "<div class=\"demo-section k-content wide\">";
                  $htmlcontrol .= "<div id=\"chart_huiswerk_".htmlentities($school_index)."\"></div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<div class=\"row mrg-bottom\">";
                  $htmlcontrol .= "<div class=\"col-md-12\">";
                  $htmlcontrol .= "<div class=\"primary-bg-color brd-full\">";
                  $htmlcontrol .= "<div class=\"box\">";
                  $htmlcontrol .= "<div class=\"box-title full-inset brd-bottom clearfix\">";
                  $htmlcontrol .= "<h2 class=\"col-md-12\">Cijfers: ".htmlentities($schoolname)."</h2>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<div class=\"primary-bg-color brd-full\">";
                  $htmlcontrol .= "<div class=\"box telerik-plugin\">";
                  $htmlcontrol .= "<div class=\"box-content full-inset default-secondary-bg-color equal-height\">";
                  $htmlcontrol .= "<div class=\"demo-section k-content wide\">";
                  $htmlcontrol .= "<div id=\"graph_three_periods_".htmlentities($school_index)."\"></div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<input type=\"hidden\" id=\"series_chart_laat_".htmlentities($school_index)."\" value=\"".htmlentities($SchoolID)."\">";
                  $htmlcontrol .= "<input type=\"hidden\" id=\"series_chart_absent_".htmlentities($school_index)."\" value=\"".htmlentities($SchoolID)."\">";
                  $htmlcontrol .= "<input type=\"hidden\" id=\"series_chart_uitgestuurd_".htmlentities($school_index)."\" value=\"".htmlentities($SchoolID)."\">";
                  $htmlcontrol .= "<input type=\"hidden\" id=\"series_chart_huiswerk_".htmlentities($school_index)."\" value=\"".htmlentities($SchoolID)."\">";


                  $school_index++;
                }
                $htmlcontrol .= "<input type=\"hidden\" name=\"school_group_count\" id=\"school_group_count\" value=\"".htmlentities($school_index)."\">";

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




  function dash_aantalstudenten($schoolid_in,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";

    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      $sql_query = "select count(*) from students where status = 1  and schoolid=? ";
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
                    //  print "total students: " . $_aantal_studenten . "<br />";
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

  function dash_absentie_school_group($date_school_group, $schoolid_in,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";

    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = ? and v.absentie = 1 and s.schoolid=?;";
    }
    else
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = ? and v.absentie = 1 and s.class = ? and s.schoolid=?;";
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
          if($select->bind_param("si",$date_school_group, $schoolid_in))
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
                    //  print "absentie count: " . $_aantal_absentie . "<br />";
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
          if($select->bind_param("ssi",$date_school_group, $klas_in,$schoolid_in))
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
                  //  print "absentie count: " . $_aantal_absentie . "<br />";
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

  function dash_telaat_school_group($date_school_group, $schoolid_in,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";

    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum =? and v.telaat = 1 and s.schoolid=?;";
    }
    else
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = ? and v.telaat = 1 and s.class = ? and s.schoolid=?;";
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
          if($select->bind_param("si",$date_school_group, $schoolid_in))
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
                    // print "telaat count: " . $_aantal_telaat . "<br />";
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
          if($select->bind_param("ssi",$date_school_group, $klas_in,$schoolid_in))
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

  function dash_uitgestuurd_school_group($date_school_group,$schoolid_in,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";

    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = ? and v.uitsturen = 1 and s.schoolid=?;";
    }
    else
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = ? and v.uitsturen = 1 and s.class = ? and s.schoolid=?;";
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
          if($select->bind_param("si",$date_school_group, $schoolid_in))
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
                    // print "uitgestuurd count: " . $_aantal_uitgestuurd . "<br />";
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
          if($select->bind_param("ssi",$date_school_group, $klas_in,$schoolid_in))
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
                  // print "uitgestuurd count: " . $_aantal_uitgestuurd . "<br />";
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

  function dash_geenhuiswerk_school_group($date_school_group,$schoolid_in,$klas_in)
  {
    $returnvalue = 0;
    $user_permission="";
    $sql_query = "";

    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = ? and v.huiswerk = 1 and s.schoolid=?;";
    }
    else
    {
      //use php date
      $sql_query = "select count(*) from le_verzuim v left join students s on v.studentid = s.id where v.datum = ? and v.huiswerk = 1 and s.class = ? and s.schoolid=?;";
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
          if($select->bind_param("si",$date_school_group, $schoolid_in))
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
                    // print "huiswerk count: " . $_aantal_huiswerk . "<br />";
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
          if($select->bind_param("ssi",$date_school_group, $klas_in,$schoolid_in))
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
                //  print "huiswerk count: " . $_aantal_huiswerk . "<br />";
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

  function dash_cijfers_school_group($schoolid_in,$klas_in)
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
          if($select->bind_param("si",$klas_in,$schoolid_in))
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

  //begin changes from Janio Acero - CaribeDevelopers - List All Schools function
  function list_schools()
  {
    $htmlcontrol ="";
    $returnvalue = "";
    $sql_query = "";
    $json_result ="";
    $indexvaluetoshow = 3;
    $inloop = false;
    $result = 0;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select =$mysqli->prepare("CALL " . $this->sp_list_schools_names))
      {        
        if($select->execute())
        {
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
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
      $result = $e->getMessage();
    }
    return $htmlcontrol;
  }
  //End changes from Janio Acero - CaribeDevelopers
} // End Class spn_school
