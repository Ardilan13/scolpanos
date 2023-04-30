<?php


class leerling
{
  public $tablename_leerling = "students";
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";

  public $debug = false;
  public $error = "";
  public $errormessage = "";

  function createleerling($schoolid,$idnumber,$studentnumber,$class,$enrollmentdate,$firstname,$lastname,$sex,$dob,$birthplace,$address,$azvnumber,$azvexpiredate,$phone1,$phone2,$colorcode)
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

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema);

      if($stmt=$mysqli->prepare("insert into " . $this->tablename_leerling . "(created,schoolid,idnumber,studentnumber,class,enrollmentdate,firstname,lastname,sex,dob,birthplace,address,azvnumber,azvexpiredate,phone1,phone2,colorcode,status) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))            
      {                
       if($stmt->bind_param("sisssssssssssssssi",$_DateTime,$schoolid,$idnumber,$studentnumber,$class,$enrollmentdate,$firstname,$lastname,$sex,$dob,$birthplace,$address,$azvnumber,$azvexpiredate,$phone1,$phone2,$colorcode,$status))
       {
        if($stmt->execute())
        {
          /*
              Need to check for errors on database side for primary key errors etc.                        
          */

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


  function editleerling($studentid,$schoolid,$idnumber,$studentnumber,$enrollmentdate,$firstname,$lastname,$sex,$dob,$birthplace,$azvnumber,$azvexpiredate,$phone1,$phone2,$colorcode)
  {
    $result = 0;       

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 0;

    mysqli_report(MYSQLI_REPORT_STRICT);        

    try
    {

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema);

      if($stmt=$mysqli->prepare("update " . $this->tablename_leerling . " set updated = ?, schoolid = ?, idnumber = ?, studentnumber = ?, enrollmentdate = ?, firstname = ?, lastname = ?, sex = ?, dob = ?, birthplace = ?, azvnumber = ?, azvexpiredate = ?, phone1 = ?, phone2 = ?, colorcode = ?, status = ? where id = ?"))            
      {                
        if($stmt->bind_param("sisssssssssssssii",$_DateTime,$schoolid,$idnumber,$studentnumber,$enrollmentdate,$firstname,$lastname,$sex,$dob,$birthplace,$azvnumber,$azvexpiredate,$phone1,$phone2,$colorcode,$status,$studentid))
        {
          if($stmt->execute())
          {
          /*
              Need to check for errors on database side for primary key errors etc.                        
          */
              if($mysqli->affected_rows >= 1)
              {
                $result = 1;                                
              }                            
              $stmt->close();
              $mysqli->close();
            }
            else
            {
              $result = 0;    
              $this->mysqlierror = $mysqli->error;                        
            }

          }
          else
          {
           $result = 0;
           $this->mysqlierror = $mysqli->error;
         }

       }
       else
       {
         $result = 0;
         $this->mysqlierror = $mysqli->error;
       }


     }
     catch(Exception $e)
     {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }


    return $result;


  }

  function liststudents($schoolid_in,$klas_in,$baseurl,$detailpage)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($klas_in == "ALL")
    {
      $sql_query = "select id,studentnumber,firstname,lastname,sex,dob,class from students where schoolid=?";
    }
    else
    {
      $sql_query = "select id,studentnumber,firstname,lastname,sex,dob,class from students where class=? and schoolid=?";  
    }


    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema);

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

            $select->bind_result($studentid,$studentnumber,$voornamen,$achternaam,$geslacht,$geboortedatum,$klas);
            $select->store_result();

            if($select->num_rows > 0)
            {
              /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */
              $htmlcontrol .= "<table id=\"dataRequest-student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

              $htmlcontrol .= "<thead><tr><th>Student#</th><th>Voornamen</th><th>Achternaam</th><th>Geslacht</th><th>Geboortedatum</th></th><th>klas</th><th>Details</th></tr></thead>";
              $htmlcontrol .= "<tbody>";

              while($select->fetch())
              {


                $htmlcontrol .="<tr><td>". htmlentities($studentnumber) ."</td><td>". htmlentities($voornamen) ."</td><td>". htmlentities($achternaam) ."</td><td>". htmlentities($geslacht) ."</td><td>". htmlentities($geboortedatum) ."</td><td>". htmlentities($klas) ."</td><td><a href=\"$baseurl/$detailpage" . "?id=" . htmlentities($studentid) ."\" class=\"link quaternary-color\">MEER <i class=\"fa fa-angle-double-right quaternary-color\"></i></a></td></tr>";

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
            if($select->bind_param("si",$klas_in,$schoolid_in))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($studentid,$studentnumber,$voornamen,$achternaam,$geslacht,$geboortedatum,$klas);
            $select->store_result();

            if($select->num_rows > 0)
            {
              /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */
              $htmlcontrol .= "<table id=\"dataRequest-student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

              $htmlcontrol .= "<thead><tr><th>Student#</th><th>Voornamen</th><th>Achternaam</th><th>Geslacht</th><th>Geboortedatum</th></th><th>klas</th><th>Details</th></tr></thead>";
              $htmlcontrol .= "<tbody>";

              while($select->fetch())
              {


                $htmlcontrol .="<tr><td>". htmlentities($studentnumber) ."</td><td>". htmlentities($voornamen) ."</td><td>". htmlentities($achternaam) ."</td><td>". htmlentities($geslacht) ."</td><td>". htmlentities($geboortedatum) ."</td><td>". htmlentities($klas) ."</td><td><a href=\"$baseurl/$detailpage" . "?id=" . htmlentities($studentid) ."\" class=\"link quaternary-color\">MEER <i class=\"fa fa-angle-double-right quaternary-color\"></i></a></td></tr>";

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

    return $returnvalue;
  }

  function liststudentdetail($studentdbid)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $returnarr = array();

    mysqli_report(MYSQLI_REPORT_STRICT);



    $sql_query = "select id,studentnumber,firstname,lastname,sex,dob,class from students where id=?";



    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema);

      if($select=$mysqli->prepare($sql_query))
      {
        if($select->bind_param("i",$studentdbid))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->bind_result($studentid,$studentnumber,$voornamen,$achternaam,$geslacht,$geboortedatum,$klas);
            $select->store_result();

            if($select->num_rows > 0)
            {
              while($select->fetch())
              {
                $returnarr["results"] = 1;
                $returnarr["studentid"] = $studentid;
                $returnarr["studentnumber"] = $studentnumber;
                $returnarr["voornamen"] = $voornamen;
                $returnarr["achternaam"] = $achternaam;
                $returnarr["geslacht"] = $geslacht;
                $returnarr["geboortedatum"] = $geboortedatum;
                $returnarr["klas"] = $klas;
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