<?php


class spn_documents_mobile
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


    $sql_query = "select * from documents where parent_id = ". $id_student;


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

                $htmlcontrol .="<div class='col-xs-4'>";
                $htmlcontrol .="<a target='_blank' href='../upload/".$document_id."'>";
                if(substr($document_id, -4) == '.pdf'){


					$htmlcontrol .="<img src='../assets/img/pdficon.png' class='img-responsive img-thumbnail'>";
					$htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
					$htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";


                }else{

					$htmlcontrol .="<img src='../upload/".$document_id."' class='img-responsive img-thumbnail'>";
					$htmlcontrol .="<div style='height:75px;position:relative;top:-50px;bottom:-50px;background:#000000;opacity:0.2;'>";
					$htmlcontrol .="<p style='color:#FFFFFF;margin:20px;'>". $description ."</p>";
                }
                $htmlcontrol .="</div>";
                $htmlcontrol .="</a>";
                $htmlcontrol .="<div style='text-align:center;'>";
                //$htmlcontrol .="<input type='checkbox' id='chk_document_delete".$id."' name='chk_document_delete".$id."' doc='".$document_id."' value='".$id."'/>";
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


  function createDocument($parent_id, $document_id, $description)
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



		if($stmt=$mysqli->prepare("insert into documents (parent_id, document_id, description) values (?,?,?)"))
		{
		   if($stmt->bind_param("sss",$parent_id, $document_id, $description))
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

}

?>
