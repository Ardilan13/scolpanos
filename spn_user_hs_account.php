<?php

class spn_user_hs_account
{
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $sp_create_user_account = "sp_create_user_account";
  public $sp_get_users_accounts = "sp_get_users_accounts";
  public $sp_get_user_hs_detail_account = "sp_get_user_hs_detail_account";
  public $sp_read_user_account = "sp_read_user_account";
  // public $sp_update_user_account = "sp_update_user_account";
  public $sp_update_klas_vak_user_hs_account = "sp_update_klas_vak_user_hs_account";
  public $sp_delete_klas_and_vack_hs_account = "sp_delete_klas_and_vack_hs_account";
  public $sp_add_klas_vak_user_hs_account = "sp_add_klas_vak_user_hs_account";
  public $debug = true;
  public $error = "";
  public $errormessage = "";


  public $hashmethod = "sha512";	/* Hashing algorithm */

  function HashPassword($password, $salt = "")
  {
    /* 	This function hashes the password
    Function expects plaintext password and salt */

    $returnhash = null;

    try
    {
      //check if salt is empty
      if(empty($salt))
      {
        $returnhash = hash($this->hashmethod, $password);
      }
      else
      {
        $returnhash = hash($this->hashmethod, $salt . $password);
      }

    }
    catch (Exception $e)
    {
      /* throw an exception */
      $this->error = true;
      $returnhash="";
      return $returnhash;
    }

    return $returnhash;
  }

