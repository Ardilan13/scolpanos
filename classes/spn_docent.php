<?php

class spn_docent
{
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $sp_read_docent = "sp_read_docent";


  function listdocent($userRights, $schoolID, $dummy)
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
        $sp_read_docent = "sp_read_docent";

        if($stmt=$mysqli->prepare("CALL " . $this->$sp_read_docent . "(?,?)"))
        {
          if ($stmt->bind_param("si",$userRights, $schoolID))
          {
            if($stmt->execute())
            {
              // Audit by Caribe Developers
              require_once ('../document_start.php');
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'docent','list',appconfig::GetDummy());
              $this->error = false;
              $result=1;
              $stmt->bind_result($userGUID, $docent_name);
              $stmt->store_result();

              if($stmt->num_rows > 0)

              {
                while($stmt->fetch())
                {
                  if(htmlentities($userGUID) == $_SESSION['UserGUID'])
                  {
                    $htmlcontrol .= "<input class=\"form-control\" type=\"text\" name=\"name_docent\" id=\"name_docent\" value=\"". htmlentities($docent_name) ."\" disabled />";
                    $htmlcontrol .= "<input type=\"hidden\" name=\"list_docent\" id=\"list_docent\" value=". htmlentities($userGUID) ." />";
                  }
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
