<?php


class spn_setting_mobile
{

  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";

  public $sp_create_setting= "sp_create_setting";
  public $sp_read_setting= "sp_read_setting";
  public $sp_update_setting= "sp_update_setting";
  public $sp_delete_setting= "sp_delete_setting";
  public $sp_check_setting = "sp_check_setting";

  public $_setting_id;
  public $_setting_school_name;
  public $_setting_school_jaar;
  public $_setting_rapnumber_1;
  public $_setting_begin_rap_1;
  public $_setting_end_rap_1;
  public $_setting_rapnumber_2;
  public $_setting_begin_rap_2;
  public $_setting_end_rap_2;
  public $_setting_rapnumber_3;
  public $_setting_begin_rap_3;
  public $_setting_end_rap_3;
  public $_setting_mj;
  public $_setting_sort;


  public $debug = true;
  public $error = "";
  public $errormessage = "";

  function create_setting(
  $setting_school_id,
  $setting_school_jaar,
  $setting_rapnumber_1,
  $setting_rapnumber_2,
  $setting_rapnumber_3,
  $setting_begin_rap_1,
  $setting_end_rap_1,
  $setting_begin_rap_2,
  $setting_end_rap_2,
  $setting_begin_rap_3,
  $setting_end_rap_3,
  $setting_mj,
  $setting_sort,
  $UserGUID,
  $dummy)
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


