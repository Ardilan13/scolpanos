<?php


class spn_todo
{

  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";

  public $sp_create_todo_list = "sp_create_todo_list";
  public $sp_read_todo_list = "sp_read_todo_list";
  public $sp_update_todo_status = "sp_update_todo_status";
  public $sp_delete_todo_list = "sp_delete_todo_list";



  public $debug = true;
  public $error = "";
  public $errormessage = "";

  function create_todo($message, $userGUID, $dummy)
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

        if($stmt=$mysqli->prepare("CALL " . $this->sp_create_todo_list . " (?,?)"))
        {
         if($stmt->bind_param("ss", $message,$userGUID))
         {
          if($stmt->execute())
          {
                $result = 1;
                $stmt->close();
                $mysqli->close();
                //Audit by Caribe Developers
                require_once ("spn_audit.php");
                $spn_audit = new spn_audit();
                $spn_audit->create_audit($_SESSION['UserGUID'], 'TODO','Create Todo List',false);


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
             $result = $mysqli->error;
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

  function update_todo($id_todo, $status, $dummy)
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

        if($stmt=$mysqli->prepare("CALL " . $this->sp_update_todo_status . " (?,?)"))
        {

         if($stmt->bind_param("is", $id_todo, $status))
         {
          if($stmt->execute())
          {
                $result = 1;
                $stmt->close();
                $mysqli->close();
                //Audit by Caribe Developers
                require_once ("spn_audit.php");
                $spn_audit = new spn_audit();
                $spn_audit->create_audit($_SESSION['UserGUID'], 'Todo','Update Todo',false);
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


  function get_todo($UserGUID, $dummy=null)
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

        if ($select =$mysqli->prepare("CALL " . $this->sp_read_todo_list ." (?)"))
        {
          if($select->bind_param("s",$UserGUID))
          {
            if($select->execute())
            {
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'To do ','List To do',appconfig::GetDummy());
              $this->error = false;
              // $result=1;
              $select->bind_result(
                                      $id_todo,
                                      $message,
                                      $date,
                                      $status,
                                      $UserGUID);
              $select->store_result();

              $htmlcontrol .= "<form name='form-todo' id='form-todo' method='POST'>";
              $htmlcontrol .= "<div class='col-md-6' >";
              $htmlcontrol .= "<div class='tertiary-bg-color brd-full'>";
              $htmlcontrol .= "<div id='div_todo_list' name='div_todo_list' class='box'>";
              $htmlcontrol .= "<div class='box-title full-inset brd-bottom'>";
              $htmlcontrol .= "<h2 class='default-primary-color'>To do List</h2>";
              $htmlcontrol .= "</div>";
              $htmlcontrol .= "<div class='box-content full-inset tertiary-bg-color clearfix equal-height'>";
              $htmlcontrol .= "<ul class='list'>";

              if($select->num_rows > 0)
              {


                while($select->fetch())
                {

                  $mesage2 = wordwrap($message,20,"\n",true);

                  $htmlcontrol .= "<li>";
                  $htmlcontrol .= "<div class='col-md-1'>";
                  // $htmlcontrol .= "<input id='chektodo".$id_todo."' name='chektodo".$id_todo."' type='checkbox' class='chektodo' ". ($status ? " checked ": " ") . ($status ? "disabled": "")." value=".  $id_todo ." />";
                  $htmlcontrol .= "<span id='chektodo".$id_todo."' name='chektodo".$id_todo."' ". ($status ? "class='chektodo fa fa-check-square-o fa-lg'" : "class='chektodo fa fa-square-o fa-lg'") ." value=".  $id_todo ." />";
                  $htmlcontrol .= "	</div>";
                  $htmlcontrol .= "<div class='col-md-9'>";
                  $htmlcontrol .= "<h7>". ($status ? "<del>". htmlentities(utf8_encode($mesage2)) ."</del>" : htmlentities(utf8_encode($mesage2))) ."</h7>";
                  $htmlcontrol .= "	</div>";
                  $htmlcontrol .= "<div class='col-md-2 fa-hover col-sm-4'>";
                  $htmlcontrol .= "<ul class='action action-alt'>";
                  $htmlcontrol .= "<li class='dropdown'>";
                  $htmlcontrol .= "<a aria-expanded='false' data-toggle='dropdown' href='#'>";
                  $htmlcontrol .= "<span class='fa fa-trash-o fa-lg deletetodo_c' flag=". $id_todo ." id='deletetodo'></span>";
                  $htmlcontrol .= "</a>";
                  $htmlcontrol .= "</div>";
                //$htmlcontrol .= "<hr>";
                  $htmlcontrol .= "</li>";

                }


              }

                  $htmlcontrol .= "<li>";
                  $htmlcontrol .= "<br>";
                  // $htmlcontrol .= "<hr>";
                  $htmlcontrol .= "<div class='col-md-1'>";
                    // $htmlcontrol .= "<input type='checkbox' class='form-control'>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<div class='col-md-9'>";
                  $htmlcontrol .= "<input type='text' id ='todo_message' name='todo_message' class='form-control' maxlength='140'>";
              // $htmlcontrol .= "<button type='submit' class='btn btn-primary btn-m-w pull-right mrg-left' id='btn_add_todo' name='btn_add_todo'>+</button>";
                  $htmlcontrol .= "	</div>";
                  $htmlcontrol .= "<div class='col-md-2'>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</li>";
                  $htmlcontrol .= "</ul>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</form>";

              //$htmlcontrol.= "<table id=\"dataRequest-setting-detail2\" class=\"table table-bordered table-colored\" data-table=\"yes\"><thead><tr><th>Student</th><th>P1</th><th>P2</th><th>P3</th></tr></thead><tbody><tr><td>Rudy Croes</td><td> Level: 9 <br>Promoted: 1 Mistakes: Sin faltas Time length: 1 hora Observation: Sin observacion</td><td> Level: 8 Promoted: 1 Mistakes: 1 falta  Time length: 1 hora Observation: Una pregunta al profesor</td><td> Level: 7 Promoted: 1 Mistakes: 2 faltas Time length: 1 hora Observation: Dos preguntas, una ida al bano</td></tr></tbody></table>";

            }
            else
            {
              $result = 0;
              $result =  $mysqli->error;
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



  function delete_todo($id_todo, $dummy)
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

        if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_todo_list . " (?)"))
        {

         if($stmt->bind_param("i", $id_todo))
         {
          if($stmt->execute())
          {
                $result = 1;
                $stmt->close();
                $mysqli->close();
                //Audit by Caribe Developers
                require_once ("spn_audit.php");
                $spn_audit = new spn_audit();
                $spn_audit->create_audit($_SESSION['UserGUID'], 'TODO','Delete todo',false);
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
