<?php

class spn_user_account
{
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $sp_create_user_account = "sp_create_user_account";
  public $sp_get_users_accounts = "sp_get_users_accounts";
  public $sp_read_user_account = "sp_read_user_account";
  public $sp_update_user_account = "sp_update_user_account";
  public $sp_delete_user_account = "sp_delete_user_account";
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
        // require_once("spn_utils.php");
        // $utils=new spn_utils();
        // $userguid=$utils->CreateGUID();
        // $_salt = $this->CreateSalt();
        // $_password = $this->HashPassword($Passwd,$_salt);

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        $sp_create_user_account = "sp_create_user_account";
        if($stmt=$mysqli->prepare("CALL " . $this->sp_create_user_account . " (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))
        {

          if($stmt->bind_param("ssssssiiisisisss", $userguid, $Email, $_password, $_salt, $FirstName, $LastName, $AuthRetriesLeft, $LockedOut, $ChangePwdOnLogin, $PasswordResetToken, $AccountEnabled, $UserRights, $SchoolID, $Class, $LastPwdChangeDateTime, $PasswordResetTokenExpiration))
          {
            if($stmt->execute())
            {
              // Audit by Caribe Developers
              // require_once ("../document_start.php");
              // require_once ("spn_audit.php");
              // $spn_audit = new spn_audit();
              // $UserGUID = $_SESSION['UserGUID'];
              // $spn_audit->create_audit($UserGUID, 'app_useraccount','create new userAccount',appconfig::GetDummy());
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

  function update_user_account($_UserGUID, $UserRights, $SchoolID, $Class, $FirstName, $LastName,$dummy)
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
        // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

        if($stmt=$mysqli->prepare("CALL " . $this->sp_update_user_account . " (?,?,?,?,?,?)"))
        {

          if($stmt->bind_param("ssisss",$_UserGUID, $UserRights, $SchoolID, $Class, $FirstName, $LastName))
          {
            if($stmt->execute())
            {
              // Audit by Caribe Developers
              // require_once ("document_start.php");
              // require_once ("spn_audit.php");
              // $spn_audit = new spn_audit();
              // $UserGUID = $_SESSION['UserGUID'];
              // $spn_audit->create_audit($UserGUID, 'app_useraccount','update User Account',appconfig::GetDummy());
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

  function read_user_account($_UserGUID,$dummy)
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

      if($select = $mysqli->prepare("CALL ".$this->sp_read_user_account." (?)"))
      {
        if($select->bind_param("i",$_UserGuid))
        {
          if($select->execute())
          {
            // Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'app_useraccount','Read User Account',appconfig::GetDummy());


            $this->error = false;
            $result = 1;

            $select->bind_result($_UserGUID);

            $select->store_result();
            if($select->num_rows > 0)
            {
              /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */
              $htmlcontrol .= "<table id=\"dataRequest-event\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

              $htmlcontrol .= "<thead><tr><th>Event Date</th><th>Due Date</th><th>Reason</th><th>Involverd</th><th>Observations</th></tr></thead>";
              $htmlcontrol .= "<tbody>";
              $u = new spn_utils();
              while($select->fetch())
              {
                $htmlcontrol .="<tr><td>". $u->convertfrommysqldate(htmlentities($event_date)) ."</td><td>". $u->convertfrommysqldate(htmlentities($due_date)) ."</td><td>". htmlentities($reason) ."</td><td>". htmlentities($involved) ."</td><td>". htmlentities($observations) ."</td><td hidden>". htmlentities($private) ."</td><td hidden>". htmlentities($important_notice) ."</td><td hidden>". htmlentities($id_user_account) ."</td><td hidden>". htmlentities($id_student) ."</td>";              // <td><a href=\"#\" class=\"accion link quaternary-color\"  title=\"delete_user_account\" name=\"$id_user_account\">Delete <i class=\"fa fa-angle-double-right quaternary-color\"></i></a></td></tr>";

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

  function delete_user_account($_UserGUID,$dummy)
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
        // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);

        if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_user_account . " (?)"))
        {

          if($stmt->bind_param("s", $_UserGUID))
          {
            if($stmt->execute())
            {
              // Audit by Caribe Developers
              // require_once ("document_start.php");
              // require_once ("spn_audit.php");
              // $spn_audit = new spn_audit();
              // $UserGUID = $_SESSION['UserGUID'];
              // $spn_audit->create_audit($UserGUID, 'app_useraccount','delete User Account',appconfig::GetDummy());
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
      echo $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }
  function list_users_accounts($SchoolID,$dummy)
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
            $htmlcontrol .= "<table id=\"tbl_list_users_accounts\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

            // $htmlcontrol .= "<thead><tr><th>Full Name</th><th>Email</th><th>Mobile Phone</th><th>Home Phone</th><th>Tutor</th><th>Details</th></tr></thead>";
            // $htmlcontrol .= "<thead><tr><th>UserGUID</th><th>Email</th><th>First Name</th><th>Last Name</th><th>User Rights</th><th>School ID</th><th>Class</th></tr></thead>";
            $htmlcontrol .= "<thead><tr><th>Email</th><th>First Name</th><th>Last Name</th><th>User Rights</th><th>Class</th></tr></thead>";
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
              $htmlcontrol .= "<td>". htmlentities($Class) ."</td>";
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

  function check_user_email($email, $dummy){
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $dummy = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();

    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($select = $mysqli->prepare("CALL sp_check_user (?)"))
      {
        if($select->bind_param("s", $email ))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($exist);

            $select->store_result();
            if($select->num_rows > 0 )
            {
              while($select->fetch())
              {
                $htmlcontrol = $exist;
              }
            }
            else
            {
              $htmlcontrol = 0;
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