        if($stmt=$mysqli->prepare("CALL " . $this->sp_create_setting. " (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))
        {

          if($stmt->bind_param("isiiissssssiss",
          $setting_school_id,
          $setting_school_jaar,
          $setting_rapnumber_1,
          $setting_rapnumber_2,
          $setting_rapnumber_3,
          $setting_begin_rap_1,
          $setting_end_rap_1,
          $setting_begin_rap_2,
          $setting_end_rap_2,
          $setting_begin_rap_3,
          $setting_end_rap_3,
          $setting_mj,
          $setting_sort,
	        $UserGUID

          ))
          {
            if($stmt->execute())
            {

              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'setting','create setting',appconfig::GetDummy());

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

  function delete_setting($id_setting, $dummy)
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


        if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_setting. " (?)"))
        {

          if($stmt->bind_param("i", $id_setting))
          {
            if($stmt->execute())
            {
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'setting','delete setting',appconfig::GetDummy());
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

  function get_setting($school_id,$dummy=null)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol="";
    $indexvaluetoshow = 3;

    $result = "";
    if ($dummy){
      $result = 1;
    }else{

      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select =$mysqli->prepare("CALL " . $this->sp_read_setting ." (?)"))
        {
          // $class = '';
          if ($select->bind_param("i", $school_id))
          {
            if($select->execute())
            {
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'setting','read setting',appconfig::GetDummy());
              $this->error = false;
              // $result=1;
              $select->bind_result(
                                  $setting_id,
                                  $setting_school_name,
                                  $setting_school_jaar,
                                  $setting_rapnumber_1,
                                  $setting_rapnumber_2,
                                  $setting_rapnumber_3,
                                  $setting_begin_rap_1,
                                  $setting_end_rap_1,
                                  $setting_begin_rap_2,
                                  $setting_end_rap_2,
                                  $setting_begin_rap_3,
                                  $setting_end_rap_3,
                                  $setting_mj,
                                  $setting_sort);
              $select->store_result();

              if($select->num_rows > 0)
              {
                $htmlcontrol .= "<table id=\"dataRequest-setting-detail\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
                $htmlcontrol .= "<thead><tr><th>School Jaar</th><th>Rapnr 1</th><th>Br1</th><th>Er1</th><th>Rapnr 2</th><th>Br2</th><th>Er2</th><th>Rapnr 3</th><th>Br3</th><th>Er3</th><th>Mj</th><th>Sort</th></tr></thead>";
                $htmlcontrol .= "<tbody>";

                while($select->fetch())
                {
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td><input type='hidden' id='id_setting' name='id_setting' value='". htmlentities($setting_id) ."'/>". htmlentities($setting_school_jaar) ."</td>";

                  $htmlcontrol .= "<td>". (htmlentities($setting_rapnumber_1)? "Active" : "Inactive") ."</td>" ;
                  $htmlcontrol .= "<td>". htmlentities($setting_begin_rap_1) ."</td>" ;
                  $htmlcontrol .= "<td>". htmlentities($setting_end_rap_1) ."</td>" ;
                  $htmlcontrol .= "<td>". (htmlentities($setting_rapnumber_2)? "Active" : "Inactive") ."</td>" ;
                  $htmlcontrol .= "<td>". htmlentities($setting_begin_rap_2) ."</td>" ;
                  $htmlcontrol .= "<td>". htmlentities($setting_end_rap_2) ."</td>" ;
                  $htmlcontrol .= "<td>". (htmlentities($setting_rapnumber_3)? "Active" : "Inactive") ."</td>" ;
                  $htmlcontrol .= "<td>". htmlentities($setting_begin_rap_3) ."</td>" ;
                  $htmlcontrol .= "<td>". htmlentities($setting_end_rap_3) ."</td>" ;
        				  $htmlcontrol .= "<td>". (htmlentities($setting_mj)? "Active" : "Inactive") ."</td>" ;
        				  $htmlcontrol .= "<td>". htmlentities($setting_sort) ."</td>" ;


                  $htmlcontrol .= "</td>";

                  $htmlcontrol .= "</tr>";
				}

                  $htmlcontrol .= "</tbody>";
                  $htmlcontrol .= "</table>";

                  //$htmlcontrol.= "<table id=\"dataRequest-setting-detail2\" class=\"table table-bordered table-colored\" data-table=\"yes\"><thead><tr><th>Student</th><th>P1</th><th>P2</th><th>P3</th></tr></thead><tbody><tr><td>Rudy Croes</td><td> Level: 9 <br>Promoted: 1 Mistakes: Sin faltas Time length: 1 hora Observation: Sin observacion</td><td> Level: 8 Promoted: 1 Mistakes: 1 falta  Time length: 1 hora Observation: Una pregunta al profesor</td><td> Level: 7 Promoted: 1 Mistakes: 2 faltas Time length: 1 hora Observation: Dos preguntas, una ida al bano</td></tr></tbody></table>";

              }else{
                $htmlcontrol .= "No results to show";
              }

            }else{
              $result = 0;
              $result =  $mysqli->error;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

            }

          }else{
            $result = 0;
            $result =  $mysqli->error;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

          }

        }else{
          $result = 0;
          $result =  $mysqli->error;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;

          $htmlcontrol = $mysqli->error;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $result = $e->getMessage();
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }
      return $result . " ". $htmlcontrol;
    }
  }

  function update_setting(
  $setting_id,
  $setting_school_jaar,
  $setting_rapnumber_1,
  $setting_begin_rap_1,
  $setting_end_rap_1,
  $setting_rapnumber_2,
  $setting_begin_rap_2,
  $setting_end_rap_2,
  $setting_rapnumber_3,
  $setting_begin_rap_3,
  $setting_end_rap_3,
  $setting_mj,
  $setting_sort,
  $UserGUID,
  $dummy)
  {

    $result = 0;
    if ($dummy)
      $result = 1;
    else
    {

      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);


      try
      {
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if($select =$mysqli->prepare("CALL " . $this->sp_update_setting . " (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))
        {
          if ($select->bind_param("isississississ",
                                    $setting_id,
                                    $setting_school_jaar,
                                    $setting_rapnumber_1,
                                    $setting_begin_rap_1,
                                    $setting_end_rap_1,
                                    $setting_rapnumber_2,
                                    $setting_begin_rap_2,
                                    $setting_end_rap_2,
                                    $setting_rapnumber_3,
                                    $setting_begin_rap_3,
                                    $setting_end_rap_3,
                                    $setting_mj,
                                    $setting_sort,
                                    $UserGUID
                                  ))
          {
            if($select->execute())
            {
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'setting','update setting',appconfig::GetDummy());
              /* Need to check for errors on database side for primary key errors etc.*/
              $result = 1;
              $select->close();
              $mysqli->close();
            }
            else
            {
              $result = 0;
              $result = $mysqli->error;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $result = $mysqli->error;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $result = $mysqli->error;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
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

  function  getsetting_info($school_id,$dummy=null)
  {
    $returnvalue = "";
    $sql_query = "";
    $response = 0;
    $indexvaluetoshow = 3;

    $result = 0;
    if ($dummy){
      $result = 1;
    }else{

      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select =$mysqli->prepare("CALL " . $this->sp_read_setting ." (?)"))
        {
          if ($select->bind_param("i", $school_id))
          {
            if($select->execute())
            {
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();


              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'setting','read setting',appconfig::GetDummy());
              $this->error = false;
              $result=1;
              $select->bind_result(
              $setting_school_name,
              $setting_id,
              $setting_school_jaar,
              $setting_rapnumber_1,
              $setting_rapnumber_2,
              $setting_rapnumber_3,
              $setting_begin_rap_1,
              $setting_end_rap_1,
              $setting_begin_rap_2,
              $setting_end_rap_2,
              $setting_begin_rap_3,
              $setting_end_rap_3,
              $setting_mj,
              $setting_sort);
              $select->store_result();

              if($select->num_rows > 0)
              {
                while($select->fetch())
                {
                   $this->_setting_id = $setting_id;
                   $this->_setting_school_name = $setting_school_jaar;
                   $this->_setting_school_jaar = $setting_school_jaar;
                   $this->_setting_rapnumber_1 = $setting_rapnumber_1;
                   $this->_setting_begin_rap_1 = $setting_begin_rap_1;
                   $this->_setting_end_rap_1 = $setting_end_rap_1;;
                   $this->_setting_rapnumber_2 = $setting_rapnumber_2;
                   $this->_setting_begin_rap_2 = $setting_begin_rap_2;
                   $this->_setting_end_rap_2 = $setting_end_rap_2;
                   $this->_setting_rapnumber_3 = $setting_rapnumber_3;
                   $this->_setting_begin_rap_3 = $setting_begin_rap_3;
                   $this->_setting_end_rap_3 = $setting_end_rap_3;
                   $this->_setting_mj = $setting_mj;
                   $this->_setting_sort = $setting_sort;
                }
              }else{
                  $this->_setting_id = 0;
                  $this->_setting_school_name = "";
                  $this->_setting_school_jaar = "";
                  $this->_setting_rapnumber_1 = 0;
                  $this->_setting_begin_rap_1 = "";
                  $this->_setting_end_rap_1 = "";;
                  $this->_setting_rapnumber_2 = 0;
                  $this->_setting_begin_rap_2 = "";
                  $this->_setting_end_rap_2 = "";
                  $this->_setting_rapnumber_3 = 0;
                  $this->_setting_begin_rap_3 = "";
                  $this->_setting_end_rap_3 = "";
                  $this->_setting_mj = "";
                  $this->_setting_sort = "";
              }

            }else{
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

            }

          }else{
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

          }

        }else{
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;

          $htmlcontrol = $mysqli->error;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }
      return $response;
    }
  }
  function check_setting($schoolID, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol="";

    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort,$dummy);
        $sp_check_setting = "sp_check_setting";

        if($stmt=$mysqli->prepare("CALL " . $this->$sp_check_setting . "(?)"))
        {
          if ($stmt->bind_param("i", $schoolID))
          {
            if($stmt->execute())
            {
              // Audit by Caribe Developers
              // require_once ('../document_start.php');
              // require_once ("spn_audit.php");
              // $spn_audit = new spn_audit();
              // $UserGUID = $_SESSION['UserGUID'];
              // $spn_audit->create_audit($UserGUID, 'docent','list',appconfig::GetDummy());
              $this->error = false;
              $result=1;
              $stmt->bind_result($checksetting);
              $stmt->store_result();

              if($stmt->num_rows > 0)

              {
                while($stmt->fetch())
                {
                  $htmlcontrol .= "". htmlentities($checksetting) ."";
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
  }


}

?>