  function CreateSalt()
  {
    /* Create random salt */
    return base64_encode(random_bytes(20));
  }
  function create_user_account($userguid, $Email, $_password, $_salt, $FirstName, $LastName, $AuthRetriesLeft, $LockedOut, $ChangePwdOnLogin, $PasswordResetToken, $AccountEnabled, $UserRights, $SchoolID, $Class, $LastPwdChangeDateTime, $PasswordResetTokenExpiration,$dummy)
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
      if ($dummy == 1)
      $result = 1;
      else
      {
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        $sp_create_user_account = "sp_create_user_account";
        if($stmt=$mysqli->prepare("INSERT IGNORE INTO `app_useraccounts` (`UserGUID`,`Email`,`Passwd`,`Salt`,`FirstName`,`LastName`,`AuthRetriesLeft`,`LockedOut`,`ChangePwdOnLogin`,`PasswordResetToken`,`AccountEnabled`,`UserRights`,`SchoolID`,`Class`,`CreatedDateTime`,`LastPwdChangeDateTime`,`PasswordResetTokenExpiration`)
                                  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),?,?);"))
        {

          if($stmt->bind_param("ssssssiiisisisss", $userguid, $Email, $_password, $_salt, $FirstName, $LastName, $AuthRetriesLeft, $LockedOut, $ChangePwdOnLogin, $PasswordResetToken, $AccountEnabled, $UserRights, $SchoolID, $Class, $LastPwdChangeDateTime, $PasswordResetTokenExpiration))
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
              echo $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }

          }
          else
          {
            $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }

        }
        else
        {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }

    }
    catch(Exception $e)
    {
      $result = -2;
      echo $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }
  function update_user_hs_account_rights($userGuid, $user_rights, $dummy)
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

        if($stmt=$mysqli->prepare("CALL sp_update_user_hs_account_rights (?,?,?)"))
        {

          if($stmt->bind_param("ssi",$userGuid, $user_rights,$_SESSION["SchoolID"]))
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
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }

  function update_klas_vak_user_hs_account($user_access_id, $vak_id, $klas, $is_tutor, $dummy)
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

        if($stmt=$mysqli->prepare("CALL " . $this->sp_update_klas_vak_user_hs_account . " (?,?,?,?)"))
        {

          if($stmt->bind_param("iiss",$user_access_id, $vak_id, $klas, $is_tutor))
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

    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }

  function read_user_account($_UserGUID, $dummy)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $dummy = 0;
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


      if($select = $mysqli->prepare("CALL sp_read_user_account (?)"))
      {
        if($select->bind_param("s",$_UserGUID))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($UserGUID, $Email, $FirstName, $LastName, $UserRights, $SchoolID, $Class);

            $select->store_result();
            if($select->num_rows > 0 )
            {
              while($select->fetch())
              {
                $htmlcontrol .= "<span>".htmlentities($FirstName) ." ".htmlentities($LastName) ."</span>";
              }
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

    return $returnvalue;


  }
  function delete_klas_and_vack_hs_account($user_access_id,$dummy)
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

        if ($_SESSION['UserGUID'] != $user_access_id){
          // print('diferente');
          if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_klas_and_vack_hs_account . " (?)"))
          {
            if($stmt->bind_param("s", $user_access_id))
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
      echo $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }
  function list_users_accounts($baseurl, $SchoolID,$dummy)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $dummy = 0;
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


      if($select = $mysqli->prepare("CALL ".$this->sp_get_users_accounts." (?)"))
      {
        if($select->bind_param("i",$SchoolID))
        {
          if($select->execute())
          {
            // Audit by Caribe Developers
            // require_once ("spn_audit.php");
            // $spn_audit = new spn_audit();
            // $UserGUID = $_SESSION['UserGUID'];
            // $spn_audit->create_audit($UserGUID, 'app_useraccount','List Users Accounts',appconfig::GetDummy());


            $this->error = false;
            $result = 1;

            $select->bind_result($_UserGUID, $Email, $FirstName, $LastName, $UserRights, $SchoolID, $Class);

            $select->store_result();
            if($select->num_rows > 0)
            {  /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */
              $htmlcontrol .= "<table id=\"tbl_list_users_accounts_hs\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
              $htmlcontrol .= "<thead><tr><th>Email</th><th>First Name</th><th>Last Name</th><th>User Rights</th><th>Details</th></tr></thead>";
              $htmlcontrol .= "<tbody>";

              while($select->fetch())
              {

                $htmlcontrol .= "<tr>";
                // $htmlcontrol .= "<td></td>";
                $htmlcontrol .= "<td>";
                $htmlcontrol .= "<input type='hidden' name='GUID' value='". $_UserGUID ."'>";
                $htmlcontrol .= htmlentities($Email) ."</td>";
                $htmlcontrol .= "<td>". htmlentities($FirstName) ."</td>";
                $htmlcontrol .= "<td>". htmlentities($LastName) ."</td>";
                $htmlcontrol .= "<td>". htmlentities($UserRights) ."</td>";
                // $htmlcontrol .= "<td>". htmlentities($SchoolID) ."</td>";
                // $htmlcontrol .= "<td>". htmlentities($Class) ."</td>";
                $htmlcontrol .= "<td><a href=\"$baseurl/user_hs_detail.php" . "?userGUID=" . htmlentities($_UserGUID) . "\" class=\"link quaternary-color\">MEER <i class=\"fa fa-angle-double-right quaternary-color\"></i></a>";
                $htmlcontrol .= "</tr>";
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

    return $returnvalue;


  }
  function detail_user_account($baseurl, $UserGUID,$dummy)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $dummy = 0;
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


      if($select = $mysqli->prepare("CALL ".$this->sp_get_user_hs_detail_account." (?)"))
      {
        if($select->bind_param("s",$UserGUID))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($id, $klas, $vak_id, $is_tutor, $vak_name);

            $select->store_result();
            if($select->num_rows > 0)
            {  /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */
              $htmlcontrol .= "<table id=\"tbl_detail_user_hs_acount\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
              $htmlcontrol .= "<thead><tr><th>Klas</th><th>Vak</th><th>Tutor</th></tr></thead>";
              $htmlcontrol .= "<tbody>";

              while($select->fetch())
              {

                $htmlcontrol .= "<tr>";
                $htmlcontrol .= "<td><input type='hidden' name='user_access_id' value='". $id ."'>".htmlentities($klas) ."</td>";
                $htmlcontrol .= "<td><input type='hidden' name='vak_id' value='". $vak_id ."'>".htmlentities($vak_name) ."</td>";
                $htmlcontrol .= "<td>". htmlentities($is_tutor) ."</td>";
                $htmlcontrol .= "</tr>";
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
    return $returnvalue;
  }
  function add_klas_and_vack_hs_account($UserGUID, $vak_id, $klas, $is_tutor, $dummy)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $dummy = 0;
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
      if($stmt = $mysqli->prepare("CALL ".$this->sp_add_klas_vak_user_hs_account." (?,?,?,?,?,?)"))
      {
        if($stmt->bind_param("sissss",$UserGUID, $vak_id, $klas, $is_tutor, $_SESSION["SchoolID"], $_SESSION["SchoolJaar"]))
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
            echo $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }

        }
        else
        {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }

      }
      else
      {
        $result = 0;
        echo $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      echo $this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }
  function check_mentor_in_klas($klas, $UserGUID, $type_check, $dummy)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $dummy = 0;
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

      if ($type_check == "Klas"){
        $sp = "sp_check_mentor_in_klas";
      }
      else{
        $sp = "sp_check_mentor";
      }
      if($select = $mysqli->prepare("CALL ".$sp." (?,?,?,?)"))
      {
        if($select->bind_param("siss",$klas, $_SESSION["SchoolID"], $_SESSION["SchoolJaar"], $UserGUID ))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($count);

            $select->store_result();


            if($select->num_rows > 0 )
            {
              while($select->fetch())
              {
                $htmlcontrol = $count;
              }
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
    return $returnvalue;
  }

  function check_mentor_in_klas_and_vak($klas, $UserGUID, $vakid, $dummy)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $dummy = 0;
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

      if($select = $mysqli->prepare("CALL sp_check_mentor_in_klas_and_vak (?,?,?,?,?)"))
      {
        if($select->bind_param("sissi",$klas, $_SESSION["SchoolID"], $_SESSION["SchoolJaar"], $UserGUID, $vakid ))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($count);

            $select->store_result();
            if($select->num_rows > 0 )
            {
              while($select->fetch())
              {
                $htmlcontrol .= $count;
              }
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
    return $returnvalue;
  }

  function check_is_docent_vak($klas, $UserGUID, $vakid, $dummy)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $dummy = 0;
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

      if($select = $mysqli->prepare("CALL sp_check_is_docent_vak (?,?,?,?,?)"))
      {
        if($select->bind_param("sissi",$klas, $_SESSION["SchoolID"], $_SESSION["SchoolJaar"], $UserGUID, $vakid ))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($count);

            $select->store_result();
            if($select->num_rows > 0 )
            {
              while($select->fetch())
              {
                $htmlcontrol .= $count;
              }
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
    return $returnvalue;
  }

}
