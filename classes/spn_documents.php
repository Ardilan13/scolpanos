<?php


class spn_documents
{
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";

  public $debug = false;
  public $error = "";
  public $errormessage = "";

  public $sp_delete_document = "sp_delete_document";

  function listdocuments($id_student, $baseurl)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);


    $sql_query = "select id, parent_id, document_id, description from documents where parent_id = ". $id_student;


    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($select->execute())
        {
          $this->error = false;
          $result=1;

          $select->store_result();

          $select->bind_result($id, $parent_id, $document_id, $description);
          //Audit by Caribe Developers
          require_once ("spn_audit.php");
          $spn_audit = new spn_audit();
          $spn_audit->create_audit($_SESSION['UserGUID'], 'Documents','List Documents',false);

          if($select->num_rows > 0)
          {

            while($select->fetch())
            {

              $htmlcontrol .="<div class='col-md-4'>";
              $htmlcontrol .="<a target='_blank' href='./upload/".$document_id."'>";
              if(substr($document_id, -4) == '.pdf'){


                $htmlcontrol .="<img src='./assets/img/pdficon.png' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";


              }else{

                $htmlcontrol .="<img src='./upload/".$document_id."' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";
              }
              $htmlcontrol .="</div>";
              $htmlcontrol .="</a>";
              $htmlcontrol .="<div style='text-align:center;'>";
              $htmlcontrol .="<input type='checkbox' id='chk_document_delete".$id."' name='chk_document_delete".$id."' doc='".$document_id."' value='".$id."'/>";
              $htmlcontrol .="</div>";

              $htmlcontrol .="</div>";
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
          // error executing query
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
        // error preparing query
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
  function get_documents_by_class($id_student, $baseurl)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);


    $sql_query = "select id, parent_id, document_id, description from documents where parent_id = ". $id_student;


    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($select->execute())
        {
          $this->error = false;
          $result=1;

          $select->store_result();

          $select->bind_result($id, $parent_id, $document_id, $description);
          //Audit by Caribe Developers
          require_once ("spn_audit.php");
          $spn_audit = new spn_audit();
          $spn_audit->create_audit($_SESSION['UserGUID'], 'Documents','List Documents',false);

          if($select->num_rows > 0)
          {

            while($select->fetch())
            {

              $htmlcontrol .="<div class='col-md-4'>";
              $htmlcontrol .="<a target='_blank' href='./upload/".$document_id."'>";
              if(substr($document_id, -4) == '.pdf'){


                $htmlcontrol .="<img src='./assets/img/pdficon.png' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";


              }else{

                $htmlcontrol .="<img src='./upload/".$document_id."' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";
              }
              $htmlcontrol .="</div>";
              $htmlcontrol .="</a>";
              $htmlcontrol .="<div style='text-align:center;'>";
              $htmlcontrol .="<input type='checkbox' id='chk_document_delete".$id."' name='chk_document_delete".$id."' doc='".$document_id."' value='".$id."'/>";
              $htmlcontrol .="</div>";

              $htmlcontrol .="</div>";
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
          // error executing query
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
        // error preparing query
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

  function createDocument($parent_id, $document_id, $description,$schoolid,$klas,$type_document)
  {
    $result = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("YmdHis");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      $result = 0;
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($stmt=$mysqli->prepare("insert into documents (parent_id, document_id, description,schoolid,klas,type_document ) values (?,?,?,?,?,?)"))
      {
        if($stmt->bind_param("sssiss",$parent_id, $document_id, $description,$schoolid,$klas,$type_document))
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
            print('cero');
            $result = 0 . $mysqli->error;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }

        }
        else
        {
          $result = 2;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }

      }
      else
      {
        $result = 3 . $mysqli->error;
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

  function deletedocument($document_id, $dummy)
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

        if($stmt=$mysqli->prepare("CALL " . $this->sp_delete_document . " (?)"))
        {

          if($stmt->bind_param("i", $document_id))
          {
            if($stmt->execute())
            {
              $result = 1;
              $stmt->close();
              $mysqli->close();
              //Audit by Caribe Developers
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $spn_audit->create_audit($_SESSION['UserGUID'], 'Document','Delete Document',false);
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
  function create_ttr_document($parent_id, $document_id, $description,$schoolid,$klas)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("YmdHis");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {

      $result = 0;

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);



      if($stmt=$mysqli->prepare("insert into documents (parent_id, document_id, description,schoolid, klas, type_document) values (?,?,?,?,?,?)"))
      {
        $type_document = 'TTR_rapport';
        if($stmt->bind_param("sssiss",$parent_id, $document_id, $description,$schoolid,$klas, $type_document))
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
            $result = 0 . $mysqli->error;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }

        }
        else
        {
          $result = 2;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }

      }
      else
      {
        $result = 3 . $mysqli->error;
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
  function list_documents_ttr($schoolname, $schoolid,$klas)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
    {
      $sql_query = "SELECT id, parent_id, document_id, description, schoolid, klas FROM documents where schoolid =".$schoolid." and type_document = 'TTR_rapport';";
    }
    else
    {
      $sql_query = "SELECT id, parent_id, document_id, description, schoolid, klas FROM documents where schoolid =".$schoolid. " and klas ='".$klas."' and type_document = 'TTR_rapport';";
    }

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($select->execute())
        {
          $this->error = false;
          $result=1;

          $select->store_result();

          $select->bind_result($id, $parent_id, $document_id, $description, $schoolid, $klas_ttr);
          //Audit by Caribe Developers
          require_once ("spn_audit.php");
          $spn_audit = new spn_audit();
          $spn_audit->create_audit($_SESSION['UserGUID'], 'Documents','List Documents TTR',false);

          if($select->num_rows > 0)
          {

            while($select->fetch())
            {
              $schoolname = str_replace (" ", "", $schoolname);
              $htmlcontrol .="<div class='col-md-4'>";
              $htmlcontrol .="<a target='_blank' href='./upload/TTR/$schoolname/$klas_ttr/$document_id'>";
              if(substr($document_id, -5) == '.xlsx' || substr($document_id, -4) == '.xls' ){


                $htmlcontrol .="<img src='./assets/img/xlsxicon.png' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";


              }else{

                $htmlcontrol .="<img src='./assets/img/fileicon.png' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";
              }
              $htmlcontrol .="</div>";
              $htmlcontrol .="</a>";
              $htmlcontrol .="<div style='text-align:center;'>";
              $htmlcontrol .="<input type='checkbox' id='chk_document_delete".$id."' name='chk_document_delete".$id."' klas='".$klas_ttr."' doc='".$document_id."' value='".$id."'/>";
              $htmlcontrol .="</div>";

              $htmlcontrol .="</div>";
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
          // error executing query
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
        // error preparing query
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

  function create_woord_rapport_document($parent_id, $document_id, $description,$schoolid,$klas)
  {
    $result = 0;
    $type_document = 'woord_rapport';

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("YmdHis");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {

      $result = 0;
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($stmt=$mysqli->prepare("insert into documents (parent_id, document_id, description,schoolid, klas, type_document) values (?,?,?,?,?,?)"))
      {
        if($stmt->bind_param("sssiss",$parent_id, $document_id, $description,$schoolid,$klas,$type_document))
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
            $result = 0 . $mysqli->error;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }

        }
        else
        {
          $result = 2;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }

      }
      else
      {
        $result = 3 . $mysqli->error;
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
  function list_documents_woord_rapport($schoolname, $schoolid,$klas)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
    {
      $sql_query = "SELECT id, parent_id, document_id, description, schoolid, klas FROM documents where schoolid =".$schoolid." and type_document = 'woord_rapport';";
    }
    else
    {
      $sql_query = "SELECT id, parent_id, document_id, description, schoolid, klas FROM documents where schoolid =".$schoolid. " and klas ='".$klas."' and type_document = 'woord_rapport';";
    }

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($select->execute())
        {
          $this->error = false;
          $result=1;

          $select->store_result();

          $select->bind_result($id, $parent_id, $document_id, $description, $schoolid, $klas_woord);
          //Audit by Caribe Developers
          require_once ("spn_audit.php");
          $spn_audit = new spn_audit();
          $spn_audit->create_audit($_SESSION['UserGUID'], 'Documents','List Documents Woord Raaport',false);

          if($select->num_rows > 0)
          {

            while($select->fetch())
            {
              $schoolname = str_replace (" ", "", $schoolname);
              $htmlcontrol .="<div class='col-md-4'>";
              $htmlcontrol .="<a target='_blank' href='./upload/woord_rapport/$schoolname/$klas_woord/$document_id'>";
              if(substr($document_id, -5) == '.xlsx' || substr($document_id, -4) == '.xls' ){


                $htmlcontrol .="<img src='./assets/img/xlsxicon.png' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";


              }else{

                $htmlcontrol .="<img src='./assets/img/fileicon.png' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";
              }
              $htmlcontrol .="</div>";
              $htmlcontrol .="</a>";
              $htmlcontrol .="<div style='text-align:center;'>";
              $htmlcontrol .="<input type='checkbox' id='chk_document_delete".$id."' name='chk_document_delete".$id."' klas='".$klas_woord."' doc='".$document_id."' value='".$id."'/>";
              $htmlcontrol .="</div>";

              $htmlcontrol .="</div>";
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
          // error executing query
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
        // error preparing query
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
  function create_document_klas($parent_id, $document_id, $description,$schoolid,$klas)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("YmdHis");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {

      $result = 0;

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);



      if($stmt=$mysqli->prepare("insert into documents (parent_id, document_id, description,schoolid, klas, type_document) values (?,?,?,?,?,?)"))
      {
        $type_document = 'Document_Klas';
        if($stmt->bind_param("sssiss",$parent_id, $document_id, $description,$schoolid,$klas, $type_document))
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
            $result = 0 . $mysqli->error;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }

        }
        else
        {
          $result = 2;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }

      }
      else
      {
        $result = 3 . $mysqli->error;
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
  function list_documents_klas($schoolname, $schoolid,$klas)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
    {
      $sql_query = "SELECT id, parent_id, document_id, description, schoolid, klas FROM documents where schoolid =".$schoolid." and type_document = 'Document_Klas';";
    }
    else
    {

      $sql_query = "SELECT id, parent_id, document_id, description, schoolid, klas FROM documents where schoolid =".$schoolid. " and klas ='".$klas."' and type_document = 'Document_Klas';";
    }

    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($select->execute())
        {
          $this->error = false;
          $result=1;

          $select->store_result();

          $select->bind_result($id, $parent_id, $document_id, $description, $schoolid, $klas_document);
          //Audit by Caribe Developers

          if($select->num_rows > 0)
          {

            while($select->fetch())
            {
              $schoolname = str_replace (" ", "", $schoolname);
              $htmlcontrol .="<div class='col-md-4'>";
              $htmlcontrol .="<a target='_blank' href='./upload/Documents_klas/$schoolname/$klas_document/$document_id'>";
              if(substr($document_id, -5) == '.xlsx' || substr($document_id, -4) == '.xls' ){


                $htmlcontrol .="<img src='./assets/img/xlsxicon.png' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:80px;position:relative;top:0px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<label style='color:#FFFFFF;margin:10px;'>Date: ".return_date_front_format(substr($document_id, 0,8));
                $htmlcontrol .="<p style='color:#FFFFFF; font-size: 11px';>". $description ."</p>";
                $htmlcontrol .="</label>";


              }else{

                $htmlcontrol .="<img src='./assets/img/fileicon.png' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:80px;position:relative;top:0px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<label style='color:#FFFFFF;margin:10px;'>Date: ".return_date_front_format(substr($document_id, 0,8));
                $htmlcontrol .="<p style='color:#FFFFFF; font-size: 11px';> ". $description ."</p>";
                $htmlcontrol .="</label>";
              }
              $htmlcontrol .="</div>";
              $htmlcontrol .="</a>";
              $htmlcontrol .="<div style='text-align:center; margin-top: 15px;'>";
              $htmlcontrol .="<input type='checkbox' id='chk_document_delete".$id."' name='chk_document_delete".$id."' klas='".$klas_document."' doc='".$document_id."' value='".$id."'/>";
              $htmlcontrol .="</div>";

              $htmlcontrol .="</div>";
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
          // error executing query
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
        // error preparing query
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
  function list_documents_klas_filter($schoolname, $schoolid,$klas_filter)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "SELECT id, parent_id, document_id, description, schoolid, klas FROM documents where schoolid =".$schoolid. " and klas ='".$klas_filter."' and type_document = 'Document_Klas';";
    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($select->execute())
        {
          $this->error = false;
          $result=1;

          $select->store_result();

          $select->bind_result($id, $parent_id, $document_id, $description, $schoolid, $klas_document);

          if($select->num_rows > 0)
          {

            while($select->fetch())
            {
              $schoolname = str_replace (" ", "", $schoolname);
              $htmlcontrol .="<div class='col-md-4'>";
              $htmlcontrol .="<a target='_blank' href='./upload/Documents_klas/$schoolname/$klas_document/$document_id'>";
              if(substr($document_id, -5) == '.xlsx' || substr($document_id, -4) == '.xls' ){


                $htmlcontrol .="<img src='./assets/img/xlsxicon.png' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";


              }else{

                $htmlcontrol .="<img src='./assets/img/fileicon.png' class='img-responsive img-thumbnail'>";
                $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";
              }
              $htmlcontrol .="</div>";
              $htmlcontrol .="</a>";
              $htmlcontrol .="<div style='text-align:center;'>";
              $htmlcontrol .="<input type='checkbox' id='chk_document_delete".$id."' name='chk_document_delete".$id."' klas='".$klas_document."' doc='".$document_id."' value='".$id."'/>";
              $htmlcontrol .="</div>";

              $htmlcontrol .="</div>";
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
          // error executing query
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
        // error preparing query
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
  function get_documents_by_studentid($studentid,$klas)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";
    $d="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $schoolid = $_SESSION['SchoolID'];
    $schoolname = $_SESSION['schoolname'];
    $class = $_SESSION['Class'];
    $Schoolid_schoolname = $schoolid."_".$schoolname;

    $type_upload = 'Calendar';

    if ($_SESSION['SchoolType']==1){

      $sql_query = "SELECT distinct d.id, d.parent_id, d.document_id, d.description, c.date, c.subject, c.observations
      FROM documents d
      inner join calendar c on d.parent_id = c.id_calendar
      inner join students s on s.schoolid = d.schoolid and d.klas = s.class
      where s.uuid ='$studentid'  order by c.date desc";
    }
    else{
      $sql_query = "SELECT * FROM ( SELECT distinct d.id, d.parent_id, d.document_id, d.description, c.date, c.subject, c.observations
      FROM documents d
      inner join calendar c on d.parent_id = c.id_calendar
      inner join students s on s.schoolid = d.schoolid and d.klas = s.class
      where s.uuid ='$studentid'
      UNION
      SELECT distinct d.id, d.parent_id, d.document_id, d.description, '', 'Document-Klas', d.type_document
      from documents d
      Where klas = '$class' and schoolid = $schoolid and parent_id not in (SELECT distinct d.parent_id
        FROM documents d
        inner join calendar c on d.parent_id = c.id_calendar
        inner join students s on s.schoolid = d.schoolid and d.klas = s.class
        where s.uuid ='$studentid')) as x order by x.date DESC;";
      }

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if($select=$mysqli->prepare($sql_query))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->store_result();

            $select->bind_result($id, $parent_id, $document_id, $description, $date, $subject, $observations);
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            if($select->num_rows > 0)
            {



              $htmlcontrol= "<div class='col-md-12'>";
              $htmlcontrol.= "<div class='table-container'>";
              $htmlcontrol.= "<table class='table table-striped table-fw-widget table-hover'>";
              $htmlcontrol.= "<thead>";
              $htmlcontrol.= "<tr>";
              $htmlcontrol.= "<th width='10%'>file</th>";
              $htmlcontrol.= "<th width='10%'>TYPE</th>";
              $htmlcontrol.= "<th width='65%'>Description</th>";
              $htmlcontrol.= "<th width='15%'>Date</th>";

              $htmlcontrol.= "</tr>";
              $htmlcontrol.= "</thead>";
              $htmlcontrol.= "<tbody class='no-border-x'>";

              while($select->fetch()){


                if ($observations == 'Document_Klas'){
                  $Schoolid_schoolname = str_replace (" ", "", $_SESSION['schoolname']);
                  $type_upload = 'Documents_klas';
                }
                else{
                  $Schoolid_schoolname = $schoolid."_".$schoolname;
                  $type_upload = 'Calendar';
                }
                $htmlcontrol.="<tr>";

                if(substr($document_id, -5) == '.xlsx' || substr($document_id, -4) == '.xls' ){
                  $htmlcontrol.="<td><a target='_blank' href='./upload/$type_upload/$Schoolid_schoolname/$class/$document_id'><i class='fa fa-file-excel-o fa-2x' aria-hidden='true'></i></a></td>";
                }
                else if(substr($document_id, -5) == '.docx' || substr($document_id, -4) == '.doc' ){
                  $htmlcontrol.="<td><a target='_blank' href='./upload/$type_upload/$Schoolid_schoolname/$class/$document_id'><i class='fa fa-file-word-o fa-2x' aria-hidden='true'></i></a></td>";
                }
                else if(substr($document_id, -4) == '.jpg' || substr($document_id, -5) == '.jpeg' || substr($document_id, -4) == '.png'){
                  $htmlcontrol.="<td><a target='_blank' href='./upload/$type_upload/$Schoolid_schoolname/$class/$document_id'><i class='fa fa-file-image-o fa-2x' aria-hidden='true'></i></a></td>";
                }
                else{
                  $htmlcontrol.="<td><a target='_blank' href='./upload/$type_upload/$Schoolid_schoolname/$class/$document_id'><i class='fa fa-file-o fa-2x' aria-hidden='true'></i></a></td>";
                }
                $htmlcontrol.="<td>$subject</td>";
                $htmlcontrol.="<td>$description</td>";
                $htmlcontrol.="<td>$date</td>";

                $htmlcontrol.="</tr>";
              }
              $htmlcontrol.= "</tbody>";
              $htmlcontrol.= "</table>";
              $htmlcontrol.= "</div>";
              $htmlcontrol.= "</div>";
              $htmlcontrol.= "</div>";
            }
            else
            {
              $htmlcontrol .= "No results to show";
            }

          }
          else
          {
            // error executing query
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
          // error preparing query
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

    function open_document($parent_id)
    {
      $returnvalue = "";
      $user_permission="";
      $sql_query = "";
      $htmlcontrol="";
      $d="";

      mysqli_report(MYSQLI_REPORT_STRICT);
      $sql_query = "SELECT id, document_id FROM documents where parent_id = '$parent_id'";
      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if($select=$mysqli->prepare($sql_query))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;

            $select->store_result();

            $select->bind_result($id, $document_id);

            if($select->num_rows > 0)
            {
              $schoolid = $_SESSION['SchoolID'];
              $schoolname = $_SESSION['schoolname'];
              $class = $_SESSION['Class'];
              $Schoolid_schoolname = $schoolid."_".$schoolname;
              $baseurl = appconfig::GetBaseURL();

              while($select->fetch()){
                $htmlcontrol.="$baseurl/upload/Calendar/$Schoolid_schoolname/$class/$document_id";
              }

            }
            else
            {
              $htmlcontrol .= "0";
            }

          }
          else
          {
            // error executing query
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
          // error preparing query
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
    function list_documents_new_forms($schoolname, $schoolid,$studentid)
    {
      $returnvalue = "";
      $user_permission="";
      $sql_query = "";
      $htmlcontrol="";
      mysqli_report(MYSQLI_REPORT_STRICT);

      $sql_query = "SELECT id, parent_id, document_id, description, schoolid, klas FROM documents where schoolid =".$schoolid. " and parent_id ='".$studentid."' and type_document = 'new_forms';";

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if($select=$mysqli->prepare($sql_query))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;
            $select->store_result();
            $select->bind_result($id, $parent_id, $document_id, $description, $schoolid, $klas_woord);
            //Audit by Caribe Developers
            if($select->num_rows > 0)
            {
              while($select->fetch())
              {
                $schoolname = str_replace (" ", "", $schoolname);
                $htmlcontrol .="<div class='col-md-4'>";
                $htmlcontrol .="<a target='_blank' href='./upload/new_forms/$schoolname/$document_id'>";
                if(substr($document_id, -5) == '.xlsx' || substr($document_id, -4) == '.xls' ){
                  $htmlcontrol .="<img src='./assets/img/xlsxicon.png' class='img-responsive img-thumbnail'>";
                  $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                  $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";
                }else{
                  $htmlcontrol .="<img src='./assets/img/fileicon.png' class='img-responsive img-thumbnail'>";
                  $htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
                  $htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";
                }
                $htmlcontrol .="</div>";
                $htmlcontrol .="</a>";
                $htmlcontrol .="<div style='text-align:center;'>";
                $htmlcontrol .="<input type='checkbox' id='chk_document_delete".$id."' name='chk_document_delete".$id."' klas='".$klas_woord."' doc='".$document_id."' value='".$id."'/>";
                $htmlcontrol .="</div>";
                $htmlcontrol .="</div>";
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
            // error executing query
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
          // error preparing query
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

    function create_new_forms_document($parent_id, $document_id, $description,$schoolid,$klas)
    {
      $result = 0;
      $type_document = 'new_forms';
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("YmdHis");
      $status = 1;
      mysqli_report(MYSQLI_REPORT_STRICT);
      try
      {
        $result = 0;
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if($stmt=$mysqli->prepare("insert into documents (parent_id, document_id, description,schoolid, klas, type_document) values (?,?,?,?,?,?)"))
        {
          if($stmt->bind_param("sssiss",$parent_id, $document_id, $description,$schoolid,$klas,$type_document))
          {
            if($stmt->execute())
            {
              /* Need to check for errors on database side for primary key errors etc. */
              $result = 1;
              $stmt->close();
              $mysqli->close();
            }
            else
            {
            $result = 0 . $mysqli->error;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 2;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      else
      {
        $result = 3 . $mysqli->error;
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

  }

  function return_date_front_format($date)
  {
    $day=substr($date, -2);
    $month=substr($date,4,2);
    $year=substr($date, 0,4);
    return $day."-".$month."-".$year;

  }
  ?>
