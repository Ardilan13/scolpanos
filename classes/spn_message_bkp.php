<?php


class spn_message
{
  public $tablename_message = "message";
  public $tablename_message_transaction = "message_transaction";
  public $tablename_app_useraccounts = "app_useraccounts";
  public $sp_send_messages = "sp_send_messages";
  public $sp_get_messages = "sp_get_messages";
  public $sp_get_message_detail = "sp_get_message_detail";
  public $sp_get_recipients_messages = "sp_get_recipients_messages";
  public $sp_read_message = "sp_read_message";
  public $sp_get_users = "sp_get_users";
  public $fn_get_num_messages = "fn_get_count_message";
  public $fn_get_num_notifications = "fn_get_count_notification";
  public $message_read = 1;
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $debug = true;
  public $error = "";
  public $errormessage = "";


  function sendmessage($type_message_m, $type_subject, $type_sender_m, $from_m, $subject_m, $message_m, $to_user_mt, $to_student_mt, $count_to_appuser, $schoolID)
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
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($stmt=$mysqli->prepare("CALL sp_send_messages (?,?,?,?,?,?,?,?,?)"))
      {
      	if($stmt->bind_param("ssssssssi", $type_message_m, $type_subject, $type_sender_m, $from_m, $subject_m, $message_m, $to_user_mt, $to_student_mt, $count_to_appuser))
        {
         if($stmt->execute())
         {
           $result = 1;
           $stmt->close();
           $mysqli->close();
           //Audit by Caribe Developers
           require_once ("spn_audit.php");
           $spn_audit = new spn_audit();
           $spn_audit->create_audit($_SESSION['UserGUID'], 'Message','Send Message',false);
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

  function sendnotification($type_message_m, $type_subject, $type_sender_m, $from_m, $subject_m, $message_m, $option_selected, $class, $schoolID)
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
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($stmt=$mysqli->prepare("CALL sp_send_notification (?,?,?,?,?,?,?,?,?)"))
      {
        if($stmt->bind_param("ssssssssi", $type_message_m, $type_subject, $type_sender_m, $from_m, $subject_m, $message_m, $option_selected, $class, $schoolID))
        {
         if($stmt->execute())
         {
           $result = 1;
           $stmt->close();
           $mysqli->close();
           //Audit by Caribe Developers
           require_once ("spn_audit.php");
           $spn_audit = new spn_audit();
           $spn_audit->create_audit($_SESSION['UserGUID'], 'Message','Send Notification',false);
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
    //return $mysqli->error;

  }

  function listmessages($userGUID, $studentid, $type)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select = $mysqli->prepare("CALL ". $this->sp_get_messages ." (?,?,?)"))
      {
        if($select->bind_param("sis",$userGUID, $studentid, $type))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($id, $from, $subject, $subject_type, $message, $date, $date_formated,$message_status);
            $select->store_result();
            //Audit by Caribe Developers
            // require_once ("spn_audit.php");
            // $spn_audit = new spn_audit();
            // $spn_audit->create_audit($_SESSION['UserGUID'], 'Message','List Messages',false);

            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<table id=\"dataRequest-". ($type == "M"? "message" : "notification") ."\" class=\"table table-notification\" data-table=\"yes\">";

              $htmlcontrol .= "<tbody>";

              $i = 0;
              $vars_colors = array("blue", "green","grey","red");

              while($select->fetch())
              {
                $htmlcontrol .= "<tr>";
                if ($type == "M")
                {
                  $htmlcontrol .= "<td ". ($message_status == 0 ? "class=\"info\"": "") ."><h4><span class='". $vars_colors[$i] ."'>". htmlentities(strtoupper(substr($from,0,1))) ."</span></h4></td". ($message_status == 0 ? "class=\"info\"": "") ."><td ". ($message_status == 0 ? "class=\"info\"": "") .">". htmlentities($from) ."</td><td ". ($message_status == 0 ? "class=\"info\"": "") ."><b>". htmlentities($subject) ."</b><br>";
                  $htmlcontrol .= "<td ". ($message_status == 0 ? "class=\"info\"": "") .">";
                  $htmlcontrol .= "<div class='lv-title'><b>". htmlentities(strtoupper(substr($subject_type,0,1))) . htmlentities(strtolower(substr($subject_type,1,strlen($subject_type)-1))) ."</b></div>";
                }
                else
                {

                  $htmlcontrol .= "<td ". ($message_status == 0 ? "class=\"info\"": "") .">";
                  $htmlcontrol .= "<div class='lv-title'><b>". htmlentities(strtoupper(substr($subject_type,0,1))) . htmlentities(strtolower(substr($subject_type,1,strlen($subject_type)-1)));
                  $htmlcontrol .= " - (". htmlentities(strtoupper(substr($subject,0,1))) . htmlentities(strtolower(substr($subject,1,strlen($subject)-1))) .")</b></div>";
                }


                $htmlcontrol .= "<span class='lv-small'>". htmlentities($message) ."</span>";
                $htmlcontrol .= "</td>";

                $htmlcontrol .= "<td ". ($message_status == 0 ? "class=\"info\"": "") .">";
                $htmlcontrol .= "<span class='time'>". htmlentities($date_formated) ."</span>";
                $htmlcontrol .= "<input type='hidden' id='id_message' name='id_message' value='". htmlentities($id) . "'>";
                $htmlcontrol .= "<input type='hidden' id='message_status' name='message_status' value='". $message_status . "'>";
                $htmlcontrol .= "</td>";

                $htmlcontrol .= "</tr>";

                $i = ($i < 3 ? $i + 1 : 0);
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

  function getmessagedetail($id_message, $userGUID, $student_id, $message_status)
  {
    $returnvalue = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();


      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select = $mysqli->prepare("CALL ". $this->sp_get_message_detail ." (?)"))
      {
        if($select->bind_param("i",$id_message))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($message, $message_date, $message_subject, $message_from, $UserGUID_message);
            $select->store_result();

            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Message','Get Message Details',false);


            if($select->num_rows > 0)
            {

              while($select->fetch())
              {
                $htmlcontrol .= "<div class=\"box-title full-inset brd-bottom primary-bg-color\"><h5>". htmlentities($message_subject) ."</h5></div>";
                $htmlcontrol .= "<table id=\"dataRequest-message-detail\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
                $htmlcontrol .= "<tbody>";
                $htmlcontrol .= "<tr><td><b>". $this->getrecipentsmessage($id_message) ."</b></td><td><b>". htmlentities($message_date) ."</b></td></tr>";
                $htmlcontrol .= "<tr><td colspan=\"2\">".  htmlentities($message) ."<br><br><br><br><br></td></tr>";
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";

              if ($message_status != $this->message_read)
                $this->readmessage($id_message, $userGUID, $student_id);

            }
            else
              $htmlcontrol .= "No results to show";

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

          $htmlcontrol .= $mysqli->error;
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

  function getrecipentsmessage($id_message)
  {
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select = $mysqli->prepare("CALL ". $this->sp_get_recipients_messages ." (?)"))
      {
        if($select->bind_param("i", $id_message))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;
            $select->bind_result($recipent_name);
            $select->store_result();
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Message','Get Recipient Message',false);

           if($select->num_rows > 0)
           {
              // $htmlcontrol .= "<ul>";
              while($select->fetch())
                $htmlcontrol .= htmlentities($recipent_name) .", ";
                // $htmlcontrol .= "<li>". htmlentities($recipent_name) ."</li>";
              // $htmlcontrol .= "</ul>";
           }
           else
             $htmlcontrol .= "No results to show";
          }
          else
          {
           /* error executing query */
           $this->error = true;
           $this->errormessage = $mysqli->error;
           $result=0;

           $htmlcontrol = $mysqli->error;

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
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
      $htmlcontrol = $mysqli->error;
    }

    return $htmlcontrol;
  }

  function readmessage($id_message, $userGUID, $student_id)
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
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($stmt=$mysqli->prepare("CALL ". $this->sp_read_message ."(?,?)"))
      {
        if($stmt->bind_param("is", $id_message, $userGUID))
        {
         if($stmt->execute())
         {
           $result = 1;
           $stmt->close();
           $mysqli->close();
           //Audit by Caribe Developers
           require_once ("spn_audit.php");
           $spn_audit = new spn_audit();
           $spn_audit->create_audit($_SESSION['UserGUID'], 'Message','Read Message',false);
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

  //  This function must be a function of class or spn_autentication spn_user
  function listusersreceivermessage($userGUID, $SchoolID)
  {
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select = $mysqli->prepare("CALL ". $this->sp_get_users ." (?, ?)"))
      {
        if($select->bind_param("si", $userGUID, $SchoolID))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;
            $select->bind_result($userGUID, $username);
            $select->store_result();
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Message','List Users Receiver Message',false);

           if($select->num_rows > 0)
           {
             $htmlcontrol .= "<select id=\"users\" name=\"users\" class=\"form-control\" >";
             $htmlcontrol .= "<option value='-1'>Select user(s)</option>";
            //  $htmlcontrol .= "<option value='0'>All</option>";
             while($select->fetch())
               $htmlcontrol .= "<option value=". htmlentities($userGUID) .">". htmlentities($username) ."</option>";
             $htmlcontrol .= "</select>";
           }
           else
             $htmlcontrol .= "No results to show";
          }
          else
          {
           /* error executing query */
           $this->error = true;
           $this->errormessage = $mysqli->error;
           $result=0;

           $htmlcontrol = $mysqli->error;

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
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
      $htmlcontrol = $mysqli->error;
    }

    return $htmlcontrol;

  }

  function getcountunreadmessages($userGUID, $studend_id, $type)
  {
    $sql_query = "";
    $unread_message="";
    $result = 0;
    $htmlcontrol= "";

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $notification_count = 0;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select = $mysqli->prepare("SELECT ". ($type == "M" ? $this->fn_get_num_messages : $this->fn_get_num_notifications) ." (?,?)"))
      {
        if($select->bind_param("si", $userGUID, $studend_id ))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($count);
            $select->store_result();
            //Audit by Caribe Developers
            // require_once ("spn_audit.php");
            // $spn_audit = new spn_audit();
            // $spn_audit->create_audit($_SESSION['UserGUID'], 'Message','Get Count Unread Message',false);

           if($select->num_rows > 0)
           {
             while($select->fetch())
              {

               if ($type == "N"){
                 $htmlcontrol  = "<a id='notification-trigger' href='#' data-toggle='dropdown' aria-expanded='false' data-get-url='notifications.php'>";
                 $htmlcontrol .= "<i class='fa fa-bell'></i>";
                 $htmlcontrol .= "<span class='quaternary-bg-color notification-numbers'>". $count ."</span>";
                 $htmlcontrol .= "<div class='dropdown-menu' aria-labelledby= 'user-profile'>";
                 $htmlcontrol .= "<div class='listview'>";
                 $htmlcontrol .= "<div class='lv-header'>";
                 $htmlcontrol .= "Notifications";
                 $htmlcontrol .= "</div>";
                 $htmlcontrol .= "<div class='lv-body dataRetrieverOnLoad' data-display='table_notifications_result' data-ajax-href='ajax/getnotifications_tabel.php'>";
                 $htmlcontrol .= "<div class='dataRequest-notification' id='table_notifications_result'></div>";
                 $htmlcontrol .= "</div>";
                 $htmlcontrol .= "<a href='notifications_create.php?m=0&status=0' class= 'lv-footer'>View All</a>";
                 $htmlcontrol .= "</div>";
                 $htmlcontrol .= "</div>";
               }
               else{
                 $htmlcontrol  = "<a id='message-trigger' href='#' data-toggle='dropdown' aria-expanded='false' data-get-url='inbox.php'>";
                 $htmlcontrol .= "<i class='fa fa-envelope'></i>";
                 $htmlcontrol .= "<span class='quaternary-bg-color notification-numbers'>". $count ."</span>";
                 $htmlcontrol .= "<div class='dropdown-menu' aria-labelledby= 'user-profile'>";
                 $htmlcontrol .= "<div class='listview'>";
                 $htmlcontrol .= "<div class='lv-header'>";
                 $htmlcontrol .= "Message";
                 $htmlcontrol .= "</div>";
                 $htmlcontrol .= "<div class='lv-body dataRetrieverOnLoad' data-display='table_notfications_messages_result' data-ajax-href='ajax/getinboxmessages_tabel.php'>";
                 $htmlcontrol .= "<div class='table_notfications_messages_result_' id='table_notfications_messages_result'></div>";
                 $htmlcontrol .= "</div>";
                 $htmlcontrol .= "<a href='inbox.php?m=0&status=0' class='lv-footer'>View All</a>";
                 $htmlcontrol .= "</div>";
                 $htmlcontrol .= "</div>";

               }
               $htmlcontrol .= "</a>";
              }
           }
          }
          else
          {
           /* error executing query */
           $this->error = true;
           $this->errormessage = $mysqli->error;
           $result=0;

           $htmlcontrol = $mysqli->error;

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
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
      $htmlcontrol = $mysqli->error;
    }

    return $htmlcontrol;
  }

}

?>
